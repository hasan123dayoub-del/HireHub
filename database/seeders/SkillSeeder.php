<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = collect(['PHP', 'Laravel', 'MySQL', 'JavaScript', 'React', 'Vue.js', 'Figma', 'UI/UX Design', 'Flutter', 'Firebase', 'SEO', 'Content Writing'])
            ->mapWithKeys(fn ($name) => [$name => Skill::create(['name' => $name])->id]);

        // Years of experience per skill, per freelancer.
        $freelancerSkills = [
            'dev@hirehub.com' => ['PHP' => 4, 'Laravel' => 3, 'MySQL' => 3, 'Vue.js' => 1],
            'lina@hirehub.com' => ['Figma' => 4, 'UI/UX Design' => 5, 'React' => 1],
            'karim@hirehub.com' => ['Flutter' => 3, 'Firebase' => 3, 'JavaScript' => 2],
            'nour@hirehub.com' => ['SEO' => 4, 'Content Writing' => 5],
        ];

        foreach ($freelancerSkills as $email => $years) {
            $user = User::where('email', $email)->firstOrFail();
            foreach ($years as $skill => $count) {
                $user->skills()->attach($skills[$skill], ['years_of_experience' => $count]);
            }
        }
    }
}
