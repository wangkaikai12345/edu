<?php

return [

    'models' => [

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your permissions. Of course, it
         * is often just the "Permission" model but you may use whatever you like.
         *
         * The model you want to use as a Permission model needs to implement the
         * `Spatie\Permission\Contracts\Permission` contract.
         */

        'permission' => Spatie\Permission\Models\Permission::class,

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your roles. Of course, it
         * is often just the "Role" model but you may use whatever you like.
         *
         * The model you want to use as a Role model needs to implement the
         * `Spatie\Permission\Contracts\Role` contract.
         */

        'role' => Spatie\Permission\Models\Role::class,

    ],

    'table_names' => [

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'roles' => 'roles',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your permissions. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'permissions' => 'permissions',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your models permissions. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'model_has_permissions' => 'model_has_permissions',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your models roles. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'model_has_roles' => 'model_has_roles',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles permissions. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'role_has_permissions' => 'role_has_permissions',
    ],

    'column_names' => [

        /*
         * Change this if you want to name the related model primary key other than
         * `model_id`.
         *
         * For example, this would be nice if your primary keys are all UUIDs. In
         * that case, name this `model_uuid`.
         */
        'model_morph_key' => 'model_id',
    ],

    /*
     * When set to true, the required permission/role names are added to the exception
     * message. This could be considered an information leak in some contexts, so
     * the default setting is false here for optimum safety.
     */

    'display_permission_in_exception' => false,

    'cache' => [

        /*
         * By default all permissions will be cached for 24 hours unless a permission or
         * role is updated. Then the cache will be flushed immediately.
         */

        'expiration_time' => 60 * 24,

        /*
         * The key to use when tagging and prefixing entries in the cache.
         */

        'key' => 'spatie.permission.cache',

        /*
         * When checking for a permission against a model by passing a Permission
         * instance to the check, this key determines what attribute on the
         * Permissions model is used to cache against.
         *
         * Ideally, this should match your preferred way of checking permissions, eg:
         * `$user->can('view-posts')` would be 'name'.
         */

        'model_key' => 'name',

        /*
         * You may optionally indicate a specific cache driver to use for permission and
         * role caching using any of the `store` drivers listed in the cache.php config
         * file. Using 'default' here means to use the `default` set in cache.php.
         */
        'store' => 'default',
    ],

    /*
     * 权限对应的中文解读
     */

    'labels' => [
        'index'       => '列表',
        'show'        => '详情',
        'store'       => '添加',
        'update'      => '更新',
        'destroy'     => '删除',
        'block'       => '锁定',
        'unblock'     => '解锁',
        'reset'       => '重置',
        'teacher'     => '教师列表',
        'recommend'   => '推荐',
        'publish'     => '发布',
        'batchDelete' => '批量删除',
        'manage'      => '管理列表',
        'recall'      => '撤回',
        'audit'       => '审核',
        'manual'      => '手动',
        'today'       => '今日数据',
        'order'       => '新增订单',
        'member'      => '新增学员',
        'active'      => '活跃用户',
        'batch'       => '批次码',
        'export'      => '导出',
        'sort'        => '排序',
        'entry'       => '准入',
        'bill'        => '账单',
        'search'      => '搜索',
        'cancel'      => '取消',
        'all'      => '全部',
    ],


    /*
     * 本系统的权限列表
     */

    'data' => [
        [
            'prefix' => 'admin',
            'label'  => '后台',
            'items'  => ['entry'],
        ],
        [
            'prefix' => 'admin.role',
            'label'  => '角色',
            'items'  => ['index', 'show', 'store', 'update', 'destroy',],
        ],
        [
            'prefix' => 'admin.userRole',
            'label'  => '用户角色',
            'items'  => ['index', 'update',],
        ],
        [
            'prefix' => 'admin.permission',
            'label'  => '权限',
            'items'  => ['index', 'show', 'store', 'update', 'destroy',],
        ],
        [
            'prefix' => 'admin.rolePermission',
            'label'  => '角色权限',
            'items'  => ['index', 'update',],
        ],
        [
            'prefix' => 'admin.user',
            'label'  => '用户',
            'items'  => ['index', 'store', 'show', 'update', 'block', 'unblock', 'reset', 'teacher', 'recommend'],
        ],
        [
            'prefix' => 'admin.course',
            'label'  => '课程',
            'items'  => ['index', 'destroy', 'publish', 'recommend'],
        ],
        [
            'prefix' => 'admin.topic',
            'label'  => '话题',
            'items'  => ['index', 'update', 'destroy'],
        ],
        [
            'prefix' => 'admin.note',
            'label'  => '笔记',
            'items'  => ['index', 'destroy'],
        ],
        [
            'prefix' => 'admin.review',
            'label'  => '评价',
            'items'  => ['index', 'destroy'],
        ],
        [
            'prefix' => 'admin.categoryGroup',
            'label'  => '分类组',
            'items'  => ['index', 'show', 'store', 'update', 'destroy'],
        ],
        [
            'prefix' => 'admin.category',
            'label'  => '分类',
            'items'  => ['index', 'show', 'store', 'update', 'destroy'],
        ],
        [
            'prefix' => 'admin.tagGroup',
            'label'  => '标签组',
            'items'  => ['index', 'show', 'store', 'update', 'destroy'],
        ],
        [
            'prefix' => 'admin.tag',
            'label'  => '标签',
            'items'  => ['index', 'show', 'store', 'update', 'destroy', 'batchDelete'],
        ],
        [
            'prefix' => 'admin.conversation',
            'label'  => '会话',
            'items'  => ['index', 'show',],
        ],
        [
            'prefix' => 'admin.message',
            'label'  => '私信',
            'items'  => ['index', 'destroy', 'manage'],
        ],
        [
            'prefix' => 'admin.notice',
            'label'  => '公告',
            'items'  => ['index', 'show', 'store', 'update', 'destroy'],
        ],
        [
            'prefix' => 'admin.notification',
            'label'  => '提醒',
            'items'  => ['index', 'show', 'store', 'update', 'destroy', 'recall'],
        ],
        [
            'prefix' => 'admin.order',
            'label'  => '订单',
            'items'  => ['all', 'index', 'show', 'update', 'destroy',],
        ],
        [
            'prefix' => 'admin.trade',
            'label'  => '交易',
            'items'  => ['index', 'show',],
        ],
        [
            'prefix' => 'admin.refund',
            'label'  => '退款',
            'items'  => ['index', 'show', 'audit', 'manual'],
        ],
        [
            'prefix' => 'admin.setting',
            'label'  => '配置',
            'items'  => ['show', 'update', 'destroy',],
        ],
        [
            'prefix' => 'admin.slide',
            'label'  => '轮播',
            'items'  => ['index', 'show', 'store', 'update', 'destroy', 'sort'],
        ],
        [
            'prefix' => 'admin.stat',
            'label'  => '统计',
            'items'  => ['today', 'order', 'member', 'active',],
        ],

        [
            'prefix' => 'admin.coupon',
            'label'  => '优惠券',
            'items'  => ['index', 'show', 'store', 'destroy', 'batch', 'export'],
        ],
        [
            'prefix' => 'admin.feedback',
            'label'  => '反馈',
            'items'  => ['index', 'update',],
        ],
        [
            'prefix' => 'admin.log',
            'label'  => '日志',
            'items'  => ['index',],
        ],
        [
            'prefix' => 'admin.cert',
            'label'  => '证书',
            'items'  => ['store',],
        ],
        [
            'prefix' => 'admin.recharging',
            'label'  => '充值额度',
            'items'  => ['index', 'store', 'update', 'destroy', 'publish'],
        ],
        [
            'prefix' => 'admin.paymentOrder',
            'label'  => '第三方支付订单',
            'items'  => ['bill', 'search', 'cancel'],
        ],
        [
            'prefix' => 'admin.classroom',
            'label'  => '班级',
            'items'  => ['index', 'publish', 'recommend'],
        ],

    ],
];
