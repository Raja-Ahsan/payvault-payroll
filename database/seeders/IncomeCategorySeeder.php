<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IncomeCategory;
class IncomeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['title' => 'Regular Hourly Pay', 'calculation_type' => 'per_hour'],
            ['title' => 'Overtime Hourly Pay', 'calculation_type' => 'per_hour'],
            ['title' => 'Yearly Salary', 'calculation_type' => 'per_year'],
            ['title' => 'Bonus', 'calculation_type' => 'variable'],
            ['title' => 'Tips Received Directly by Employee', 'calculation_type' => 'variable'],
            ['title' => 'Tips Paid by Employer', 'calculation_type' => 'variable'],
            ['title' => 'Double-Time', 'calculation_type' => 'per_hour'],
            ['title' => 'Commission', 'calculation_type' => 'variable'],
            ['title' => 'Mileage', 'calculation_type' => 'per_mile'],
            ['title' => 'Piece Work', 'calculation_type' => 'per_piece'],
            ['title' => 'Fringe Benefits', 'calculation_type' => 'fixed'],
            ['title' => 'Life Insurance over 50,000', 'calculation_type' => 'variable'],
            ['title' => 'Sick Pay', 'calculation_type' => 'variable'],
            ['title' => 'Vacation Pay Hourly', 'calculation_type' => 'per_hour'],
            ['title' => 'Sick Pay Hourly', 'calculation_type' => 'per_hour'],
        ];

        foreach ($data as $item) {
            IncomeCategory::firstOrCreate(
                ['title' => $item['title']], // unique check
                $item // insert data
            );
        }
    }
}
