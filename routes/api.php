<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\ClubController;

Route::post('/signup', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/universities', [UniversityController::class, 'store']);

Route::middleware('api')->group(function () {

    Route::get('/user', [AuthController::class, 'me']);
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::prefix('universities')->group(function () {
        Route::get('/', [UniversityController::class, 'index']);
        Route::get('/{id}/clubs', [UniversityController::class, 'getClubs']);
        Route::put('{id}/status', [UniversityController::class, 'statusUniversity']);
        Route::put('/{id}', [UniversityController::class, 'update']);
        Route::delete('/{id}', [UniversityController::class, 'destroy']);
    });

    Route::prefix('clubs')->group(function () {
        Route::get('/', [ClubController::class, 'index']);
        Route::get('/{id}', [ClubController::class, 'show']);
        Route::post('/', [ClubController::class, 'store']);
        Route::put('/{id}', [ClubController::class, 'update']);
        Route::delete('/{id}', [ClubController::class, 'destroy']);
    });
});
