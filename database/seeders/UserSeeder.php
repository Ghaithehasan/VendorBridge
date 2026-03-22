<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * One user per role — departments resolved by name. Idempotent by email.
     */
    public function run(): void
    {
        $password = Hash::make('password');
        $now = now();

        $rows = [
            [
                'email' => 'ahmad@factory.com',
                'name' => 'Ahmad Al-Mansouri',
                'department' => 'Production',
                'role' => 'requester',
            ],
            [
                'email' => 'sara@factory.com',
                'name' => 'Sara Al-Hassan',
                'department' => 'Procurement',
                'role' => 'procurement_manager',
            ],
            [
                'email' => 'khalid@factory.com',
                'name' => 'Ali Nasser',
                'department' => 'Procurement',
                'role' => 'purchasing_officer',
            ],
            [
                'email' => 'admin@factory.com',
                'name' => 'Mohamed Ali',
                'department' => 'Procurement',
                'role' => 'admin',
            ],
        ];

        foreach ($rows as $row) {
            $dept = Department::where('name', $row['department'])->first();
            if ($dept === null) {
                throw new \RuntimeException("Department not found: {$row['department']}. Run DepartmentSeeder first.");
            }

            User::firstOrCreate(
                ['email' => $row['email']],
                [
                    'name' => $row['name'],
                    'password' => $password,
                    'department_id' => $dept->department_id,
                    'role' => $row['role'],
                    'is_active' => true,
                    'email_verified_at' => $now,
                ]
            );
        }
    }
}
