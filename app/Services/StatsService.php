<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class StatsService
{
    public function getGlobalStats(): array
    {
        return Cache::remember('global_platform_stats', 600, function () {
            $stats = DB::table('users')
                ->selectRaw('COUNT(*) as total_users')
                ->addSelect([
                    'total_projects' => DB::table('projects')->selectRaw('COUNT(*)'),
                    'total_proposals' => DB::table('proposals')->selectRaw('COUNT(*)'),
                    'total_value' => DB::table('proposals')
                        ->where('status', 'accepted')
                        ->selectRaw('COALESCE(SUM(amount), 0)')
                ])
                ->first();

            return [
                'total_users'     => (int) $stats->total_users,
                'total_projects'  => (int) $stats->total_projects,
                'total_proposals' => (int) $stats->total_proposals,
                'total_value'     => (float) $stats->total_value,
            ];
        });
    }
}
