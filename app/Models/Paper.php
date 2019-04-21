<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class Paper extends Model
{
    use SoftDeletes, SortableTrait, SearchableTrait;

    protected $tables = 'papers';

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
        'title',
        'expect_time',
        'extra',
        'total_score',
        'status',
        'questions_count'
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
     * 标签
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'model', 'model_has_tags');
    }

    /**
     * 试卷下的所有问题-关联
     */
    public function paperQuestions()
    {
        return $this->hasMany(PaperQuestion::class);
    }

    /**
     * 试卷下的所有问题
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'paper_questions')->withPivot('score');
    }

    /**
     * 活动
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function task()
    {
        return $this->morphOne(Task::class, 'target');
    }

    /**
     * 试卷结果
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author 王凯
     */
    public function paperResult()
    {
        return $this->hasMany(PaperResult::class);
    }

    public function currentPaperResult()
    {
        return $this->paperResult()->where('user_id', auth('web')->id());
    }

}
