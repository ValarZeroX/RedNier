<?php
namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

            if ($user) {
                // 如果用戶存在，則登入
                Auth::login($user);
            } else {
                // 否則創建新用戶並登入
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    // 這裡可以生成一個隨機密碼
                    'password' => bcrypt(Str::random(16)),
                ]);

                Auth::login($user);
            }
            return redirect()->away('http://localhost:3000');
            // // 創建 API Token
            // $token = $user->createToken('API Token')->plainTextToken;

            // // 重定向到前端應用，並攜帶 token
            // return redirect()->away('http://localhost:3000/login/callback?token=' . $token);

        } catch (Exception $e) {
            return redirect('/login');
        }
    }

    // 登出 API
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
