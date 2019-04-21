<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaperQuestion extends Model
{

    /**
     * @var array 批量赋值
     */
    protected $fillable = [
        'paper_id',
        'question_id',
        'score'
    ];

    public $timestamps = false;

    /**
     * 关联表所属于的问题
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
