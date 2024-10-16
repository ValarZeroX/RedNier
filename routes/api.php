<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
// use App\Http\Requests\EmailVerificationRequest;
// use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

// 公開路由
Route::post('/register', [AuthController::class, 'register'])->middleware('locale');
Route::post('/login', [AuthController::class, 'login'])->middleware('locale');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// 受保護路由
Route::middleware('auth:sanctum', 'verified', 'locale')->group(function () {
    Route::get('/user', [UserController::class, 'getAuthenticatedUser']);
    Route::get('/user/auth-methods', [AuthController::class, 'getUserAuthMethods']);
    // 其他需要認證的路由...

    // // 重新發送驗證郵件
    // Route::get('/email/verify/resend', function (Request $request) {
    //     $request->user()->sendEmailVerificationNotification();

    //     return response()->json(['message' => 'Verification link sent!']);
    // })->name('verification.send');

    // // 驗證電子郵件
    // Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    //     \Log::info('Email verified successfully !!!!');
    //     $request->fulfill();
    //     \Log::info('Email verified successfully !!!!');

    //     return response()->json(['message' => 'Email verified successfully']);
    // })->middleware(['signed'])->name('verification.verify');
});

// 將這些路由移到 api 組中
Route::get('/auth/{provider}', [AuthController::class, 'redirectToProvider']);
Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback']);

// 驗證郵件
Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth:sanctum', 'signed', 'throttle:6,1', 'locale'])
    ->name('verification.verify');
    
Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth:sanctum', 'throttle:6,1'])
    ->name('verification.send');

// Route::get('/email/verify/{id}/{hash}', [EmailVerificationRequest::class, 'verify'])->middleware(['signed'])->name('verification.verify');

// Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
 
//     // 完成驗證
//     $request->fulfill();
//     dd("Email verified successfully !!!!!");
//     \Log::info('Email verified successfully !!!!');
 
//     return redirect('/');
// })->middleware(['auth:sanctum','signed', 'throttle:6,1'])->name('verification.verify');

// Route::post('/email/verification-notification', function (Request $request) {
//     \Log::info('Email verification notification sent !!!!');
//     $request->user()->sendEmailVerificationNotification();
//     \Log::info('Email verification notification sent !!!!');
 
//     // return back()->with('message', 'Verification link sent!');
// })->middleware(['auth', 'throttle:6,1'])->name('verification.send');



// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });
