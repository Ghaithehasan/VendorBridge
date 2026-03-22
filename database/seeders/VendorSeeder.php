<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * International industrial suppliers — idempotent by unique email.
     */
    public function run(): void
    {
        $vendors = [
            [
                'name' => 'SteelTech GmbH',
                'email' => 'sales@steeltech-gmbh.de',
                'phone' => '+49-30-8845-2200',
                'country' => 'Germany',
            ],
            [
                'name' => 'AluCo Trading Co.',
                'email' => 'procurement@aluco-trading.com.tr',
                'phone' => '+90-212-555-4410',
                'country' => 'Turkey',
            ],
            [
                'name' => 'PlastiMold Industries',
                'email' => 'export@plastimold-sh.cn',
                'phone' => '+86-21-6012-8830',
                'country' => 'China',
            ],
            [
                'name' => 'MetalCraft Middle East',
                'email' => 'quotes@metalcraft-me.ae',
                'phone' => '+971-4-388-9900',
                'country' => 'United Arab Emirates',
            ],
            [
                'name' => 'EuroCable Systems',
                'email' => 'rfq@eurocable-systems.de',
                'phone' => '+49-89-4521-7700',
                'country' => 'Germany',
            ],
        ];

        foreach ($vendors as $v) {
            Vendor::firstOrCreate(
                ['email' => $v['email']],
                [
                    'name' => $v['name'],
                    'phone' => $v['phone'],
                    'country' => $v['country'],
                ]
            );
        }
    }
}
