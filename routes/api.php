<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CursoController;
use App\Http\Controllers\Api\AuthController;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('/curso', [CursoController::class, 'index']);
Route::get('/curso/{id}', [CursoController::class, 'show']);
Route::middleware('auth:api')->group(function(){
    Route::apiResource('/curso', CursoController::class)->except(['index', 'show']);;
});
