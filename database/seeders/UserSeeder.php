<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Mehmed Bosnić',
            'email' => 'admin@mbitshop.com',
            'password' => Hash::make('admin'), 
            'address' => 'Kostelska bb',
            'city' => 'Bihać',
            'country' => 'Bosna i Hercegovina',
            'phone_number' => '+387603004395',
            'credit_card' => null, 
            'role' => 'admin', 
        ]);
    }
}