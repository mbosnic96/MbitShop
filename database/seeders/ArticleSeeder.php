<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $names = [
            'Gaming laptop', 'Ultrabook', 'Monitor za igrice', 'Profesionalni printer', 'Smart telefon', 'Grafička karta', 'Desktop računar', 'SSD Disk', 'RAM Memorija', 'Zvučnik s Bluetoothom',
            'Pametni TV', 'Klima uređaj', 'Gaming miš', 'Bežična tastatura', 'All-in-One PC', 'Server sistem', 'Projektor', 'Tablet uređaj', 'Laptop za posao', 'Slušalice s mikrofonom'
        ];

        $processors = ['Intel i5', 'Intel i7', 'AMD Ryzen 5', 'AMD Ryzen 7', null];
        $ramOptions = ['8', '16', '32', null];
        $storageOptions = ['256', '512', '1', '1', null];
        $graphicsOptions = ['NVIDIA GTX 1660', 'NVIDIA RTX 3060', 'AMD Radeon RX 6600', null];
        $osOptions = ['Windows 10', 'Windows 11', 'Linux', 'Bez operativnog sistema'];

        $imageLogos = [
            'products/apple.svg',
            'products/asus.png',
            'products/samsung.png',
            'products/gigabyte.png',
            'products/amd.webp',
            'products/nvidia.png',
        ];

        $promoIndexes = collect(range(0, 49))->random(5)->toArray();
        $discountIndexes = collect(range(0, 49))->diff($promoIndexes)->random(10)->toArray();

        $brandIds = DB::table('brands')->pluck('id')->toArray();
        $categoryIds = DB::table('categories')->pluck('id')->toArray();

        for ($i = 0; $i < 50; $i++) {
            $name = $names[array_rand($names)] . " " . strtoupper(Str::random(3));
            $price = rand(300, 2500);
            $discount = in_array($i, $discountIndexes) ? rand(10, 50) : 0;

            DB::table('products')->insert([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => 'Savršen uređaj za svakodnevnu upotrebu, dolazi sa naprednim performansama i modernim dizajnom.',
                'price' => $price,
                'promo' => in_array($i, $promoIndexes) ? 1 : 0,
                'stock_quantity' => rand(5, 50),
                'model' => strtoupper(Str::random(5)) . '-' . rand(100, 999),
                'processor' => $processors[array_rand($processors)],
                'ram_size' => $ramOptions[array_rand($ramOptions)],
                'storage' => $storageOptions[array_rand($storageOptions)],
                'graphics_card' => $graphicsOptions[array_rand($graphicsOptions)],
                'operating_system' => $osOptions[array_rand($osOptions)],
                'screen_size' => rand(13, 32),
                'discount' => $discount,
               'image' => json_encode([$imageLogos[array_rand($imageLogos)]]),
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'brand_id' => $brandIds[array_rand($brandIds)],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
