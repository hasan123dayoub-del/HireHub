<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ProposalController;
use App\Http\Controllers\Api\FreelancerProfileController;
use App\Http\Controllers\Api\StatsController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::delete('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::delete('destroyaccount', [AuthController::class, 'deleteAccount'])->middleware('auth:sanctum');
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::prefix('projects')->group(function () {
    Route::get('/', [ProjectController::class, 'index']);
    Route::get('/{id}', [ProjectController::class, 'show']);
});

Route::prefix('freelancers')->group(function () {
    Route::get('/', [FreelancerProfileController::class, 'index']);
    Route::get('/{id}', [FreelancerProfileController::class, 'show']);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::middleware('role:client')->group(function () {
        Route::post('/projects', [ProjectController::class, 'store']);
        Route::put('/projects/{project}', [ProjectController::class, 'update']);
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);
    });

    Route::middleware('role:freelancer')->group(function () {
        Route::post('/proposals', [ProposalController::class, 'store']);
        Route::get('/proposals/{id}', [ProposalController::class, 'show']);
        Route::put('/proposals/{proposal}', [ProposalController::class, 'update']);

        Route::prefix('profile')->group(function () {
            Route::get('/', [FreelancerProfileController::class, 'myProfile']);
            Route::put('/', [FreelancerProfileController::class, 'update']);
            Route::post('/skills', [FreelancerProfileController::class, 'addSkill']);
            Route::put('/skills/{skillId}', [FreelancerProfileController::class, 'updateSkill']);
            Route::delete('/skills/{skillId}', [FreelancerProfileController::class, 'deleteSkill']);
        });
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/stats', [StatsController::class, 'index']);
    });
});
