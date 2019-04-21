<?php

namespace App\Http\Requests\Admin\Permission;

use App\Http\Requests\BaseRequest;

class PermissionRequest extends BaseRequest
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
                    'name' => ['required', 'string', 'min:1', 'max:30', 'unique:permissions'],
                    'title' => ['required', 'string', 'min:1', 'max:30', 'unique:permissions']
                ];
                break;
            case 'PUT':
                return [
                    'name' => ['required', 'string', 'min:1', 'max:30', 'unique:permissions,name,' . $this->permission->id],
                    'title' => ['required', 'string', 'min:1', 'max:30', 'unique:permissions,name,' . $this->permission->id]
                ];
                break;
        }
    }

    public function attributes()
    {
        return [
            'title' => '名称',
            'name' => '编码',
        ];
    }
}
