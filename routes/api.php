<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EntrepriseController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/entreprise', [EntrepriseController::class, 'store']);
    Route::put('/entreprise', [EntrepriseController::class, 'update']);
    Route::post('/user/logo', [AuthController::class, 'uploadLogo']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
});