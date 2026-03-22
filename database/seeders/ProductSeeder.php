<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Industrial finished goods — idempotent by product code.
     */
    public function run(): void
    {
        $products = [
            [
                'code' => 'PRD-001',
                'name' => 'Steel Frame Assembly',
                'description' => 'Welded structural frame for conveyor and machine mounting; hot-dip galvanized finish, tolerance per ISO 2768-m.',
            ],
            [
                'code' => 'PRD-002',
                'name' => 'Copper Cable Bundle',
                'description' => 'Multi-core power and control cable assembly with numbered cores, flame-retardant jacket, for industrial panel wiring.',
            ],
            [
                'code' => 'PRD-003',
                'name' => 'Plastic Housing Unit',
                'description' => 'Injection-molded ABS enclosure with gasket seat and threaded inserts for field electronics.',
            ],
            [
                'code' => 'PRD-004',
                'name' => 'Aluminum Bracket Set',
                'description' => 'CNC-machined bracket kit with mounting hardware for line-side equipment supports.',
            ],
            [
                'code' => 'PRD-005',
                'name' => 'Industrial Valve Kit',
                'description' => 'Ball valve assembly with seals, lubricant, and fasteners for process fluid isolation.',
            ],
        ];

        foreach ($products as $p) {
            Product::firstOrCreate(
                ['code' => $p['code']],
                ['name' => $p['name'], 'description' => $p['description']]
            );
        }
    }
}
