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
                ['number' => '1234-1234', 'type'=> 'movil','imei' => 123456789123455, 'price' => 9999.99,'active' => true, 'adm_employee_id' => 1,'pho_phone_plan_id'=>1,'pho_phone_contract_id'=>1,'pho_phone_model_id'=>1],
                ['number' => '1234-1235', 'type'=> 'movil','imei' => 123456789123456, 'price' => 200,'active' => true, 'adm_employee_id' => 2,'pho_phone_plan_id'=>2,'pho_phone_contract_id'=>2,'pho_phone_model_id'=>2],
                ['number' => '1234-1236', 'type'=> 'movil','imei' => 123456789123457, 'price' => 300,'active' => true, 'adm_employee_id' => 3,'pho_phone_plan_id'=>3,'pho_phone_contract_id'=>3,'pho_phone_model_id'=>3],
                ['number' => '1234-1237', 'type'=> 'movil','imei' => 123456789123458, 'price' => 400,'active' => true, 'adm_employee_id' => 4,'pho_phone_plan_id'=>4,'pho_phone_contract_id'=>4,'pho_phone_model_id'=>4],
                ['number' => '1234-1238', 'type'=> 'movil','imei' => 123456789123459, 'price' => 500,'active' => true, 'adm_employee_id' => 5,'pho_phone_plan_id'=>5,'pho_phone_contract_id'=>5,'pho_phone_model_id'=>5]
            ]
        );
    }
}
