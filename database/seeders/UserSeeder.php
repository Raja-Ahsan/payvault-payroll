<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
        [
            'email' => 'admin@diypayroll.com',
        ],
        [

        'name' => 'Admin',
        'password' => bcrypt('Admin@123'),
        ]
    );
        $admin->assignRole('admin');

        $client = User::firstOrCreate(
            [
                'email' => 'client@gmail.com',
            ],
            [
                'name' => 'Client',
                'password' => bcrypt('client@123'),
            ]
        );
        $client->assignRole('client');
    }
}
