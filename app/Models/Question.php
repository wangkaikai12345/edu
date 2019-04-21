<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class Question extends Model
{
    use SearchableTrait, SortableTrait, SoftDeletes;

    /**
     * @var string 题目
     */
    protected $table = 'questions';

    /**
     * @var array 可搜索字段
     */
    public $searchable = [
        'title',
        'type',
        'user_id',
        'user:username',
        'rate',
    ];

    /**
     * @var array 可排序字段
     */
    public $sortable = ['rate', 'created_at'];

    /**
     * @var array
     */
    protected $casts = ['options' => 'array', 'answers' => 'array'];

    /**
     * @var array 批量赋值
     */
    protected $fillable = [
        'title',
        'type',
        'options',
        'answers',
        'rate',
        'explain',
        'status'
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
     * 题目结果
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author 王凯
     */
    public function questionResult()
    {
        return $this->hasMany(QuestionResult::class);
    }

    /**
     * 通过修改器对正确 answers 排序；当比对正确答案时仅需排序匹配即可
     *
     * @param  array $value
     * @return void
     */
    public function setAnswersAttribute(array $value)
    {
        $value = array_map(function ($item) {
            return (integer)$item;
        }, $value);

        sort($value);

        $this->attributes['answers'] = json_encode($value);
    }
}
