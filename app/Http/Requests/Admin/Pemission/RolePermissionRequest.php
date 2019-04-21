<?php

namespace App\Http\Requests\Admin\Permission;

use App\Http\Requests\BaseRequest;
use App\Rules\RolePermissionRule;

class RolePermissionRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'permission_ids' => [
                'required',
                'array',
                'distinct',
                new RolePermissionRule($this->role)
            ]
        ];
    }
}
