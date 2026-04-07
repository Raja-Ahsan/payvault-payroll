<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IncomeType;

class IncomeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['title' => 'Per Year',],
            ['title' => 'Per Hour',],
            ['title' => 'Fixed',],
            ['title' => 'Bonus',],
            ['title' => 'Variable',],
            ['title' => 'Per Piece',],
            ['title' => 'Per Mile',],
            ['title' => 'Percentage of Sales',],
        ];

        foreach ($data as $item) {
            IncomeType::firstOrCreate(
                [
                    'title' => $item['title'],
                    'created_by' => 1 // ya auth user id
                ]
            , $item);
        }
    }
}
