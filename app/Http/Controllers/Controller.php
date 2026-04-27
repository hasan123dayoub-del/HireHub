<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use App\Models\Project;

abstract class Controller
{
    use AuthorizesRequests;
    public function index()
    {
        return Cache::tags(['projects'])->remember('open_projects_page_' . request('page', 1), 3600, function () {
            return Project::where('status', 'open')->latest()->paginate(10);
        });
    }
}
