<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Programming', 'Design', 'Marketing', 'Application', 'Mobile', 'E-commerce'] as $tag) {
            Tag::create(['name' => $tag]);
        }
    }
}
