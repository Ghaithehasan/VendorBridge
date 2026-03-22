<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * USD, EUR, TRY, CNY, AED, GBP, SAR — idempotent by currency code.
     */
    public function run(): void
    {
        $rows = [
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$'],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            ['code' => 'TRY', 'name' => 'Turkish Lira', 'symbol' => '₺'],
            ['code' => 'CNY', 'name' => 'Chinese Yuan', 'symbol' => '¥'],
            ['code' => 'AED', 'name' => 'UAE Dirham', 'symbol' => 'AED'],
            ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£'],
            ['code' => 'SAR', 'name' => 'Saudi Riyal', 'symbol' => 'SAR'],
        ];

        foreach ($rows as $row) {
            Currency::firstOrCreate(
                ['code' => $row['code']],
                ['name' => $row['name'], 'symbol' => $row['symbol']]
            );
        }
    }
}
