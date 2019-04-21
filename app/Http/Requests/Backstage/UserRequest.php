<?php

namespace App\Http\Requests\Backstage;

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
                    'role' => ['sometimes', 'string', 'exists:roles,id']
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
                    'title' => 'sometimes|nullable|string',
                    'name' => 'sometimes|nullable|string',
                    'idcard' => ['sometimes', 'nullable', 'regex:/^(\d{17}[Xx1234567890]{1})|(\s)$/', Rule::unique('profile', 'idcard')->ignore($this->user->profile->user_id, 'user_id')],
                    'gender' => 'sometimes|nullable|string|in:male,female,secret',
                    'profile.birthday' => 'sometimes|nullable|date|before:now',
                    'profile.city' => 'sometimes|nullable|string',
                    'qq' => 'sometimes|nullable|string',
                    'about' => 'sometimes|nullable|string',
                    'company' => 'sometimes|nullable|string',
                    'job' => 'sometimes|nullable|string',
                    'school' => 'sometimes|nullable|string',
                    'major' => 'sometimes|nullable|string',
                    'weibo' => 'sometimes|nullable|string',
                    'weixin' => ['sometimes', 'nullable', 'string', Rule::unique('profile', 'weixin')->ignore($this->user->profile->user_id, 'user_id')],
                    'site' => 'sometimes|nullable',
                    'profile.is_qq_public' => 'sometimes|boolean',
                    'profile.is_weixin_public' => 'sometimes|boolean',
                    'profile.is_weibo_public' => 'sometimes|boolean',
                ];
                break;
        }
    }
}
