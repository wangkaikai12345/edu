<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;
use App\Traits\HashIdTrait;

class PaperResult extends Model
{
    use SortableTrait, SearchableTrait, HashIdTrait;

    protected $tables = 'paper_results';

    /**
     * @var array 可搜索字段
     */
    public $searchable = [
        'title',
    ];

    /**
     * @var array 可排序字段
     */
    public $sortable = [
        'created_at'
    ];

    /**
     * @var array 自动转换
     */
    protected $casts = [

    ];

    /**
     * @var array 批量赋值
     */
    protected $fillable = [

    ];

    /**
     * 创建人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 创建人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 所属试卷
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    /**
     * 所属任务
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * 考试结果下的题目答题结果
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author 王凯
     */
    public function questionResult()
    {
        return $this->hasMany(QuestionResult::class);
    }

    // 任务下的结果
    public function scopeTask($query, Task $task)
    {
        return $query->where('user_id', auth('web')->id())->where('task_id', $task->id);
    }

    /*
     * 所有的回答
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function results()
    {
        return $this->hasMany(QuestionResult::class);
    }

}
