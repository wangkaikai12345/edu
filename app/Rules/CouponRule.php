<?php

namespace App\Rules;

use App\Enums\CouponType;
use Illuminate\Contracts\Validation\Rule;

class CouponRule implements Rule
{
    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $message;

    /**
     * Create a new rule instance.
     *
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->type === CouponType::DISCOUNT && ($value < 0 || $value > 100)) {
            $this->message = '折扣额度 范围 0 - 100。';
            return false;
        }
        if ($this->type === CouponType::VOUCHER && $value < 0) {
            $this->message = '代金额度 不能小于 0。';
            return false;
        }
        if ($this->type === CouponType::AUDITION && $value < 0) {
            $this->message = '试听天数 不能小于 0';
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
