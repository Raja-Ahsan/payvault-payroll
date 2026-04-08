<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IncomeCategory;
use App\Models\IncomeType;

class IncomeCategorySeeder extends Seeder
{
    public function run(): void
    {
        $types = IncomeType::pluck('id', 'title');

        $data = [
            ['title' => 'Regular Hourly Pay', 'income_type_id' => 2],
            ['title' => 'Overtime Hourly Pay', 'income_type_id' => 2],
            ['title' => 'Yearly Salary', 'income_type_id' => 1],
            ['title' => 'Bonus', 'income_type_id' => 5],
            ['title' => 'Tips Received Directly by Employee', 'income_type_id' => 5],
            ['title' => 'Tips Paid by Employer', 'income_type_id' => 5],
            ['title' => 'Double-Time', 'income_type_id' => 2],
            ['title' => 'Commission', 'income_type_id' => 5],
            ['title' => 'Mileage', 'income_type_id' => 7],
            ['title' => 'Piece Work', 'income_type_id' => 6],
            ['title' => 'Fringe Benefits', 'income_type_id' => 3],
            ['title' => 'Life Insurance over 50,000', 'income_type_id' => 5],
            ['title' => 'Sick Pay', 'income_type_id' => 5],
            ['title' => 'Vacation Pay Hourly', 'income_type_id' => 2],
            ['title' => 'Sick Pay Hourly', 'income_type_id' => 2],
        ];

        foreach ($data as $item) {
            IncomeCategory::firstOrCreate(
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
