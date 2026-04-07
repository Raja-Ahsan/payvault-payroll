<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IncomeType;
use App\Models\TaxCategory;

class TaxCategorySeeder extends Seeder
{
    public function run(): void
    {
        $types = IncomeType::pluck('id', 'title');

        $data = [
            ['title' => 'Federal Income Tax', 'income_type_id' => 1],
            ['title' => 'Social Security (Employee)', 'income_type_id' => 2],
            ['title' => 'Social Security (Employer)', 'income_type_id' => 3],
            ['title' => 'Medicare (Employee)', 'income_type_id' => 4],
            ['title' => 'Medicare (Employer)', 'income_type_id' => 5],
            ['title' => 'Fed Unemployment (Employer)', 'income_type_id' => 6],
            ['title' => 'State Income Tax', 'income_type_id' => 7],
            ['title' => 'State Unemployment (Employer)', 'income_type_id' => 8],
            ['title' => 'Local Income Tax', 'income_type_id' => 1],
            ['title' => 'State Disability Insurance (Employee)', 'income_type_id' => 2],
            ['title' => 'State Disability Insurance (Employer)', 'income_type_id' => 3],
            ['title' => 'New York City Tax', 'income_type_id' => 4],
        ];

        foreach ($data as $item) {
            TaxCategory::firstOrCreate(
                ['title' => $item['title']],
                [
                    'title' => $item['title'],
                    'income_type_id' => $item['income_type_id'] ?? null,
                    'created_by' => 1 
                ]
            );
        }
    }
}
