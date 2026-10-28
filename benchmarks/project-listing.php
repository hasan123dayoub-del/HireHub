<?php
// Measures the HireHub project listing: the real eager-loaded repository vs a naive per-row version
// that returns the same data. It inserts bench rows, so point DB_DATABASE at a scratch SQLite file:
//   touch bench.sqlite && DB_DATABASE=$PWD/bench.sqlite php artisan migrate:fresh --seed
//   DB_DATABASE=$PWD/bench.sqlite CACHE_STORE=array php benchmarks/project-listing.php .

$base = $argv[1];
require $base . '/vendor/autoload.php';
$app = require $base . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\Tag;
use App\Models\User;
use App\Repositories\Eloquent\EloquentProjectRepository;
use Illuminate\Support\Facades\DB;

// Bench data: 15 open projects (one page), each with its own client, 3 tags and 5 proposals.
Illuminate\Database\Eloquent\Model::unguard();
Illuminate\Database\Eloquent\Model::withoutEvents(function () {
    DB::transaction(function () {
        $tags = collect(range(1, 10))->map(fn ($i) => Tag::firstOrCreate(['name' => "bench-tag-$i"]));
        $freelancers = collect(range(1, 5))->map(fn ($i) => User::create([
            'name' => "Bench Freelancer $i", 'email' => "bench-f$i@example.com",
            'password' => 'password', 'role' => 'freelancer', 'city_id' => 1,
        ]));
        foreach (range(1, 15) as $i) {
            $client = User::create([
                'name' => "Bench Client $i", 'email' => "bench-c$i@example.com",
                'password' => 'password', 'role' => 'client', 'city_id' => 1,
            ]);
            $project = Project::create([
                'user_id' => $client->id, 'title' => "Bench project number $i",
                'description' => str_repeat('Benchmark project description. ', 4),
                'budget_type' => 'fixed', 'budget_amount' => 100 * $i,
                'delivery_date' => now()->addMonth(), 'status' => 'open',
            ]);
            $project->tags()->attach($tags->random(3)->pluck('id'));
            foreach ($freelancers as $f) {
                Proposal::create([
                    'user_id' => $f->id, 'project_id' => $project->id, 'amount' => 90 * $i,
                    'cover_letter' => 'Benchmark cover letter', 'delivery_days' => 7, 'status' => 'pending',
                ]);
            }
        }
    });
});

function measure(callable $fn, int $runs = 30): array
{
    $times = [];
    $queries = 0;
    foreach (range(1, $runs) as $r) {
        DB::flushQueryLog();
        DB::enableQueryLog();
        $t = hrtime(true);
        $fn();
        $times[] = (hrtime(true) - $t) / 1e6;
        $queries = count(DB::getQueryLog());
        DB::disableQueryLog();
    }
    sort($times);
    return ['queries' => $queries, 'median_ms' => round($times[intdiv(count($times), 2)], 2)];
}

$optimized = measure(function () {
    $page = app(EloquentProjectRepository::class)->listProjects();
    ProjectResource::collection($page)->response()->getData(true);
});

$naive = measure(function () {
    $page = Project::query()->open()->latest()->paginate(15);
    foreach ($page as $p) {
        [$p->user?->name, $p->user?->rating_cache, $p->tags->pluck('name'), $p->proposals()->count()];
    }
});

echo json_encode(['projects_on_page' => 15, 'naive_per_row' => $naive, 'eager_loading' => $optimized], JSON_PRETTY_PRINT), PHP_EOL;
