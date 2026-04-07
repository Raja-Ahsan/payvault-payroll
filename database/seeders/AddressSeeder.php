<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Address;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['address_1' => '123 Main St', 'address_2' => null, 'city' => 'New York', 'state' => 'NY', 'zip_code' => '10001'],
            ['address_1' => '456 Main St', 'address_2' => 'Apt 2', 'city' => 'New York', 'state' => 'NY', 'zip_code' => '10002'],
        ];

        foreach ($data as $item) {
            Address::firstOrCreate(
                ['address_1' => $item['address_1'], 'address_2' => $item['address_2'], 'city' => $item['city'], 'state' => $item['state'], 'zip_code' => $item['zip_code']],
                [
                    'address_1' => $item['address_1'],
                    'address_2' => $item['address_2'],
                    'city' => $item['city'],
                    'state' => $item['state'],
                    'zip_code' => $item['zip_code'],
                    'created_by' => 1
                ]
            , $item);
        }
    }
}
