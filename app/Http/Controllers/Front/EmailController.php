<?php

namespace App\Http\Controllers\Front;

use App\Enums\SettingType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\EmailRequest;
use App\Mail\SendMailBindMail;
use App\Mail\SendRegisterMail;
use App\Mail\SendResetPasswordMail;
use App\Models\PasswordReset;
use Facades\App\Models\Setting;
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
     * 跳转的前端的通过邮箱修改密码页面
     *
     * @var string
     */
    public $resetPasswordRedirectUri = '/password/email';

    /**
     * 发送验证邮件
     *
     * @param $type
     * @param EmailRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author 王凯
     */
    public function send($type, EmailRequest $request)
    {
        // 获取邮件配置
        if (!($emailSetting = Setting::where('namespace', SettingType::EMAIL)->value('value'))) {
            return ajax('400', __('Mail is not support.'));
        }

        $expires = $emailSetting['expires'] ?? 24 * 60;
        $expiredAt = now()->addMinutes($expires)->toDateTimeString();

        switch ($type) {
            // 绑定邮箱
            case 'bind':
                !($me = auth('web')->user()) && abort(404);

                if (!Hash::check($request->password, $me->getAuthPassword())) {
                    return ajax('400', __('Password error.'));
                }

                $code = str_random(100);
                $url = route('users.safe.email', $code);

                PasswordReset::create(['email' => $request->email, 'token' => $code, 'created_at' => now()]);

                $me->email = $request->email;
                $me->is_email_verified = false;
                $me->save();

                try {
                    Mail::to($me)->queue(new SendMailBindMail($me, $url, $expiredAt));
                } catch (\Exception $exception) {
                    \Log::useFiles(storage_path('logs/email.log'));
                    \Log::error($exception);
                    return ajax('400', __('Mail is not supported.'));
                }
                break;
            // 注册邮箱
            case 'register':

                // 生成随机验证码
                $key = 'verification_' . str_random(15);
                $token = mt_rand(100000, 999999);

                Mail::to($request->email)->queue(new SendRegisterMail($token, $expiredAt));

                // 加入缓存
                Cache::put($key, ['email' => $request->email, 'code' => $token], now()->addMinutes($expires));

                return ajax('200', '验证码发送成功', [
                    'verification_key' => $key,
                    'expired_at' => now()->addMinutes($expires)->toDateTimeString()
                ]);

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

                if (!$user->is_email_verified) return ajax('400', '您的邮箱还未验证');

                // 生成随机验证码
                $key = 'verification_' . str_random(15);
                $token = mt_rand(100000, 999999);

                Mail::to($user)->queue(new SendResetPasswordMail($user, '', $token, $expiredAt));

                // 加入缓存
                Cache::put($key, ['email' => $request->email, 'code' => $token], now()->addMinutes($expires));

                return ajax('200', '验证码发送成功', [
                    'verification_key' => $key,
                    'expired_at' => now()->addMinutes($expires)->toDateTimeString()
                ]);

                break;
        }

        return ajax('200', '邮件发送成功');
    }

    /**
     * 验证邮箱
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @author 王凯
     */
    public function update(Request $request)
    {
        $row = PasswordReset::where('token', $request->code)->first();

        $emailSetting = Setting::namespace(SettingType::EMAIL);

        $expiredMinutes = data_get($emailSetting, 'expires', 24 * 60);

        if (!$row || $row->created_at->addMinutes($expiredMinutes)->gt(now())) {
            $verified = 0;
        } else {
            $user = User::where('email', $row->email)->first();
            $user->is_email_verified = true;
            $user->save();
            $verified = 1;
        }

        return redirect()->to('users.safe');
    }
}
