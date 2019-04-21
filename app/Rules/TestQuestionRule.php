<?php

namespace App\Rules;

use App\Enums\QuestionType;
use App\Models\Question;
use Illuminate\Contracts\Validation\Rule;

class TestQuestionRule implements Rule
{
    public $message = '';

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
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!is_array($value)) {
            $this->message = ':attribute 必须是一个数组';
            return false;
        }

        switch ($attribute) {
            case 'type':
                foreach ($value as $item) {
                    if (!in_array($item, [QuestionType::SINGLE, QuestionType::MULTIPLE, QuestionType::JUDGE])) {
                        $this->message = ':attribute 必须是单选、多选、判断';
                        return false;
                    }
                }
                return true;
                break;
            case 'questions':
                foreach ($value as $item) {
                    if (empty($item['id']) || !Question::where('id', $item['id'])->exists()) {
                        $this->message = ':attribute 不存在';
                        return false;
                    }
                    if (!isset($item['score'])) {
                        $this->message = '分值 不存在';
                        return false;
                    }
                }
                return true;
                break;
        }
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
