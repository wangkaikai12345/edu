<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\BaseRequest;

class SmsRequest extends BaseRequest
{
    public function rules()
    {
        switch ($this->type) {
            case 'register':
                return [
                    'phone' => 'required|regex:/1[3-9]\d{9}/|unique:users',
                    'captcha_key' => 'required|string',
                    'captcha_code' => 'required|string',
                ];
                break;
            case 'password':
            case 'login':
            return [
                'phone' => 'required|regex:/1[3-9]\d{9}/|exists:users',
                'captcha_key' => 'required|string',
                'captcha_code' => 'required|string',
            ];
            case 'verify':
                return [
                    'phone' => 'required|regex:/1[3-9]\d{9}/|unique:users',
                    'captcha_key' => 'required|string',
                    'captcha_code' => 'required|string',
                ];
                break;
        }
    }
}
