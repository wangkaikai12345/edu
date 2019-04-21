<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\BaseRequest;

class PasswordByOldPasswordRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ];
    }
}
