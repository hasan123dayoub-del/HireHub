<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FreelancerProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $freelancer = \App\Models\User::where('role', 'freelancer')->first();

        if ($freelancer) {
            \App\Models\FreelancerProfile::create([
                'user_id' => $freelancer->id, 
                'bio' => 'A full-stack developer specializing in Laravel and Vue.js...',
                'hourly_rate' => 35,
                'phone_number' => '0501234567',
                'availability' => 'available',
                'portfolio_links' => [
                    'github' => 'https://github.com/osama',
                    'web' => 'https://osama.dev'
                ],
            ]);
        }
    }
}
