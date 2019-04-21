<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class ClassroomResult extends Model
{
    use SearchableTrait, SortableTrait;

    /**
     * @var string 考试结果
     */
    protected $table = 'classroom_results';

    /**
     * @var array 可搜索字段
     */
    public $searchable = [

    ];

    /**
     * @var array 可排序字段
     */
    public $sortable = [];

    /**
     * @var array 批量赋值字段
     */
    protected $fillable = [
        'progress',
        'score',
        'time',
    ];

    /**
     * 用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
