<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PhonesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pho_phones')->insert(
            [
                ['number' => '1234-1234', 'imei' => 123456789123455, 'price' => 9999.99,'active' => true, 'pho_phone_type_phone_id' => 1,'adm_employee_id' => 1,'pho_phone_plan_id'=>1,'pho_phone_contract_id'=>1,'pho_phone_model_id'=>1, 'adm_manager_id'=>1],
                ['number' => '1234-1235', 'imei' => 123456789123456, 'price' => 200,'active' => true, 'pho_phone_type_phone_id' => 2,'adm_employee_id' => 2,'pho_phone_plan_id'=>1,'pho_phone_contract_id'=>1,'pho_phone_model_id'=>1, 'adm_manager_id'=>1],
                ['number' => '1234-1236', 'imei' => 123456789123457, 'price' => 300,'active' => true, 'pho_phone_type_phone_id' => 1,'adm_employee_id' => 3,'pho_phone_plan_id'=>3,'pho_phone_contract_id'=>3,'pho_phone_model_id'=>3, 'adm_manager_id'=>2],
                ['number' => '1234-1237', 'imei' => 123456789123458, 'price' => 400,'active' => true, 'pho_phone_type_phone_id' => 2,'adm_employee_id' => 4,'pho_phone_plan_id'=>4,'pho_phone_contract_id'=>4,'pho_phone_model_id'=>4, 'adm_manager_id'=>null],
                ['number' => '1234-1238', 'imei' => 123456789123459, 'price' => 500,'active' => true, 'pho_phone_type_phone_id' => 1,'adm_employee_id' => 5,'pho_phone_plan_id'=>5,'pho_phone_contract_id'=>5,'pho_phone_model_id'=>5, 'adm_manager_id'=>null]
            ]
        );
    }
}
