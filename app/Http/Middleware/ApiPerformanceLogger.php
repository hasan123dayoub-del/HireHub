<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiPerformanceLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->attributes->add(['start_time' => microtime(true)]);
        return $next($request);
    }
    public function terminate(Request $request, Response $response)
    {
        $endTime = microtime(true);
        $duration = ($endTime - $request->attributes->get('start_time')) * 1000;
        Log::channel('daily')->info('API_PERFORMANCE_LOG', [
            'user_id' => $request->user()?->id,
            'url'     => $request->fullUrl(),
            'method'  => $request->method(),
            'status'  => $response->getStatusCode(),
            'duration' => number_format($duration, 2) . 'ms',
            'ip'      => $request->ip(),
        ]);
    }
}
