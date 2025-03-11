<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\programs\ProgramController;
use App\Http\Controllers\Projects\ProjectController;

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
Route::group(['prefix' => 'auth', 'middleware' => ['throttle:60,1']], function () {
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

// Rutas para Projects
Route::group(['prefix' => 'projects'], function () {
    Route::get('/', [ProjectController::class, 'index']);       // Listar todos los proyectos
    Route::get('count', [ProjectController::class, 'count']);   // Obtener la cantidad de proyectos
    Route::get('{id}', [ProjectController::class, 'show']);     // Obtener un proyecto por ID
    Route::post('/', [ProjectController::class, 'store']);      // Crear un nuevo proyecto
    Route::put('{project}', [ProjectController::class, 'update']);  // Actualizar un proyecto
    Route::delete('{project}', [ProjectController::class, 'destroy']); // Eliminar un proyecto
});

// Rutas protegidas
Route::group(['middleware' => ['auth:api', 'throttle:60,1']], function () {
    // Autenticación
    Route::group(['prefix' => 'auth'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });
    Route::group(['prefix' => 'programs'], function () {
        Route::get('mount', [ProgramController::class, 'mount']);
        Route::get('get', [ProgramController::class, 'get']);
    });

    Route::group(['middleware' => 'role:admin', 'prefix' => 'admin'], function () {
        // Aquí añadirías rutas accesibles solo para administradores
        Route::get('dashboard', function () {
            return response()->json([
                'message' => 'Área de administrador',
                'data' => 'Acceso exclusivo para administradores'
            ]);
        });
    });

    Route::group(['middleware' => 'permission:user_create', 'prefix' => 'users'], function () {
        Route::post('/', function () {
            return response()->json([
                'message' => 'Creación de usuario',
                'data' => 'Tienes permiso para crear usuarios'
            ]);
        });
    });
});
    