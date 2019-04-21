<?php

namespace App\Http\Requests\Front;

use App\Enums\FinishType;
use App\Enums\TaskTargetType;
use App\Enums\TaskType;
use App\Http\Requests\BaseRequest;
use App\Rules\CustomEnumRule;

class TaskRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
//            'target_type' => ['required', new CustomEnumRule(TaskTargetType::class)],
            'target_type' => ['required'],
//            'type' => ['required', new CustomEnumRule(TaskType::class)],
            'type' => ['required'],
            'title' => 'required|string|min:1',
            'is_free' => 'sometimes|boolean',
            'is_optional' => 'sometimes|boolean',
            'length' => 'sometimes|numeric',
            'started_at' => 'sometimes|date',
            'ended_at' => 'sometimes|date',
            'finish_type' => ['sometimes'],
//            'finish_type' => ['sometimes', new CustomEnumRule(FinishType::class)],
            'finish_detail' => ['required_with:finish_type', 'integer'],
            'keyword' => 'sometimes|string|min:1',     // 关键词
            'describe' => 'sometimes|string|min:1',    // 描述
        ];

        switch ($this->method()) {
            case 'POST':
                if ($this->target_type === TaskTargetType::TEXT) {
                    $rules['body'] = ['required', 'string'];
                } else if ($this->target_type === TaskTargetType::TEST) {
                    $rules['media_uri'] = ['required', 'integer', 'exists:tests,id'];
                } else {
                    $rules['media_uri'] = ['required', 'exists:' . str_plural($this->target_type ?? TaskTargetType::VIDEO)];
                }
                break;
            case 'PUT':

                if ($this->target_type === TaskTargetType::TEXT) {
                    $rules['body'] = ['required', 'string'];
                    $rules['target_id'] = ['required', 'exists:texts,id'];
                } else if ($this->target_type == TaskTargetType::PAPER) {
                    $rules['target_id'] = ['required', 'integer', 'exists:papers,id'];
                }else if ( $this->target_type == TaskTargetType::HOMEWORK || $this->target_type == TaskTargetType::PRACTICE) {
                    $rules['target_id'] = ['required', 'integer', 'exists:homeworks,id'];
                } else {
                    $rules['target_id'] = ['required', 'exists:' . str_plural($this->target_type ?? TaskTargetType::VIDEO).',id'];
                    $rules['media_uri'] = ['required', 'string'];
                    $rules['hash'] = ['required', 'string'];
                }
                break;
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'media_uri' => '资源名称',
            'keyword' => 'seo关键词',
            'describe' => 'seo描述',
            'target_id' => '资源',
        ];
    }
}
