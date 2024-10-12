<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\UserController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group( function () {
    Route::post('/logout', [SocialiteController::class, 'logout']);
    Route::get('/user', [UserController::class, 'getAuthenticatedUser']);
});