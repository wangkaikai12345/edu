<?php

namespace App\Models;

use App\Traits\HashIdTrait;

/**
 * @SWG\Definition(
 *      definition="Role",
 *      type="object",
 *      required={},
 *      description="角色模型",
 *      @SWG\Property(property="id",type="integer",readOnly=true),
 *      @SWG\Property(property="name",type="string",description="名称",readOnly=true),
 *      @SWG\Property(property="title",type="string",description="中文名称"),
 *      @SWG\Property(property="guard_name",type="string",default="api",description="Guard",readOnly=true),
 *      @SWG\Property(property="created_at",type="string",format="date-time",description="创建时间",readOnly=true),
 *      @SWG\Property(property="updated_at",type="string",format="date-time",description="更新时间",readOnly=true)
 * )
 * @SWG\Response(
 *      response="RolePagination",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Role")),
 *          @SWG\Property(property="meta",ref="$/definitions/MetaProperty")
 *      )
 * )
 * @SWG\Response(
 *      response="RoleResponse",
 *      description="",
 *      @SWG\Schema(
 *          @SWG\Property(property="data",type="array",@SWG\Items(ref="#/definitions/Role"))
 *      )
 * )
 */
class Role extends \Spatie\Permission\Models\Role
{
    use HashIdTrait;
}
