<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\RawMaterial;
use App\Models\Vendor;
use App\Models\VendorMaterial;
use Illuminate\Database\Seeder;
class VendorMaterialSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            // ── SteelTech GmbH (Germany, EUR) ── steel specialist
            [
                'vendor'               => 'SteelTech GmbH',
                'material'             => 'Structural Steel',
                'currency'             => 'EUR',
                'last_price'           => 1.7200,
                'lead_time_days'       => 21,
                'minimum_order_qty'    => 8000.0000,
                'preferred_vendor'     => true,
                'vendor_material_code' => 'ST-ST4420-HR',
            ],
            [
                'vendor'               => 'SteelTech GmbH',
                'material'             => 'Stainless Steel Bolt',
                'currency'             => 'EUR',
                'last_price'           => 0.0950,
                'lead_time_days'       => 14,
                'minimum_order_qty'    => 5000.0000,
                'preferred_vendor'     => false,
                'vendor_material_code' => 'ST-BOLT-A2-70-M10',
            ],

            // ── AluCo Trading Co. (Turkey, TRY) ── aluminum specialist
            [
                'vendor'               => 'AluCo Trading Co.',
                'material'             => 'Aluminum Sheet',
                'currency'             => 'TRY',
                'last_price'           => 3120.0000,
                'lead_time_days'       => 28,
                'minimum_order_qty'    => 120.0000,
                'preferred_vendor'     => true,
                'vendor_material_code' => 'AC-AL5083-4MM',
            ],

            // ── PlastiMold Industries (China, USD) ── plastics & rubber specialist
            [
                'vendor'               => 'PlastiMold Industries',
                'material'             => 'ABS Plastic',
                'currency'             => 'USD',
                'last_price'           => 2.4500,
                'lead_time_days'       => 22,
                'minimum_order_qty'    => 2000.0000,
                'preferred_vendor'     => true,
                'vendor_material_code' => 'PM-ABS-NC010',
            ],
            [
                'vendor'               => 'PlastiMold Industries',
                'material'             => 'Rubber Gasket',
                'currency'             => 'USD',
                'last_price'           => 0.6800,
                'lead_time_days'       => 18,
                'minimum_order_qty'    => 5000.0000,
                'preferred_vendor'     => true,
                'vendor_material_code' => 'PM-NBR-70A-ORING',
            ],
            [
                'vendor'               => 'PlastiMold Industries',
                'material'             => 'PVC Pipe',
                'currency'             => 'USD',
                'last_price'           => 3.2000,
                'lead_time_days'       => 15,
                'minimum_order_qty'    => 1000.0000,
                'preferred_vendor'     => false,
                'vendor_material_code' => 'PM-PVC-SCH40-2IN',
            ],

            // ── MetalCraft Middle East (UAE, AED) ── mixed metals & industrial
            [
                'vendor'               => 'MetalCraft Middle East',
                'material'             => 'Structural Steel',
                'currency'             => 'AED',
                'last_price'           => 7.2500,
                'lead_time_days'       => 10,
                'minimum_order_qty'    => 5000.0000,
                'preferred_vendor'     => false,
                'vendor_material_code' => 'MC-ST-HR-S275-12MM',
            ],
            [
                'vendor'               => 'MetalCraft Middle East',
                'material'             => 'Stainless Steel Bolt',
                'currency'             => 'AED',
                'last_price'           => 0.4200,
                'lead_time_days'       => 12,
                'minimum_order_qty'    => 8000.0000,
                'preferred_vendor'     => true,
                'vendor_material_code' => 'MC-SSB-A4-80-M12',
            ],
            [
                'vendor'               => 'MetalCraft Middle East',
                'material'             => 'Aluminum Sheet',
                'currency'             => 'AED',
                'last_price'           => 28.5000,
                'lead_time_days'       => 8,
                'minimum_order_qty'    => 200.0000,
                'preferred_vendor'     => false,
                'vendor_material_code' => 'MC-AL-6061-T6-3MM',
            ],
            [
                'vendor'               => 'MetalCraft Middle East',
                'material'             => 'PVC Pipe',
                'currency'             => 'AED',
                'last_price'           => 18.5000,
                'lead_time_days'       => 10,
                'minimum_order_qty'    => 600.0000,
                'preferred_vendor'     => true,
                'vendor_material_code' => 'MC-PVC-110MM-SDR11',
            ],
            [
                'vendor'               => 'MetalCraft Middle East',
                'material'             => 'Industrial Lubricant',
                'currency'             => 'AED',
                'last_price'           => 145.0000,
                'lead_time_days'       => 7,
                'minimum_order_qty'    => 80.0000,
                'preferred_vendor'     => false,
                'vendor_material_code' => 'MC-SYN-ISOVG220-20L',
            ],

            // ── EuroCable Systems (Germany, EUR) ── copper & cable specialist
            [
                'vendor'               => 'EuroCable Systems',
                'material'             => 'Copper Wire',
                'currency'             => 'EUR',
                'last_price'           => 9.8500,
                'lead_time_days'       => 19,
                'minimum_order_qty'    => 1000.0000,
                'preferred_vendor'     => true,
                'vendor_material_code' => 'ECS-CU-H07RN-F-95',
            ],
            [
                'vendor'               => 'EuroCable Systems',
                'material'             => 'Industrial Lubricant',
                'currency'             => 'EUR',
                'last_price'           => 24.5000,
                'lead_time_days'       => 11,
                'minimum_order_qty'    => 120.0000,
                'preferred_vendor'     => true,
                'vendor_material_code' => 'ECS-LUB-SYN-EP-5L',
            ],
        ];

        foreach ($rows as $row) {
            $vendor   = Vendor::where('name', $row['vendor'])->first();
            $material = RawMaterial::where('name', $row['material'])->first();
            $currency = Currency::where('code', $row['currency'])->first();

            if ($vendor === null || $material === null || $currency === null) {
                throw new \RuntimeException(
                    'VendorMaterial seed missing vendor, material, or currency: '
                    . json_encode($row)
                );
            }

            VendorMaterial::firstOrCreate(
                [
                    'vendor_id'   => $vendor->vendor_id,
                    'material_id' => $material->material_id,
                ],
                [
                    'currency_id'          => $currency->id,
                    'last_price'           => $row['last_price'],
                    'lead_time_days'       => $row['lead_time_days'],
                    'minimum_order_qty'    => $row['minimum_order_qty'],
                    'preferred_vendor'     => $row['preferred_vendor'],
                    'vendor_material_code' => $row['vendor_material_code'],
                ]
            );
        }
    }
}