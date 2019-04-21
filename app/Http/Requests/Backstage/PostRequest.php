<?php

namespace App\Http\Requests\Backstage;

use App\Enums\Status;
use App\Http\Requests\BaseRequest;
use App\Models\Tag;
use Illuminate\Validation\Rule;

class PostRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->getMethod()) {
            case 'POST':
                return [
                    'category_id' => ['required', 'numeric', 'exists:categories,id'],
                    'tags' => ['required', 'array', function ($attribute, $value, $fail) {
                        info($value);
                        $count = Tag::whereIn('id', $value)->count();

                        if ($count != count($value)) {
                            return $fail('标签数据异常');
                        }
                    }],
                    'title' => ['required', 'string', 'max:100', Rule::unique('posts')
                        ->where(function ($query) {
                            $query->where('category_id', $this->get('category_id'));
                        })
                    ],
                    'subtitle' => ['required', 'string', 'max:150',],
                    'body' => ['required', 'string'],
                    'status' => ['required', Rule::in(Status::getValues())]
                ];
                break;
            case "PUT":
                return [
                    'category_id' => ['required', 'numeric', 'max:100', 'exists:categories,id'],
                    'tags' => ['required', 'array', function ($attribute, $value, $fail) {
                        info($value);
                        $count = Tag::whereIn('id', $value)->count();

                        if ($count != count($value)) {
                            return $fail('标签数据异常');
                        }
                    }],
                    'title' => ['required', 'string', 'max:100', Rule::unique('posts')
                        ->where(function ($query) {
                            $query->where('category_id', $this->get('category_id'));
                        })->ignore($this->route('post')->id, 'id')
                    ],
                    'subtitle' => ['required', 'string', 'max:150',],
                    'body' => ['required', 'string'],
                    'status' => ['required', Rule::in(Status::getValues())]
                ];
                break;
        }
    }

    public function attributes()
    {
        return [
            'category_id' => '分类',
            'tags' => '标签',
            'title' => '标题',
            'subtitle' => '副标题',
            'body' => '内容',
            'status' => '发布状态'
        ];
    }
}
