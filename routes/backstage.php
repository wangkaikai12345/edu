<?php

Route::group(['namespace' => 'Backstage', 'middleware' => ['backstage', 'auth'], 'as' => 'backstage.'], function () {

    // 后台首页
    Route::get('/', 'IndexController@index')->name('index');

    /**
     * 权限管理
     */

    Route::group(['namespace' => 'Permission'], function () {
        // 角色
        Route::get('roles', 'RoleController@index')->name('roles.index');
        // 角色添加
        Route::get('roles/create', 'RoleController@create')->name('roles.create');
        // 角色保存
        Route::post('roles', 'RoleController@store')->name('roles.store');
        // 角色修改
        Route::get('roles/{role}/edit', 'RoleController@edit')->name('roles.edit');
        // 更新
        Route::put('roles/{role}', 'RoleController@update')->name('roles.update');
        // 删除
        Route::delete('roles/{role}', 'RoleController@destroy')->name('roles.destroy');
        // 字段验证
        Route::post('roles-verify', 'RoleController@verifyFieldUniqueness')->name('roles.verify');

        // 权限
        Route::get('permissions', 'PermissionController@index')->name('permissions.index');
        // 添加
        Route::get('permissions/create', 'PermissionController@create')->name('permissions.create');
        // 保存
        Route::post('permissions', 'PermissionController@store')->name('permissions.store');
        // 修改
        Route::get('permissions/{permission}/edit', 'PermissionController@edit')->name('permissions.edit');
        // 更新
        Route::put('permissions/{permission}', 'PermissionController@update')->name('permissions.update');
        // 删除
        Route::delete('permissions/{permission}', 'PermissionController@destroy')->name('permissions.destroy');
        // 字段验证
        Route::post('permissions-verify', 'PermissionController@verifyFieldUniqueness')->name('permissions.verify');


        Route::get('users/{user}/roles', 'UserRoleController@index')->name('users.roles.show');
        Route::put('users/{user}/roles', 'UserRoleController@update')->name('users.roles.update');
    });

    /*****用户管理******/

    // 用户列表
    Route::get('users', 'UserController@index')->name('users.index');
    // 添加
    Route::get('users/create', 'UserController@create')->name('users.create');
    // 用户详情
    Route::get('users/{user}', 'UserController@show')->name('users.show');
    // 用户修改
    Route::get('users/{user}/edit', 'UserController@edit')->name('users.edit');
    // 添加用户
    Route::post('users', 'UserController@store')->name('users.store');
    // 用户更新
    Route::put('users/{user}', 'UserController@update')->name('users.update');
    // 用户禁用与恢复
    Route::patch('user/{user}/block', 'UserController@block')->name('users.block');
    // 用户重置密码
    Route::patch('users/{user}/reset', 'UserController@reset')->name('users.reset');
    // 用户重置密码
    Route::get('users/{user}/reset', 'UserController@resetPasswordShow')->name('users.reset.show');
    // 验证字段唯一性
    Route::post('users/verify', 'UserController@verifyFieldUniqueness')->name('users.verify');
    // 在线用户
    Route::get('users-active', 'UserController@active')->name('active.users');
    // 登陆日志
    Route::get('users-login-logs', 'UserController@loginLog')->name('users.login_log');
    // 用户日志
    Route::get('users/{user}/logs', 'UserController@userLog')->name('users.logs');
    // 教师列表
    Route::get('teachers', 'TeacherController@index')->name('users.teacher.index');
    // 推荐教师列表
    Route::get('teachers-recommend', 'TeacherController@recommendIndex')->name('users.teacher.recommend.index');
    // 添加老师
    Route::get('teachers/create', 'TeacherController@create')->name('users.teacher.create');
    // 推荐教师
    Route::patch('teachers/{user}/recommend', 'TeacherController@recommend')->name('users.teacher.recommend');
    // 推荐教师页面
    Route::get('teachers/{user}/recommend', 'TeacherController@recommendShow')->name('users.teacher.recommend.show');

    /***************私信管理*****************/
    // 私信列表
    Route::get('/manage-messages', 'MessageController@manage')->name('messages.manage.index');
    // 私信删除
    Route::delete('/messages', 'MessageController@destroy')->name('messages.destroy');
    // 消息列表、管理列表、删除
    Route::get('/conversations/{conversation}/messages', 'MessageController@index')->name('messages.index');

    /****************课程管理****************/
    // 课程列表
    Route::get('courses', 'CourseController@index')->name('courses.index');
    // 课程推荐列表
    Route::get('courses-recommend', 'CourseController@recommendIndex')->name('courses.recommend.index');
    // 课程推荐
    Route::get('courses/{course}/recommend', 'CourseController@recommendShow')->name('courses.recommend.show');
    // 课程推荐
    Route::patch('courses/{course}/recommend', 'CourseController@recommend')->name('courses.recommend');
    // 课程发布
    Route::patch('courses/{course}/publish', 'CourseController@publish')->name('courses.publish');
    // 课程删除
    Route::delete('courses/{course}', 'CourseController@destroy')->name('courses.destroy');

    /******************班级管理******************/
    if (config('app.model') == 'classroom') {
        // 班级列表
        Route::get('/classrooms', 'ClassroomController@index')->name('classrooms.index');
        // 班级推荐列表
        Route::get('/classrooms-recommend', 'ClassroomController@recommendIndex')->name('classrooms.recommend.index');
        // 班级推荐
        Route::get('/classrooms/{classroom}/recommend', 'ClassroomController@recommendShow')->name('classrooms.recommend.show');
        // 发布、
        Route::patch('/classrooms/{classroom}/publish', 'ClassroomController@publish')->name('classrooms.publish');
        // 推荐
        Route::patch('/classrooms/{classroom}/recommend', 'ClassroomController@recommend')->name('classrooms.recommend');
        // 删除
        Route::delete('/classrooms/{classroom}', 'ClassroomController@destroy')->name('classrooms.destroy');
    }

    /***************分类群组********************/

    // 分类群组列表、详情、添加、更新、删除
    Route::get('category-groups', 'CategoryGroupController@index')->name('categoryGroup.index');
    // 添加
    Route::get('category-groups/create', 'CategoryGroupController@create')->name('categoryGroup.create');
    // 修改
    Route::get('category-groups/{categoryGroup}/edit', 'CategoryGroupController@edit')->name('categoryGroup.edit');
    // 更新
    Route::put('category-groups/{categoryGroup}', 'CategoryGroupController@update')->name('categoryGroup.update');
    // 保存
    Route::post('category-groups', 'CategoryGroupController@store')->name('categoryGroup.store');
    // 验证字段唯一性
    Route::post('category-groups-verify', 'CategoryGroupController@verifyFieldUniqueness')->name('categoryGroup.verify');
    // 详情
    Route::get('category-groups/{categoryGroup}', 'CategoryGroupController@show')->name('categoryGroup.show');


    /***************分类群组子类********************/
    // 获取分组子类数据
    Route::get('category-groups/{categoryGroup}/categories', 'CategoryController@index')->name('category.index');
    // 子类创建
    Route::get('category-groups/{categoryGroup}/categories/create', 'CategoryController@create')->name('category.create');
    // 子类添加
    Route::post('category-groups/{categoryGroup}/categories', 'CategoryController@store')->name('category.store');
    // 修改
    Route::get('category-groups/{categoryGroup}/categories/{category}/edit', 'CategoryController@edit')->name('category.edit');
    // 更新
    Route::put('category-groups/{categoryGroup}/categories/{category}', 'CategoryController@update')->name('category.update');
    // 数据验证
    Route::post('category-groups/{categoryGroup}/categories-verify', 'CategoryController@verifyFieldUniqueness')->name('category.verify');

    /***********标签分组*************/
    // 标签群组列表、添加、详情、更新、删除
    Route::get('tag-groups', 'TagGroupController@index')->name('tagGroups.index');
    // 添加
    Route::get('tag-groups/create', 'TagGroupController@create')->name('tagGroups.create');
    // 保存
    Route::post('tag-groups', 'TagGroupController@store')->name('tagGroups.store');
    // 修改
    Route::get('tag-groups/{tagGroup}/edit', 'TagGroupController@edit')->name('tagGroups.edit');
    // 更新
    Route::put('tag-groups/{tagGroup}', 'TagGroupController@update')->name('tagGroups.update');
    // 验证字段唯一性
    Route::post('tag-groups-verify', 'TagGroupController@verifyFieldUniqueness')->name('tagGroups.verify');
    //Route::delete('tag-groups/{tagGroup}', 'TagGroupController@destroy')->name('tagGroups.destroy');

    // 标签列表列表、添加、详情、更新、删除、批量删除
    //Route::get('tag-groups/{tagGroup}/tags', 'TagController@index')->name('tags.index');
    /**************标签子类*****************/
    // 添加
    Route::get('tag-groups/{tagGroup}/tags/create', 'TagController@create')->name('tags.create');
    // 保存
    Route::post('tag-groups/{tagGroup}/tags', 'TagController@store')->name('tags.store');
    // 修改
    Route::get('tag-groups/{tagGroup}/tags/{tag}/edit', 'TagController@edit')->name('tags.edit');
    // 更新
    Route::put('tag-groups/{tagGroup}/tags/{tag}', 'TagController@update')->name('tags.update');
    // 验证字段唯一性
    Route::post('tag-groups/{tagGroup}/tags-verify', 'TagController@verifyFieldUniqueness')->name('tags.verify');
    // 删除
    Route::delete('tag-groups/{tagGroup}/tags/{tag}', 'TagController@destroy')->name('tags.destroy');


    /****************** 公告管理 ******************/

    // 列表
    Route::get('notices', 'NoticeController@index')->name('notices.index');
    // 添加
    Route::get('notices/create', 'NoticeController@create')->name('notices.create');
    // 保存
    Route::post('notices', 'NoticeController@store')->name('notices.store');
    // 修改
    Route::get('notices/{notice}/edit', 'NoticeController@edit')->name('notices.edit');
    // 更新
    Route::put('notices/{notice}', 'NoticeController@update')->name('notices.update');
    // 删除
    Route::delete('notices/{notice}', 'NoticeController@destroy')->name('notices.destroy');
    //Route::get('notices/{notice}', 'NoticeController@show')->name('notices.show');


    /***************** 站内通知 ***********************/

    // 列表
    Route::get('notifications', 'NotificationController@index')->name('notifications.index');
    // 添加
    Route::get('notifications/create', 'NotificationController@create')->name('notifications.create');
    // 保存
    Route::post('notifications', 'NotificationController@store')->name('notifications.store');

    Route::get('notifications/{notification}', 'NotificationController@show')->name('notifications.show');
    Route::put('notifications/{notification}', 'NotificationController@update')->name('notifications.update');
    // 删除
    Route::delete('notifications', 'NotificationController@destroy')->name('notifications.destroy');
    // 通知用户搜索
    Route::get('notification-users', 'NotificationController@users')->name('notifications.search.users');

    //Route::delete('recall-last-notifications', 'NotificationController@recall')->name('notifications.recall');


    /****************** 轮播图设置 ************************/

    // 列表
    Route::get('slides', 'SlideController@index')->name('slides.index');
    // 添加
    Route::get('slides/create', 'SlideController@create')->name('slides.create');
    // 保存
    Route::post('slides', 'SlideController@store')->name('slides.store');
    // 修改
    Route::get('slides/{slide}/edit', 'SlideController@edit')->name('slides.edit');

    Route::get('slides/{slide}', 'SlideController@show')->name('slides.show');

    Route::put('slides/{slide}', 'SlideController@update')->name('slides.update');

    Route::delete('slides/{slide}', 'SlideController@destroy')->name('slides.destroy');

    Route::patch('slides/sort', 'SlideController@sort')->name('slides.sort');


    /********************** 优惠码管理 ***************************/

    // 列表
    Route::get('coupons', 'CouponController@index')->name('coupons.index');
    // 获取课程
    Route::get('coupons-courses', 'CouponController@courses')->name('coupons.courses');
    // 课程保存
    Route::post('coupons', 'CouponController@store')->name('coupons.store');
    // 删除
    Route::delete('coupons', 'CouponController@destroy')->name('coupons.destroy');
    // 批量撤销
    Route::get('coupon-batches', 'CouponController@batch')->name('coupons.batch');
    //Route::get('coupons/{coupon}', 'CouponController@show')->name('coupons.show');
    Route::get('export-coupons', 'CouponController@export')->name('coupons.export');


    /************************ 订单管理 *****************************/

    // 列表
    Route::get('orders', 'OrderController@index')->name('orders.index');
    // 订单详情
    Route::get('orders/{order}', 'OrderController@show')->name('orders.show');

    //Route::get('all-orders', 'OrderController@all')->name('orders.all');
    //Route::put('orders/{order}', 'OrderController@update')->name('orders.update');
    //Route::delete('orders/{order}', 'OrderController@destroy')->name('orders.destroy');

    /************************ 退款订单管理 *****************************/

    // 列表
    Route::get('refunds', 'RefundController@index')->name('refunds.index');
    // 退款订单审核页
    Route::get('refunds/{refund}/show', 'RefundController@refundShow')->name('refunds.examine.show');
    // 查看详情
    Route::get('refunds/{refund}', 'RefundController@show')->name('refunds.show');
    // 退款
    Route::post('refunds/{refund}/audit', 'RefundController@audit')->name('refunds.audit');
    Route::post('refunds/{refund}/manual', 'RefundController@manual')->name('refunds.manual');


    /************************ 问答管理 *****************************/

    // 话题列表、话题加精及取消、话题置顶及取消、删除话题
    Route::get('topics', 'TopicController@index')->name('topics.index');
    Route::put('topics/{topic}', 'TopicController@update')->name('topics.update');
    Route::delete('topics/{topic}', 'TopicController@destroy')->name('topics.destroy');


    /************************ 笔记管理 *****************************/

    Route::get('notes', 'NoteController@index')->name('notes.index');
    Route::delete('notes/{note}', 'NoteController@destroy')->name('notes.destroy');


    /************************ 评价管理 *****************************/

    Route::get('reviews', 'ReviewController@index')->name('reviews.index');
    Route::delete('reviews/{review}', 'ReviewController@destroy')->name('reviews.destroy');


    /***************** 导航管理 ****************/

    // 顶部导航管理
    Route::get('settings/navigation-heads', 'NavigationsController@headIndex')->name('settings.head');
    // 底部导航
    Route::get('settings/navigation-footers', 'NavigationsController@footerIndex')->name('settings.footer');
    // 顶部导航添加
    Route::get('settings/heads/create', 'NavigationsController@headCreate')->name('settings.head.create');
    // 顶部导航保存
    Route::post('settings/heads', 'NavigationsController@headStore')->name('settings.head.store');
    // 顶部导航添加
    Route::get('settings/footers/create', 'NavigationsController@footerCreate')->name('settings.footer.create');
    // 底部导航保存
    Route::post('settings/footers', 'NavigationsController@footerStore')->name('settings.footer.store');
    // 顶部导航修改
    Route::get('settings/navigations/{navigation}/edit', 'NavigationsController@edit')->name('settings.navigation.edit');
    // 导航更新
    Route::put('settings/navigations/{navigation}', 'NavigationsController@update')->name('settings.navigation.update');
    // 添加子导航
    Route::get('settings/navigations/{navigation}/children/create', 'NavigationsController@createChild')->name('settings.navigation.create.child');
    // 保存子导航
    Route::post('settings/navigations/{navigation}/children', 'NavigationsController@storeChild')->name('settings.navigation.store.child');
    // 导航删除
    Route::delete('settings/navigations/{navigation}', 'NavigationsController@destroy')->name('settings.navigation.destroy');
    // 导航表单唯一性验证
    Route::post('settings-nav-verify', 'NavigationsController@verifyFieldUniqueness')->name('settings.nav.verify');

    /*************** 网站配置 ************/

    // 站点配置
    Route::get('settings', 'SettingController@index')->name('settings.index');
    Route::get('settings/{namespace}', 'SettingController@show')->name('settings.show');
    Route::put('settings/{namespace}', 'SettingController@update')->name('settings.update');
    Route::delete('settings/{namespace}', 'SettingController@destroy')->name('settings.destroy');

    // 删除隐藏
    // Route::delete('settings/{namespace}', 'SettingController@destroy')->name('settings.destroy');


    /**
     * 运营管理
     */


    //Route::get('tag-groups/{tagGroup}/tags/{tag}', 'TagController@show')->name('tags.show');
    //Route::put('tag-groups/{tagGroup}/tags/{tag}', 'TagController@update')->name('tags.update');
    //Route::delete('tag-groups/{tagGroup}/tags/delete', 'TagController@batchDelete')->name('tags.batchDelete');


    // 分类列表、分类详情、分类添加、分类更新、分类删除、分类批量删除
    //Route::get('category-groups/{categoryGroup}/categories', 'CategoryController@index')->name('category.index');
    //Route::get('category-groups/{categoryGroup}/categories/{category}', 'CategoryController@show')->name('category.show');
    //Route::delete('category-groups/{categoryGroup}/categories', 'CategoryController@destroy')->name('category.destroy');


    // 会话列表、详情
    Route::get('conversations', 'ConversationController@index')->name('conversations.index');
    Route::get('conversations/{conversation}', 'ConversationController@show')->name('conversations.show');


    // 第三方支付服务订单 下载对账单、订单查询、取消订单
    Route::get('payment-orders', 'PaymentOrderController@bill')->name('paymentOrders.bill');
    Route::get('payment-orders/{order}', 'PaymentOrderController@search')->name('paymentOrders.search');
    Route::patch('payment-orders/{order}', 'PaymentOrderController@cancel')->name('paymentOrders.cancel');

    // 交易记录列表、详情
    Route::get('trades', 'TradeController@index')->name('trades.index');
    Route::get('trades/{trade}', 'TradeController@show')->name('trades.show');


    // 充值额度管理
    Route::get('recharging', 'RechargingController@index')->name('recharging.index');
    Route::post('recharging', 'RechargingController@store')->name('recharging.store');
    Route::put('recharging/{recharging}', 'RechargingController@update')->name('recharging.update');
    Route::patch('recharging/{recharging}/publish', 'RechargingController@publish')->name('recharging.publish');
    Route::delete('recharging/{recharging}', 'RechargingController@destroy')->name('recharging.destroy');

    /********************* 反馈管理 *********************/

    // 反馈列表、状态更新
    Route::get('feedback', 'FeedbackController@index')->name('feedback.index');
    // 更新
    Route::get('feedback/{feedback}/edit', 'FeedbackController@edit')->name('feedback.edit');

    Route::put('feedback/{feedback}', 'FeedbackController@update')->name('feedback.update');

    // 证书上传
    Route::post('certs', 'CertController@store')->name('cert.store');


    /*********************** 文章管理 ************************/

    // 文章列表
    Route::get('posts', 'PostController@index')->name('posts.index');
    // 推荐文章列表
    Route::get('posts-recommend', 'PostController@recommendIndex')->name('posts.recommend.index');
    // 文章创建
    Route::get('posts/create', 'PostController@create')->name('posts.create');
    // 文章保存
    Route::post('posts', 'PostController@store')->name('posts.store');
    // 文章修改
    Route::get('posts/{post}/edit', 'PostController@edit')->name('posts.edit');
    // 文章更新
    Route::put('posts/{post}', 'PostController@update')->name('posts.update');
    // 文章删除
    Route::delete('posts/{post}', 'PostController@destroy')->name('posts.destroy');
    // 发布
    Route::patch('posts/{post}/publish', 'PostController@publish')->name('posts.publish');
    // 取消发布
    Route::patch('posts/{post}/close', 'PostController@close')->name('posts.close');
    // 精华
    Route::patch('posts/{post}/essence', 'PostController@essence')->name('posts.essence');
    // 取消精华
    Route::patch('posts/{post}/un-essence', 'PostController@unEssence')->name('posts.un-essence');
    // 置顶
    Route::patch('posts/{post}/stick', 'PostController@stick')->name('posts.stick');
    // 取消置顶
    Route::patch('posts/{post}/un-stick', 'PostController@unStick')->name('posts.un-stick');
    // 文章推荐
    Route::get('posts/{post}/recommend', 'PostController@recommendShow')->name('posts.recommend.show');
    // 文章推荐保存
    Route::post('posts/{post}/recommend', 'PostController@recommend')->name('posts.recommend');
    // 文章取消推荐
    Route::patch('posts/{post}/un-recommend', 'PostController@unRecommend')->name('posts.un-recommend');

});

// 王凯修改为不加后台权限验证
Route::group(['middleware' => ['auth']], function () {
    // 获取七牛TOKEN
    Route::post('token', '\App\Http\Controllers\Front\Manage\QiniuController@imgToken')->name('backstage.qi_niu.token');
});

