<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Rules\TagRule;

class TagRequest extends BaseRequest
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
                ];
                break;
            case 'PUT':
                return [
                    'name' => 'string|min:2',
                ];
                break;
            case 'DELETE':
                return [
                    'ids' => ['required', 'array', 'bail', new TagRule($this->tagGroup)]
                ];
                break;
        }
    }
}
