<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\BaseRequest;

class WxUserBindRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => [
                'regex:/^1\d{10}$/',
                'unique:users,phone',
            ],
//            'verification_key' => 'required|string',
            'verification_code' => 'required|string',
        ];
    }
}
