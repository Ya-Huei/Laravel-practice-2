<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesSeeder::class,
            PermissionsSeeder::class,
            LocationsSeeder::class,
            FirmsSeeder::class,
            UsersSeeder::class,
            StatusesSeeder::class,
            DevicesSeeder::class,
            FirmwareSeeder::class,
            RecipesSeeder::class,
            OtaRecordsSeeder::class,
            RepairRecordsSeeder::class,
            RecipeStepsSeeder::class,
            RecipeActionsSeeder::class,
        ]);
    }
}
