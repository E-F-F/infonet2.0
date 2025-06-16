<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\StaffAccess;
use App\Models\SystemAccess;
use App\Models\StaffAuth;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Branch::create([
            'name' => 'KKHQ',
            'is_active' => true,
        ]);

        SystemAccess::create([
            'branch_id' => 1,
            'hrms' => true,
        ]);

        StaffAuth::create([
            'username' => 'username',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        StaffAccess::create([
            'staff_auth_id' => 1,
            'system_access_id' => 1,
        ]);

        $this->call(\Modules\HRMS\Database\Seeders\HRMSDatabaseSeeder::class);
    }
}
