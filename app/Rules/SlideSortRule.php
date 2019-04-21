<?php

namespace App\Rules;

use App\Models\Slide;
use Illuminate\Contracts\Validation\Rule;

class SlideSortRule implements Rule
{
    public $message = '参数错误。';

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $requestCount = count($value);
        $dbCount = Slide::count();

        if ($requestCount != $dbCount) {
            $this->message = '传递的数据与已存在数据不一致。';
            return false;
        }

        if (Slide::whereIn('id', $value)->count() != $dbCount) {
            $this->message = '传递的数据与已存在数据有冲突。';
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
