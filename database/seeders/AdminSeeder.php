<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Admin Account',
            'email'    => 'admin@hirehub.com',
            'password' => 'Example@Pass2026',
            'role'     => 'admin',
            'city_id' => 3,
            'email_verified_at' => now(),
        ]);
    }
}
