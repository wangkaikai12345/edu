<?php

namespace App\Rules;

use App\Models\CategoryGroup;
use Illuminate\Contracts\Validation\Rule;

class CategoryRule implements Rule
{
    /**
     * @var
     */
    private $categoryGroup;

    protected $message = '';

    /**
     * CategoryRule constructor.
     * @param $categoryGroup
     */
    public function __construct(CategoryGroup $categoryGroup)
    {
        $this->categoryGroup = $categoryGroup;
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
            $item = $this->categoryGroup->categories()->find($id);
            if (!$item) {
                $this->message =  '该 ID :' . $id . '不存在';
                return false;
            }
            if ($item->children()->count()) {
                $this->message = "{$item->name} 下包含子分类，无法删除。";
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
