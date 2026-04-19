<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // CountrySeeder.php
    public function run(): void
    {
        $countries = [
            ['name' => 'السعودية', 'code' => 'SA'],
            ['name' => 'مصر', 'code' => 'EG'],
            ['name' => 'الإمارات', 'code' => 'AE'],
        ];
        foreach ($countries as $country) \App\Models\Country::create($country);
    }
}
