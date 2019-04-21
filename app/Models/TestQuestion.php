<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TestQuestion extends Pivot
{
    /**
     * @var string 考试题目
     */
    protected $table = 'paper_questions';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    public $fillable = ['test_id', 'question_id', 'score'];
}

