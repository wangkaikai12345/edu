<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => [
                'required',
                'max:64',
                'min:2',
                'regex:/^[\x{4e00}-\x{9fa5}\w][\x{4e00}-\x{9fa5}\w\d_]{1,31}$/u',
                'unique:users',
            ],
            'password' => 'required|confirmed|min:6',
            'verification_key' => 'required|string',
            'verification_code' => 'required|string',
//            'invitation_code' => 'sometimes|exists:users,invitation_code',
        ];
    }

    public function messages()
    {
        return [
            'username.regex' => '用户名首字符必须为英文字母或中文；用户只能由中文、英文字母、下划线组成。'
        ];
    }
}
