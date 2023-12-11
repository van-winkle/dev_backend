<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pho_phone_brands')->insert(
            [['name' => 'Apple', 'active'=> true],
             ['name' => 'Samsumg', 'active'=> true],
             ['name' => 'Xiaomi' , 'active'=> true],
             ['name' => 'Motorola', 'active'=> true],
             ['name' => 'Huawei','active'=> true]]
        );
    }
}
