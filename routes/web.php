<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});


// OAuth
// Route::get('auth/google', [SocialiteController::class, 'redirectToGoogle']);
// Route::get('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);

// Google 登入
Route::get('/login/google', [SocialiteController::class, 'redirectToGoogle']);

// Google 登入的回呼
Route::get('/login/google/callback', [SocialiteController::class, 'handleGoogleCallback']);

// 登出
// Route::post('/logout', [SocialiteController::class, 'logout'])->middleware('auth:sanctum');

// Route::middleware('auth:sanctum')->group( function () {
//     Route::post('/logout', [SocialiteController::class, 'logout']);
//     Route::get('/user', [UserController::class, 'getAuthenticatedUser']);
// });

// Route::group([
//     'middleware' => ['auth:sanctum']
// ], function () {
//     Route::post('/logout', [SocialiteController::class, 'logout'])
// })