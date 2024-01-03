<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncidentAttachesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pho_phone_incident_attaches')->insert([
            [
                'file_name_original' => 'incident1_original',
                'file_name' => 'incident1',
                'file_size' => '1024',
                'file_extension' => 'jpg',
                'file_mimetype' => 'image/png',
                'file_location' => '/path/to/incident1',
                'pho_phone_incident_id' => 1,
            ],
            [
                'file_name_original' => 'incident2_original',
                'file_name' => 'incident2',
                'file_size' => '1024',
                'file_extension' => 'jpg',
                'file_mimetype' => 'image/png',
                'file_location' => '/path/to/incident2',
                'pho_phone_incident_id' => 1,
            ],
            [
                'file_name_original' => 'incident3_original',
                'file_name' => 'incident3',
                'file_size' => '2048',
                'file_extension' => 'png',
                'file_mimetype' => 'image/png',
                'file_location' => '/path/to/incident2',
                'pho_phone_incident_id' => 3,
            ],
            [
                'file_name_original' => 'incident4_original',
                'file_name' => 'incident4',
                'file_size' => '3072',
                'file_extension' => 'gif',
                'file_mimetype' => 'image/gif',
                'file_location' => '/path/to/incident3',
                'pho_phone_incident_id' => 4,
            ],
            [
                'file_name_original' => 'incident5_original',
                'file_name' => 'incident5',
                'file_size' => '4096',
                'file_extension' => 'jpeg',
                'file_mimetype' => 'image/jpeg',
                'file_location' => '/path/to/incident4',
                'pho_phone_incident_id' => 1,
            ],
        ]);
    }
}
