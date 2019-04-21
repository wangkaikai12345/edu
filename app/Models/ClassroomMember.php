<?php

namespace App\Models;

use App\Enums\StudentStatus;
use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class ClassroomMember extends Model
{
    use SearchableTrait, SortableTrait;

    /**
     * @var string
     */
    protected $table = 'classroom_members';

//    protected $primaryKey = ['classroom_id', 'user_id'];

    /**
     * @var array
     */
    protected $fillable = [
        'classroom_id',
        'user_id',
        'remark',
    ];
    /**
     * @var array
     */
    public $searchable = [
        'user_id',
        'deadline',
        'type',
        'status',
        'learned_count',
        'learned_compulsory_count',
        'finished_at',
        'exited_at',
        'last_learned_at',
        'user:username',
    ];
    public $sortable = ['created_at'];
    /**
     * @var array
     */
    protected $dates = [
        'deadline',
        'finished_at',
        'exited_at',
        'last_learned_at',
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

    /**
     * 班级
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * 正常学员
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeNormal(Builder $query)
    {
        return $query->whereIn('status', [StudentStatus::BEGINNING, StudentStatus::LEARNING, StudentStatus::FINISHED]);
    }
}
