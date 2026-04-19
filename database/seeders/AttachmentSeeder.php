<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Attachment::create([
            'file_path' => 'files/specs.pdf',
            'file_name' => 'Specifications',
            'attachable_id' => 1,
            'attachable_type' => \App\Models\Project::class,
        ]);
        \App\Models\Attachment::create([
            'file_path' => 'files/cv.pdf',
            'file_name' => 'the biography',
            'attachable_id' => 1,
            'attachable_type' => \App\Models\Proposal::class,
        ]);
    }
}
