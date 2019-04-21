<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Rules\CategoryRule;
use Illuminate\Validation\Rule;

class CategoryRequest extends BaseRequest
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
                    'name' => ['required', 'string', 'max:16', Rule::unique('categories')],
                    'seq' => 'sometimes|integer|min:0'
                ];
                break;
            // 更新
            case 'PUT':
                return [
                    'name' => ['required', 'string', 'max:20', Rule::unique('categories')->ignore($this->category)],
                    'seq' => 'nullable|integer|min:0|max:9999',
                ];
                break;
            // 批量删除
            case 'DELETE':
                return [
                    'ids' => ['required', 'array', 'bail', new CategoryRule($this->categoryGroup)]
                ];
                break;
        }
    }
}
