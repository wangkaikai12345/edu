<?php

namespace App\Http\Requests\Admin\Permission;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UserRoleRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'role_id' => [
                'required', 'integer','exists:roles,id'
            ]
        ];
    }
}
