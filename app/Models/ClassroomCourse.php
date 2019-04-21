<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class ClassroomCourse extends Model
{
    use SearchableTrait, SortableTrait;

    /**
     * @var string
     */
    protected $table = 'classroom_courses';
    /**
     * @var string
     */
    protected $fillable = [
        'classroom_id',
        'course_id',
        'plan_id',
        'seq'
    ];
    /**
     * @var array
     */
    public $searchable = [
        'classroom_id',
        'course_id',
        'created_at',
        'is_synced',
        'course:title',
    ];
    /**
     * @var array
     */
    public $sortable = ['created_at', 'seq'];

    /**
     * 从属班级
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * 附属课程
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * 附属版本
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
