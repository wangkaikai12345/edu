<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Cache;
use Hash;
use Illuminate\Http\Request;

class PhoneController extends Controller
{
    /**
     * @SWG\Patch(
     *  path="/bind-phone",
     *  tags={"web/phone"},
     *  summary="绑定手机",
     *  description="",
     *  @SWG\Parameter(name="sms_key",in="formData",type="string",description="key"),
     *  @SWG\Parameter(name="sms_code",in="formData",type="string",description="短信验证码"),
     *  @SWG\Parameter(name="password",in="formData",type="string",description="密码"),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function bind(Request $request)
    {
        $me = auth()->user();

        $this->validate($request, [
            'sms_key' => 'required|string',
            'sms_code' => 'required|string',
            'password' => 'required|min:6'
        ]);

        // 获取验证码 KEY
        if (!($cacheData = Cache::get($request->sms_key))) {
            $this->response->errorBadRequest(__('Verification code has been expired.'));
        }

        // 验证码不匹配
        if ($cacheData['code'] != $request->sms_code) {
            $this->response->errorBadRequest(__('Verification code is error.'));
        }

        // 密码验证
        if (!Hash::check($request->input('password'), $me->getAuthPassword())) {
            $this->response->errorBadRequest(__('Password error.'));
        }

        $me->is_phone_verified = true;
        $me->phone = $cacheData['phone'];
        $me->save();

        Cache::forget($request->sms_key);

        return $this->response->noContent();
    }

    /**
     * @SWG\Patch(
     *  path="/remove-phone",
     *  tags={"web/phone"},
     *  summary="解除绑定",
     *  description="",
     *  @SWG\Parameter(name="phone",in="formData",type="string",description="已绑定手机"),
     *  @SWG\Parameter(name="password",in="formData",type="string",description="密码"),
     *  @SWG\Response(response=204,description=""),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function remove(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|regex:/1[3-9]\d{9}/',
            'password' => 'required|min:6'
        ]);

        // 比对手机是否与当前用户匹配
        $me = auth()->user();
        if ($me->phone != $request->phone) {
            $this->response->errorBadRequest(__('Phone error.'));
        }

        if (!Hash::check($request->input('password'), $me->getAuthPassword())) {
            $this->response->errorBadRequest(__('Password error.'));
        }

        $me->is_phone_verified = false;
        $me->phone = null;
        $me->save();

        return $this->response->noContent();
    }
}
