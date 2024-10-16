<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\App;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            // 'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => "",
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        event(new Registered($user));

        // Auth::login($user);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => trans('auth.login.invalidCredentials')
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    // 整合自 SocialiteController 的方法
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            $user = User::where('email', $socialUser->getEmail())->first();
            \Log::info($socialUser->getId());
            \Log::info($socialUser->getEmail());
            \Log::info($socialUser->getName());
            \Log::info($provider);
            if (!$user) {
                if ($provider == 'google') {
                    $user = User::create([
                        'name' => $socialUser->getName(),
                        'email' => $socialUser->getEmail(),
                        'password' => Hash::make(Str::random(16)),
                        'google_id' => $socialUser->getId(),    
                    ]);
                } else if ($provider == 'facebook') {
                    $user = User::create([
                        'name' => $socialUser->getName(),
                        'email' => $socialUser->getEmail(),
                        'password' => Hash::make(Str::random(16)),
                        'facebook_id' => $socialUser->getId(),
                    ]);
                }
            } else {
                // 如果用戶已存在，更新 provider 信息
                if ($provider == 'google') {
                    $user->update([
                        'google_id' => $socialUser->getId(),
                    ]);
                } else if ($provider == 'facebook') {
                    $user->update([ 
                        'facebook_id' => $socialUser->getId(),
                    ]);
                }

                // // 檢查對應的 provider 欄位是否為 null
                // if ($provider == 'google' && is_null($user->google_id)) {
                //     return redirect(env('FRONTEND_URL') . '/auth/error?reason=google_id_missing');
                // } elseif ($provider == 'facebook' && is_null($user->facebook_id)) {
                //     return redirect(env('FRONTEND_URL') . '/auth/error?reason=facebook_id_missing');
                // }
            }

            Auth::login($user);
            $token = $user->createToken('auth_token')->plainTextToken;

            // 重定向到前端頁面，並在 URL 中包含 token
            return redirect(env('FRONTEND_URL') . '/auth/callback?token=' . $token);
        } catch (\Exception $e) {
            // 重定向到前端的錯誤頁面
            return redirect(env('FRONTEND_URL') . '/auth/error');
        }
    }

    public function getUserAuthMethods()
    {
        $user = Auth::user();
        $methods = ['email'];
        
        if ($user->provider) {
            $methods[] = $user->provider;
        }

        return response()->json(['methods' => $methods]);
    }
}
