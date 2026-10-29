<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Demo clients and freelancers. Every demo account uses the password "password"
     * (hashed by the User model's password mutator) and has a verified email,
     * because the protected API routes require one.
     */
    public function run(): void
    {
        $users = [
            ['Ali Hassan', 'client@hirehub.com', 'client', 1],
            ['Sara Khalil', 'sara@hirehub.com', 'client', 4],
            ['Omar Nasser', 'omar@hirehub.com', 'client', 2],
            ['Osama Darwish', 'dev@hirehub.com', 'freelancer', 3],
            ['Lina Haddad', 'lina@hirehub.com', 'freelancer', 4],
            ['Karim Saleh', 'karim@hirehub.com', 'freelancer', 1],
            ['Nour Aziz', 'nour@hirehub.com', 'freelancer', 3],
        ];

        foreach ($users as [$name, $email, $role, $cityId]) {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => 'password',
                'role' => $role,
                'city_id' => $cityId,
                'email_verified_at' => now(),
            ]);
        }
    }
}
