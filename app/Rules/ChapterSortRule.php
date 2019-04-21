<?php

namespace App\Rules;

use App\Models\Chapter;
use App\Models\Plan;
use App\Models\Task;
use Illuminate\Contracts\Validation\Rule;

class ChapterSortRule implements Rule
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
        $parentId = (int)request('parent_id', 0);
        $beforeId = request('before_id', false);

        switch (request('type')) {
            // 移动章
            case 'chapter':
            case 'section':
                $target =  Chapter::where('plan_id', $this->plan->id)->where('parent_id', $parentId)->find($value);
                if (!$target) {
                    $this->message = '移动目标 不存在。';
                    return false;
                }
                if (!$beforeId) {
                    return true;
                }

                $before = Chapter::where('plan_id', $this->plan->id)->where('parent_id', $parentId)->find($beforeId);
                if (!$before) {
                    $this->message = '无法跨越章节排序。';
                    return false;
                }
                return true;
                break;
                // 移动任务
            case 'task':
                if (!$parentId) {
                    $this->message = '父级 必填。';
                    return false;
                }
                $parent = Chapter::where('parent_id', '!=', 0)->find($parentId);
                if (!$parent) {
                    $this->message = '父级 不存在。';
                    return false;
                }

                $target = Task::where('chapter_id', $parent->id)->find($value);
                if (!$target) {
                    $this->message = '移动目标 不存在。';
                    return false;
                }
                if (!$beforeId) {
                    return true;
                }
                $before = Task::where('chapter_id', $parent->id)->find($beforeId);
                if (!$before) {
                    $this->message = '无法跨越章节排序。';
                    return false;
                }
                return true;
                break;
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
