<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CursoController;

Route::apiResource('/curso', CursoController::class);