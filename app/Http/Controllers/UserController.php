<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\UserSetting;

class UserController extends Controller
{
    public function getAuthenticatedUser()
    {
        // 获取当前登录用户
        $user = Auth::user();
        
        if ($user) {
            $userSetting = UserSetting::where('user_id', $user->id)->first();
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                // 'google_id' => $user->google_id, // 假设你存储了 Google ID
                'language' => $userSetting->language,
                'theme' => $userSetting->theme,
                // 你可以根据需要返回其他字段
            ]);
        }

        return response()->json(['message' => 'User not logged in'], 401);
    }
}