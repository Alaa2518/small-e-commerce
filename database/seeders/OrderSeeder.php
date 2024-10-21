<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::factory(20)->create()->each(function ($order) {
            $products = Product::inRandomOrder()->take(rand(1, 5))->pluck('id');

            foreach ($products as $productId) {
                $order->products()->attach($productId, ['quantity' => rand(1, 10)]);
            }
        });
    }
}
