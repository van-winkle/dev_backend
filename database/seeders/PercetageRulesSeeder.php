<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PercetageRulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pho_phone_percentage_rules')->insert(
            [
                ['percentage_discount' => 20.00, 'pho_phone_contract_id' => 1],
                ['percentage_discount' => 50, 'pho_phone_contract_id' => 1],
                ['percentage_discount' => 70.50, 'pho_phone_contract_id' => 1],
                ['percentage_discount' => 100, 'pho_phone_contract_id' => 1],
                ['percentage_discount' => 20, 'pho_phone_contract_id' => 2],
                ['percentage_discount' => 50, 'pho_phone_contract_id' => 2],
            ]
        );
    }
}
