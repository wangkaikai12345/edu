<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\BaseRequest;

class SmsRequest extends BaseRequest
{
    public function rules()
    {
        switch ($this->type) {
            case 'register':
                return [
                    'phone' => 'required|regex:/1[3-9]\d{9}/|unique:users',
                    'captcha' => 'required|captcha',
                ];
                break;
            case 'password':
                return [
                    'phone' => 'required|regex:/1[3-9]\d{9}/|exists:users',
                    'captcha' => 'required|captcha',
                ];
            case 'login':
                return [
                    'phone' => 'required|regex:/1[3-9]\d{9}/|exists:users',
                    'captcha_code' => 'required|string',
                ];
            case 'verify':
                return [
                    'phone' => 'required|regex:/1[3-9]\d{9}/|unique:users',
                    'captcha' => 'required|captcha',
                    'password' => 'required|min:6',
                ];
                break;
        }
    }
}
