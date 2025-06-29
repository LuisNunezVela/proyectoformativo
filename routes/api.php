<?php

use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Middleware\CheckApiToken;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::post('/', [UserController::class, 'store']);
    Route::put('/{id}', [UserController::class, 'update']); 
    Route::delete('/{id}', [UserController::class, 'destroy']);
});

// Ruta de login
Route::post('/login', [AuthController::class, 'login']);

// Ruta de registrar
Route::post('/register', [AuthController::class, 'register']);

// Ruta para subir foto con middleware personalizado para validar token
Route::post('/profile/photo', [ProfileController::class, 'uploadPhoto'])
    ->middleware(CheckApiToken::class);
