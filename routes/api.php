<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Rutas públicas
Route::group(['prefix' => 'auth' , 'middleware' => ['throttle:60,1']], function () {
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

// Rutas protegidas
Route::group(['middleware' => ['auth:api', 'throttle:60,1']], function () {
    // Autenticación
    Route::group(['prefix' => 'auth'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });

    // Rutas para administradores
    Route::group(['middleware' => 'role:admin', 'prefix' => 'admin'], function () {
        // Aquí añadirías rutas accesibles solo para administradores
        Route::get('dashboard', function () {
            return response()->json([
                'message' => 'Área de administrador',
                'data' => 'Acceso exclusivo para administradores'
            ]);
        });
    });

    // Ejemplo de ruta que requiere permisos específicos
    Route::group(['middleware' => 'permission:user_create', 'prefix' => 'users'], function () {
        Route::post('/', function () {
            return response()->json([
                'message' => 'Creación de usuario',
                'data' => 'Tienes permiso para crear usuarios'
            ]);
        });
    });
});
