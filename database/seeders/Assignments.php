<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class Assignments extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('adm_employee_pho_phone')->insert(
            [
                [
                    'pho_phone_id' => 1,
                    'adm_employee_id' => 1,
                ],
                [
                    'pho_phone_id' => 2,
                    'adm_employee_id' => 1,
                ],
                [
                    'pho_phone_id' => 3,
                    'adm_employee_id' => 2,
                ],

            ]
        );
    }
}
