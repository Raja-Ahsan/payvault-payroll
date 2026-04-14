<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            [
                'title' => 'Simple Payroll, Direct Deposit',
                'price' => 350,
                'currency' => 'USD',
                'billing_label' => '/Flat yearly Fee',
                'billing_cycle' => 'yearly',
                'cta_label' => 'Start Today',
                'features' => [
                    'No per-employee fees',
                    'Unlimited runs. Perfect for up to 15 employees',
                    'Secure ACH direct deposits every payday',
                ],
                'quickbooks_item_id' => null,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Simple Payroll, Direct Deposit',
                'price' => 500,
                'currency' => 'USD',
                'billing_label' => '/Flat yearly Fee',
                'billing_cycle' => 'yearly',
                'cta_label' => 'Start Today',
                'features' => [
                    'No per-employee fees',
                    'Unlimited runs. Perfect for up to 30 employees',
                    'Secure ACH direct deposits every payday',
                ],
                'quickbooks_item_id' => null,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Simple Payroll, Direct Deposit',
                'price' => 900,
                'currency' => 'USD',
                'billing_label' => '/Flat yearly Fee',
                'billing_cycle' => 'yearly',
                'cta_label' => 'Start Today',
                'features' => [
                    'Sign up and Pay your federal payroll taxes online',
                ],
                'quickbooks_item_id' => null,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($defaults as $row) {
            Package::updateOrCreate(
                [
                    'title' => $row['title'],
                    'price' => $row['price'],
                    'billing_label' => $row['billing_label'],
                ],
                $row
            );
        }
    }
}
