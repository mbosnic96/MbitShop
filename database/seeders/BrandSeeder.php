<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BrandSeeder extends Seeder
{

        public function run()
        {
            $brands = [
               
                'Intel',
                'AMD',
                'NVIDIA',
                'ASUS',
                'MSI',
                'Gigabyte',
                'Corsair',
                'Cooler Master',
                'EVGA',
                'Thermaltake',
                'NZXT',
                'G.Skill',
                'Kingston',
                'Crucial',
                'Western Digital',
                'Seagate',
                'Samsung',
                'Adata',
    
              
                'Apple',
                'Samsung',
                'Xiaomi',
                'OnePlus',
                'Oppo',
                'Vivo',
                'Huawei',
                'Realme',
                'Motorola',
                'Nokia',
                'Google',
    
              
                'LG',
                'Sony',
                'TCL',
                'Philips',
                'Panasonic',
                'Hisense',
                'Sharp',
                'Samsung',
                'Vizio',
    
             
                'Daikin',
                'Mitsubishi Electric',
                'Hitachi',
                'Carrier',
                'Gree',
                'Haier',
                'LG',
                'Panasonic',
                'Samsung',
                'Toshiba',
    
              
                'Logitech',
                'Razer',
                'Anker',
                'JBL',
                'Bose',
                'Beats',
                'Canon',
                'Nikon',
                'Sony',
                'HP',
                'Dell',
                'Lenovo',
                'Acer',
            ];
    
            foreach (array_unique($brands) as $brand) {
                DB::table('brands')->insert([
                    'name' => $brand,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
    