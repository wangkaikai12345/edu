<?php

namespace App\Models;

use App\Traits\NoticeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class HomeworkPost extends Model
{
    use SoftDeletes, SearchableTrait, SortableTrait, NoticeTrait;

    /**
     * 数据表
     */
    protected $table = 'homework_posts';

    /**
     * 搜索字段
     */
    public $searchable = ['title', 'user_id', 'user:username'];

    /**
     * 排序字段
     */
    public $sortable = ['created_at'];

    /**
     * 填充字段
     */
    protected $fillable = ['title', 'homework_id', 'course_id', 'plan_id', 'package', 'code', 'post_img', 'student_review',];

    /**
     * 默认排序
     */
    protected $defaultSortCriteria = 'created_at,desc';

    /**
     * 类型设置
     */
    protected $casts = [
        'post_img' => 'array',
        'grades' => 'array',
    ];

    /**
     * 创建人
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 创建人
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * 所属课程
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * 所属版本
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * 所属作业
     */
    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }

    // 任务下的作业
    public function scopeTask($query, Task $task)
    {
        return $query->where('user_id', auth('web')->id())->where('task_id', $task->id);
    }
}
