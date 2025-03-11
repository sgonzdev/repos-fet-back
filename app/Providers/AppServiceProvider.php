<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
   /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Forzar HTTPS en producción
        if(config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Configuración de longitud de cadena por defecto para evitar problemas con MySQL
    }
}
