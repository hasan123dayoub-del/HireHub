<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use App\Models\Project;

abstract class Controller
{
    use AuthorizesRequests;
}
