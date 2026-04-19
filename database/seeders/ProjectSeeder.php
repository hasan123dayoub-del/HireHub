<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $project = \App\Models\Project::create([
            'user_id' => 1,
            'title' => 'Devoloping an itegrated online store',
            'description' => 'We are looking for a developer to build an animation using Laravel 12',
            'budget_type' => 'fixed',
            'budget_amount' => 2500.00,
            'delivery_date' => now()->addDays(45),
            'status' => 'open'
        ]);
        $project->tags()->attach([1, 4]); 
    }
}
