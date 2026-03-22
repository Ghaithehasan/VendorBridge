<?php

namespace Database\Seeders;

use App\Models\RawMaterial;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class RawMaterialSeeder extends Seeder
{
    /**
     * Raw materials — base unit resolved by unit symbol. Idempotent by material name.
     */
    public function run(): void
    {
        $materials = [
            ['name' => 'Structural Steel', 'unit_name' => 'Kilogram'],
            ['name' => 'Copper Wire', 'unit_name' => 'Kilogram'],
            ['name' => 'ABS Plastic', 'unit_name' => 'Kilogram'],
            ['name' => 'Aluminum Sheet', 'unit_name' => 'Square Meter'],
            ['name' => 'Rubber Gasket', 'unit_name' => 'Piece'],
            ['name' => 'Stainless Steel Bolt', 'unit_name' => 'Piece'],
            ['name' => 'PVC Pipe', 'unit_name' => 'Meter'],
            ['name' => 'Industrial Lubricant', 'unit_name' => 'Liter'],
        ];

        foreach ($materials as $m) {
            $unit = Unit::where('name', $m['unit_name'])->first();
            if ($unit === null) {
                throw new \RuntimeException("Unit not found: {$m['unit_name']}. Run UnitSeeder first.");
            }

            RawMaterial::firstOrCreate(
                ['name' => $m['name']],
                ['base_unit_id' => $unit->unit_id]
            );
        }
    }
}
