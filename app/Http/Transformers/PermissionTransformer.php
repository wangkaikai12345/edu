<?php
/**
 * Created by PhpStorm.
 * Permission: wangbaolong
 * Date: 2018/4/18
 * Time: 10:22
 */

namespace App\Http\Transformers;

use Spatie\Permission\Models\Permission;

class PermissionTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = ['roles'];

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    public function transform(Permission $model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'title' => $model->title,
            'is_menu' => (boolean)$model->is_menu,
        ];
    }

    /**
     * 权限
     */
    public function includeRoles(Permission $model)
    {
        return $this->setDataOrItem($model->roles, new RoleTransformer());
    }
}