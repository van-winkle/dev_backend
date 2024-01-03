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
        DB::table('pho_phone_incidents')->insert(
            [
                [
                    'paymentDifference' => 100.50,
                    'percentage' => 15.5,
                    'pho_phone_id' => 1,
                    'pho_phone_incident_category_id' => 1,
                ],
                [
                    'paymentDifference' => 75.25,
                    'percentage' => 20.0,
                    'pho_phone_id' => 2,
                    'pho_phone_incident_category_id' => 2,
                ],
                [
                    'paymentDifference' => 50.75,
                    'percentage' => 12.5,
                    'pho_phone_id' => 3,
                    'pho_phone_incident_category_id' => 1,
                ],
                [
                    'paymentDifference' => 120.0,
                    'percentage' => 18.75,
                    'pho_phone_id' => 4,
                    'pho_phone_incident_category_id' => 1,
                ],
                [
                    'paymentDifference' => 90.25,
                    'percentage' => 22.5,
                    'pho_phone_id' => 5,
                    'pho_phone_incident_category_id' => 2,
                ],
            ]
            // [
            //     ['file_name' => 'file1', 'file_name_original' => 'file', 'file_mimetype' => 'file', 'file_size' => 'file', 'file_path' => 'file', 'percentage' => 15, 'pho_phone_id' => 1, 'pho_phone_incident_category_id' => 1],
            //     ['file_name' => 'file2', 'file_name_original' => 'file', 'file_mimetype' => 'file', 'file_size' => 'file', 'file_path' => 'file', 'percentage' => 15, 'pho_phone_id' => 1, 'pho_phone_incident_category_id' => 1],
            //     ['file_name' => 'file3', 'file_name_original' => 'file', 'file_mimetype' => 'file', 'file_size' => 'file', 'file_path' => 'file', 'percentage' => 15, 'pho_phone_id' => 3, 'pho_phone_incident_category_id' => 3],
            //     ['file_name' => 'file4', 'file_name_original' => 'file', 'file_mimetype' => 'file', 'file_size' => 'file', 'file_path' => 'file', 'percentage' => 15, 'pho_phone_id' => 4, 'pho_phone_incident_category_id' => 4],
            //     ['file_name' => 'fil4', 'file_name_original' => 'file', 'file_mimetype' => 'file', 'file_size' => 'file', 'file_path' => 'file', 'percentage' => 15, 'pho_phone_id' => 5, 'pho_phone_incident_category_id' => 5],
            // ]

            // [
            //     ['file_name' => 'file1', 'file_name_original' => 'file', 'file_mimetype' => 'file', 'file_size' => 'file', 'file_path' => 'file', 'price' => 458.55, 'percentage' => 15, 'pho_phone_id' => 1, 'pho_phone_incident_category_id' => 1],
            //     ['file_name' => 'file2', 'file_name_original' => 'file', 'file_mimetype' => 'file', 'file_size' => 'file', 'file_path' => 'file', 'price' => 358.85, 'percentage' => 15, 'pho_phone_id' => 1, 'pho_phone_incident_category_id' => 1],
            //     ['file_name' => 'file3', 'file_name_original' => 'file', 'file_mimetype' => 'file', 'file_size' => 'file', 'file_path' => 'file', 'price' => 258.55, 'percentage' => 15, 'pho_phone_id' => 3, 'pho_phone_incident_category_id' => 3],
            //     ['file_name' => 'file4', 'file_name_original' => 'file', 'file_mimetype' => 'file', 'file_size' => 'file', 'file_path' => 'file', 'price' => 158.55, 'percentage' => 15, 'pho_phone_id' => 4, 'pho_phone_incident_category_id' => 4],
            //     ['file_name' => 'fil4', 'file_name_original' => 'file', 'file_mimetype' => 'file', 'file_size' => 'file', 'file_path' => 'file', 'price' => 788.55, 'percentage' => 15, 'pho_phone_id' => 5, 'pho_phone_incident_category_id' => 5],
            // ]
        );
    }
}
