<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Ambil semua produk aktif beserta variannya
        $products = Product::with('variants')
            ->where('is_active', true)
            ->get();

        // Bentuk ulang supaya mirip struktur lama (key = slug)
        $productsForView = $products->mapWithKeys(function ($product) {
            return [
                $product->slug => [
                    'id'          => $product->id,
                    'slug'        => $product->slug,
                    'name'        => $product->name,
                    'description' => $product->description, // bisa diisi dari DB
                    'image'       => asset('images/products/' . $product->image),
                    'packages'    => $product->variants->map(function ($variant) {
                        return [
                            'id'    => $variant->id,
                            'label' => $variant->name,
                            'price' => $variant->price,
                        ];
                    })->toArray(),
                ],
            ];
        });

        return view('landing', [
            'products' => $productsForView,
        ]);
    }
}
