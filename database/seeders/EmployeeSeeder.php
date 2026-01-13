<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get employee user
        $employeeUser = User::where('email', 'employee@payvault.com')->first();
        
        if (!$employeeUser) {
            $this->command->error('Employee user not found! Please run UserSeeder first.');
            return;
        }

        // Get a company (use first company or create one)
        $company = Company::first();
        
        if (!$company) {
            $this->command->error('No company found! Please create a company first.');
            return;
        }

        // Create employee record for the employee user
        Employee::updateOrCreate(
            ['user_id' => $employeeUser->id],
            [
                'company_id' => $company->id,
                'user_id' => $employeeUser->id,
                'employee_number' => 'EMP001',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'employee@payvault.com',
                'phone' => '555-0100',
                'pay_type' => 'hourly',
                'hourly_rate' => 25.00,
                'is_active' => true,
            ]
        );

        $this->command->info('Employee record created successfully!');
    }
}
