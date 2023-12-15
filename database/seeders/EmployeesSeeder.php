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
            ['name' => 'Alejandro Argueta', 'active' => true],
            ['name' => 'Hazel Paola', 'active' => true],
            ['name' => 'Carlos Eduardo', 'active' => true],
            ['name' => 'David Mendez', 'active' => true],
            ['name' => 'Sarai Buendia', 'active' => true],
        ]);

    }
}
