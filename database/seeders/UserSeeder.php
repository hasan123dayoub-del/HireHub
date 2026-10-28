<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Ali ',
            'email' => 'client@hirehub.com',
            'password' => 'password', // hashed by the User model's password mutator
            'role' => 'client',
            'city_id' => 1,
        ]);

        \App\Models\User::create([
            'name' => 'Osama',
            'email' => 'dev@hirehub.com',
            'password' => 'password', // hashed by the User model's password mutator
            'role' => 'freelancer',
            'city_id' => 3,
            'email_verified_at' => now(),
        ]);
    }
}
