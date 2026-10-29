<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProposelSeeder extends Seeder
{
    public function run(): void
    {
        // [project title, freelancer email, amount, delivery days, status, cover letter]
        $proposals = [
            ['Build an online store with Laravel and Stripe', 'dev@hirehub.com', 2300, 40, 'pending', 'I have built two Laravel stores with Stripe checkout. I would start with the order and payment flow, then the admin page, and document every endpoint in Postman.'],
            ['Build an online store with Laravel and Stripe', 'karim@hirehub.com', 2450, 45, 'pending', 'I can deliver the API and later a Flutter app on top of it if you need mobile.'],
            ['Redesign the landing page of a SaaS product', 'lina@hirehub.com', 850, 15, 'pending', 'I would start with a short review of your analytics, then deliver two directions in Figma and refine the one you choose.'],
            ['Flutter app for booking gym classes', 'karim@hirehub.com', 3800, 55, 'accepted', 'I recently shipped a booking app with Flutter and Firebase notifications, so the reminder feature is familiar ground.'],
            ['Flutter app for booking gym classes', 'dev@hirehub.com', 4000, 60, 'rejected', 'I can help with the API side and any changes it needs for the app.'],
            ['REST API for a restaurant ordering system', 'dev@hirehub.com', 30, 25, 'pending', 'This is exactly my focus: role-based Laravel APIs with feature tests. I can share a similar project.'],
            ['SEO audit and content plan for an online store', 'nour@hirehub.com', 550, 18, 'pending', 'I have audited several online stores. You will get a prioritized list of fixes and 12 ready-to-write briefs.'],
            ['React dashboard for monthly sales reports', 'lina@hirehub.com', 1700, 28, 'pending', 'I design and build dashboards in React, so the charts will be both accurate and easy to read.'],
            ['Speed up a slow Laravel API', 'dev@hirehub.com', 35, 7, 'accepted', 'I recently cut a listing endpoint from 48 to 5 queries with eager loading and caching. I would profile yours first.'],
            ['Logo and brand identity for a coffee shop', 'lina@hirehub.com', 400, 12, 'pending', 'I would love to work on this. Three logo concepts first, then the palette and brand guide.'],
        ];

        foreach ($proposals as [$title, $email, $amount, $days, $status, $letter]) {
            $proposal = new Proposal([
                'project_id' => Project::where('title', $title)->value('id'),
                'amount' => $amount,
                'cover_letter' => $letter,
                'delivery_days' => $days,
                'status' => $status,
            ]);
            $proposal->user_id = User::where('email', $email)->value('id');
            $proposal->save();
        }
    }
}
