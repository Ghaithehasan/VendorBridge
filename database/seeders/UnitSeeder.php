<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Manufacturing units — idempotent by symbol.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'Kilogram', 'symbol' => 'kg'],
            ['name' => 'Gram', 'symbol' => 'g'],
            ['name' => 'Metric Ton', 'symbol' => 'ton'],
            ['name' => 'Liter', 'symbol' => 'liter'],
            ['name' => 'Meter', 'symbol' => 'm'],
            ['name' => 'Square Meter', 'symbol' => 'm²'],
            ['name' => 'Piece', 'symbol' => 'piece'],
            ['name' => 'Box', 'symbol' => 'box'],
            ['name' => 'Roll', 'symbol' => 'roll'],
        ];

        // Resolve by `name` (unique) so re-seeding does not clash with legacy symbol values (e.g. meter vs m).
        foreach ($units as $u) {
            Unit::firstOrCreate(
                ['name' => $u['name']],
                ['symbol' => $u['symbol']]
            );
        }
    }
}
