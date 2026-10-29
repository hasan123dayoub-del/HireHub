<?php

namespace Database\Seeders;

use App\Models\FreelancerProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class FreelancerProfileSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = [
            'dev@hirehub.com' => ['Backend developer focused on Laravel APIs, MySQL performance and clean architecture.', 35, 'available', '0501234567', 'osama'],
            'lina@hirehub.com' => ['UI/UX designer who turns product ideas into clear, tested Figma designs and design systems.', 30, 'available', '0559876543', 'lina'],
            'karim@hirehub.com' => ['Mobile developer building cross-platform Flutter apps with Firebase and REST backends.', 40, 'busy', '0543217890', 'karim'],
            'nour@hirehub.com' => ['Digital marketer specializing in SEO audits, content strategy and analytics for online stores.', 25, 'available', '0567654321', 'nour'],
        ];

        foreach ($profiles as $email => [$bio, $rate, $availability, $phone, $handle]) {
            $profile = new FreelancerProfile([
                'bio' => $bio,
                'hourly_rate' => $rate,
                'phone_number' => $phone,
                'availability' => $availability,
                'portfolio_links' => ['github' => "https://github.com/{$handle}", 'web' => "https://{$handle}.dev"],
            ]);
            $profile->user()->associate(User::where('email', $email)->firstOrFail());
            $profile->save();
        }
    }
}
