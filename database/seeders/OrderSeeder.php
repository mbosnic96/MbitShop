<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $statusOptions = ['na čekanju', 'u obradi', 'poslano', 'otkazano'];
        $users = User::all();

        for ($i = 0; $i < 10; $i++) {
            $user = $users->random();

            DB::table('orders')->insert([
                'user_id' => $user->id,
                'order_number' => 'MBIT-' . strtoupper(Str::random(8)),
                'total_price' => rand(100, 3000) + rand(0, 99) / 100,
                'status' => $statusOptions[array_rand($statusOptions)],
                'shipping_address' => $user->name . ', ' . $user->phone_number . ', ' . $user->address . ', ' . $user->city . ', ' . $user->country,
                'created_at' => $now->copy()->subDays(rand(1, 15)),
                'updated_at' => $now,
            ]);
        }
    }
}
