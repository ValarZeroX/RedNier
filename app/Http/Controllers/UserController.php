<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getAuthenticatedUser()
    {
        // 获取当前登录用户
        $user = Auth::user();

        if ($user) {
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->google_id, // 假设你存储了 Google ID
                // 你可以根据需要返回其他字段
            ]);
        }

        return response()->json(['message' => 'User not logged in'], 401);
    }
}