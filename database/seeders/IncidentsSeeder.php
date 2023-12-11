<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class IncidentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pho_phones_incidents')->insert(
            [['file_name' => 'file1', 'file_name_original'=> 'file','file_mimetype' => 'file', 'file_size' => 'file','file_path' => 'file', 'porcentage' => 15,'pho_phone_id'=>1],
            ['file_name' => 'file2', 'file_name_original'=> 'file','file_mimetype' => 'file', 'file_size' => 'file','file_path' => 'file', 'porcentage' => 15,'pho_phone_id'=>2],
            ['file_name' => 'file3', 'file_name_original'=> 'file','file_mimetype' => 'file', 'file_size' => 'file','file_path' => 'file', 'porcentage' => 15,'pho_phone_id'=>3],
            ['file_name' => 'file4', 'file_name_original'=> 'file','file_mimetype' => 'file', 'file_size' => 'file','file_path' => 'file', 'porcentage' => 15,'pho_phone_id'=>4],
            ['file_name' => 'fil4', 'file_name_original'=> 'file','file_mimetype' => 'file', 'file_size' => 'file','file_path' => 'file', 'porcentage' => 15,'pho_phone_id'=>5],]
        );
    }
}
