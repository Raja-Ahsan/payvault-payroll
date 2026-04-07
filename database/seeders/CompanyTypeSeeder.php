<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CompanyType;

class CompanyTypeSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['title' => 'Regular (Form 941)',],
            ['title' => 'Annual (Form 941)',],
            ['title' => 'Agricultural (Form 941)',],
        ];

        foreach ($data as $item) {
            CompanyType::firstOrCreate(
                ['title' => $item['title']],
                [
                    'title' => $item['title'],
                    'created_by' => 1
                ]
            );
        }
    }
}
