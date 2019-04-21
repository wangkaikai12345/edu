<?php

namespace App\Http\Controllers\Web;

use App\Enums\SettingType;
use App\Http\Controllers\Controller;
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
    /**
     * @SWG\Tag(name="web/password",description="密码")
     */

    /**
     * @SWG\Patch(
     *  path="/password/sms",
     *  tags={"web/password"},
     *  summary="通过短信验证码重置密码",
     *  description="通过图形验证码，发送短信，然后通过短信验证码去修改密码",
     *  @SWG\Parameter(name="password",in="formData",type="string",minLength=6,description="密码"),
     *  @SWG\Parameter(name="password_confirmation",in="formData",type="string",minLength=6,description="确认密码"),
     *  @SWG\Parameter(name="sms_key",in="formData",type="string",description="短信验证码 Key"),
     *  @SWG\Parameter(name="sms_code",in="formData",type="string",description="短信验证码"),
     *  @SWG\Response(response=204,description=""),
     * )
     */
    public function sms(PasswordByPhoneCodeRequest $request, User $user)
    {
        // 验证验证码是否正确
        if (!($passwordData = \Cache::get($request->sms_key))) {
            $this->response->errorBadRequest(__('Verification code has been expired.'));
        }

        if (!hash_equals((string)$passwordData['code'], (string)$request->sms_code)) {
            $this->response->errorBadRequest(__('Verification code is error.'));
        }

        // 查询用户更改密码
        $user = $user->where('phone', $passwordData['phone'])->first();
        $user->password = bcrypt($request->password);
        $user->save();

        Cache::forget($request->sms_key);

        return $this->response->noContent();
    }

    /**
     * @SWG\Patch(
     *  path="/password/last",
     *  tags={"web/password"},
     *  summary="通过旧密码重置密码",
     *  description="",
     *  @SWG\Parameter(name="old_password",in="formData",type="string",minLength=6,description="原密码"),
     *  @SWG\Parameter(name="password",in="formData",type="string",minLength=6,description="新密码"),
     *  @SWG\Parameter(name="password_confirmation",in="formData",type="string",minLength=6,description="确认密码"),
     *  @SWG\Response(response=204,description="")
     * )
     */
    public function password(PasswordByOldPasswordRequest $request)
    {
        $me = auth()->user();

        if (!Hash::check($request->old_password, $me->getAuthPassword())) {
            $this->response->errorBadRequest(__('Password error.'));
        }

        $me->password = bcrypt($request->password);
        $me->save();

        return $this->response->noContent();
    }

    /**
     * @SWG\Patch(
     *  path="/password/email",
     *  tags={"web/password"},
     *  summary="通过邮件验证码重置密码",
     *  description="",
     *  @SWG\Parameter(name="email",in="formData",type="string",description="邮箱"),
     *  @SWG\Parameter(name="token",in="formData",type="string",minLength=6,description="邮件验证码"),
     *  @SWG\Parameter(name="password",in="formData",type="string",minLength=6,description="新密码"),
     *  @SWG\Parameter(name="password_confirmation",in="formData",type="string",minLength=6,description="确认密码"),
     *  @SWG\Response(response=204,description=""),
     * )
     */
    public function email(ResetByEmailCodeRequest $request)
    {
        if (!$passwordReset = PasswordReset::where('token', $request->token)->where('email', $request->email)->first()) {
            $this->response->errorBadRequest(__('Verification code is error.'));
        }

        // 获取邮件配置
        $emailSetting = Setting::namespace(SettingType::EMAIL);
        $expires = $emailSetting['expires'] ?? 1440;

        // 判断是否过期 当前时间是否大于截止时间
        if (now()->gt($passwordReset->created_at->addMinutes($expires))) {
            $this->response->errorBadRequest(__('Verification code has been expired.'));
        }

        $user = User::where('email', $request->email)->first();

        DB::transaction(function () use ($passwordReset, $user, $request) {
            $passwordReset->delete();

            $user->password = bcrypt($request->input('password'));
            $user->save();
        });

        return $this->response->noContent();
    }
}
