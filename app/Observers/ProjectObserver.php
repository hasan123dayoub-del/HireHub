<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;
use App\Models\Project;

class ProjectObserver
{
    public function created(Project $project)
    {
        Cache::tags(['projects'])->flush();
    }
}
