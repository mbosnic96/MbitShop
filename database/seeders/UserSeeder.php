<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('users')->insert([
            'name' => 'Mehmed Bosnić',
            'email' => 'mehmed_bossnic@hotmail.com',
            'email_verified_at' => $now,
            'password' => Hash::make('admin'),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'current_team_id' => null,
            'profile_photo_path' => null,
            'created_at' => $now,
            'updated_at' => $now,
            'address' => 'Kostelska',
            'city' => 'Bihać',
            'country' => 'Bosna i Hercegovina',
            'phone_number' => '0603004395',
            'credit_card' => null,
            'role' => 'admin',
        ]);

        $firstNames = ['Amir', 'Haris', 'Adnan', 'Emina', 'Elma', 'Mirza', 'Selma', 'Dino', 'Lejla', 'Faruk', 'Sabina', 'Jasmin', 'Ajla', 'Nermin', 'Aida', 'Armin', 'Edin', 'Amela', 'Tarik', 'Sara'];
        $lastNames = ['Hadžić', 'Kovačević', 'Alić', 'Suljić', 'Omerović', 'Ibrahimović', 'Mujić', 'Mehić', 'Delić', 'Avdić'];

        for ($i = 1; $i <= 20; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $fullName = "$firstName $lastName";

            DB::table('users')->insert([
                'name' => $fullName,
                'email' => "user{$i}@mailx.com",
                'email_verified_at' => $now,
                'password' => Hash::make('user'),
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'remember_token' => Str::random(10),
                'current_team_id' => null,
                'profile_photo_path' => null,
                'created_at' => $now,
                'updated_at' => $now,
                'address' => 'Ulica broj ' . rand(1, 50),
                'city' => 'Sarajevo',
                'country' => 'Bosna i Hercegovina',
                'phone_number' => '06' . rand(1000000, 9999999),
                'credit_card' => null,
                'role' => 'user',
            ]);
        }
    }
}