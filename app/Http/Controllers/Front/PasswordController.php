<?php

namespace App\Http\Controllers\Front;

use App\Enums\SettingType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\ResetPasswordRequest;
use App\Http\Requests\Web\PasswordByOldPasswordRequest;
use App\Http\Requests\Web\PasswordByPhoneCodeRequest;
use App\Http\Requests\Web\ResetByEmailCodeRequest;
use App\Models\PasswordReset;
use App\Models\Setting;
use App\Models\User;
use Cache;
use DB;
use Hash;

class PasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * 重置密码页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function reset()
    {
        return frontend_view('password_reset');
    }

    /**
     * 重置密码
     *
     * @param ResetPasswordRequest $request
     * @param User $user
     * @return \Dingo\Api\Http\Response|\Illuminate\Http\RedirectResponse
     * @author 王凯
     */
    public function password(ResetPasswordRequest $request)
    {
        // 获取验证码，存在即验证，否则错误响应
        if (!($verifyData = \Cache::get($request->verification_key))) {
            return back()->with('danger', '验证码不存在');
        }

        if (!hash_equals((string)$verifyData['code'], (string)$request->verification_code)) {
            return back()->with('danger', __('Verification code is error.'));
        }

        // 查询用户更改密码
        if (!empty($verifyData['phone'])) $user = User::where('phone', $verifyData['phone'])->first();
        if (!empty($verifyData['email'])) $user = User::where('email', $verifyData['email'])->first();

        if (!$user) return ajax('400', '重置密码账户不存在');

        $user->password = bcrypt($request->password);
        $user->save();

        Cache::forget($request->verification_key);

        return ajax('200', '重置密码成功');
    }
}
