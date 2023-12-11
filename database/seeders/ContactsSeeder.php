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
        DB::table('dir_contacts')->insert([
            'name' => 'Kevin Perez',
        ]);
        DB::table('dir_contacts')->insert([
            'name' => 'Cristian Castro',
        ]);
        DB::table('dir_contacts')->insert([
            'name' => 'Leon Laguirre',
        ]);
        DB::table('dir_contacts')->insert([
            'name' => 'Mon Laferte',
        ]);
        DB::table('dir_contacts')->insert([
            'name' => 'Til Linderman',
        ]);
    }
}
