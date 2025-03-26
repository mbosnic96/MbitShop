<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $now = Carbon::now();

        // Glavne kategorije
        $mainCategories = [
            'Računari i Komponente' => [
                'Laptopi',
                'Desktop računari',
                'Procesori (CPU)',
                'Grafičke karte (GPU)',
                'Matične ploče',
                'RAM memorija',
                'SSD / HDD diskovi',
                'Napajanja',
                'Kućišta',
                'Hlađenje'
            ],
            'Mobilni telefoni' => [
                'Pametni telefoni',
                'Dodaci za telefone',
                'Punjači i kablovi',
                'Zaštitne maske',
                'Bluetooth slušalice'
            ],
            'TV i Video' => [
                'Televizori',
                'Smart TV',
                'TV Prijemnici',
                'Zvučnici i Soundbar',
                'Projektori'
            ],
            'Klima uređaji' => [
                'Zidni klima uređaji',
                'Prenosni klima uređaji',
                'Ugradbeni klima uređaji',
                'Grijalice i ventilatori'
            ],
            'Periferija' => [
                'Tastature',
                'Miševi',
                'Monitori',
                'Web kamere',
                'Zvučnici',
                'Mikrofoni',
                'Printeri'
            ],
            'Ostalo' => [
                'Kablovi i adapteri',
                'Torbe i ruksaci',
                'UPS uređaji',
                'Softver',
                'Gaming oprema'
            ]
        ];

        $position = 1;

        foreach ($mainCategories as $mainName => $subcategories) {
            // Insert glavne kategorije
            $mainId = DB::table('categories')->insertGetId([
                'parent_id' => null,
                'name' => $mainName,
                'slug' => Str::slug($mainName),
                'position' => $position++,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Insert podkategorije
            foreach ($subcategories as $index => $subName) {
                DB::table('categories')->insert([
                    'parent_id' => $mainId,
                    'name' => $subName,
                    'slug' => Str::slug($subName),
                    'position' => $index + 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}