<?php

namespace App\Http\Requests\Backstage;

use App\Http\Requests\BaseRequest;
use App\Rules\CategoryRule;
use Illuminate\Validation\Rule;

class CategoryGroupRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            // 添加
            case 'POST':
                return [
                    'name' => ['required', 'string', 'max:30', Rule::unique('category_groups')],
                    'title' => ['required', 'string', 'max:30', Rule::unique('category_groups')],
                ];
                break;
            // 添加
            case 'PUT':
                return [
                    'name' => ['required', 'string', 'max:30', Rule::unique('category_groups')->ignore($this->route('categoryGroup')->id)],
                    'title' => ['required', 'string', 'max:30', Rule::unique('category_groups')->ignore($this->route('categoryGroup')->id)],
                ];
                break;
        }
    }
}
