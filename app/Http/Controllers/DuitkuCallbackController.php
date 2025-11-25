<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\DuitkuService;
use Illuminate\Http\Request;

class DuitkuCallbackController extends Controller
{
    public function callback(Request $request)
    {
        \Log::info('Duitku callback hit', ['payload' => $request->all()]);

        $merchantCode    = $request->merchantCode;
        $amount          = $request->amount;          // string
        $merchantOrderId = $request->merchantOrderId; // order_code
        $resultCode      = $request->resultCode;
        $signature       = $request->signature;

        if (! $merchantCode || ! $merchantOrderId || ! $resultCode || ! $signature) {
            return response('BAD_REQUEST', 400);
        }

        // SIGNATURE FIX
        $serverKey    = config('duitku.merchant_key');
        $stringToSign = $merchantCode . $amount . $merchantOrderId . $serverKey;
        $expectedSig  = md5($stringToSign);

        if (strtolower($signature) !== strtolower($expectedSig)) {
            \Log::warning('Duitku callback invalid signature', [
                'received' => $signature,
                'expected' => $expectedSig,
                'stringToSign' => $stringToSign
            ]);
            return response('INVALID_SIGNATURE', 403);
        }

        // GET ORDER
        $order = Order::where('order_code', $merchantOrderId)->first();
        if (! $order) {
            return response('ORDER_NOT_FOUND', 404);
        }

        // GET PAYMENT
        $payment = Payment::where('order_id', $order->id)
            ->where('merchant_order_id', $merchantOrderId)
            ->first();

        if (! $payment) {
            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->merchant_order_id = $merchantOrderId;
        }

        // SAVE CALLBACK FIELDS
        $payment->payment_method      = $request->paymentCode ?? null;
        $payment->issuer_name         = $request->issuerCode ?? null;
        $payment->reference           = $request->reference ?? null;
        $payment->publisher_order_id  = $request->publisherOrderId ?? null;
        $payment->settlement_date     = $request->settlementDate
                                        ? Carbon::parse($request->settlementDate)
                                        : null;

        $payment->callback_at         = now();
        $payment->callback_signature  = $signature;
        $payment->raw_callback        = json_encode($request->all());

        // STATUS
        if ($resultCode === '00') {
            $payment->status = 'paid';
            $payment->paid_at = now();

            $order->status = 'paid';
            $order->paid_at = now();
        } else {
            $payment->status = 'failed';
            $order->status   = 'failed';
        }

        $payment->status_code    = $resultCode;
        $payment->status_message = $request->resultMsg ?? null;
        $payment->amount         = (int)$amount;

        $payment->save();
        $order->save();

        return response('SUCCESS', 200);
    }


    public function return(Request $request)
    {
        // Ini dipanggil setelah user selesai / batal di halaman Duitku (return URL)
        // Bisa ambil order_code dari query jika dikirim (cek dokumentasi detail),
        // untuk sekarang kita cukup tampilkan halaman generic.
        return view('payments.duitku-return');
    }
}
