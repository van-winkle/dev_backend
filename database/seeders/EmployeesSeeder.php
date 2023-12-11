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
            'name' => 'Alejandro Argueta',
        ]);
        DB::table('adm_employees')->insert([
            'name' => 'Hazel Paola',
        ]);
        DB::table('adm_employees')->insert([
            'name' => 'Carlos Eduardo',
        ]);
        DB::table('adm_employees')->insert([
            'name' => 'Miguel Jose',
        ]);
        DB::table('adm_employees')->insert([
            'name' => 'Oscar Reyes',
        ]);
    }
}
