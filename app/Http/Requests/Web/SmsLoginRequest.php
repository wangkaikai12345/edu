<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\BaseRequest;

class SmsLoginRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sms_key' => 'required|string',
            'sms_code' => 'required|string'
        ];
    }
}
