<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Idempotent by department name.
     */
    public function run(): void
    {
        foreach (['Production', 'Procurement', 'Maintenance', 'Warehouse'] as $name) {
            Department::firstOrCreate(['name' => $name]);
        }
    }
}
