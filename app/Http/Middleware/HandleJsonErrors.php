<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleJsonErrors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (Throwable $e) {
            \Log::error($e);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'stack_trace' => config('app.debug') ? $e->getTrace() : null, 
            ], 500);
        }

    }
}
