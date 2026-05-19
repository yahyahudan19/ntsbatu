<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $baseUrl;
    protected $sessionId;
    protected $username;
    protected $password;

    public function __construct()
    {
        $this->baseUrl = config('services.whatsapp.url');
        $this->sessionId = config('services.whatsapp.session_id');
        $this->username = config('services.whatsapp.username');
        $this->password = config('services.whatsapp.password');
    }

    /**
     * Send a text message to a specific phone number.
     *
     * @param string $phone The recipient's phone number (e.g., 628123456789)
     * @param string $message The message content
     * @return array
     */
    public function sendMessage(string $phone, string $message)
    {
        // Ensure phone number format is correct (remove leading 0 if present and add 62, or ensure it starts with 62)
        // This is a basic normalization. Adjust as needed.
        $phone = $this->normalizePhoneNumber($phone);

        // Clean base URL and ensure no trailing slash
        $baseUrl = rtrim($this->baseUrl, '/');

        try {
            $url = $baseUrl . '/send/message';

            $payload = [
                'phone' => $phone . '@s.whatsapp.net',
                'message' => $message,
            ];

            // Setup headers
            $headers = [];
            if ($this->sessionId) {
                // Ensure session ID is string
                $headers['X-Device-Id'] = (string) $this->sessionId;
            }

            // Log for debugging (excluding password)
            Log::info("WhatsApp Sending to: $url", [
                'phone' => $phone,
                'has_auth' => ($this->username && $this->password),
                'username' => $this->username,
                'session_id' => $this->sessionId,
            ]);

            $http = Http::withHeaders($headers);

            if ($this->username && $this->password) {
                $http->withBasicAuth($this->username, $this->password);
            }

            $response = $http->post($url, $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            Log::error('WhatsApp Gateway Error: ' . $response->body());

            return [
                'success' => false,
                'error' => $response->body()
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp Service Exception: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send order notification (invoice style) to customer.
     */
    public function sendOrderNotification(\App\Models\Order $order)
    {
        if (!$order->customer_phone) {
            return [
                'success' => false,
                'error' => 'No customer phone number'
            ];
        }

        // Load relationship payment if not loaded
        $order->loadMissing(['items.product', 'payment']);

        // Determine status
        // Prioritize payment status from Payment model, fallback to Order status column
        $status = $order->payment?->status ?? $order->status ?? 'pending';

        // Format pesan
        $message = "Halo *{$order->customer_name}*,\n\n";
        $message .= "Terima kasih telah memesan di *NTS Batu*.\n";
        $message .= "Berikut adalah detail pesanan Anda:\n\n";
        $message .= "No. Order: *{$order->order_code}*\n";
        $message .= "Tanggal: " . $order->created_at->format('d M Y H:i') . "\n";

        // Handle status display
        $statusDisplay = strtoupper(str_replace('_', ' ', $status));
        $message .= "Status: *{$statusDisplay}*\n\n";

        $message .= "--- Detail Item ---\n";
        foreach ($order->items as $item) {
            $productName = $item->product->name ?? $item->product_name ?? 'Item';
            $message .= "- {$productName} ({$item->quantity}x) : Rp " . number_format($item->subtotal, 0, ',', '.') . "\n";
            if ($item->variant_name) {
                $message .= "  Varian: {$item->variant_name}\n";
            }
        }
        $message .= "\n";

        $message .= "Subtotal: Rp " . number_format($order->subtotal, 0, ',', '.') . "\n";
        if ($order->delivery_fee > 0) {
            $message .= "Ongkir: Rp " . number_format($order->delivery_fee, 0, ',', '.') . "\n";
        }
        if ($order->discount_amount > 0) {
            $message .= "Diskon: -Rp " . number_format($order->discount_amount, 0, ',', '.') . "\n";
        }
        $message .= "*Total Bayar: Rp " . number_format($order->grand_total, 0, ',', '.') . "*\n\n";

        // Logic pesan tambahan berdasarkan status
        if ($status === 'paid' || $status === 'settlement' || $status === 'completed') {
            $message .= "Pembayaran Anda telah kami terima. Pesanan sedang diproses. Terima kasih!\n";
        } else if ($status === 'processing') {
            $message .= "Pesanan Anda sedang kami proses scara COD. Kami akan segera menghubungi Anda untuk konfirmasi.\n";
        } else {
            $message .= "Mohon segera lakukan pembayaran agar pesanan dapat kami proses.\n";

            // If payment URL exists and is pending
            if ($order->payment && $order->payment->payment_url && in_array($status, ['pending', 'pending_payment'])) {
                $message .= "\nLink Pembayaran: " . $order->payment->payment_url . "\n";
            }
        }

        return $this->sendMessage($order->customer_phone, $message);
    }

    protected function normalizePhoneNumber($phone)
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If starts with 0, replace with 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // If doesn't start with 62, prepend 62 (assuming ID numbers)
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}
