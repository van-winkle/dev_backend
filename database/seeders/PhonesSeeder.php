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
            [['number' => 101, 'type'=> 'movil','imei' => 101, 'price' => 100,'active' => true, 'adm_employee_id' => 1,'pho_phone_plan_id'=>1,'pho_phone_contract_id'=>1,'pho_phone_model_id'=>1],
            ['number' => 102, 'type'=> 'movil','imei' => 102, 'price' => 200,'active' => true, 'adm_employee_id' => 2,'pho_phone_plan_id'=>2,'pho_phone_contract_id'=>2,'pho_phone_model_id'=>2],
            ['number' => 103, 'type'=> 'movil','imei' => 104, 'price' => 300,'active' => true, 'adm_employee_id' => 3,'pho_phone_plan_id'=>3,'pho_phone_contract_id'=>3,'pho_phone_model_id'=>3],
            ['number' => 104, 'type'=> 'movil','imei' => 105, 'price' => 400,'active' => true, 'adm_employee_id' => 4,'pho_phone_plan_id'=>4,'pho_phone_contract_id'=>4,'pho_phone_model_id'=>4],
            ['number' => 105, 'type'=> 'movil','imei' => 106, 'price' => 500,'active' => true, 'adm_employee_id' => 5,'pho_phone_plan_id'=>5,'pho_phone_contract_id'=>5,'pho_phone_model_id'=>5]]
        );
    }
}
