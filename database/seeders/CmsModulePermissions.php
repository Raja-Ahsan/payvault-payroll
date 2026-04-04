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
            'product-categories' => CmsModule::where('route_name', 'product-categories-module')->first(),
            'products' => CmsModule::where('route_name', 'products-module')->first(),
        ];

        $submenus = [
            'users.index' => CmsModule::where('route_name', 'users.index')->first(),
            'users.create' => CmsModule::where('route_name', 'users.create')->first(),
            'product-categories.index' => CmsModule::where('route_name', 'product-categories.index')->first(),
            'product-categories.create' => CmsModule::where('route_name', 'product-categories.create')->first(),
            'products.index' => CmsModule::where('route_name', 'products.index')->first(),
            'products.create' => CmsModule::where('route_name', 'products.create')->first(),
        ];

        $permissions = [
            // admin modules
            ['role_id' => $adminRole->id, 'module_id' => $modules['dashboard']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0], // Dashboard
            ['role_id' => $adminRole->id, 'module_id' => $modules['users']->id ?? null, 'is_add' => 1, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0], // Users
            ['role_id' => $adminRole->id, 'module_id' => $modules['subscriptions']->id ?? null, 'is_add' => 1, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0], // Subscriptions
            // Submenus
            ['role_id' => $adminRole->id, 'module_id' => $submenus['users.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0], // All Users (users.index)
            ['role_id' => $adminRole->id, 'module_id' => $submenus['users.create']->id ?? null, 'is_add' => 0, 'is_view' => 0, 'is_update' => 0, 'is_delete' => 0], // Add User (users.create)
            ['role_id' => $adminRole->id, 'module_id' => $submenus['subscriptions.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0], // All Subscriptions (subscriptions.index)


            
            // // user modules
            // ['role_id' => $userRole->id, 'module_id' => $modules['dashboard']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0], // Dashboard
            // ['role_id' => $userRole->id, 'module_id' => $modules['users']->id ?? null, 'is_add' => 0, 'is_view' => 0, 'is_update' => 0, 'is_delete' => 0], // Users
            // ['role_id' => $userRole->id, 'module_id' => $modules['subscriptions']->id ?? null, 'is_add' => 0, 'is_view' => 0, 'is_update' => 1, 'is_delete' => 0], // Subscriptions
            // ['role_id' => $userRole->id, 'module_id' => $modules['payments']->id ?? null, 'is_add' => 0, 'is_view' => 0, 'is_update' => 0, 'is_delete' => 0], // payments
            // ['role_id' => $userRole->id, 'module_id' => $modules['referrals']->id ?? null, 'is_add' => 0, 'is_view' => 0, 'is_update' => 1, 'is_delete' => 0], // referrals
            // // ['role_id' => $userRole->id, 'module_id' => $modules['contractors']->id ?? null, 'is_add' => 0, 'is_view' => 0, 'is_update' => 1, 'is_delete' => 0], // contractors
            // ['role_id' => $userRole->id, 'module_id' => $modules['services']->id ?? null, 'is_add' => 1, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0], // services
            // ['role_id' => $userRole->id, 'module_id' => $modules['products']->id ?? null, 'is_add' => 0, 'is_view' => 0, 'is_update' => 1, 'is_delete' => 0], // products
            // ['role_id' => $userRole->id, 'module_id' => $modules['orders']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0], // orders
            // ['role_id' => $userRole->id, 'module_id' => $modules['contractor_approval']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0], // contractor approval
            // ['role_id' => $userRole->id, 'module_id' => $modules['packages']->id ?? null, 'is_add' => 0, 'is_view' => 0, 'is_update' => 0, 'is_delete' => 0], // packages

            // // Submenus
            // ['role_id' => $userRole->id, 'module_id' => $submenus['users.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0], // All Users (users.index)
            // ['role_id' => $userRole->id, 'module_id' => $submenus['users.create']->id ?? null, 'is_add' => 0, 'is_view' => 0, 'is_update' => 0, 'is_delete' => 0], // Add User (users.create)
            // ['role_id' => $userRole->id, 'module_id' => $submenus['subscriptions.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0], // All Subscriptions (subscriptions.index)
            // ['role_id' => $userRole->id, 'module_id' => $submenus['referrals.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0], // All Referrals (referrals.index)
            // ['role_id' => $userRole->id, 'module_id' => $submenus['referrals.create']->id ?? null, 'is_add' => 1, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0], // Add Referral (referrals.create)
            // // ['role_id' => $userRole->id, 'module_id' => $submenus['contractors.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0], // All Contractors (contractors.index)
            // // ['role_id' => $userRole->id, 'module_id' => $submenus['contractors.create']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0], // Add Contractor (contractors.create)
            // ['role_id' => $userRole->id, 'module_id' => $submenus['services.index']->id ?? null, 'is_add' => 0, 'is_view' => 0, 'is_update' => 1, 'is_delete' => 0], // All Services (services.index)
            // ['role_id' => $userRole->id, 'module_id' => $submenus['services.create']->id ?? null, 'is_add' => 0, 'is_view' => 0, 'is_update' => 1, 'is_delete' => 0], // Add Service (services.create)
            // ['role_id' => $userRole->id, 'module_id' => $submenus['services.bookings']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 0, 'is_delete' => 0], // Services bookings
            // ['role_id' => $userRole->id, 'module_id' => $modules['services-categories.create']->id ?? null, 'is_add' => 1, 'is_view' => 0, 'is_update' => 1, 'is_delete' => 0], // services categories
            // ['role_id' => $userRole->id, 'module_id' => $modules['services-categories.index']->id ?? null, 'is_add' => 1, 'is_view' => 0, 'is_update' => 1, 'is_delete' => 0], // all services categories
            // ['role_id' => $userRole->id, 'module_id' => $submenus['products.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0], // All Products (products.index)
            // ['role_id' => $userRole->id, 'module_id' => $submenus['products.create']->id ?? null, 'is_add' => 0, 'is_view' => 0, 'is_update' => 1, 'is_delete' => 0], // Add Product (products.create)
            // ['role_id' => $userRole->id, 'module_id' => $submenus['product-categories.create']->id ?? null, 'is_add' => 0, 'is_view' => 0, 'is_update' => 0, 'is_delete' => 0], // Create Category (admin only - no access)
            // ['role_id' => $userRole->id, 'module_id' => $submenus['product-categories.index']->id ?? null, 'is_add' => 0, 'is_view' => 0, 'is_update' => 0, 'is_delete' => 0], // All Categories (admin only - no access)
            // ['role_id' => $userRole->id, 'module_id' => $submenus['orders.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0], // All Orders (orders.index)
            // ['role_id' => $userRole->id, 'module_id' => $submenus['contractor-approval.index']->id ?? null, 'is_add' => 0, 'is_view' => 1, 'is_update' => 1, 'is_delete' => 0], // All Contractor Approval (contractor-approval.index)
            // ['role_id' => $userRole->id, 'module_id' => $submenus['admin.packages.index']->id ?? null, 'is_add' => 0, 'is_view' => 0, 'is_update' => 0, 'is_delete' => 0], // All Packages (admin.packages.index)
            // ['role_id' => $userRole->id, 'module_id' => $submenus['admin.packages.create']->id ?? null, 'is_add' => 0, 'is_view' => 0, 'is_update' => 0, 'is_delete' => 0], // Add Package (admin.packages.create)
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
