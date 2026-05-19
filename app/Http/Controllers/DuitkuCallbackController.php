<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\DuitkuService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DuitkuCallbackController extends Controller
{
    public function callback(Request $request)
    {
        \Log::info('=== DUITKU CALLBACK HIT ===');
        \Log::info('Duitku callback payload', ['payload' => $request->all()]);
        \Log::info('Duitku callback headers', ['headers' => $request->headers->all()]);

        try {
            $merchantCode    = $request->input('merchantCode');
            $amount          = $request->input('amount');          // string
            $merchantOrderId = $request->input('merchantOrderId'); // order_code
            $resultCode      = $request->input('resultCode');
            $signature       = $request->input('signature');

            \Log::info('Duitku callback parsed', [
                'merchantCode'    => $merchantCode,
                'amount'          => $amount,
                'merchantOrderId' => $merchantOrderId,
                'resultCode'      => $resultCode,
                'signature'       => $signature,
            ]);

            if (! $merchantCode || ! $merchantOrderId || $resultCode === null || ! $signature) {
                \Log::warning('Duitku callback BAD_REQUEST — missing required fields');
                return response('BAD_REQUEST', 400);
            }

            // SIGNATURE VERIFICATION
            $serverKey    = config('duitku.merchant_key');
            $stringToSign = $merchantCode . $amount . $merchantOrderId . $serverKey;
            $expectedSig  = md5($stringToSign);

            \Log::info('Duitku signature check', [
                'stringToSign' => $stringToSign,
                'expected'     => $expectedSig,
                'received'     => $signature,
            ]);

            if (strtolower($signature) !== strtolower($expectedSig)) {
                \Log::warning('Duitku callback INVALID_SIGNATURE', [
                    'received' => $signature,
                    'expected' => $expectedSig,
                ]);
                return response('INVALID_SIGNATURE', 403);
            }

            // GET ORDER
            $order = Order::where('order_code', $merchantOrderId)->first();
            if (! $order) {
                \Log::warning('Duitku callback ORDER_NOT_FOUND', ['merchantOrderId' => $merchantOrderId]);
                return response('ORDER_NOT_FOUND', 404);
            }

            \Log::info('Duitku callback order found', ['order_id' => $order->id, 'current_status' => $order->status]);

            // GET PAYMENT
            $payment = Payment::where('order_id', $order->id)
                ->where('merchant_order_id', $merchantOrderId)
                ->first();

            if (! $payment) {
                \Log::info('Duitku callback — no payment found, creating new');
                $payment = new Payment();
                $payment->order_id = $order->id;
                $payment->merchant_order_id = $merchantOrderId;
            }

            // SAVE CALLBACK FIELDS
            $payment->payment_method      = $request->input('paymentCode') ?? $payment->payment_method;
            $payment->issuer_name         = $request->input('issuerCode') ?? $payment->issuer_name;
            $payment->reference           = $request->input('reference') ?? $payment->reference;
            $payment->publisher_order_id  = $request->input('publisherOrderId') ?? $payment->publisher_order_id;
            $payment->settlement_date     = $request->input('settlementDate')
                ? Carbon::parse($request->input('settlementDate'))
                : $payment->settlement_date;

            $payment->callback_at         = now();
            $payment->callback_signature  = $signature;
            $payment->raw_callback        = json_encode($request->all());

            // STATUS — cast resultCode to string for safety
            $resultCodeStr = (string) $resultCode;

            if ($resultCodeStr === '00') {
                \Log::info('Duitku callback — payment SUCCESS');

                // Check if already paid to avoid duplicate notifications (e.g. if handled by return url)
                $alreadyPaid = ($order->status === 'paid');

                $payment->status = 'paid';
                if (!$payment->paid_at) {
                    $payment->paid_at = now();
                }

                $order->status = 'paid';

                // --- Kirim Notifikasi Sukses Bayar (Only if not already paid) ---
                if (!$alreadyPaid) {
                    try {
                        /** @var \App\Services\WhatsAppService $waService */
                        $waService = app(\App\Services\WhatsAppService::class);
                        // Refresh order to ensure relations are loaded if needed, though service handles it
                        $waService->sendOrderNotification($order);
                    } catch (\Exception $e) {
                        \Log::error('Failed to send WA notification on payment success: ' . $e->getMessage());
                    }
                }
            } else {
                \Log::info('Duitku callback — payment FAILED', ['resultCode' => $resultCodeStr]);
                $payment->status = 'failed';
                $order->status   = 'failed';
            }

            $payment->status_code    = $resultCodeStr;
            $payment->status_message = $request->input('resultMsg') ?? null;
            $payment->amount         = (int) $amount;

            $payment->save();
            $order->save();

            \Log::info('=== DUITKU CALLBACK DONE ===', [
                'order_status'   => $order->status,
                'payment_status' => $payment->status,
                'payment_id'     => $payment->id,
            ]);

            return response('SUCCESS', 200);
        } catch (\Exception $e) {
            \Log::error('Duitku callback EXCEPTION', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return response('SERVER_ERROR', 500);
        }
    }



    public function return(Request $request)
    {
        // Cari payment terakhir milik user yang masih pending
        // Duitku redirect kembali tanpa parameter, jadi kita cari payment terbaru
        $payment = Payment::where('status', 'pending')
            ->whereNotNull('reference')
            ->orderByDesc('id')
            ->first();

        $statusMessage = 'Menunggu konfirmasi pembayaran...';
        $statusType    = 'pending'; // pending, success, failed

        if ($payment) {
            try {
                /** @var \App\Services\DuitkuService $duitku */
                $duitku = app(DuitkuService::class);
                $result = $duitku->checkTransactionStatus($payment->merchant_order_id);

                \Log::info('Duitku return — status check', [
                    'merchant_order_id' => $payment->merchant_order_id,
                    'result'            => $result,
                ]);

                $order = Order::find($payment->order_id);

                if ($result && isset($result->statusCode)) {
                    if ($result->statusCode === '00') {
                        // PAID
                        $payment->status      = 'paid';
                        $payment->paid_at      = now();
                        $payment->status_code  = '00';
                        $payment->status_message = $result->statusMessage ?? 'SUCCESS';
                        $payment->save();

                        if ($order) {
                            $alreadyPaid = ($order->status === 'paid');

                            $order->status = 'paid';
                            $order->save();

                            // --- Kirim Notifikasi Sukses Bayar (via Return) ---
                            if (!$alreadyPaid) {
                                try {
                                    /** @var \App\Services\WhatsAppService $waService */
                                    $waService = app(\App\Services\WhatsAppService::class);
                                    $waService->sendOrderNotification($order);
                                } catch (\Exception $e) {
                                    \Log::error('Failed to send WA notification on return success: ' . $e->getMessage());
                                }
                            }
                        }

                        $statusMessage = 'Pembayaran berhasil! Pesanan kamu sedang diproses.';
                        $statusType    = 'success';
                    } elseif ($result->statusCode === '01') {
                        // STILL PENDING
                        $statusMessage = 'Pembayaran masih dalam proses. Status akan diperbarui otomatis.';
                        $statusType    = 'pending';
                    } else {
                        // FAILED / EXPIRED
                        $payment->status      = 'failed';
                        $payment->status_code  = $result->statusCode;
                        $payment->status_message = $result->statusMessage ?? 'FAILED';
                        $payment->save();

                        if ($order) {
                            $order->status = 'failed';
                            $order->save();
                        }

                        $statusMessage = 'Pembayaran gagal atau kedaluwarsa. Silakan coba lagi.';
                        $statusType    = 'failed';
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Duitku return — status check error', ['error' => $e->getMessage()]);
                $statusMessage = 'Tidak dapat mengecek status pembayaran. Silakan hubungi admin.';
            }
        }

        return view('payments.duitku-return', [
            'statusMessage' => $statusMessage,
            'statusType'    => $statusType,
            'payment'       => $payment,
        ]);
    }
}
