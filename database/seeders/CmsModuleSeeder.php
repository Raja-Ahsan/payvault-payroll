<?php

namespace Database\Seeders;

use App\Models\CmsModule;
use Illuminate\Database\Seeder;

class CmsModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dashboard = CmsModule::firstOrCreate([
            'route_name' => 'admin.dashboard',
        ], [
            'name' => 'Dashboard',
            'icon' => 'fa-regular fa-house',
            'sort_order' => 1,
            'status' => 'active',
            'parent_id' => 0,
        ]);

        $checks = CmsModule::firstOrCreate([
            'route_name' => 'checks-module',
        ], [
            'name' => 'Checks',
            'icon' => 'fa-solid fa-check',
            'sort_order' => 2,
            'status' => 'active',
            'parent_id' => 0,
        ]);

        $employees = CmsModule::firstOrCreate([
            'route_name' => 'employees-module',
        ], [
            'name' => 'Employees',
            'icon' => 'fa-solid fa-user',
            'sort_order' => 3,
            'status' => 'active',
            'parent_id' => 0,
        ]);

        $users = CmsModule::firstOrCreate([
            'route_name' => 'users-module',
        ], [
            'name' => 'Users',
            'icon' => 'fa-solid fa-users',
            'sort_order' => 4,
            'status' => 'active',
            'parent_id' => 0,
        ]);

        $forms = CmsModule::firstOrCreate([
            'route_name' => 'admin.forms.index',
        ], [
            'name' => 'Forms',
            'icon' => 'fa-solid fa-file',
            'sort_order' => 5,
            'status' => 'active',
            'parent_id' => 0,
        ]);

        $reports = CmsModule::firstOrCreate([
            'route_name' => 'reports-module',
        ], [
            'name' => 'Reports',
            'icon' => 'fa-solid fa-chart-line',
            'sort_order' => 6,
            'status' => 'active',
            'parent_id' => 0,
        ]);

        $companies = CmsModule::firstOrCreate([
            'route_name' => 'companies-module',
        ], [
            'name' => 'Companies',
            'icon' => 'fa-solid fa-building',
            'sort_order' => 7,
            'status' => 'active',
            'parent_id' => 0,
        ]);

        $packages = CmsModule::firstOrCreate([
            'route_name' => 'packages-module',
        ], [
            'name' => 'Packages',
            'icon' => 'fa-solid fa-box-open',
            'sort_order' => 8,
            'status' => 'active',
            'parent_id' => 0,
        ]);

        CmsModule::updateOrCreate(
            ['route_name' => 'subscription.index'],
            [
                'name' => 'My subscription',
                'icon' => 'fa-solid fa-receipt',
                'sort_order' => 9,
                'status' => 'active',
                'parent_id' => 0,
            ]
        );

        // submenus
        // users submenu start
        CmsModule::firstOrCreate([
            'route_name' => 'users.index',
        ], [
            'name' => 'All Users',
            'icon' => 'fa-solid fa-list-ul',
            'sort_order' => 1,
            'status' => 'active',
            'parent_id' => $users->id,
        ]);

        CmsModule::firstOrCreate([
            'route_name' => 'users.create',
        ], [
            'name' => 'Add User',
            'icon' => 'fa-solid fa-circle-plus',
            'sort_order' => 2,
            'status' => 'active',
            'parent_id' => $users->id,
        ]);

        CmsModule::firstOrCreate([
            'route_name' => 'users.subscriptions.index',
        ], [
            'name' => 'Package subscriptions',
            'icon' => 'fa-solid fa-receipt',
            'sort_order' => 3,
            'status' => 'active',
            'parent_id' => $users->id,
        ]);

        CmsModule::firstOrCreate([
            'route_name' => 'companies.index'
        ], [
            'name' => 'All Companies',
            'icon' => 'fa-solid fa-list-ul',
            'sort_order' => 1,
            'status' => 'active',
            'parent_id' => $companies->id,
        ]);
        CmsModule::firstOrCreate([
            'route_name' => 'categories.income.index',
        ], [
            'name' => 'Income Categories',
            'icon' => 'fa-solid fa-money-bill',
            'sort_order' => 2,
            'status' => 'active',
            'parent_id' => $companies->id,
        ]);

        CmsModule::firstOrCreate([
            'route_name' => 'categories.tax.index',
        ], [
            'name' => 'Tax Categories',
            'icon' => 'fa-solid fa-money-bill',
            'sort_order' => 3,
            'status' => 'active',
            'parent_id' => $companies->id,
        ]);

        CmsModule::firstOrCreate([
            'route_name' => 'categories.deduction.index',
        ], [
            'name' => 'Deduction Categories',
            'icon' => 'fa-solid fa-minus-circle',
            'sort_order' => 4,
            'status' => 'active',
            'parent_id' => $companies->id,
        ]);

        CmsModule::firstOrCreate([
            'route_name' => 'employees.index',
        ], [
            'name' => 'All Employees',
            'icon' => 'fa-solid fa-list-ul',
            'sort_order' => 1,
            'status' => 'active',
            'parent_id' => $employees->id,
        ]);

        CmsModule::firstOrCreate([
            'route_name' => 'packages.index',
        ], [
            'name' => 'All Packages',
            'icon' => 'fa-solid fa-list-ul',
            'sort_order' => 1,
            'status' => 'active',
            'parent_id' => $packages->id,
        ]);

        CmsModule::firstOrCreate([
            'route_name' => 'packages.create',
        ], [
            'name' => 'Add Package',
            'icon' => 'fa-solid fa-circle-plus',
            'sort_order' => 2,
            'status' => 'active',
            'parent_id' => $packages->id,
        ]);
    }
}
