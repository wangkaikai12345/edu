<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class TagGroupTagRequest extends BaseRequest
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
                    'name' => 'required|string|min:2',
                    'scope' => 'required|string|min:2'
                ];
                break;
            case 'PUT':
                return [
                    'name' => 'required|string|min:2',
                    'scope' => 'required|string|min:2'
                ];
                break;
        }
    }
}
