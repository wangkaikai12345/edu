<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class TagGroupRequest extends BaseRequest
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
                    'name'        => 'required|min:1',
                    'description' => 'nullable',
                ];
                break;
            case 'PUT':
                return [
                    'name'        => 'required|min:1',
                    'description' => 'nullable',
                ];
                break;
        }
    }
}
