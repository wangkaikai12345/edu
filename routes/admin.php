<?php

/*
|--------------------------------------------------------------------------
| Admin API Routes（后台接口）
|--------------------------------------------------------------------------
|
| 1. 后台接口的统一添加前缀 admin，为了与前台命名路由区分
| 2. 权限使用中间件做判断，默认仅使用 permission，而不使用 role（由于 Dingo Router 在中间件中获取 currentRouteName 不知为何总产生 502 错误，故只能写在路由之上）
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Http\Controllers\Admin', 'prefix' => 'admin', 'as' => 'admin', 'middleware' => ['auth', 'is-updated-password', 'api', 'bindings', 'serializer:array', 'change-locale', 'admin']], function ($api) {
        $api->group(['as' => 'role'], function ($api) {
            // 角色列表、详情、添加、更新、删除
            $api->get('roles', ['uses' => 'Permission\RoleController@index', 'as' => 'index'])->middleware('permission:admin.role.index');
            $api->get('roles/{role}', ['uses' => 'Permission\RoleController@show', 'as' => 'show'])->middleware('permission:admin.role.show');
            $api->post('roles', ['uses' => 'Permission\RoleController@store', 'as' => 'store'])->middleware('permission:admin.role.store');
            $api->put('roles/{role}', ['uses' => 'Permission\RoleController@update', 'as' => 'update'])->middleware('permission:admin.role.update');
            $api->delete('roles/{role}', ['uses' => 'Permission\RoleController@destroy', 'as' => 'destroy'])->middleware('permission:admin.role.destroy');
        });
        $api->group(['as' => 'userRole'], function ($api) {
            // 用户角色列表、添加
            $api->get('users/{user}/roles', ['uses' => 'Permission\UserRoleController@index', 'as' => 'index'])->middleware('permission:admin.userRole.index');
            $api->put('users/{user}/roles', ['uses' => 'Permission\UserRoleController@update', 'as' => 'update'])->middleware('permission:admin.userRole.update');
        });
        $api->group(['as' => 'permission'], function ($api) {
            // 权限列表、详情、添加、更新、删除
            $api->get('permissions', ['uses' => 'Permission\PermissionController@index', 'as' => 'index'])->middleware('permission:admin.permission.index');
            $api->get('permissions/{permission}', ['uses' => 'Permission\PermissionController@show', 'as' => 'show'])->middleware('permission:admin.permission.show');
            $api->post('permissions', ['uses' => 'Permission\PermissionController@store', 'as' => 'store'])->middleware('permission:admin.permission.store');
            $api->put('permissions/{permission}', ['uses' => 'Permission\PermissionController@update', 'as' => 'update'])->middleware('permission:admin.permission.update');
            $api->delete('permissions/{permission}', ['uses' => 'Permission\PermissionController@destroy', 'as' => 'destroy'])->middleware('permission:admin.permission.destroy');
        });
        $api->group(['as' => 'rolePermission'], function ($api) {
            // 角色权限列表、添加
            $api->get('roles/{role}/permissions', ['uses' => 'Permission\RolePermissionController@index', 'as' => 'index'])->middleware('permission:admin.rolePermission.index');
            $api->put('roles/{role}/permissions', ['uses' => 'Permission\RolePermissionController@update', 'as' => 'update'])->middleware('permission:admin.rolePermission.update');
        });
        $api->group(['as' => 'user'], function ($api) {
            // 用户列表、详情、添加、更新、解禁用户、禁用用户、重置密码
            $api->get('users', ['uses' => 'UserController@index', 'as' => 'index'])->middleware('permission:admin.user.index');
            $api->get('users/{user}', ['uses' => 'UserController@show', 'as' => 'show'])->middleware('permission:admin.user.show');
            $api->post('users', ['uses' => 'UserController@store', 'as' => 'store'])->middleware('permission:admin.user.store');
            $api->put('users/{user}', ['uses' => 'UserController@update', 'as' => 'update'])->middleware('permission:admin.user.update');
            $api->patch('user/{user}/unblock', ['uses' => 'UserController@unblock', 'as' => 'unblock'])->middleware('permission:admin.user.unblock');
            $api->patch('users/{user}/block', ['uses' => 'UserController@block', 'as' => 'block'])->middleware('permission:admin.user.block');
            $api->patch('users/{user}/reset', ['uses' => 'UserController@reset', 'as' => 'reset'])->middleware('permission:admin.user.reset');
            $api->get('teachers', ['uses' => 'UserController@teacher', 'as' => 'teacher'])->middleware('permission:admin.user.teacher');
            $api->patch('teachers/{user}/recommend', ['uses' => 'UserController@recommend', 'as' => 'recommend'])->middleware('permission:admin.user.recommend');
        });
        $api->group(['as' => 'course'], function ($api) {
            // 课程推荐、课程发布、课程列表、删除课程
            $api->patch('courses/{course}/recommend', ['uses' => 'CourseController@recommend', 'as' => 'recommend'])->middleware('permission:admin.course.recommend');
            $api->patch('courses/{course}/publish', ['uses' => 'CourseController@publish', 'as' => 'publish'])->middleware('permission:admin.course.publish');
            $api->get('courses', ['uses' => 'CourseController@index', 'as' => 'index'])->middleware('permission:admin.course.index');
            $api->delete('courses/{course}', ['uses' => 'CourseController@destroy', 'as' => 'destroy'])->middleware('permission:admin.course.destroy');
        });
        $api->group(['as' => 'topic'], function ($api) {
            // 话题列表、话题加精及取消、话题置顶及取消、删除话题
            $api->get('topics', ['uses' => 'TopicController@index', 'as' => 'index'])->middleware('permission:admin.topic.index');
            $api->put('topics/{topic}', ['uses' => 'TopicController@update', 'as' => 'update'])->middleware('permission:admin.topic.update');
            $api->delete('topics/{topic}', ['uses' => 'TopicController@destroy', 'as' => 'destroy'])->middleware('permission:admin.topic.destroy');
        });
        $api->group(['as' => 'note'], function ($api) {
            // 笔记列表、笔记删除
            $api->get('notes', ['uses' => 'NoteController@index', 'as' => 'index'])->middleware('permission:admin.note.index');
            $api->delete('notes/{note}', ['uses' => 'NoteController@destroy', 'as' => 'destroy'])->middleware('permission:admin.note.destroy');
        });
        $api->group(['as' => 'review'], function ($api) {
            // 评价列表、评价删除
            $api->get('reviews', ['uses' => 'ReviewController@index', 'as' => 'index'])->middleware('permission:admin.review.index');
            $api->delete('reviews/{review}', ['uses' => 'ReviewController@destroy', 'as' => 'destroy'])->middleware('permission:admin.review.destroy');
        });
        $api->group(['as' => 'categoryGroup'], function ($api) {
            // 分类群组列表、详情、添加、更新、删除
            $api->get('category-groups', ['uses' => 'CategoryGroupController@index', 'as' => 'index'])->middleware('permission:admin.categoryGroup.index');
            $api->get('category-groups/{categoryGroup}', ['uses' => 'CategoryGroupController@show', 'as' => 'show'])->middleware('permission:admin.categoryGroup.show');
        });
        $api->group(['as' => 'category'], function ($api) {
            // 分类列表、分类详情、分类添加、分类更新、分类删除、分类批量删除
            $api->get('category-groups/{categoryGroup}/categories', ['uses' => 'CategoryController@index', 'as' => 'index'])->middleware('permission:admin.category.index');
            $api->get('category-groups/{categoryGroup}/categories/{category}', ['uses' => 'CategoryController@show', 'as' => 'show'])->middleware('permission:admin.category.show');
            $api->post('category-groups/{categoryGroup}/categories', ['uses' => 'CategoryController@store', 'as' => 'store'])->middleware('permission:admin.category.store');
            $api->put('category-groups/{categoryGroup}/categories/{category}', ['uses' => 'CategoryController@update', 'as' => 'update'])->middleware('permission:admin.category.update');
            $api->delete('category-groups/{categoryGroup}/categories', ['uses' => 'CategoryController@destroy', 'as' => 'destroy'])->middleware('permission:admin.category.destroy');
        });
        $api->group(['as' => 'tagGroup'], function ($api) {
            // 标签群组列表、添加、详情、更新、删除
            $api->get('tag-groups', ['uses' => 'TagGroupController@index', 'as' => 'index'])->middleware('permission:admin.tagGroup.index');
            $api->post('tag-groups', ['uses' => 'TagGroupController@store', 'as' => 'store'])->middleware('permission:admin.tagGroup.store');
            $api->put('tag-groups/{tagGroup}', ['uses' => 'TagGroupController@update', 'as' => 'update'])->middleware('permission:admin.tagGroup.update');
            $api->delete('tag-groups/{tagGroup}', ['uses' => 'TagGroupController@destroy', 'as' => 'destroy'])->middleware('permission:admin.tagGroup.destroy');
        });
        $api->group(['as' => 'tag'], function ($api) {
            // 标签列表列表、添加、详情、更新、删除、批量删除
            $api->get('tag-groups/{tagGroup}/tags', ['uses' => 'TagController@index', 'as' => 'index'])->middleware('permission:admin.tag.index');
            $api->post('tag-groups/{tagGroup}/tags', ['uses' => 'TagController@store', 'as' => 'store'])->middleware('permission:admin.tag.store');
            $api->get('tag-groups/{tagGroup}/tags/{tag}', ['uses' => 'TagController@show', 'as' => 'show'])->middleware('permission:admin.tag.show');
            $api->put('tag-groups/{tagGroup}/tags/{tag}', ['uses' => 'TagController@update', 'as' => 'update'])->middleware('permission:admin.tag.update');
            $api->delete('tag-groups/{tagGroup}/tags/delete', ['uses' => 'TagController@batchDelete', 'as' => 'batchDelete'])->middleware('permission:admin.tag.batchDelete');
            $api->delete('tag-groups/{tagGroup}/tags/{tag}', ['uses' => 'TagController@destroy', 'as' => 'destroy'])->middleware('permission:admin.tag.destroy');
        });

        $api->group(['as' => 'conversation'], function ($api) {
            // 会话列表、详情
            $api->get('conversations', ['uses' => 'ConversationController@index', 'as' => 'index'])->middleware('permission:admin.conversation.index');
            $api->get('conversations/{conversation}', ['uses' => 'ConversationController@show', 'as' => 'show'])->middleware('permission:admin.conversation.show');
        });
        $api->group(['as' => 'message'], function ($api) {
            // 消息列表、管理列表、删除
            $api->get('/conversations/{conversation}/messages', ['uses' => 'MessageController@index', 'as' => 'index'])->middleware('permission:admin.message.index');
            $api->get('/manage-messages', ['uses' => 'MessageController@manage', 'as' => 'manage'])->middleware('permission:admin.message.manage');
            $api->delete('/messages', ['uses' => 'MessageController@destroy', 'as' => 'destroy'])->middleware('permission:admin.message.destroy');
        });
        $api->group(['as' => 'notice'], function ($api) {
            // 公告管理列表、详情、添加、更新、删除
            $api->get('notices', ['uses' => 'NoticeController@index', 'as' => 'index'])->middleware('permission:admin.notice.index');
            $api->get('notices/{notice}', ['uses' => 'NoticeController@show', 'as' => 'show'])->middleware('permission:admin.notice.show');
            $api->post('notices', ['uses' => 'NoticeController@store', 'as' => 'store'])->middleware('permission:admin.notice.store');
            $api->put('notices/{notice}', ['uses' => 'NoticeController@update', 'as' => 'update'])->middleware('permission:admin.notice.update');
            $api->delete('notices/{notice}', ['uses' => 'NoticeController@destroy', 'as' => 'destroy'])->middleware('permission:admin.notice.destroy');
        });
        $api->group(['as' => 'notification'], function ($api) {
            // 通知列表、详情、添加、更新、删除、批量删除（撤回）
            $api->get('notifications', ['uses' => 'NotificationController@index', 'as' => 'index'])->middleware('permission:admin.notification.index');
            $api->get('notifications/{notification}', ['uses' => 'NotificationController@show', 'as' => 'show'])->middleware('permission:admin.notification.show');
            $api->post('notifications', ['uses' => 'NotificationController@store', 'as' => 'store'])->middleware('permission:admin.notification.store');
            $api->put('notifications/{notification}', ['uses' => 'NotificationController@update', 'as' => 'update'])->middleware('permission:admin.notification.update');
            $api->delete('notifications', ['uses' => 'NotificationController@destroy', 'as' => 'destroy'])->middleware('permission:admin.notification.destroy');
            $api->delete('recall-last-notifications', ['uses' => 'NotificationController@recall', 'as' => 'recall'])->middleware('permission:admin.notification.recall');
        });
        $api->group(['as' => 'order'], function ($api) {
            // 全部订单、列表、详情、改价
            $api->get('all-orders', ['uses' => 'OrderController@all', 'as' => 'all'])->middleware('permission:admin.order.all');
            $api->get('orders', ['uses' => 'OrderController@index', 'as' => 'index'])->middleware('permission:admin.order.index');
            $api->get('orders/{order}', ['uses' => 'OrderController@show', 'as' => 'show'])->middleware('permission:admin.order.show');
            $api->put('orders/{order}', ['uses' => 'OrderController@update', 'as' => 'update'])->middleware('permission:admin.order.update');
            $api->delete('orders/{order}', ['uses' => 'OrderController@destroy', 'as' => 'destroy'])->middleware('permission:admin.order.destroy');
        });
        $api->group(['as' => 'trade'], function ($api) {
            // 交易记录列表、详情
            $api->get('trades', ['uses' => 'TradeController@index', 'as' => 'index'])->middleware('permission:admin.trade.index');
            $api->get('trades/{trade}', ['uses' => 'TradeController@show', 'as' => 'show'])->middleware('permission:admin.trade.show');
        });
        $api->group(['as' => 'paymentOrder'], function ($api) {
            // TODO 添加权限
            // 第三方支付服务订单 下载对账单、订单查询、取消订单
            $api->get('payment-orders', ['uses' => 'PaymentOrderController@bill', 'as' => 'index'])->middleware('permission:admin.paymentOrder.bill');
            $api->get('payment-orders/{order}', ['uses' => 'PaymentOrderController@search', 'as' => 'show'])->middleware('permission:admin.paymentOrder.search');
            $api->patch('payment-orders/{order}', ['uses' => 'PaymentOrderController@cancel', 'as' => 'update'])->middleware('permission:admin.paymentOrder.cancel');
        });
        $api->group(['as' => 'refund'], function ($api) {
            // 退款列表、详情、审核
            $api->get('refunds', ['uses' => 'RefundController@index', 'as' => 'index'])->middleware('permission:admin.refund.index');
            $api->get('refunds/{refund}', ['uses' => 'RefundController@show', 'as' => 'show'])->middleware('permission:admin.refund.show');
            $api->post('refunds/{refund}/audit', ['uses' => 'RefundController@audit', 'as' => 'audit'])->middleware('permission:admin.refund.audit');
            $api->post('refunds/{refund}/manual', ['uses' => 'RefundController@manual', 'as' => 'manual'])->middleware('permission:admin.refund.manual');
        });
        $api->group(['as' => 'setting'], function ($api) {
            // 系统配置详情、更新、删除
            $api->get('settings/{namespace}', ['uses' => 'SettingController@show', 'as' => 'show'])->middleware('permission:admin.setting.show');
            $api->put('settings/{namespace}', ['uses' => 'SettingController@update', 'as' => 'update'])->middleware('permission:admin.setting.update');
            $api->delete('settings/{namespace}', ['uses' => 'SettingController@destroy', 'as' => 'destroy'])->middleware('permission:admin.setting.destroy');
        });
        $api->group(['as' => 'slide'], function ($api) {
            // 轮播列表、添加、详情、更新、删除、排序
            $api->get('slides', ['uses' => 'SlideController@index', 'as' => 'index'])->middleware('permission:admin.slide.index');
            $api->post('slides', ['uses' => 'SlideController@store', 'as' => 'store'])->middleware('permission:admin.slide.store');
            $api->get('slides/{slide}', ['uses' => 'SlideController@show', 'as' => 'show'])->middleware('permission:admin.slide.show');
            $api->put('slides/{slide}', ['uses' => 'SlideController@update', 'as' => 'update'])->middleware('permission:admin.slide.update');
            $api->delete('slides/{slide}', ['uses' => 'SlideController@destroy', 'as' => 'destroy'])->middleware('permission:admin.slide.destroy');
            $api->patch('slides/sort', ['uses' => 'SlideController@sort', 'as' => 'sort'])->middleware('permission:admin.slide.sort');
        });

        $api->group(['as' => 'stat'], function ($api) {
            // 统计今日统计、新增订单统计、新增学员、活跃用户
            $api->get('stats/today-count', ['uses' => 'StatController@today', 'as' => 'today'])->middleware('permission:admin.stat.today');
            $api->get('stats/order', ['uses' => 'StatController@order', 'as' => 'orderStat'])->middleware('permission:admin.stat.order');
            $api->get('stats/member', ['uses' => 'StatController@member', 'as' => 'memberStat'])->middleware('permission:admin.stat.member');
            $api->get('stats/active-users', ['uses' => 'StatController@active', 'as' => 'activeStat'])->middleware('permission:admin.stat.active');
        });
        $api->group(['as' => 'coupon'], function ($api) {
            // 优惠券列表、详情、添加、删除、批次号码
            $api->get('coupons', ['uses' => 'CouponController@index', 'as' => 'index'])->middleware('permission:admin.coupon.index');
            $api->get('coupons/{coupon}', ['uses' => 'CouponController@show', 'as' => 'show'])->middleware('permission:admin.coupon.show');
            $api->post('coupons', ['uses' => 'CouponController@store', 'as' => 'store'])->middleware('permission:admin.coupon.store');
            $api->delete('coupons', ['uses' => 'CouponController@destroy', 'as' => 'destroy'])->middleware('permission:admin.coupon.destroy');
            $api->get('coupon-batches', ['uses' => 'CouponController@batch', 'as' => 'batch'])->middleware('permission:admin.coupon.batch');
            $api->get('export-coupons', ['uses' => 'CouponController@export', 'as' => 'export'])->middleware('permission:admin.coupon.export');
        });
        $api->group(['as' => 'feedback'], function ($api) {
            // 反馈列表、状态更新
            $api->get('feedback', ['uses' => 'FeedbackController@index', 'as' => 'index'])->middleware('permission:admin.feedback.index');
            $api->put('feedback/{feedback}', ['uses' => 'FeedbackController@update', 'as' => 'update'])->middleware('permission:admin.feedback.update');
        });
        $api->group(['as' => 'recharging'], function ($api) {
            // 充值额度管理
            $api->get('recharging', ['uses' => 'RechargingController@index', 'as' => 'index'])->middleware('permission:admin.recharging.index');
            $api->post('recharging', ['uses' => 'RechargingController@store', 'as' => 'store'])->middleware('permission:admin.recharging.store');
            $api->put('recharging/{recharging}', ['uses' => 'RechargingController@update', 'as' => 'update'])->middleware('permission:admin.recharging.update');
            $api->patch('recharging/{recharging}/publish', ['uses' => 'RechargingController@publish', 'as' => 'publish'])->middleware('permission:admin.recharging.publish');
            $api->delete('recharging/{recharging}', ['uses' => 'RechargingController@destroy', 'as' => 'destroy'])->middleware('permission:admin.recharging.destroy');
        });
        // 登陆日志
        $api->get('/logs', ['uses' => 'LogController@index', 'as' => 'index'])->middleware('permission:admin.log.index');
        // 证书上传
        $api->post('certs', ['uses' => 'CertController@store', 'as' => 'cert.store'])->middleware('permission:admin.cert.store');
        // 班级列表、发布、推荐
        $api->get('/classrooms', ['uses' => 'ClassroomController@index', 'as' => 'classroom.index'])->middleware('permission:admin.classroom.index');
        $api->patch('/classrooms/{classroom}/publish', ['uses' => 'ClassroomController@publish', 'as' => 'classroom.publish'])->middleware('permission:admin.classroom.publish');
        $api->patch('/classrooms/{classroom}/recommend', ['uses' => 'ClassroomController@recommend', 'as' => 'classroom.recommend'])->middleware('permission:admin.classroom.recommend');
        // 回收站相关（暂时无用）
        // $api->group(['namespace' => 'Recycle', 'as' => 'recycle', 'prefix' => 'recycle'], function ($api) {
        //     // 分类
        //     $api->get('categories', ['uses' => 'CategoryController@index', 'as' => 'category.index'])->middleware('permission:admin.recycle.category.index');
        //     $api->put('categories/{category}', ['uses' => 'CategoryController@update', 'as' => 'category.update'])->middleware('permission:admin.recycle.category.update');
        //     $api->delete('categories/{category}', ['uses' => 'CategoryController@destroy', 'as' => 'category.destroy'])->middleware('permission:admin.recycle.category.destroy');
        //     // 标签
        //     $api->get('tags', ['uses' => 'TagController@index', 'as' => 'tag.index'])->middleware('permission:admin.recycle.tag.index');
        //     $api->put('tags/{tag}', ['uses' => 'TagController@update', 'as' => 'tag.update'])->middleware('permission:admin.recycle.tag.update');
        //     $api->delete('tags/{tag}', ['uses' => 'TagController@destroy', 'as' => 'tag.destroy'])->middleware('permission:admin.recycle.tag.destroy');
        //     $api->delete('tags/delete', ['uses' => 'TagController@batchDelete', 'as' => 'tag.batchDelete'])->middleware('permission:admin.recycle.tag.batchDelete');
        // });
    });
});