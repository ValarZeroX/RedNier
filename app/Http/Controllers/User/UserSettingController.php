<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\UserSetting;
use Illuminate\Http\Request;

class UserSettingController extends Controller
{
    public function updateUserSettings(Request $request)
{
    $request->validate([
        'anguage' => 'string',
        'theme' => 'string',
    ]);

    $user = auth()->user();

    // 如果设置存在则更新，否则创建新的设置
    $userSetting = UserSetting::updateOrCreate(
        ['user_id' => $user->id],
        [
            'language' => $request->language ?? $user->settings->language,
            'theme' => $request->theme ?? $user->settings->theme,
        ]
    );

        return response()->json(['message' => 'Settings updated successfully', 'settings' => $userSetting]);
    }
}
