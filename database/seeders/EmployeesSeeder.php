<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('adm_employees')->insert([
            ['name' => 'Elizabeth', 'last_name' => 'Olsen', 'active' => true],
            ['name' => 'Ana', 'last_name' => 'de Armas', 'active' => true],
            ['name' => 'Carlos', 'last_name' => 'Sanchez', 'active' => true],
            ['name' => 'Juan', 'last_name' => 'Fulano', 'active' => true],
            ['name' => 'John', 'last_name' => 'Doe', 'active' => true],
        ]);
    }
}
