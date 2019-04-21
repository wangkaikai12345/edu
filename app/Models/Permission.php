<?php

namespace App\Models;

use App\Traits\HashIdTrait;

/**
 * @SWG\Definition(
 *      definition="Permission",
 *      type="object",
 *      required={},
 *      description="权限模型",
 *      @SWG\Property(property="id",type="string",readOnly=true),
 *      @SWG\Property(property="name",type="string",description="英文名称"),
 *      @SWG\Property(property="title",type="string",description="中文名称"),
 *      @SWG\Property(property="is_menu",type="boolean",description="是否为菜单"),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="PermissionPagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Permission")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="PermissionResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Permission"))
 *      )
 * )
 * @SWG\Definition(
 *      definition="PermissionTree",
 *      type="object",
 *      required={},
 *      description="权限树",
 *      @SWG\Property(property="label",type="string",description="权限组名称"),
 *      @SWG\Property(property="prefix",type="string",description="权限前缀"),
 *      @SWG\Property(property="items",type="array",description="权限元素数组",@SWG\Items(ref="#/definitions/Permission"))
 * )
 */
class Permission extends \Spatie\Permission\Models\Permission
{
    use HashIdTrait;

    public function children()
    {
        return $this->hasMany(__CLASS__, 'parent_id', 'id');
    }


    public function scopeParent($query)
    {
        return $query->where('parent_id', 0);
    }
}
