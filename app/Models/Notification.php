<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\DatabaseNotification;
use Jedrzej\Sortable\SortableTrait;

/**
 * @SWG\Definition(
 *      definition="Notification",
 *      type="object",
 *      required={},
 *      description="提醒模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="type",type="string",description="类型",readOnly=true),
 *      @SWG\Property(property="notifiable_id",type="integer",description="模型ID",readOnly=true),
 *      @SWG\Property(property="notifiable_type",type="string",description="模型",readOnly=true),
 *      @SWG\Property(property="data",type="string",description="提醒信息",readOnly=true),
 *      @SWG\Property(property="read_at",type="string",format="date-time",description="阅读时间"),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="NotificationPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Notification")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="NotificationResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Notification"))
 *      )
 * )
 */
class Notification extends DatabaseNotification
{
    use SortableTrait, SoftDeletes;

    /**
     * @var string 提醒
     */
    protected $table = 'notifications';

    /**
     * @var array
     */
    public $sortable = ['*'];

    protected $casts = [ 'data' => 'array' ];

    public $defaultSortCriteria = 'created_at,desc';
}
