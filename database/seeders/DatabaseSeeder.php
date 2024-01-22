<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Phones\IncidentsAttaches;
use Illuminate\Database\Seeder;
use Database\Seeders\EmployeesSeeder;

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
            IncidentsSupervisorsSeeder::class,
            IncidentsResolutionsSeeder::class

        ]);

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
