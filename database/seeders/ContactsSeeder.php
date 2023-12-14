<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContactsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dir_contacts')->insert(
            [['name' => 'Kevin Perez','active' => true],
             ['name' => 'Juan Carlos','active' => true],
             ['name' => 'Sebastian Yatra','active' => true],
             ['name' => 'Luis Ernesto','active' => true],
             ['name' => 'Sofia Jose','active' => true]]
        );
    }
}
