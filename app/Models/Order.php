<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_code',

        'customer_name',
        'customer_phone',
        'customer_address',
        'city',
        'area',

        'delivery_date',
        'delivery_time_slot',
        'notes',
        
        'subtotal',
        'delivery_fee',
        'discount_amount',
        'grand_total',

        'status',
        'paid_at',
    ];

    protected $casts = [
        'paid_at'   => 'datetime',
        'delivery_date'   => 'date',
        'subtotal'        => 'integer',
        'delivery_fee'    => 'integer',
        'discount_amount' => 'integer',
        'grand_total'     => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 1 order punya banyak item
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // 1 order punya 1 payment (Duitku)
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // helper: total qty semua item
    public function getTotalQuantityAttribute()
    {
        return $this->items->sum('quantity');
    }
}
