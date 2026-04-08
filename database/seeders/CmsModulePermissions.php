<?php

namespace Database\Seeders;

use App\Models\CmsModule;
use App\Models\CmsModulePermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'companies' => CmsModule::where('route_name', 'companies-module')->first(),
            'categories' => CmsModule::where('route_name', 'categories-module')->first(),
            
        ];

        $submenus = [
            'users.index' => CmsModule::where('route_name', 'users.index')->first(),
            'users.create' => CmsModule::where('route_name', 'users.create')->first(),
            'companies.index' => CmsModule::where('route_name', 'companies.index')->first(),
            'categories.income.index' => CmsModule::where('route_name', 'categories.income.index')->first(),
            'categories.tax.index' => CmsModule::where('route_name', 'categories.tax.index')->first(),
            'categories.deduction.index' => CmsModule::where('route_name', 'categories.deduction.index')->first(),
        ];

        $permissions = [
            // admin modules
            ['role_id' => $adminRole->id, 'module_id' => $modules['dashboard']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0], // Dashboard
            ['role_id' => $adminRole->id, 'module_id' => $modules['users']->id ?? null, 'is_add' => 1, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0], // Users
            
            // Submenus
            ['role_id' => $adminRole->id, 'module_id' => $submenus['users.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0], // All Users (users.index)
            ['role_id' => $adminRole->id, 'module_id' => $submenus['users.create']->id ?? null, 'is_add' => 0, 'is_view' => 0, 'is_update' => 0, 'is_delete' => 0], // Add User (users.create)
            ['role_id' => $adminRole->id, 'module_id' => $submenus['categories']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0], // Categories
            // // client modules
            ['role_id' => $clientRole->id, 'module_id' => $modules['dashboard']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0], // Dashboard
            ['role_id' => $clientRole->id, 'module_id' => $modules['companies']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0], // Dashboard
            ['role_id' => $clientRole->id, 'module_id' => $modules['categories']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0], // Dashboard


            
            // // Submenus
            ['role_id' => $clientRole->id, 'module_id' => $submenus['companies.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0], // All Users (users.index)
            ['role_id' => $clientRole->id, 'module_id' => $submenus['categories.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0], // Categories
            ['role_id' => $clientRole->id, 'module_id' => $submenus['categories.income.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0], // Categories
            ['role_id' => $clientRole->id, 'module_id' => $submenus['categories.tax.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0], // Categories
            ['role_id' => $clientRole->id, 'module_id' => $submenus['categories.deduction.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0], // Categories
        ];

        foreach ($permissions as $perm) {
            // Skip if module not found
            if ($perm['module_id'] === null) {
                continue;
            }

            CmsModulePermission::firstOrCreate(
                [
                    'role_id' => $perm['role_id'],
                    'module_id' => $perm['module_id']
                ],
                $perm
            );
        }
    }
}
