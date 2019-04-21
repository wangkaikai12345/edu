<?php

namespace App\Http\Controllers\Web;

use App\Enums\SettingType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\EmailRequest;
use App\Mail\SendMailBindMail;
use App\Mail\SendResetPasswordMail;
use App\Models\PasswordReset;
use App\Models\Setting;
use App\Models\User;
use Cache;
use Hash;
use Mail;

/**
 * 由于邮箱绑定 与 password_resets 表需要的字段一致，故延续使用，不再新增表
 */
class EmailController extends Controller
{
    /**
     * @SWG\Tag(name="web/verification",description="验证码")
     */

    /**
     * 跳转的前端的通过邮箱修改密码页面
     *
     * @var string
     */
    public $resetPasswordRedirectUri = '/password/email';

    /**
     * @SWG\Post(
     *  path="/email/{type}",
     *  tags={"web/verification"},
     *  summary="邮件验证码/邮箱绑定邮件发送",
     *  description="当为bind时，需要全部参数，且为登录状态；当为resend时，仅需captcha_key、captcha_code，且为登录状态；当为password时，需要captcha_key、captcha_code、email。",
     *  @SWG\Parameter(name="type",in="path",type="string",enum={"bind","resend","password"},required=true,description="邮件类型:邮箱绑定邮件、邮箱绑定邮件重发、密码重置邮件"),
     *  @SWG\Parameter(name="captcha_key",in="formData",type="string",required=true,description="图形验证码 key"),
     *  @SWG\Parameter(name="captcha_code",in="formData",type="string",required=true,description="图形验证码 code"),
     *  @SWG\Parameter(name="email",in="formData",type="string",description="邮箱"),
     *  @SWG\Parameter(name="password",in="formData",type="string",description="密码"),
     *  @SWG\Response(response=201,description="ok"),
     *  @SWG\Response(response=422,ref="#/responses/ResourceException"),
     * )
     */
    public function send($type, EmailRequest $request)
    {
        !($captchaData = Cache::get($request->captcha_key)) && $this->response->errorBadRequest(__('Verification code has been expired.'));

        $captchaData['code'] != $request->captcha_code && $this->response->errorBadRequest(__('Verification code is error.'));

        // 获取邮件配置
        !($emailSetting = Setting::where('namespace', SettingType::EMAIL)->value('value')) && $this->response->errorBadRequest(__('Mail is not support.'));

        $expires = $emailSetting['expires'] ?? 24 * 60;
        $expiredAt = now()->addMinutes($expires)->toDateTimeString();

        switch ($type) {
            // 绑定邮箱
            case 'bind':
                !($me = auth()->user()) && abort(401);

                !Hash::check($request->input('password'), $me->getAuthPassword()) && $this->response->errorBadRequest(__('Password error.'));

                $code = str_random(100);
                $url = dingo_route('emailBind.update') . '?code=' . $code;

                PasswordReset::create(['email' => $request->email, 'token' => $code, 'created_at' => now()]);

                $me->email = $request->email;
                $me->is_email_verified = false;
                $me->save();

                try {
                    Mail::to($me)->queue(new SendMailBindMail($me, $url, $expiredAt));
                } catch (\Exception $exception) {
                    \Log::useFiles(storage_path('logs/email.log'));
                    \Log::error($exception);
                    $this->response->errorInternal(__('Mail is not supported.'));
                }
                break;
            // 重新发送绑定邮件：已经绑定，但尚未验证邮箱，则允许其重新发送验证邮件
            case 'resend':
                !($me = auth()->user()) && abort(401);

                !$me->email && $this->response->errorBadRequest(__('No email.'));

                $me->is_email_verified && $this->response->errorBadRequest(__('Already bound.'));

                $code = str_random(100);
                $url = dingo_route('emailBind.update') . '?code=' . $code;

                if ($row = PasswordReset::where('email', $me->email)->first()) {
                    $row->code = $code;
                    $row->created_at = now();
                    $row->save();
                } else {
                    PasswordReset::create(['email' => $me->email, 'token' => $code, 'created_at' => now()]);
                }

                try {
                    Mail::to($me)->queue(new SendMailBindMail($me, $url, $expiredAt));
                } catch (\Exception $exception) {
                    \Log::useFiles(storage_path('logs/email.log'));
                    \Log::error($exception);
                    $this->response->errorInternal($exception->getMessage() ?? __('Mail is not supported.'));
                }
                break;
            // 重置密码
            case 'password':
                $user = User::where($request->only('email'))->first();

                !$user && $this->response->errorBadRequest(__('No email.'));

                !$user->is_email_verified && $this->response->errorBadRequest(__('Mail is not verified.'));

                // 删除之前密码重置 token 记录
                PasswordReset::where('email', $user->email)->delete();

                $token = mt_rand(100000, 999999);

                PasswordReset::create(['email' => $user->email, 'token' => $token, 'created_at' => now()]);

                $url = config('app.url') . $this->resetPasswordRedirectUri;

                Mail::to($user)->queue(new SendResetPasswordMail($user, $url, $token, $expiredAt));

                break;
        }
        // 清空验证码
        Cache::forget(request('cache_key'));

        return $this->response->array(['message' => '发送成功'])->setStatusCode(201);
    }
}
