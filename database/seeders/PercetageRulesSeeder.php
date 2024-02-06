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
                ['percentage_discount' => 33.44, 'pho_phone_contract_id' => 2],
                ['percentage_discount' => 65.77, 'pho_phone_contract_id' => 2],
                ['percentage_discount' => 88.55, 'pho_phone_contract_id' => 2],
                ['percentage_discount' => 13, 'pho_phone_contract_id' => 3],
                ['percentage_discount' => 45, 'pho_phone_contract_id' => 3],
                ['percentage_discount' => 76, 'pho_phone_contract_id' => 3],
                ['percentage_discount' => 12, 'pho_phone_contract_id' => 4],
                ['percentage_discount' => 45, 'pho_phone_contract_id' => 4],
                ['percentage_discount' => 56, 'pho_phone_contract_id' => 4],
                ['percentage_discount' => 100, 'pho_phone_contract_id' => 4],
                ['percentage_discount' => 23, 'pho_phone_contract_id' => 5],
                ['percentage_discount' => 45, 'pho_phone_contract_id' => 5],
                ['percentage_discount' => 56, 'pho_phone_contract_id' => 5],
                ['percentage_discount' => 67, 'pho_phone_contract_id' => 5],
            ]
        );
    }
}
