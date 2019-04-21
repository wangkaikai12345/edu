<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // 以树形结构返回
        $data = config('permission.data');


        $labels = config('permission.labels');

        $preData = collect($data)->map(function ($item, $key) use ($labels) {
            $items = array_map(function ($value) use ($item, $labels) {
                return ['prefix' => $item['prefix'] . '.' . $value, 'label' => $item['label'] . $labels[$value]];
            }, $item['items']);

            return collect([
                'label' => $item['label'],
                'prefix' => $item['prefix'],
                'items' => $items,
            ]);
        })->toArray();



        foreach ($preData as $itemKey => $item) {
            $parent = Permission::create(['name' => $item['prefix'], 'title' => $item['label']]);

            foreach ($item['items'] as $key => $value) {
                Permission::create(['name' => $value['prefix'], 'title' => $value['label'], 'parent_id' => $parent->id]);
            }
        }

        // 创建角色
        $this->createRoles();
    }

    private function createRoles()
    {
        // 超管：所有权限
        $superAdmin = Role::create(['name' => 'super-admin', 'title' => '超级管理员']);
        $superAdmin->givePermissionTo(Permission::all());

        // 普通管理:除 Role、Permission、Setting 权限外所有
        $admin = Role::create(['name' => 'admin', 'title' => '管理员']);
        $admin->givePermissionTo(Permission::all());

        Role::create(['name' => 'teacher', 'title' => '教师']);
        Role::create(['name' => 'student', 'title' => '学生']);
    }
}
