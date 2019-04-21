<?php
/**
 * Created by PhpStorm.
 * Role: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use Spatie\Permission\Models\Role;

class RoleTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['permissions'];

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    public function transform(Role $model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'title' => $model->title,
        ];
    }

    /**
     * 权限
     */
    public function includePermissions(Role $model)
    {
        return $this->setDataOrItem($model->permissions, new PermissionTransformer());
    }
}