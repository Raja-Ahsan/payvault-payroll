<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    
    public function run(): void
    {
        Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);

        Role::firstOrCreate([
            'name' => 'client',
            'guard_name' => 'web'
        ]);

        Role::firstOrCreate([
            'name' => 'employee',
            'guard_name' => 'web'
        ]);
    }
}
