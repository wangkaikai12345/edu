<?php

namespace App\Models;

use App\Traits\HashIdTrait;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Navigation extends Model
{
    use SoftDeletes, HashIdTrait, Cachable;

    protected $table = 'navigations';

    protected $fillable = ['name', 'target', 'status', 'link', 'parent_id', 'type'];

    protected $casts = [
        'target' => 'boolean',
        'status' => 'boolean',
    ];

    // 导航类型
    const TYPE = [
        'HEAD' => 'header',
        'FOOTER' => 'footer',
    ];


    /**
     * 头部链接
     *
     * @param $query
     * @return mixed
     */
    public function scopeHead($query)
    {
        return $query->where('type', self::TYPE['HEAD'])->where('parent_id', 0);
    }


    /**
     * 底部链接
     *
     * @param $query
     * @return mixed
     */
    public function scopeFooter($query)
    {
        return $query->where('type', self::TYPE['FOOTER'])->where('parent_id', 0);
    }


    /**
     * 子数据
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(__CLASS__, 'parent_id', 'id');
    }
}
