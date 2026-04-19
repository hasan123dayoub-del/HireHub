<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFreelancerIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->user() &&
            $request->user()->role === 'freelancer' &&
            !$request->user()->email_verified_at
        )

            return response()->json([
                'status'  => 'error',
                'message' => 'Your account must be verified to perform this action.',
            ], 403);

        return $next($request);
    }
}
