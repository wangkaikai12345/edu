<?php

namespace App\Http\Requests\Backstage;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

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
                    'name' => ['required', 'string', 'max:30', Rule::unique('tag_groups')],
                    'description' => ['required', 'string', 'max:30', Rule::unique('tag_groups')],
                ];
                break;
            case 'PUT':
                return [
                    'name' => ['required', 'string', 'max:30', Rule::unique('tag_groups')->ignore($this->route('tagGroup')->id)],
                    'description' => ['required', 'string', 'max:30', Rule::unique('tag_groups')->ignore($this->route('tagGroup')->id)],
                ];
                break;
        }
    }
}
