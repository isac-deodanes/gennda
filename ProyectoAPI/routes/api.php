<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UsuariosController;
use App\Http\Controllers\API\CategoriaController;
use App\Http\Controllers\API\EventoController;

Route::post('/login', [UsuariosController::class, 'login']);
Route::post('/register', [UsuariosController::class, 'register']);


Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [UsuariosController::class, 'logout']);
    Route::post('/perfil/foto', [UsuariosController::class, 'updatePhoto']);
    Route::put('/perfil', [UsuariosController::class, 'updateProfile']);
    Route::apiResource('categorias', CategoriaController::class);
    Route::apiResource('eventos', EventoController::class);

});

