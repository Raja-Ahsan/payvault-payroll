<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CmsModule;

class CmsModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dashboard = CmsModule::firstOrCreate([
            'route_name' => 'admin.dashboard'
        ], [
            'name' => 'Dashboard',
            'icon' => 'fa-regular fa-house',
            'sort_order' => 1,
            'status' => 'active',
            'parent_id' => 0,
        ]);

        $users = CmsModule::firstOrCreate([
            'route_name' => 'users-module'
        ], [
            'name' => 'Users',
            'icon' => 'fa-solid fa-users',
            'sort_order' => 2,
            'status' => 'active',
            'parent_id' => 0,
        ]);

        $companies = CmsModule::firstOrCreate([
            'route_name' => 'companies-module'
        ], [
            'name' => 'Companies',
            'icon' => 'fa-solid fa-building',
            'sort_order' => 3,
            'status' => 'active',
            'parent_id' => 0,
        ]);
        $categories = CmsModule::firstOrCreate([
            'route_name' => 'categories-module'
        ], [
            'name' => 'Categories',
            'icon' => 'fa-solid fa-list-ul',
            'sort_order' => 4,
            'status' => 'active',
            'parent_id' => 0,
        ]);

        // submenus
        // users submenu start
        CmsModule::firstOrCreate([
            'route_name' => 'users.index'
        ], [
            'name' => 'All Users',
            'icon' => 'fa-solid fa-list-ul',
            'sort_order' => 1,
            'status' => 'active',
            'parent_id' => $users->id,
        ]);

        CmsModule::firstOrCreate([
            'route_name' => 'users.create'
        ], [
            'name' => 'Add User',
            'icon' => 'fa-solid fa-circle-plus',
            'sort_order' => 2,
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
            'route_name' => 'categories.income.index'
        ], [
            'name' => 'Income Categories',
            'icon' => 'fa-solid fa-money-bill',
            'sort_order' => 1,
            'status' => 'active',
            'parent_id' => $categories->id,
        ]);

        CmsModule::firstOrCreate([
            'route_name' => 'categories.tax.index'
        ], [
            'name' => 'Tax Categories',
            'icon' => 'fa-solid fa-money-bill',
            'sort_order' => 2,
            'status' => 'active',
            'parent_id' => $categories->id,
        ]);

        CmsModule::firstOrCreate([
            'route_name' => 'categories.deduction.index'
        ], [
            'name' => 'Deduction Categories',
            'icon' => 'fa-solid fa-minus-circle',
            'sort_order' => 3,
            'status' => 'active',
            'parent_id' => $categories->id,
        ]);
    }
}
