<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IncomeType;
use App\Models\DeductionCategory;

class DeductionCategorySeeder extends Seeder
{
    public function run(): void
    {
        $types = IncomeType::pluck('id', 'title');

        $data = [
            ['title' => '401K (Employee)', 'income_type_id' => 5],
            ['title' => '401K (Employer)', 'income_type_id' => 8],
            ['title' => 'Health Insurance', 'income_type_id' => 3],
        ];

        foreach ($data as $item) {
            DeductionCategory::firstOrCreate(
                ['title' => $item['title']],
                [
                    'title' => $item['title'],
                    'income_type_id' => $item['income_type_id'] ?? null,
                    'abbreviation' => $item['abbreviation'] ?? null,
                    'calculation' => $item['income_type_id'] ?? null,
                    'paid_by' => $item['paid_by'] ?? 'employee',
                    'inactive' => $item['inactive'] ?? false,
                    'created_by' => 1,
                ]
            );
        }

        // DeductionCategory::query()
        //     ->where(function ($q) {
        //         $q->whereNull('calculation')->orWhere('calculation', '');
        //     })
        //     ->update(['calculation' => 'percentage']);
    }
}
