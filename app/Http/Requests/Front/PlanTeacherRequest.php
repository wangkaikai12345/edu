<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class PlanTeacherRequest extends BaseRequest
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
                    'user_id' => [
                        'required',
                        'exists:users,id',
                        Rule::unique('plan_teachers')->where('plan_id', $this->plan->id)
                    ],
                    'seq' => 'integer'
                ];
                break;
            // 更新
            case 'PUT':
                return [
                    'seq' => 'required|integer|between:1,999999'
                ];
                break;
        }
    }
}
