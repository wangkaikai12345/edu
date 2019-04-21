<?php

namespace App\Rules;

use App\Models\Plan;
use Illuminate\Contracts\Validation\Rule;

class ChapterRule implements Rule
{
    /**
     * @var Plan
     */
    private $plan;

    /**
     * @var string
     */
    private $message;

    /**
     * Create a new rule instance.
     *
     * @param Plan $plan
     */
    public function __construct(Plan $plan)
    {
        $this->plan = $plan;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // 查询是否为章，章节父级ID为0
        if ((int)$value === 0) {
            return true;
        }

        $count = $this->plan->chapters()->where('id', $value)->count();

        if (!$count) {
            $this->message = '该版本下不存在该章';
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
