<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Zip extends Model
{
    /**
     * @var string
     */
    protected $table = 'zips';

    /**
     * @var array
     */
    protected $fillable = ['media_uri', 'length', 'hash'];

    /**
     * 任务
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function task()
    {
        return $this->morphOne(Task::class, 'target');
    }
}
