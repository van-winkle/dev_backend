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
            ['name' => 'Alejandro', 'last_name'=> 'Argueta', 'active' => true],
            ['name' => 'Hazel', 'last_name'=> 'Molina', 'active' => true],
             ['name' => 'Carlos', 'last_name'=> 'Sanchez', 'active' => true],
            ['name' => 'David', 'last_name'=> 'Rivera', 'active' => true],
            ['name' => 'Sarai', 'last_name'=> 'Fulanito', 'active' => true],
        ]);

    }
}
