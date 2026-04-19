<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProposelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Proposal::create([
            'project_id' => 1,
            'user_id' => 2,
            'amount' => 2300.00,
            'cover_letter' => 'I have previous experience in stores and am ready to start immediately',
            'delivery_days' => 30,
            'status' => 'pending'
        ]);
    }
}
