<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            StatesSeeder::class,
            CmsModuleSeeder::class,
            CmsModulePermissions::class,
            IncomeTypeSeeder::class,
            IncomeCategorySeeder::class,
            TaxCategorySeeder::class,
            DeductionCategorySeeder::class,
            CompanyTypeSeeder::class,
            PackageSeeder::class,
            StatesSeeder::class,
        ]);
    }
}
