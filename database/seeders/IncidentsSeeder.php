<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class IncidentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pho_phone_incidents')->insert(
            [
                [
                    'description' => 'Me robaron por andar de bolo',
                    'paymentDifference' => 100.50,
                    'percentage' => 0,
                    'resolution' => null,
                    'date_incident' => '2020-11-11',
                    'date_resolution'=>null,
                    'pho_phone_supervisor_id'=>'1',
                    'adm_employee_id' => 1,
                    'pho_phone_id' => 1,
                    'pho_phone_incident_category_id' => 1,
                ],
                [
                    'description' => 'Se me perdio',
                    'paymentDifference' => 75.25,
                    'percentage' => 0,
                    'resolution' => null,
                    'date_incident' => '2020-11-11',
                    'date_resolution'=>null,
                    'pho_phone_supervisor_id'=>'1',
                    'adm_employee_id' => 2,
                    'pho_phone_id' => 2,
                    'pho_phone_incident_category_id' => 2,
                ],
                [
                    'description' => 'Ya estaba rompido',
                    'paymentDifference' => 50.75,
                    'percentage' => 0,
                    'resolution' => null,
                    'date_incident' => '2020-11-11',
                    'date_resolution'=>null,
                    'pho_phone_supervisor_id'=>'1',
                    'adm_employee_id' => 3,
                    'pho_phone_id' => 3,
                    'pho_phone_incident_category_id' => 4,
                ],
                [
                    'description' => 'Me robaron por boludo',
                    'paymentDifference' => 120.0,
                    'percentage' => 0,
                    'resolution' => null,
                    'date_incident' => '2020-11-11',
                    'date_resolution'=>null,
                    'pho_phone_supervisor_id'=>'1',
                    'adm_emplpoyee_id' => 4,
                    'pho_phone_id' => 4,
                    'pho_phone_incident_category_id' => 1,
                ],
                [
                    'description' => 'No se donde lo deje',
                    'paymentDifference' => 90.25,
                    'percentage' => 0,
                    'resolution' => null,
                    'date_incident' => '2020-11-11',
                    'date_resolution'=>null,
                    'pho_phone_supervisor_id'=>'1',
                    'adm_employee_id' => 5,
                    'pho_phone_id' => 5,
                    'pho_phone_incident_category_id' => 2,
                ],
            ]
        );
    }
}
