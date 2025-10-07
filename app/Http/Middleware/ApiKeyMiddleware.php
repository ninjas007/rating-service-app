<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $bridging = config('bridging');

        if (!$bridging['enabled']) {
            return response()->json([
                'status' => false,
                'message' =>  'Bridging is disabled']
                , 401);
        }

        if ($bridging['method'] !== 'api') {
            return response()->json([
                'status' => false,
                'message' => 'Bridging method is not supported'
            ], 401);
        }

        if ($request->header('X-API-KEY') !== env('API_KEY')) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        return $next($request);
    }
}
