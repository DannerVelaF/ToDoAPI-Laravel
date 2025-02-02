<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TareaController;
use App\Http\Middleware\VerifyToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::middleware([VerifyToken::class])->controller(AuthController::class)->group(function () {
    Route::post('/logout', 'logout');
});

Route::middleware([VerifyToken::class])->controller(TareaController::class)->group(function () {
    Route::get('/me', 'me');
    Route::get('/tareas', 'index');
    Route::post('/tareas', 'store');
    Route::get('/tarea/{id}', [TareaController::class, 'show'])->where('id', '[0-9]+');
    Route::put('/tareas/{id}', 'update');
    Route::delete('/tareas/{id}', 'destroy');

    Route::patch('/tareas/{id}/completar', 'completeTask');
    Route::patch('/tareas/completar', 'multipleCompleteTask');

    Route::get('/tareas/buscar', 'searchByTitle');
    Route::get('/tareas/tareas-completas', 'filterCompleteTask');
});
