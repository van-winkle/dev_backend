<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class IncidentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pho_phone_incident_categories')->insert([
            ['name' => 'Robo', 'active' => true],
            ['name' => 'Perdida', 'active' => true],
             ['name' => 'Accidente', 'active' => true],
            ['name' => 'Error de Fabrica', 'active' => true],
        ]);
    }
}
