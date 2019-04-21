<?php

namespace App\Models;

use App\Traits\HitPostTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    use HitPostTrait;

    protected $table = 'posts';

    protected $fillable = ['title', 'subtitle', 'body', 'status', 'category_id', 'slug', 'is_essence', 'is_stick'];


    /**
     * 分类
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


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
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'model', 'model_has_tags');
    }


    /**
     * 收藏
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'model');
    }


    /**
     * 点赞
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function votes()
    {
        return $this->morphMany(Vote::class, 'voteable');
    }

    /**
     * 搜索
     *
     * @param $query
     * @param $params
     * @return mixed
     */
    public function scopeSearch($query, $params)
    {
        return $query
            ->when(!empty($params['title']), function ($q) use ($params) {
                return $q->where('title', 'like', "%{$params['title']}%");
            })
            ->when(!empty($params['category_id']), function ($q) use ($params) {
                return $q->where('category_id', $params['category_id']);
            })
            ->when(!empty($params['status']), function ($q) use ($params) {
                return $q->where('status', $params['status']);
            })
            ->when(!empty($params['recommend']), function ($q) {
                return $q->where('is_recommend', true);
            })
            ->when(!empty($params['essence']) && $params['essence'] == 1, function ($q) {
                return $q->where('is_essence', true);
            })
            ->when(!empty($params['stick']) && $params['stick'] == 1, function ($q) {
                return $q->where('is_stick', true);
            })
            ->when(!empty($params['creator_ids']), function ($q) use($params){
                return $q->whereIn('user_id', $params['creator_ids']);
            });
    }
}
