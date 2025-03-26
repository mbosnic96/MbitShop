<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Order;

class OrderNotificationSeeder extends Seeder
{
    public function run()
    {
        $orders = DB::table('orders')->inRandomOrder()->limit(10)->get();

        foreach ($orders as $order) {
            DB::table('notifications')->insert([
                'id' => Str::uuid(),
                'type' => 'App\Notifications\OrderNotification',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $order->user_id,
                'data' => json_encode([
                    'message' => "Nova narudžba #{$order->order_number} je registrovana sa statusom \"{$order->status}\".",
                    'order_id' => $order->id,
                    'total_price' => $order->total_price,
                ]),
                'read_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
