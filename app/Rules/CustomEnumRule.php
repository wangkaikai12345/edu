<?php

namespace App\Rules;

use App\Enums\TaskTargetType;
use Illuminate\Contracts\Validation\Rule;

class CustomEnumRule implements Rule
{
    private $validValues;
    private $attribute;

    /**
     * Create a new rule instance.
     */
    public function __construct($enum)
    {
        $this->validValues =$enum::getValues();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->attribute = str_contains($attribute, '.') ? str_after($attribute, '.') : $attribute;
        return in_array($value, $this->validValues, true);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __(':attribute is invalid.', [
            'attribute' => __('validation.attributes.' . $this->attribute)
        ]);
    }
}
