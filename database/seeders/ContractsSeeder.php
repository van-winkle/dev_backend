<?php

namespace Database\Seeders;


use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContractsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pho_phone_contracts')->insert(
            [['code' => '1a', 'start_date'=> Carbon::now(),'expiry_date' => Carbon::now(), 'active' => true, 'dir_contact_id' => 1],
            ['code' => '2a', 'start_date'=> Carbon::now(),'expiry_date' => Carbon::now(), 'active' => true, 'dir_contact_id' => 1],
            ['code' => '3a', 'start_date'=> Carbon::now(),'expiry_date' => Carbon::now(), 'active' => true, 'dir_contact_id' => 3],
            ['code' => '4a', 'start_date'=> Carbon::now(),'expiry_date' => Carbon::now(), 'active' => true, 'dir_contact_id' => 4],
            ['code' => '5a', 'start_date'=> Carbon::now(),'expiry_date' => Carbon::now(), 'active' => true, 'dir_contact_id' => 5]]
        );
    }
}
