<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Phones\IncidentsAttaches;
use Illuminate\Database\Seeder;
use Database\Seeders\EmployeesSeeder;
use SebastianBergmann\CodeCoverage\Util\Percentage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            EmployeesSeeder::class,
            ContactsSeeder::class,
            BrandsSeeder::class,
            ModelsSeeder::class,
            ContractsSeeder::class,
            TypePhonesSeeder::class,
            PlansSeeder::class,
            PhonesSeeder::class,
            IncidentCategorySeeder::class,
            IncidentsSeeder::class,
            IncidentAttachesSeeder::class,
            Assignments::class,
            IncidentsResolutionsSeeder::class,
            PercentageRulesSeeder::class,
            ContractAttachesSeeder::class,

        ]);
    }
}
