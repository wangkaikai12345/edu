<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class TeacherRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'username' => 'required|string|min:1|unique:users',
                    'phone' => 'required|string|unique:users|regex:/^1[3-9]\d{9}$/',
                    'email' => 'sometimes|string|email|unique:users',
                    'password' => 'required|string|min:6|confirmed',
                ];
                break;
            case 'PATCH':
            case 'PUT':
            return [
                    'phone' => 'sometimes|string|unique:users,phone,' . $this->teacher->id,
                    'avatar' => 'sometimes|nullable',
                    'signature' => 'sometimes|max:191',
                    'profile.tags' => 'sometimes|array|distinct',
                    'profile.title' => 'sometimes|string',
                    'profile.name' => 'sometimes|string',
                    'profile.idcard' => ['sometimes', 'nullable', 'regex:/^(\d{17}[Xx1234567890]{1})|(\s)$/'],
                    'profile.gender' => 'sometimes|string|in:male,female,secret',
                    'profile.birthday' => 'sometimes|date|before:now',
                    'profile.city' => 'sometimes|string',
                    'profile.qq' => 'sometimes|string',
                    'profile.about' => 'sometimes|string',
                    'profile.company' => 'sometimes|string',
                    'profile.job' => 'sometimes|string',
                    'profile.school' => 'sometimes|string',
                    'profile.major' => 'sometimes|string',
                    'profile.weibo' => 'sometimes|string',
                    'profile.weixin' => 'sometimes|string',
                    'profile.site' => 'sometimes|nullable',
                    'profile.is_qq_public' => 'sometimes|boolean',
                    'profile.is_weixin_public' => 'sometimes|boolean',
                    'profile.is_weibo_public' => 'sometimes|boolean',
                ];
                break;
        }
    }
}
