<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\BaseRequest;

class ResetPasswordRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required|confirmed|min:6',
            'verification_key' => 'required|string',
            'verification_code' => 'required|string',
        ];
    }

}
