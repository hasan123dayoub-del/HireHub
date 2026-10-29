<?php

namespace Database\Seeders;

use App\Models\Attachment;
use App\Models\Project;
use App\Models\Proposal;
use Illuminate\Database\Seeder;

class AttachmentSeeder extends Seeder
{
    public function run(): void
    {
        $store = Project::where('title', 'Build an online store with Laravel and Stripe')->firstOrFail();

        Attachment::create([
            'file_path' => 'files/store-requirements.pdf',
            'file_name' => 'Store requirements',
            'attachable_id' => $store->id,
            'attachable_type' => Project::class,
        ]);
        Attachment::create([
            'file_path' => 'files/osama-darwish-portfolio.pdf',
            'file_name' => 'Portfolio',
            'attachable_id' => Proposal::where('project_id', $store->id)->value('id'),
            'attachable_type' => Proposal::class,
        ]);
    }
}
