<?php

namespace App\Http\Requests\Admin\Permission;

use App\Http\Requests\BaseRequest;

class RoleRequest extends BaseRequest
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
                    'name' => ['required', 'string', 'min:1', 'max:30', 'unique:roles'],
                    'title' => ['required', 'string', 'min:1', 'max:30', 'unique:roles'],
                    'permissions' => ['nullable', 'array']
                ];
                break;
            case 'PUT':
                return [
                    'name' => ['required', 'string', 'min:1', 'max:30', 'unique:roles,name,' . $this->role->id],
                    'title' => ['required', 'string', 'min:1', 'max:30', 'unique:roles,title,' . $this->role->id],
                    'permissions' => ['nullable', 'array']
                ];
                break;
        }
    }
}
