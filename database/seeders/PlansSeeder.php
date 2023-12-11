<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pho_phone_plans')->insert(
            [['name' => 'plan1', 'mobile_data'=> 600,'roaming_data' => 600, 'minutes' => 600, 'roaming_minutes' => 600,'active' => true,'type'=>'movil'],
            ['name' => 'plan2', 'mobile_data'=> 500,'roaming_data' => 500, 'minutes' => 500, 'roaming_minutes' => 500,'active' => true,'type'=>'movil'],
            ['name' => 'plan3', 'mobile_data'=> 400,'roaming_data' => 400, 'minutes' => 400, 'roaming_minutes' => 400,'active' => true,'type'=>'movil'],
            ['name' => 'plan4', 'mobile_data'=> 300,'roaming_data' => 300, 'minutes' => 300, 'roaming_minutes' => 300,'active' => true,'type'=>'movil'],
            ['name' => 'plan5', 'mobile_data'=> 200,'roaming_data' => 200, 'minutes' => 200, 'roaming_minutes' => 200,'active' => true,'type'=>'movil']]
        );
    }
}
