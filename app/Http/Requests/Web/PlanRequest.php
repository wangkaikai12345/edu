<?php

namespace App\Http\Requests\Web;

use App\Enums\SerializeMode;
use App\Http\Requests\BaseRequest;
use App\Rules\CustomEnumRule;
use Illuminate\Validation\Rule;

class PlanRequest extends BaseRequest
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
                    'title' => [
                        'required',
                        'string',
                        'max:20',
                        Rule::unique('plans')->where('course_id', $this->course->id)
                    ],
                    'about' => 'string|max:100',
                    'preview' => 'string|max:100',
                    'learn_mode' => 'in:free,lock',
                    'expiry_mode' => 'in:forever,period',
                    'expiry_started_at' => 'required_if:expiry_mode,period|date',
                    'expiry_ended_at' => 'required_if:expiry_mode,period|date|after:expiry_started_at',
                    'goals' => 'array',
                    'audiences' => 'array',
                    'max_students_count' => 'integer',
                    'free_started_at' => 'sometimes|date',
                    'free_ended_at' => 'sometimes|date|after:free_started_at',
                    'services' => 'array',
                    'show_services' => 'boolean',
                    'enable_finish' => 'boolean',
                    'price' => 'integer',
                    'coin_price' => 'integer',
                    'status' => 'string',
                    'buy' => 'boolean',
                    'serialize_mode' => [new CustomEnumRule(SerializeMode::class)],
                    'deadline_notification' => 'boolean',
                    'notify_before_days_of_deadline' => 'integer',
                ];
                break;
            case 'PUT':
                return [
                    'title' => [
                        'string',
                        'max:20',
                        Rule::unique('plans')->where('course_id', $this->course->id)->ignore($this->plan)
                    ],
                    'about' => 'string|max:100',
                    'learn_mode' => 'in:free,lock',
                    'expiry_mode' => 'in:forever,period',
                    'expiry_started_at' => 'required_if:expiry_mode,period|date',
                    'expiry_ended_at' => 'required_if:expiry_mode,period|date|after:expiry_started_at',
                    'goals' => 'array',
                    'audiences' => 'array',
                    'is_default' => 'boolean',
                    'max_students_count' => 'integer',
                    'status' => 'in:published,closed',
                    'is_free' => 'boolean',
                    'free_started_at' => 'sometimes|date',
                    'free_ended_at' => 'sometimes|date|after:free_ended_at',
                    'services' => 'array',
                    'show_services' => 'boolean',
                    'enable_finish' => 'boolean',
                    'price' => 'integer',
                    'coin_price' => 'integer',
                    'status' => 'string',
                    'buy' => 'boolean',
                    'serialize_mode' => [new CustomEnumRule(SerializeMode::class)],
                    'deadline_notification' => 'boolean',
                    'notify_before_days_of_deadline' => 'integer',
                ];
                break;
        }
    }

}
