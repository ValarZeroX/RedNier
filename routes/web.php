<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});


// 社交媒體登入
// Route::get('/login/{provider}', [SocialiteController::class, 'redirectToProvider']);
// Route::get('/login/{provider}/callback', [SocialiteController::class, 'handleProviderCallback']);

