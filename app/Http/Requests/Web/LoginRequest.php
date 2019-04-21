<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'password' => 'required|string|min:6',
        ];

        $username = request('username');

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $rules['username'] = 'required|exists:users,email';
        } else if (preg_match('/^1[3-9]\d{9}$/', $username)) {
            $rules['username'] = 'required|exists:users,phone';
        } else {
            $rules['username'] = 'required|exists:users,username';
        }

        return $rules;
    }
}
