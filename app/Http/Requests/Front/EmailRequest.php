<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\BaseRequest;

class EmailRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->type) {
            case 'bind':
                return [
                    'captcha' => 'required|captcha',
                    'email' => 'required|email|unique:users,email,' . auth('web')->id(),
                    'password' => 'required|min:6'
                ];
                break;
            case 'register':
                return [
                    'captcha' => 'required|captcha',
                    'email' => 'required|email|unique:users,email,' . auth('web')->id(),
                ];
                break;
            case 'resend':
                return [
                    'captcha_key' => 'required|string',
                    'captcha_code' => 'required|string',
                ];
                break;
            case 'password':
                return [
                    'captcha' => 'required|captcha',
                    'email' => 'required|email|exists:users'
                ];
                break;
        }

    }
}
