<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class SessionTimeoutServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['router']->matched(function (\Illuminate\Routing\Events\RouteMatched $event) {
            if (Auth::check()) {
                try {
                    if (JWTAuth::getToken()) {
                        $payload = JWTAuth::parseToken()->getPayload();
                        $tokenCreatedAt = $payload->get('iat');

                        if (time() - $tokenCreatedAt > 1800) {
                            Auth::logout();
                            abort(401, 'Session has expired due to inactivity');
                        }
                    } else {
                        Auth::logout();
                        abort(401, 'Token not provided');
                    }

                } catch (JWTException $e) {
                    Auth::logout();
                    abort(401, 'Invalid or expired token');
                }
            }
        });
    }
}
