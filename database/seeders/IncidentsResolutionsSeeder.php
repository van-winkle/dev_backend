<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class IncidentsResolutionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pho_phone_resolutions')->insert([
            ['title' => 'Me huertaron el phon', 'reply' => 'lorem imsupm','pho_phone_incident_id'=>1, 'adm_employee_id'=>1],
            ['title' => 'Me huertaron la cuestion', 'reply' => 'lorem imsupm','pho_phone_incident_id'=>1, 'adm_employee_id'=>1],
            ['title' => 'Me huertaron el tv', 'reply' => 'lorem imsupm','pho_phone_incident_id'=>1, 'adm_employee_id'=>1],
            ['title' => 'Me huertaron el on fire', 'reply' => 'lorem imsupm','pho_phone_incident_id'=>1, 'adm_employee_id'=>1],
        ]);
    }
}
