<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',

        'merchant_order_id',
        'amount',
        'currency',
        'product_details',
        'additional_param',
        'payment_method',

        'reference',
        'payment_url',
        'va_number',
        'qr_string',
        'status_code',
        'status_message',
        'expires_at',

        'status',
        'result_code',
        'publisher_order_id',
        'issuer_name',
        'issuer_bank',
        'settlement_date',
        'callback_at',
        'paid_at',

        'callback_signature',
        'raw_callback',
        'raw_request',
        'raw_response',
    ];

    protected $casts = [
        'amount'          => 'integer',
        'expires_at'      => 'datetime',
        'settlement_date' => 'datetime',
        'callback_at'     => 'datetime',
        'paid_at'         => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Kalau nanti mau di-link ke tabel gateway (opsional)
    public function gateway()
    {
        return $this->belongsTo(PaymentGateway::class, 'payment_gateway_id');
    }

    // Helper: cek apakah sudah paid
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    

}
