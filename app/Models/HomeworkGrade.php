<?php

namespace App\Models;

use App\Traits\NoticeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class HomeworkGrade extends Model
{
    use SoftDeletes, SearchableTrait, SortableTrait, NoticeTrait;

    /**
     * 数据表
     */
    protected $table = 'homework_grades';

    /**
     * 搜索字段
     */
    public $searchable = ['title', 'user_id'];

    /**
     * 排序字段
     */
    public $sortable = ['created_at'];

    /**
     * 填充字段
     */
    protected $fillable = ['title', 'remarks', 'status', 'comment_bad', 'comment_middle', 'comment_good'];

    /**
     * 默认排序
     */
    protected $defaultSortCriteria = 'created_at,desc';

    /**
     * 类型设置
     */
    protected $casts = [
        'comment_bad' => 'array',
        'comment_middle' => 'array',
        'comment_good' => 'array',
    ];

    /**
     * 创建人
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
