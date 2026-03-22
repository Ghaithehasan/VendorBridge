<?php

namespace Database\Seeders;

use App\Models\Bom;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class BomSeeder extends Seeder
{
    /**
     * BOM lines — product/material resolved by code/name; units by symbol.
     * Idempotent on unique (product_id, material_id).
     */
    public function run(): void
    {
        $lines = [
            // PRD-001 Steel Frame Assembly
            ['product_code' => 'PRD-001', 'material' => 'Structural Steel', 'qty' => 50.0000, 'unit_name' => 'Kilogram'],
            ['product_code' => 'PRD-001', 'material' => 'Stainless Steel Bolt', 'qty' => 200.0000, 'unit_name' => 'Piece'],
            ['product_code' => 'PRD-001', 'material' => 'Rubber Gasket', 'qty' => 24.0000, 'unit_name' => 'Piece'],

            // PRD-002 Copper Cable Bundle
            ['product_code' => 'PRD-002', 'material' => 'Copper Wire', 'qty' => 120.0000, 'unit_name' => 'Kilogram'],
            ['product_code' => 'PRD-002', 'material' => 'ABS Plastic', 'qty' => 8.0000, 'unit_name' => 'Kilogram'],
            ['product_code' => 'PRD-002', 'material' => 'PVC Pipe', 'qty' => 85.0000, 'unit_name' => 'Meter'],

            // PRD-003 Plastic Housing Unit
            ['product_code' => 'PRD-003', 'material' => 'ABS Plastic', 'qty' => 42.0000, 'unit_name' => 'Kilogram'],
            ['product_code' => 'PRD-003', 'material' => 'Stainless Steel Bolt', 'qty' => 64.0000, 'unit_name' => 'Piece'],
            ['product_code' => 'PRD-003', 'material' => 'Rubber Gasket', 'qty' => 18.0000, 'unit_name' => 'Piece'],

            // PRD-004 Aluminum Bracket Set
            ['product_code' => 'PRD-004', 'material' => 'Aluminum Sheet', 'qty' => 12.0000, 'unit_name' => 'Square Meter'],
            ['product_code' => 'PRD-004', 'material' => 'Stainless Steel Bolt', 'qty' => 150.0000, 'unit_name' => 'Piece'],
            ['product_code' => 'PRD-004', 'material' => 'Structural Steel', 'qty' => 6.0000, 'unit_name' => 'Kilogram'],

            // PRD-005 Industrial Valve Kit
            ['product_code' => 'PRD-005', 'material' => 'Stainless Steel Bolt', 'qty' => 48.0000, 'unit_name' => 'Piece'],
            ['product_code' => 'PRD-005', 'material' => 'Rubber Gasket', 'qty' => 20.0000, 'unit_name' => 'Piece'],
            ['product_code' => 'PRD-005', 'material' => 'Industrial Lubricant', 'qty' => 2.5000, 'unit_name' => 'Liter'],
            ['product_code' => 'PRD-005', 'material' => 'Copper Wire', 'qty' => 3.0000, 'unit_name' => 'Kilogram'],
        ];

        foreach ($lines as $line) {
            $product = Product::where('code', $line['product_code'])->first();
            $material = RawMaterial::where('name', $line['material'])->first();
            $unit = Unit::where('name', $line['unit_name'])->first();

            if ($product === null || $material === null || $unit === null) {
                throw new \RuntimeException('BOM seed missing product, material, or unit for: '.json_encode($line));
            }

            Bom::firstOrCreate(
                [
                    'product_id' => $product->product_id,
                    'material_id' => $material->material_id,
                ],
                [
                    'quantity_required' => $line['qty'],
                    'unit_id' => $unit->unit_id,
                ]
            );
        }
    }
}
