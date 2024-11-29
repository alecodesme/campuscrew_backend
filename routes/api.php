<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\ClubController;
use App\Http\Middleware\EnsureTokenIsProvided;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('/signup', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/universities', [UniversityController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/universities', [UniversityController::class, 'index']);;
    Route::put('/universities/{id}', [UniversityController::class, 'update']);
    Route::delete('/universities/{id}', [UniversityController::class, 'destroy']);
})->middleware(EnsureTokenIsProvided::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/clubs', [ClubController::class, 'index']);
    Route::get('/clubs/{id}', [ClubController::class, 'show']);
    Route::post('/clubs', [ClubController::class, 'store']);
    Route::put('/clubs/{id}', [ClubController::class, 'update']);
    Route::delete('/clubs/{id}', [ClubController::class, 'destroy']);
})->middleware(EnsureTokenIsProvided::class);
