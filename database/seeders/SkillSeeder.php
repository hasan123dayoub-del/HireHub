<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = ['PHP', 'Laravel', 'JavaScript', 'SQL'];

        $createdSkills = [];

        foreach ($skills as $name) {
            $createdSkills[] = \App\Models\Skill::create(['name' => $name]);
        }

        $freelancer = \App\Models\User::where('role', 'freelancer')->first();

        if (!$freelancer) {
            return;
        }

        $freelancer->skills()->attach($createdSkills[0]->id, [
            'years_of_experience' => 4
        ]);
    }
}
