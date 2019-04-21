<?php

namespace App\Rules;

use App\Models\Tag;
use Illuminate\Contracts\Validation\Rule;

class TagsRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (Tag::whereIn('id', $value)->count() !== count($value)) {
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
        return '标签不存在.';
    }
}
