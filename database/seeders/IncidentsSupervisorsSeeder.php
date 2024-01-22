<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class IncidentsSupervisorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pho_phone_incident_supervisors')->insert([
            ['adm_employee_id'=>1],
            ['adm_employee_id'=>1],
            ['adm_employee_id'=>1],
            ['adm_employee_id'=>1],
        ]);
    }
}
