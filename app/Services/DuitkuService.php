<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Duitku\Config as DuitkuConfig;
use Duitku\Pop;

class DuitkuService
{
    protected function makeConfig(): DuitkuConfig
    {
        $config = new DuitkuConfig(
            config('duitku.merchant_key'),
            config('duitku.merchant_code')
        );

        $config->setSandboxMode((bool) config('duitku.sandbox'));
        $config->setSanitizedMode((bool) config('duitku.sanitized'));
        $config->setDuitkuLogs((bool) config('duitku.log'));

        return $config;
    }

    /**
     * Buat invoice Duitku POP untuk sebuah Order
     * dan simpan ke tabel payments.
     */
    public function createPopInvoice(Order $order): Payment
    {
        $config = $this->makeConfig();

        $paymentAmount   = (int) $order->grand_total;
        $merchantOrderId = $order->order_code; // unik per order

        // Data customer (boleh kamu sesuaikan)
        $fullName  = $order->customer_name;
        $firstName = $fullName;
        $lastName  = ''; // kalau mau split nama, silakan
        $email     = $order->customer_email ?? 'no-reply@ntsbatu.biz.id';
        $phone     = $order->customer_phone;
        $alamat    = $order->customer_address;
        $city      = $order->city ?? 'Kota Batu';
        $postal    = $order->postal_code ?? '65100';
        $country   = 'ID';

        $address = [
            'firstName'   => $firstName,
            'lastName'    => $lastName,
            'address'     => $alamat,
            'city'        => $city,
            'postalCode'  => $postal,
            'phone'       => $phone,
            'countryCode' => $country,
        ];

        $customerDetail = [
            'firstName'       => $firstName,
            'lastName'        => $lastName,
            'email'           => $email,
            'phoneNumber'     => $phone,
            'billingAddress'  => $address,
            'shippingAddress' => $address,
        ];

        // Item detail ambil dari order_items (snapshot)
        $itemDetails = [];
        foreach ($order->items as $item) {
            $itemDetails[] = [
                'name'     => $item->variant_name ?? $item->product_name,
                'price'    => (int) $item->unit_price,
                'quantity' => (int) $item->quantity,
            ];
        }

        // Kalau mau simple, bisa satu item "Total Pre-Order"
        if (empty($itemDetails)) {
            $itemDetails[] = [
                'name'     => 'Pre-order ' . $order->order_code,
                'price'    => $paymentAmount,
                'quantity' => 1,
            ];
        }

        $productDetails   = 'Pre-order buah - ' . $order->order_code;
        $additionalParam  = '';
        $merchantUserInfo = '';
        $customerVaName   = $order->customer_name;
        $callbackUrl      = config('duitku.callback_url');
        $returnUrl        = config('duitku.return_url');
        $expiryPeriod     = 60; // menit

        $params = [
            'paymentAmount'   => $paymentAmount,
            'merchantOrderId' => $merchantOrderId,
            'productDetails'  => $productDetails,
            'additionalParam' => $additionalParam,
            'merchantUserInfo'=> $merchantUserInfo,
            'customerVaName'  => $customerVaName,
            'email'           => $email,
            'phoneNumber'     => $phone,
            'itemDetails'     => $itemDetails,
            'customerDetail'  => $customerDetail,
            'callbackUrl'     => $callbackUrl,
            'returnUrl'       => $returnUrl,
            'expiryPeriod'    => $expiryPeriod,
        ];

        // Sesuai contoh README: Pop::createInvoice($params, $config) :contentReference[oaicite:2]{index=2}
        $responseJson = Pop::createInvoice($params, $config);
        $response     = json_decode($responseJson);

        if (! $response || ($response->statusCode ?? null) !== '00') {
            throw new \RuntimeException(
                'Duitku error: ' . ($response->statusMessage ?? 'Unknown error')
            );
        }

        // Response API V2 biasanya mengandung:
        // reference, paymentUrl, amount, statusCode, statusMessage :contentReference[oaicite:3]{index=3}
        $payment = Payment::create([
            'order_id'         => $order->id,
            'provider'         => 'duitku',
            'merchant_order_id'=> $merchantOrderId,
            'reference'        => $response->reference ?? null,
            'payment_url'      => $response->paymentUrl ?? null,
            'amount'           => $paymentAmount,
            'status'           => 'pending',
            'status_code'      => $response->statusCode ?? null,
            'status_message'   => $response->statusMessage ?? null,
            'raw_response'     => $responseJson,
        ]);

        return $payment;
    }

    /**
     * Handle callback Duitku POP (otomatis update status)
     */
    public function handlePopCallback(): void
    {
        $config   = $this->makeConfig();
        $callback = Pop::callback($config);   // data mentah JSON string dari Duitku

        \Log::info('Duitku callback raw', ['payload' => $callback]);

        $notif = json_decode($callback);

        if (! $notif) {
            throw new \RuntimeException('Invalid callback payload');
        }

        $merchantOrderId = $notif->merchantOrderId ?? null;
        $amount          = (int) ($notif->amount ?? 0);
        $resultCode      = $notif->resultCode ?? null; // "00" = success
        $resultMsg       = $notif->resultMsg ?? null;

        if (! $merchantOrderId) {
            throw new \RuntimeException('merchantOrderId missing');
        }

        // Cari ORDER berdasarkan order_code (yang kita kirim sebagai merchantOrderId)
        $order = Order::where('order_code', $merchantOrderId)->first();

        if (! $order) {
            throw new \RuntimeException('Order not found for merchantOrderId '.$merchantOrderId);
        }

        // Cari PAYMENT berdasarkan merchant_order_id (yang sudah kita simpan saat createInvoice)
        $payment = Payment::where('merchant_order_id', $merchantOrderId)
            ->orderByDesc('id')
            ->first();

        if (! $payment) {
            // fallback: kalau belum ada (harusnya ada), kita buat baru
            $payment = new Payment([
                'order_id'          => $order->id,
                'merchant_order_id' => $merchantOrderId,
            ]);
        }

        $payment->amount          = $amount ?: $payment->amount;
        $payment->status_code     = $resultCode;
        $payment->status_message  = $resultMsg;
        $payment->raw_callback    = $callback;

        if ($resultCode === '00') {
            // Sukses dibayar
            $payment->status  = 'paid';
            $payment->paid_at = now();

            $order->status    = 'paid';
            if (property_exists($order, 'paid_at')) {
                $order->paid_at = now();
            }
        } elseif ($resultCode === '01') {
            // Gagal / dibatalkan
            $payment->status = 'failed';
            $order->status   = 'failed';
        } else {
            // status lain-lain → anggap gagal
            $payment->status = 'failed';
            $order->status   = 'failed';
        }

        $payment->save();
        $order->save();

        \Log::info('Duitku callback processed', [
            'merchantOrderId' => $merchantOrderId,
            'resultCode'      => $resultCode,
            'order_status'    => $order->status,
            'payment_status'  => $payment->status,
        ]);
    }
}
