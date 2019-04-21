<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserType;
use App\Http\Requests\BaseRequest;
use App\Rules\CustomEnumRule;
use Illuminate\Validation\Rule;

class UserRequest extends BaseRequest
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
                    'phone' => 'sometimes|string|regex:/1[3-9]\d{9}/|unique:users',
                    'email' => 'sometimes|string|email|unique:users',
                    'password' => 'required|string|min:6|confirmed',
                    'role' => ['sometimes', 'string', new CustomEnumRule(UserType::class)]
                ];
                break;
            case 'PATCH':
            case 'PUT':
                return [
                    'username' => 'sometimes|string|min:1|unique:users,username,' . $this->user->id,
                    'phone' => 'sometimes|string|unique:users,phone,' . $this->user->id,
                    'email' => 'sometimes|email|unique:users,email,' . $this->user->id,
                    'avatar' => 'sometimes|nullable',
                    'signature' => 'sometimes|nullable|string',
                    'profile.tags' => 'sometimes|nullable|array|distinct',
                    'profile.title' => 'sometimes|nullable|string',
                    'profile.name' => 'sometimes|nullable|string',
                    'profile.idcard' => ['sometimes', 'nullable', 'regex:/^(\d{17}[Xx1234567890]{1})|(\s)$/', Rule::unique('profile', 'idcard')->ignore($this->user->profile->user_id, 'user_id')],
                    'profile.gender' => 'sometimes|nullable|string|in:male,female,secret',
                    'profile.birthday' => 'sometimes|nullable|date|before:now',
                    'profile.city' => 'sometimes|nullable|string',
                    'profile.qq' => 'sometimes|nullable|string',
                    'profile.about' => 'sometimes|nullable|string',
                    'profile.company' => 'sometimes|nullable|string',
                    'profile.job' => 'sometimes|nullable|string',
                    'profile.school' => 'sometimes|nullable|string',
                    'profile.major' => 'sometimes|nullable|string',
                    'profile.weibo' => 'sometimes|nullable|string',
                    'profile.weixin' => ['sometimes', 'nullable', 'string', Rule::unique('profile', 'weixin')->ignore($this->user->profile->user_id, 'user_id')],
                    'profile.site' => 'sometimes|nullable',
                    'profile.is_qq_public' => 'sometimes|boolean',
                    'profile.is_weixin_public' => 'sometimes|boolean',
                    'profile.is_weibo_public' => 'sometimes|boolean',
                ];
                break;
        }
    }
}
