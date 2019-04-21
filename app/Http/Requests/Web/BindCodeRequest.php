<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\BaseRequest;

class BindCodeRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required|string',
            'phone' => [
                'required_without_all:phone,email',
                'unique:users,phone,' .$this->user()->id,
                'regex:/^1[3-9]\d{9}$/',
            ],
            'email' => [
                'required_without_all:phone,email',
                'unique:users,email,' .$this->user()->id,
                'email',
            ],
        ];
    }
}
