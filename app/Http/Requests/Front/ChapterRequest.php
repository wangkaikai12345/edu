<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\BaseRequest;
use App\Rules\ChapterRule;
use App\Rules\ChapterSortRule;

class ChapterRequest extends BaseRequest
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
                    'title' => 'required|string|min:2',
                    'goals' => 'string|min:2',  // 学习目标
                    'length' => 'integer',      // 预计视频时长
                    'parent_id' => ['integer', new ChapterRule($this->plan)],
                ];
                break;
            case 'PUT':
                return [
                    'title' => 'string|min:2',
                    'goals' => 'nullable|string|min:2',  // 学习目标
                ];
                break;
            case 'PATCH':
                return [
                    'type' => 'required|in:chapter,section,task',
                    'parent_id' => ['required_if:type,chapter'],
                    'target_id' => ['required', 'integer', new ChapterSortRule($this->plan)]
                ];
                break;
        }
    }
}
