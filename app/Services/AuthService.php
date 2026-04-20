<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthService
{
    public function register(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => $data['password'],
                'role'     => $data['role'],
                'city_id'  => $data['city_id'],
                'email_verified_at' => now(),
            ]);

            if ($user->role === 'freelancer') {
                $user->Profile()->create([
                    'bio' => 'This is a default bio. Please update it.',
                    'hourly_rate' => 20.00,
                    'phone_number' => '0000000000',
                    'availability' => 'available',
                ]);
            }

            return $user;
        });
    }

    public function login(string $email, string $password)
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        return [
            'user'  => $user->load(['city.country']),
            'token' => $user->createToken('HireHubToken')->plainTextToken
        ];
    }
    public function logout($user)
    {
        return $user->currentAccessToken()->delete();
    }

    public function deleteAccount($user)
    {
        return $user->delete();
    }
}
