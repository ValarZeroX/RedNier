<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;

class EmailVerificationRequest extends FormRequest
{
    public function authorize()
    {
        
        // dd($this->user()->getKey());
        // if (! hash_equals((string) $this->user()->getKey(), (string) $this->route('id'))) {
        //     dd("1");
        //     return false;
        // }

        // if (! hash_equals(sha1($this->user()->getEmailForVerification()), (string) $this->route('hash'))) {
        //     dd("2");
        //     return false;
        // }
        // 此處您可以自定義是否授權該請求
        return true;
    }

    public function rules()
    {
        // 驗證規則
        return [
            'id' => 'required|integer',
            'hash' => 'required|string',
        ];
    }

    public function fulfill()
    {
        $user = $this->getUserById();
        dd("EmailVerificationRequest!!!");
        if (!$this->isValidSignature()) {
            throw new ValidationException('Invalid signature');
        }

        if ($user->hasVerifiedEmail()) {
            throw new AuthorizationException('User email is already verified.');
        }

        // 標記用戶的 email 為已驗證
        $user->markEmailAsVerified();
    }

    protected function getUserById()
    {
        // 根據請求中的 id 獲取用戶
        return User::findOrFail($this->route('id'));
    }

    protected function isValidSignature()
    {
        // 驗證鏈接的簽名是否有效
        return hash_equals((string) $this->route('hash'), sha1($this->getUserById()->getEmailForVerification()));
    }


    public function verify(Request $request)
    {
        dd("verify");
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard'); // or any other route you want to redirect if already verified
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->route('dashboard')->with('verified', true);
    }
}
