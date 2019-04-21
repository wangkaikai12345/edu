<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Storage;

class CertRule implements Rule
{
    public $attribute;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $this->attribute = $attribute;


        return file_exists($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {

        return '证书文件 ' . $this->attribute . ' 不存在';
    }
}
