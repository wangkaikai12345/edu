<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VideoQuestion extends Model
{
//    use SoftDeletes;

    /**
     * @var string 视频资源
     */
    protected $table = 'video_questions';

    /**
     * @var array
     */
    protected $fillable = ['paper_id', 'video_id', 'video_time', 'pattern'];

    /**
     * 所属试卷
     */
    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

}
