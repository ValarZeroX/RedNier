<?php
namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Http\Request;
// use App\Http\Requests;

class SocialiteController extends Controller
{
    // 將用戶重定向到提供者的登入頁面
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // 接收提供者的回呼，並返回 API Token
    public function handleGoogleCallback()
    {
        try {
            // 從 Google 獲取用戶信息
            $googleUser = Socialite::driver('google')->stateless()->user();

            // 檢查用戶是否存在於數據庫
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(Str::random(16)),  // 使用 Hash::make() 替代 bcrypt()
                ]);
            }

            Auth::login($user);
            $token = $user->createToken('social-auth-token')->plainTextToken;

            $frontendUrl = config('app.frontend_url');
            return redirect()->away($frontendUrl . '/auth/callback?token=' . $token);

        } catch (Exception $e) {
            $frontendUrl = config('app.frontend_url');
            return redirect()->away($frontendUrl . '/login?error=social_login_failed');
        }
    }

    // 登出 API
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
            $user = User::where('email', $socialUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    $provider . '_id' => $socialUser->getId(),
                    'password' => Hash::make(Str::random(24)),  // 使用 Hash::make() 替代 bcrypt()
                ]);
            }

            Auth::login($user);
            $token = $user->createToken('social-auth-token')->plainTextToken;

            $frontendUrl = config('app.frontend_url');
            return redirect()->away($frontendUrl . '/auth/callback?token=' . $token);

        } catch (\Exception $e) {
            $frontendUrl = config('app.frontend_url');
            return redirect()->away($frontendUrl . '/login?error=social_login_failed');
        }
    }
}
