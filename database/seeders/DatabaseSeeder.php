<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database (dependency order).
     */
    public function run(): void
    {
        $this->call([
            CurrencySeeder::class,
            UnitSeeder::class,
            DepartmentSeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
            RawMaterialSeeder::class,
            BomSeeder::class,
            VendorSeeder::class,
            VendorMaterialSeeder::class,
        ]);
    }
}
