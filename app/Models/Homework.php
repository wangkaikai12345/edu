<?php

namespace App\Models;

use App\Traits\NoticeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class Homework extends Model
{
    use SoftDeletes, SearchableTrait, SortableTrait, NoticeTrait;

    /**
     * 数据表
     */
    protected $table = 'homeworks';

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
    protected $fillable = [
        'title',
        'about',
        'hint',
        'post_type',
        'grades',
        'grades_content',
        'tags',
        'video',
        'package',
        'status',
        'type',
    ];

    /**
     * 默认排序
     */
    protected $defaultSortCriteria = 'created_at,desc';

    /**
     * 类型设置
     */
    protected $casts = [
        'post_type' => 'array',
        'grades' => 'array',
        'grades_content' => 'array',
    ];

    /**
     * 创建人
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function task()
    {
        return $this->morphOne(Task::class, 'target');
    }

    /**
     * 作业结果
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author 王凯
     */
    public function homeworkPosts()
    {
        return $this->hasMany(HomeworkPost::class);
    }

    /**
     * 考试结果的前三名
     *
     * @param $query
     * @param Task $task
     * @return mixed
     * @author 王凯
     */
    public function scopeGetThird($query, Task $task)
    {
        return $this->homeworkPosts()->where('task_id', $task->id)->where('locked', 'open')->orderBy('result', 'desc')->take(3);
    }

    /**
     * 相关标签
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'model', 'model_has_tags');
    }
}
