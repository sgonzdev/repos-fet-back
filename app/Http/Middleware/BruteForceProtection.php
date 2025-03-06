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
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Obtener dirección IP
        $ip = $request->ip();
        $email = $request->input('email');

        if ($email) {
            // Crear una clave única basada en IP y email
            $key = "login:{$ip}:{$email}";

            // Verificar si hay demasiados intentos (10 en 10 minutos)
            if (Cache::has($key) && Cache::get($key) >= 10) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Too many login attempts. Please try again after 10 minutes.'
                ], 429);
            }

            // Incrementar el contador de intentos
            if (Cache::has($key)) {
                Cache::increment($key);
            } else {
                Cache::put($key, 1, 600); // 10 minutos
            }
        }

        return $next($request);
    }
}
