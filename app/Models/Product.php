<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // 1 product punya banyak varian
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // hanya varian aktif
    public function activeVariants()
    {
        return $this->variants()->where('is_active', true);
    }

    // kalau mau akses semua item order yang pernah pakai produk ini (opsional)
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
