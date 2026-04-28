<?php

namespace Database\Seeders;

use App\Models\CmsModule;
use App\Models\CmsModulePermission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class CmsModulePermissions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CmsModulePermission::truncate();

        $adminRole = Role::firstOrCreate(['name' => config('roles.admin')]);
        $clientRole = Role::firstOrCreate(['name' => config('roles.client')]);
        $employeeRole = Role::firstOrCreate(['name' => config('roles.employee')]);
        $modules = [
            'dashboard' => CmsModule::where('route_name', 'admin.dashboard')->first(),
            'users' => CmsModule::where('route_name', 'users-module')->first(),
            'employees' => CmsModule::where('route_name', 'employees-module')->first(),
            'companies' => CmsModule::where('route_name', 'companies-module')->first(),
            'forms' => CmsModule::where('route_name', 'admin.forms.index')->first(),
            'reports' => CmsModule::where('route_name', 'reports-module')->first(),
            'checks' => CmsModule::where('route_name', 'checks.index')->first(),
            'packages' => CmsModule::where('route_name', 'packages-module')->first(),
            'subscription' => CmsModule::where('route_name', 'subscription.index')->first(),
        ];

        $submenus = [
            'users.index' => CmsModule::where('route_name', 'users.index')->first(),
            'users.create' => CmsModule::where('route_name', 'users.create')->first(),
            'users.subscriptions.index' => CmsModule::where('route_name', 'users.subscriptions.index')->first(),
            'companies.index' => CmsModule::where('route_name', 'companies.index')->first(),
            'categories.income.index' => CmsModule::where('route_name', 'categories.income.index')->first(),
            'categories.tax.index' => CmsModule::where('route_name', 'categories.tax.index')->first(),
            'categories.deduction.index' => CmsModule::where('route_name', 'categories.deduction.index')->first(),
            'employees.index' => CmsModule::where('route_name', 'employees.index')->first(),
            'packages.index' => CmsModule::where('route_name', 'packages.index')->first(),
            'packages.create' => CmsModule::where('route_name', 'packages.create')->first(),
        ];

        $permissions = [
            // admin — top-level
            ['role_id' => $adminRole->id, 'module_id' => $modules['dashboard']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0],
            ['role_id' => $adminRole->id, 'module_id' => $modules['users']->id ?? null, 'is_add' => 1, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0],
            ['role_id' => $adminRole->id, 'module_id' => $modules['companies']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0],
            ['role_id' => $adminRole->id, 'module_id' => $modules['employees']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0],
            ['role_id' => $adminRole->id, 'module_id' => $modules['forms']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0],
            ['role_id' => $adminRole->id, 'module_id' => $modules['reports']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0],
            ['role_id' => $adminRole->id, 'module_id' => $modules['checks']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0],
            ['role_id' => $adminRole->id, 'module_id' => $modules['packages']->id ?? null, 'is_add' => 1, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 1],

            // admin submenus
            ['role_id' => $adminRole->id, 'module_id' => $submenus['users.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0],
            ['role_id' => $adminRole->id, 'module_id' => $submenus['users.create']->id ?? null, 'is_add' => 0, 'is_view' => 0, 'is_update' => 0, 'is_delete' => 0],
            ['role_id' => $adminRole->id, 'module_id' => $submenus['users.subscriptions.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0],
            ['role_id' => $adminRole->id, 'module_id' => $submenus['companies.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0],
            ['role_id' => $adminRole->id, 'module_id' => $submenus['categories.income.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0],
            ['role_id' => $adminRole->id, 'module_id' => $submenus['categories.tax.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0],
            ['role_id' => $adminRole->id, 'module_id' => $submenus['categories.deduction.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0],
            ['role_id' => $adminRole->id, 'module_id' => $submenus['employees.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0],
            ['role_id' => $adminRole->id, 'module_id' => $submenus['packages.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 1],
            ['role_id' => $adminRole->id, 'module_id' => $submenus['packages.create']->id ?? null, 'is_add' => 1, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0],

            // client — top-level
            ['role_id' => $clientRole->id, 'module_id' => $modules['dashboard']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0],
            ['role_id' => $clientRole->id, 'module_id' => $modules['companies']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0],
            ['role_id' => $clientRole->id, 'module_id' => $modules['employees']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0],
            ['role_id' => $clientRole->id, 'module_id' => $modules['forms']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0],
            ['role_id' => $clientRole->id, 'module_id' => $modules['reports']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0],
            ['role_id' => $clientRole->id, 'module_id' => $modules['checks']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0],
            ['role_id' => $clientRole->id, 'module_id' => $modules['subscription']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0],

            // client submenus
            ['role_id' => $clientRole->id, 'module_id' => $submenus['companies.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0],
            ['role_id' => $clientRole->id, 'module_id' => $submenus['categories.income.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0],
            ['role_id' => $clientRole->id, 'module_id' => $submenus['categories.tax.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0],
            ['role_id' => $clientRole->id, 'module_id' => $submenus['categories.deduction.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0],
            ['role_id' => $clientRole->id, 'module_id' => $submenus['employees.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0],

            // employee — top-level
            ['role_id' => $employeeRole->id, 'module_id' => $modules['checks']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0],
            ['role_id' => $employeeRole->id, 'module_id' => $modules['dashboard']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0],
        ];

        foreach ($permissions as $perm) {
            // Skip if module not found
            if ($perm['module_id'] === null) {
                continue;
            }

            CmsModulePermission::firstOrCreate(
                [
                    'role_id' => $perm['role_id'],
                    'module_id' => $perm['module_id'],
                ],
                $perm
            );
        }
    }
}
