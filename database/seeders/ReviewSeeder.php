<?php

namespace Database\Seeders;

use App\Models\FreelancerProfile;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        // [client email, freelancer email, rating, comment]
        $reviews = [
            ['client@hirehub.com', 'dev@hirehub.com', 5, 'Found the slow queries in a day and our listing is now fast. Clear communication throughout.'],
            ['sara@hirehub.com', 'dev@hirehub.com', 4, 'Solid API work and good tests. Delivered one day late.'],
            ['sara@hirehub.com', 'karim@hirehub.com', 5, 'The first builds of the app are great, and he keeps us updated every week.'],
            ['omar@hirehub.com', 'lina@hirehub.com', 5, 'Beautiful designs and very fast with revisions.'],
            ['omar@hirehub.com', 'nour@hirehub.com', 4, 'Useful audit with clear priorities.'],
        ];

        foreach ($reviews as [$client, $freelancer, $rating, $comment]) {
            $profile = FreelancerProfile::whereRelation('user', 'email', $freelancer)->firstOrFail();
            $review = new Review(['rating' => $rating, 'comment' => $comment]);
            $review->user_id = User::where('email', $client)->value('id');
            $review->reviewable()->associate($profile);
            $review->save();
        }
    }
}
