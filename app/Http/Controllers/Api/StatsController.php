<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StatsService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\StatsResource;
use App\Models\User;

class StatsController extends Controller
{
    protected StatsService $statsService;

    public function __construct(StatsService $statsService)
    {
        $this->statsService = $statsService;
    }
    public function index(): JsonResponse
    {
        $this->authorize('viewStats', User::class);

        $stats = $this->statsService->getGlobalStats();

        return response()->json([
            'status' => 'success',
            'data'   => new StatsResource($stats)
        ]);
    }
}
