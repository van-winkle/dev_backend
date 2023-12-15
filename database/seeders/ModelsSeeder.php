<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ModelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pho_phone_models')->insert(
            [
             ['name' => 'iPhone 15 Pro Max', 'active'=> true,'pho_phone_brand_id'=>1],
             ['name' => 'iPhone 14 Pro Max', 'active'=> true,'pho_phone_brand_id'=>1],
             ['name' => 'Galaxy A03', 'active'=> true,'pho_phone_brand_id'=>2],
             ['name' => 'Xiaomi Redmi Note 12 Pro+' , 'active'=> true,'pho_phone_brand_id'=>3],
             ['name' => 'moto g84 5g', 'active'=> true,'pho_phone_brand_id'=>4],
             ['name' => 'P Series. HUAWEI P60 Pro','active'=> true,'pho_phone_brand_id'=>5]
             ]
        );
    }
}
