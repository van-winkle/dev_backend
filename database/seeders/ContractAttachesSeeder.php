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
            // ONLY RETURN [TEXT] CUZ THIS ISN'T A FILE
            // [
            //     'file_name_original' => 'incident1_original.jpg',
            //     'name' => 'incident1',
            //     'file_size' => '1024',
            //     'file_extension' => 'jpg',
            //     'file_mimetype' => 'image/png',
            //     'file_location' => '/path/to/incident1',
            //     'pho_phone_contract_id' => 1,
            // ],
        ]);
    }
}
