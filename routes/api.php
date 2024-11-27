<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UniversityController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('/signup', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/universities', [UniversityController::class, 'index']);    
    Route::post('/universities', [UniversityController::class, 'store']);
    Route::put('/universities/{id}', [UniversityController::class, 'update']);
    Route::delete('/universities/{id}', [UniversityController::class, 'destroy']);
});
