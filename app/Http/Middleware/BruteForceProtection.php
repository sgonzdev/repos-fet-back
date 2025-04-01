<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class BruteForceProtection
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $email = $request->input('email');

        if ($email) {
            $key = "login:{$ip}:{$email}";

            if (Cache::has($key) && Cache::get($key) >= 10) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Too many login attempts. Please try again after 10 minutes.'
                ], 429);
            }

            if (Cache::has($key)) {
                Cache::increment($key);
            } else {
                Cache::put($key, 1, 600);
            }
        }

        return $next($request);
    }
}
