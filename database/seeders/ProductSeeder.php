<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            'strawberry' => [
                'slug'  => 'strawberry',
                'name'  => 'Strawberry Segar',
                'image' => 'strawberry.jpg', // file di public/images/products
                'packages' => [
                    ['label' => 'Pack Kecil (250g)',  'price' => 35000],
                    ['label' => 'Pack Besar (500g)',  'price' => 65000],
                    ['label' => 'Hemat 2 Pack Besar', 'price' => 110000],
                ],
            ],

            'murbei' => [
                'slug'  => 'murbei',
                'name'  => 'Murbei Segar',
                'image' => 'murbei.jpg',
                'packages' => [
                    ['label' => 'Pack Kecil (250g)', 'price' => 25000],
                ],
            ],

            'strawberry-frozen' => [
                'slug'  => 'strawberry-frozen',
                'name'  => 'Strawberry Frozen',
                'image' => 'strawberry-frozen.jpg',
                'packages' => [
                    ['label' => 'Pack Besar (1000g)', 'price' => 30000],
                ],
            ],
        ];

        foreach ($products as $data) {

            // Insert Product
            $product = Product::create([
                'slug'       => $data['slug'],
                'name'       => $data['name'],
                'description'=> null,
                'image'      => $data['image'], // hanya nama file
                'is_active'  => true,
            ]);

            // Insert Variants
            foreach ($data['packages'] as $pkg) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name'       => $pkg['label'],
                    'price'      => $pkg['price'],
                    'is_active'  => true,
                ]);
            }
        }
    }
}
