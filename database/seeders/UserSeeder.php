<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $clientRole = Role::where('name', 'client')->first();
        $employeeRole = Role::where('name', 'employee')->first();

        if (!$adminRole || !$clientRole || !$employeeRole) {
            $this->command->error('Roles not found! Please run RoleSeeder first.');
            return;
        }

        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@payvault.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@payvault.com',
                'password' => Hash::make('password123'),
                'role_id' => $adminRole->id,
            ]
        );

        // Create Client User
        User::updateOrCreate(
            ['email' => 'client@payvault.com'],
            [
                'name' => 'Client User',
                'email' => 'client@payvault.com',
                'password' => Hash::make('password123'),
                'role_id' => $clientRole->id,
            ]
        );

        // Create Employee User
        User::updateOrCreate(
            ['email' => 'employee@payvault.com'],
            [
                'name' => 'Employee User',
                'email' => 'employee@payvault.com',
                'password' => Hash::make('password123'),
                'role_id' => $employeeRole->id,
            ]
        );

        $this->command->info('Dummy users created successfully!');
        $this->command->info('Admin: admin@payvault.com / password123');
        $this->command->info('Client: client@payvault.com / password123');
        $this->command->info('Employee: employee@payvault.com / password123');
    }
}
