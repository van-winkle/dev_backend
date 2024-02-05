<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContractAttachesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pho_phone_contract_attaches')->insert([
            // [
            //     'file_name_original' => 'incident1_original.jpg',
            //     'name' => 'incident1',
            //     'file_size' => '1024',
            //     'file_extension' => 'jpg',
            //     'file_mimetype' => 'image/png',
            //     'file_location' => '/path/to/incident1',
            //     'pho_phone_contract_id' => 1,
            // ],
            // [
            //     'file_name_original' => 'incident2_original.pdf',
            //     'name' => 'incident2',
            //     'file_size' => '1024',
            //     'file_extension' => 'jpg',
            //     'file_mimetype' => 'image/png',
            //     'file_location' => '/path/to/incident2',
            //     'pho_phone_contract_id' => 1,
            // ],
            // [
            //     'file_name_original' => 'incident3_original.pdf',
            //     'name' => 'incident3',
            //     'file_size' => '2048',
            //     'file_extension' => 'png',
            //     'file_mimetype' => 'image/png',
            //     'file_location' => '/path/to/incident2',
            //     'pho_phone_contract_id' => 3,
            // ],
            // [
            //     'file_name_original' => 'incident4_original.pdf',
            //     'name' => 'incident4',
            //     'file_size' => '3072',
            //     'file_extension' => 'gif',
            //     'file_mimetype' => 'image/gif',
            //     'file_location' => '/path/to/incident3',
            //     'pho_phone_contract_id' => 4,
            // ],
            // [
            //     'file_name_original' => 'incident5_original.jpg',
            //     'name' => 'incident5',
            //     'file_size' => '4096',
            //     'file_extension' => 'jpeg',
            //     'file_mimetype' => 'image/jpeg',
            //     'file_location' => '/path/to/incident4',
            //     'pho_phone_contract_id' => 1,
            // ],
        ]);
    }
}
