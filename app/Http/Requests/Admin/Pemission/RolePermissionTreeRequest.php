<?php

namespace App\Http\Requests\Admin\Pemission;

use App\Http\Requests\BaseRequest;

class RolePermissionTreeRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|in:admin,web',
        ];

    }
}
