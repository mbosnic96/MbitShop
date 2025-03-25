<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;

class OrderItemSeeder extends Seeder
{
    public function run()
    {
        $orders = Order::all(); // Uzmi sve narudžbe
        $products = Product::all(); // Uzmi sve proizvode

        foreach ($orders as $order) {
            // Nasumično uzimanje 1 do 5 proizvoda za svaku narudžbu
            $itemsCount = rand(1, 5);

            for ($i = 0; $i < $itemsCount; $i++) {
                $product = $products->random();

                DB::table('order_items')->insert([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => rand(1, 3), // Nasumična količina
                    'price' => $product->price, // Preuzmi cenu proizvoda
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
