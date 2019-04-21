<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\BaseRequest;

class ResetByEmailCodeRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|string|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|min:6|confirmed'
        ];
    }
}
