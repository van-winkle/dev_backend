<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class TypePhonesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('pho_phone_type_phones')->insert(
            [
                ['name' => 'Movil', 'active' => true],
                ['name' => 'Fijo', 'active' => true]
            ]

        );
    }
}
