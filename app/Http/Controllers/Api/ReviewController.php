<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Services\ReviewService;

class ReviewController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function store(StoreReviewRequest $request)
    {
        $review = $this->reviewService->submitReview(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Review submitted successfully',
            'data'    => $review
        ], 201);
    }
}
