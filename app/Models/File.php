<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /**
     * @var string 题目
     */
    protected $table = 'files';

    /**
     * @var array
     */
    protected $casts = [];

    /**
     * @var array 批量赋值
     */
    protected $fillable = [];

    /**
     * 创建人
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
