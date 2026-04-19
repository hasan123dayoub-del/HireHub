<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ['name' => 'الرياض', 'country_id' => 1],
            ['name' => 'جدة', 'country_id' => 1],
            ['name' => 'القاهرة', 'country_id' => 2],
            ['name' => 'دبي', 'country_id' => 3],
        ];
        foreach ($cities as $city) \App\Models\City::create($city);
    }
}
