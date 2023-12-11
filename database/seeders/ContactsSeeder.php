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
            [['name' => 'Kevin Perez',],
             ['name' => 'Juan Carlos',],
             ['name' => 'Sebastian Yatra',],
             ['name' => 'Luis Ernesto',],
             ['name' => 'Sofia Jose',]]
        );
    }
}
