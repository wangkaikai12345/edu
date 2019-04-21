<?php

namespace App\Http\Requests\Web;

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
                    'captcha_key' => 'required|string',
                    'captcha_code' => 'required|string',
                    'email' => 'required|email|unique:users,email,' . auth()->id(),
                    'password' => 'required|min:6'
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
                    'captcha_key' => 'required|string',
                    'captcha_code' => 'required|string',
                    'email' => 'required|email|exists:users'
                ];
                break;
        }

    }
}
