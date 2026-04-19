<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Review::create([
            'user_id' => 1,
            'reviewable_id' => 1,
            'reviewable_type' => \App\Models\Project::class,
            'rating' => 5,
            'comment' => 'Excellent work'
        ]);
    }
}
