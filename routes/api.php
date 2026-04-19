<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ProposalController;
use App\Http\Controllers\Api\FreelancerProfileController;
use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\Api\ProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 1. مسار المستخدم الحالي (User Info)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 2. المسارات العامة (Public Endpoints)
// متاحة للجميع (حتى الزوار) لرؤية المشاريع والمستقلين
Route::prefix('projects')->group(function () {
    Route::get('/', [ProjectController::class, 'index']); // قائمة المشاريع المفتوحة مع pagination، client، عدد العروض، متوسط التقييم، tags
    Route::get('/{id}', [ProjectController::class, 'show']); // تفاصيل مشروع محدد مع العروض، الملفات المرفقة، reviews
});

Route::prefix('freelancers')->group(function () {
    Route::get('/', [FreelancerProfileController::class, 'index']); // قائمة المستقلين المتاحين والـ verified مع فلترة وترتيب
    Route::get('/{id}', [FreelancerProfileController::class, 'show']); // تفاصيل مستقل محدد مع profile كامل، مهارات، سنوات الخبرة، تقييمات، عدد المشاريع
});

// 3. المسارات المحمية (Protected Endpoints)
Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // --- مسارات العملاء (Clients) ---
    Route::middleware('role:client')->group(function () {
        Route::post('/projects', [ProjectController::class, 'store']); // إنشاء مشروع جديد
        Route::put('/projects/{project}', [ProjectController::class, 'update']); // تحديث مشروع (إذا لزم الأمر)
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy']); // حذف مشروع (إذا لزم الأمر)
    });

    // --- مسارات المستقلين (Freelancers) ---
    Route::middleware('role:freelancer')->group(function () {
        Route::post('/proposals', [ProposalController::class, 'store']); // تقديم عرض جديد
        Route::get('/proposals/{id}', [ProposalController::class, 'show']); // تفاصيل عرض محدد بناءً على حالته
        Route::put('/proposals/{proposal}', [ProposalController::class, 'update']); // تحديث عرض (إذا لزم الأمر)

        // مسارات الـ Profile
        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'show']); // عرض الـ profile الحالي
            Route::put('/', [ProfileController::class, 'update']); // تعديل الـ profile
            Route::post('/skills', [ProfileController::class, 'addSkill']); // إضافة مهارة جديدة مع سنوات الخبرة
            Route::put('/skills/{skillId}', [ProfileController::class, 'updateSkill']); // تحديث مهارة مع سنوات الخبرة
            Route::delete('/skills/{skillId}', [ProfileController::class, 'deleteSkill']); // حذف مهارة (إذا لزم الأمر)
        });
    });

    // --- مسارات الإحصائيات (للمؤسسين فقط) ---
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/stats', [StatsController::class, 'index']); // لوحة الإحصائيات: إجمالي المستخدمين، المشاريع، العروض، قيمة العروض الكلية
    });

});