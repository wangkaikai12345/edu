<?php

namespace App\Rules;

use App\Models\TagGroup;
use Illuminate\Contracts\Validation\Rule;

class TagRule implements Rule
{
    protected $message = '';

    /**
     * @var TagGroup
     */
    private $tagGroup;

    /**
     * CategoryRule constructor.
     * @param TagGroup $tagGroup
     * @internal param $categoryGroup
     */
    public function __construct(TagGroup $tagGroup)
    {
        $this->tagGroup = $tagGroup;
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
        // 判断该数据是否存在于数据库中
        foreach ($value as $id) {
            $item = $this->tagGroup->tags()->find($id);
            if (!$item) {
                $this->message =  '该 ID :' . $id . '不存在';
                return false;
            }
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
