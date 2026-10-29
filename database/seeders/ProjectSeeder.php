<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $tags = Tag::pluck('id', 'name');

        // [client email, title, description, budget type, amount, days to deliver, status, posted days ago, tags]
        $projects = [
            ['client@hirehub.com', 'Build an online store with Laravel and Stripe', 'We need a small online store: product catalog, cart, Stripe checkout and an admin page to manage orders. The backend must be Laravel with a documented REST API.', 'fixed', 2500, 45, 'open', 1, ['Programming', 'E-commerce']],
            ['client@hirehub.com', 'Redesign the landing page of a SaaS product', 'Our landing page converts poorly. We are looking for a designer to redesign it in Figma (desktop and mobile) based on our existing brand colors.', 'fixed', 900, 20, 'open', 2, ['Design']],
            ['sara@hirehub.com', 'Flutter app for booking gym classes', 'A mobile app (Android and iOS) where members see the weekly schedule, book a class and get a reminder before it starts. The API already exists.', 'fixed', 4000, 60, 'in_progress', 12, ['Mobile', 'Application']],
            ['sara@hirehub.com', 'REST API for a restaurant ordering system', 'Laravel API for menus, orders and order status updates, with role-based access for staff and customers, plus feature tests.', 'hourly', 30, 30, 'open', 3, ['Programming']],
            ['omar@hirehub.com', 'SEO audit and content plan for an online store', 'Audit our store (technical SEO, keywords, competitors) and deliver a three-month content plan with 12 article briefs.', 'fixed', 600, 21, 'open', 4, ['Marketing', 'E-commerce']],
            ['omar@hirehub.com', 'React dashboard for monthly sales reports', 'A React dashboard that reads our existing API and shows monthly sales, top products and charts, with filters by date range.', 'fixed', 1800, 30, 'open', 5, ['Programming', 'Design']],
            ['client@hirehub.com', 'Speed up a slow Laravel API', 'Our product listing endpoint takes several seconds. Find the bottlenecks (N+1 queries, missing indexes, caching) and fix them.', 'hourly', 40, 10, 'closed', 25, ['Programming']],
            ['sara@hirehub.com', 'Logo and brand identity for a coffee shop', 'A logo, color palette and simple brand guide for a new specialty coffee shop, delivered as vector files.', 'fixed', 450, 14, 'open', 6, ['Design']],
        ];

        foreach ($projects as [$email, $title, $description, $type, $amount, $days, $status, $postedDaysAgo, $projectTags]) {
            $posted = now()->subDays($postedDaysAgo);
            $project = new Project([
                'title' => $title,
                'description' => $description,
                'budget_type' => $type,
                'budget_amount' => $amount,
                'delivery_date' => $posted->copy()->addDays($days),
                'status' => $status,
            ]);
            $project->user_id = User::where('email', $email)->value('id');
            $project->created_at = $project->updated_at = $posted;
            $project->save();
            $project->tags()->attach($tags->only($projectTags)->values());
        }
    }
}
