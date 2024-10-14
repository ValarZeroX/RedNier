<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// 公開路由
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// 受保護路由
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'getAuthenticatedUser']);
    Route::get('/user/auth-methods', [AuthController::class, 'getUserAuthMethods']);
    // 其他需要認證的路由...
});

// 將這些路由移到 api 組中
Route::get('/auth/{provider}', [AuthController::class, 'redirectToProvider']);
Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback']);
