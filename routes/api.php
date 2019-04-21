<?php

/*
|--------------------------------------------------------------------------
| Web API Routes（前台接口）
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Http\Controllers\Web', 'middleware' => ['bindings', 'serializer:array', 'change-locale']], function ($api) {
        // TODO 解锁式学习
        // TODO 增加虚拟币重置功能
        // TODO 增加账户余额流水信息
        // TODO 实现第三方登录（需要微信开发方平台的账户，以及授权应用）

        $api->group(['middleware' => ['auth']], function ($api) {
            // 七牛上传 和 持久化处理
            $api->get('qiniu/token', ['uses' => 'QiniuController@token', 'as' => 'qiniu.token']);
            $api->delete('qiniu/delete/{key}', ['uses' => 'QiniuController@delete', 'as' => 'qiniu.delete']);
            $api->get('qiniu/video/status', ['uses' => 'QiniuController@persistentStatus', 'as' => 'qiniu.persistentStatus']);
            $api->get('qiniu/avinfo', ['uses' => 'QiniuController@avinfo', 'as' => 'qiniu.avinfo']);
            $api->post('qiniu/database', ['uses' => 'QiniuController@toDatabase', 'as' => 'qiniu.toDatabase']);
        });
        $api->post('qiniu/callback', ['uses' => 'QiniuController@callBack', 'as' => 'qiniu.callbakc']);

        /**
         * 限流策略：（次数/分钟）
         * 1. 短信验证码接口限制为 1/1
         * 2. 图形验证码接口限制为 5/1
         * 3. 邮件验证码接口限制为 1/5
         * 4. 登录接口限制为 5/60
         * 5. 反馈接口限制为 5/60
         * 4. 普通接口限制为 1000/1
         */

        // 短信验证码（包含：登录、注册、重置密码、身份验证）
        $api->group(['middleware' => 'api.throttle', 'limit' => config('api.rate_limits.sms.limit'), 'expires' => config('api.rate_limits.sms.expires')], function ($api) {
            $api->post('sms/{type}', ['uses' => 'SmsController@send', 'as' => 'sms.send']);
        });
        // 图形验证码
        $api->group(['middleware' => 'api.throttle', 'limit' => config('api.rate_limits.captcha.limit'), 'expires' => config('api.rate_limits.captcha.expires')], function ($api) {
            $api->post('captcha', ['uses' => 'CaptchaController@store', 'as' => 'captcha.store']);
        });
        // 邮件服务
        $api->group(['middleware' => 'api.throttle', 'limit' => config('api.rate_limits.email.limit'), 'expires' => config('api.rate_limits.email.expires')], function ($api) {
            $api->post('email/{type}', ['uses' => 'EmailController@send', 'as' => 'email.send']);
        });
        // 登录、注册、Token 刷新
        $api->group(['middleware' => 'api.throttle', 'limit' => config('api.rate_limits.login.limit'), 'expires' => config('api.rate_limits.login.expires')], function ($api) {
            // 登陆、短信登录、刷新 Token
            $api->post('login', ['uses' => 'LoginController@store', 'as' => 'login.store']);
            $api->post('sms-login', ['uses' => 'LoginController@sms', 'as' => 'login.sms']);
            $api->patch('refresh', ['uses' => 'LoginController@refresh', 'as' => 'login.refresh']);
            // 注册接口
            $api->post('register', ['uses' => 'RegisterController@store', 'as' => 'register.store']);
            // 通过邮件验证码重设密码
            $api->patch('password/email', ['uses' => 'PasswordController@email', 'as' => 'password.email']);
            // 通过短信验证码重置密码
            $api->patch('password/sms', ['uses' => 'PasswordController@sms', 'as' => 'password.sms']);
        });
        // 无需登录的写入接口：反馈
        $api->group(['middleware' => 'api.throttle', 'limit' => config('api.rate_limits.feedback.limit'), 'expires' => config('api.rate_limits.feedback.expires')], function ($api) {
            $api->post('feedback', ['uses' => 'FeedbackController@store', 'as' => 'feedback.store']);
        });

        // 公共路由
        $api->group(['middleware' => 'api.throttle', 'limit' => config('api.rate_limits.general.limit'), 'expires' => config('api.rate_limits.general.expires')], function ($api) {
            // 网站信息
            $api->get('settings/site', ['uses' => 'SettingController@site', 'as' => 'setting.site']);
            $api->get('settings/login', ['uses' => 'SettingController@login', 'as' => 'setting.login']);
            $api->get('settings/register', ['uses' => 'SettingController@register', 'as' => 'setting.register']);
            // 首页信息
            $api->get('home', ['uses' => 'HomeController@index', 'as' => 'home.index']);
            // 课程列表、详情
            $api->get('courses', ['uses' => 'CourseController@index', 'as' => 'course.index']);
            $api->get('courses/{course}', ['uses' => 'CourseController@show', 'as' => 'course.show']);
            // 教学版本列表、详情、更新
            $api->get('courses/{course}/plans', ['uses' => 'PlanController@index', 'as' => 'plan.index']);
            $api->get('courses/{course}/plans/{plan}', ['uses' => 'PlanController@show', 'as' => 'plan.show']);
            // 教学版本下教师列表
            $api->get('plans/{plan}/teachers', ['uses' => 'PlanTeacherController@index', 'as' => 'planTeacher.index']);
            $api->get('plans/{plan}/teachers/{user}', ['uses' => 'PlanTeacherController@show', 'as' => 'planTeacher.show']);
            // 推荐教师列表、查询
            $api->get('teachers', ['uses' => 'TeacherController@index', 'as' => 'teacher.index']);
            // 教师简易列表
            $api->get('teachers/list', ['uses' => 'TeacherController@list', 'as' => 'teacher.list']);
            // 教学版本下章节列表、详情
            $api->get('plans/{plan}/chapters', ['uses' => 'ChapterController@index', 'as' => 'chapter.index']);
            // 章节下的任务列表、详情
            $api->get('chapters/{chapter}/tasks', ['uses' => 'TaskController@index', 'as' => 'task.index']);
            $api->get('chapters/{chapter}/tasks/{task}', ['uses' => 'TaskController@show', 'as' => 'task.show']);
            // 教学版本成员列表、详情
            $api->get('plans/{plan}/members', ['uses' => 'MemberController@index', 'as' => 'member.index']);
            // 教学版本笔记列表、详情
            $api->get('plans/{plan}/notes', ['uses' => 'NoteController@index', 'as' => 'note.index']);
            // 教学版本下的话题列表、详情
            $api->get('plans/{plan}/topics', ['uses' => 'TopicController@index', 'as' => 'topic.index']);
            $api->get('plans/{plan}/topics/{topic}', ['uses' => 'TopicController@show', 'as' => 'topic.show']);
            // 全局话题列表、详情
            $api->get('topics', ['uses' => 'GlobalTopicController@index', 'as' => 'globalTopic.index']);
            $api->get('topics/{topic}', ['uses' => 'GlobalTopicController@show', 'as' => 'globalTopic.show']);
            // 话题下回复列表、详情
            $api->get('topics/{topic}/replies', ['uses' => 'ReplyController@index', 'as' => 'reply.index']);
            $api->get('topics/{topic}/replies/{reply}', ['uses' => 'ReplyController@show', 'as' => 'reply.show']);
            // 轮播图
            $api->get('slides', ['uses' => 'SlideController@index', 'as' => 'slide.index']);
            // 分类群组标签
            $api->get('category-groups', ['uses' => 'CategoryGroupController@index', 'as' => 'categoryGroup.index']);
            // 分类
            $api->get('category-groups/{group}/categories', ['uses' => 'CategoryController@index', 'as' => 'category.index']);
            // 分类群组标签
            $api->get('tag-groups', ['uses' => 'TagGroupController@index', 'as' => 'tagGroup.index']);
            $api->get('tag-groups/{group}', ['uses' => 'TagGroupController@show', 'as' => 'tagGroup.show']);
            // 分类
            $api->get('tag-groups/{group}/tags', ['uses' => 'TagController@index', 'as' => 'tag.index']);
            $api->get('tag-groups/{group}/tags/{tag}', ['uses' => 'TagController@show', 'as' => 'tag.show']);
            // 全局搜索
            $api->get('search', ['uses' => 'SearchController@index', 'as' => 'search.index']);
            $api->get('search/hot-words', ['uses' => 'SearchController@hot', 'as' => 'search.hot']);
            // 搜索热词
            $api->get('suggest', ['uses' => 'SearchController@suggest', 'as' => 'search.suggest']);
            // 版本评价列表、详情
            $api->get('plans/{plan}/reviews', ['uses' => 'ReviewController@index', 'as' => 'review.index']);
            $api->get('plans/{plan}/reviews/{review_id}', ['uses' => 'ReviewController@show', 'as' => 'review.show']);
            // 公告列表
            $api->get('notices', ['uses' => 'NoticeController@index', 'as' => 'notice.index']);
            // 版本公告
            $api->get('plans/{plan}/notices', ['uses' => 'PlanNoticeController@index', 'as' => 'planNotice.index']);
            // 用户查询
            $api->get('users', ['uses' => 'UserController@index', 'as' => 'user.index']);
            $api->get('users/{user}', ['uses' => 'UserController@show', 'as' => 'user.show']);
            // 用户信息、课程收藏、学习课程、在教课程、笔记、回复、话题、粉丝、关注
            $api->get('users/{user}/favorites', ['uses' => 'HerController@favorites', 'as' => 'favorites']);
            $api->get('users/{user}/courses', ['uses' => 'HerController@courses', 'as' => 'courses']);
            $api->get('users/{user}/teachings', ['uses' => 'HerController@teachings', 'as' => 'teachings']);
            $api->get('users/{user}/notes', ['uses' => 'HerController@notes', 'as' => 'notes']);
            $api->get('users/{user}/replies', ['uses' => 'HerController@replies', 'as' => 'replies']);
            $api->get('users/{user}/topics', ['uses' => 'HerController@topics', 'as' => 'topics']);
            $api->get('users/{user}/followers', ['uses' => 'HerController@followers', 'as' => 'followers']);
            $api->get('users/{user}/fans', ['uses' => 'HerController@fans', 'as' => 'fans']);
            // 绑定邮箱
            $api->get('bind-email', ['uses' => 'EmailBindController@update', 'as' => 'emailBind.update']);
            // 通过条件搜索，获取所有任务列表
            $api->get('tasks', ['uses' => 'GlobalTaskController@index', 'as' => 'globalTask.index']);
            // 班级列表、课程、成员、教师
            $api->get('classrooms', ['uses' => 'ClassroomController@index', 'as' => 'classrooms.index']);
            $api->get('classrooms/{classroom}', ['uses' => 'ClassroomController@show', 'as' => 'classroom.show']);
            $api->get('classrooms/{classroom}/courses', ['uses' => 'ClassroomCourseController@index', 'as' => 'classrooms.courses.index']);
            $api->get('classrooms/{classroom}/members', ['uses' => 'ClassroomMemberController@index', 'as' => 'classrooms.members.index']);
            $api->get('classrooms/{classroom}/teachers', ['uses' => 'ClassroomTeacherController@index', 'as' => 'classrooms.teachers.index']);
        });

        $api->group(['middleware' => 'api.throttle', 'limit' => config('api.rate_limits.general.limit'), 'expires' => config('api.rate_limits.general.expires')], function ($api) {
            // 学生路由/普通用户
            $api->group(['middleware' => ['auth', 'is-updated-password']], function ($api) {
                // 个人信息
                $api->get('me', ['uses' => 'LoginController@me', 'as' => 'login.me']);
                // 手机绑定
                $api->patch('bind-phone', ['uses' => 'PhoneController@bind', 'as' => 'phone.bind']);
                // 移除手机绑定
                $api->patch('remove-phone', ['uses' => 'PhoneController@remove', 'as' => 'phone.remove']);
                // 获取学习版本的学习进度
                $api->get('plan-progress', ['uses' => 'MeController@getPlanProgress', 'as' => 'me.getPlanProgress']);
                // 登出
                $api->delete('logout', ['uses' => 'LoginController@logout', 'as' => 'login.logout']);
                // 重置密码
                $api->patch('password/last', ['uses' => 'PasswordController@password', 'as' => 'password.password']);
                // 更新邀请码信息
                $api->patch('invitation-code', ['uses' => 'MeController@updateInvitationCode', 'as' => 'me.updateInvitationCode']);
                // 未读消息数
                $api->get('unread-count', ['uses' => 'MeController@unread', 'as' => 'me.unread']);
                // 个人详情
                $api->get('my-profile', ['uses' => 'MeController@index', 'as' => 'me.index']);
                // 个人信息更新
                $api->put('my-profile', ['uses' => 'MeController@update', 'as' => 'me.update']);
                // 话题回复添加、删除
                $api->post('topics/{topic}/replies', ['uses' => 'ReplyController@store', 'as' => 'reply.store']);
                $api->delete('topics/{topic}/replies/{reply}', ['uses' => 'ReplyController@destroy', 'as' => 'reply.destroy']);
                // 我的学习、笔记、回复、任务、教学、话题
                $api->get('my-courses', ['uses' => 'MyController@courses', 'as' => 'my.courses']);
                $api->get('my-notes', ['uses' => 'MyController@notes', 'as' => 'my.notes']);
                $api->get('my-replies', ['uses' => 'MyController@replies', 'as' => 'my.replies']);
                $api->get('my-tasks', ['uses' => 'MyController@tasks', 'as' => 'my.tasks']);
                $api->get('my-teachings', ['uses' => 'MyController@teachings', 'as' => 'my.teachings']);
                $api->get('my-topics', ['uses' => 'MyController@topics', 'as' => 'my.topics']);
                // 我的订单列表、详情、新增、取消、删除
                $api->get('my-orders', ['uses' => 'MyOrderController@index', 'as' => 'myOrder.index']);
                $api->get('my-orders/{order}', ['uses' => 'MyOrderController@show', 'as' => 'myOrder.show']);
                $api->post('orders', ['uses' => 'MyOrderController@store', 'as' => 'myOrder.store']);
                $api->put('my-orders/{order_id}', ['uses' => 'MyOrderController@update', 'as' => 'myOrder.update']);
                $api->delete('my-orders/{order_id}', ['uses' => 'MyOrderController@destroy', 'as' => 'myOrder.destroy']);
                // 我的关注者、粉丝、新增关
                $api->get('my-followers', ['uses' => 'FollowController@index', 'as' => 'follow.index']);
                $api->get('my-fans', ['uses' => 'FollowController@fans', 'as' => 'follow.fans']);
                $api->post('my-followers', ['uses' => 'FollowController@store', 'as' => 'follow.store']);
                $api->delete('my-followers/{follow}', ['uses' => 'FollowController@destroy', 'as' => 'follow.destroy']);
                // 喜爱/收藏列表、添加/删除
                $api->get('favorites', ['uses' => 'FavoriteController@index', 'as' => 'favorite.index']);
                $api->post('favorites', ['uses' => 'FavoriteController@store', 'as' => 'favorite.store']);
                // 版本评价添加、删除
                $api->post('plans/{plan}/reviews', ['uses' => 'ReviewController@store', 'as' => 'review.store']);
                $api->delete('plans/{plan}/reviews/{review_id}', ['uses' => 'ReviewController@destroy', 'as' => 'review.destroy']);
                // 消息通知
                $api->patch('my-notifications/read-all', ['uses' => 'NotificationController@readAll', 'as' => 'notification.readAll']);
                $api->get('my-notifications', ['uses' => 'NotificationController@index', 'as' => 'notification.index']);
                $api->get('my-notifications/{notification}', ['uses' => 'NotificationController@show', 'as' => 'notification.show']);
                $api->delete('my-notifications/{notification}', ['uses' => 'NotificationController@destroy', 'as' => 'notification.destroy']);
                // 话题添加、更新、删除
                $api->post('plans/{plan}/topics', ['uses' => 'TopicController@store', 'as' => 'topic.store']);
                $api->put('plans/{plan}/topics/{topic}', ['uses' => 'TopicController@update', 'as' => 'topic.update']);
                $api->delete('plans/{plan}/topics/{topic}', ['uses' => 'TopicController@destroy', 'as' => 'topic.destroy']);
                // 任务
                $api->get('tasks/{task}/result', ['uses' => 'TaskResultController@show', 'as' => 'task.show']);
                $api->put('tasks/{task}/result', ['uses' => 'TaskResultController@update', 'as' => 'task.update']);
                // 版本下笔记添加、删除
                $api->post('plans/{plan}/notes', ['uses' => 'NoteController@store', 'as' => 'note.store']);
                $api->delete('plans/{plan}/notes/{note}', ['uses' => 'NoteController@destroy', 'as' => 'note.destroy']);
                // 请求支付二维码
                $api->post('pay', ['uses' => 'PayController@store', 'as' => 'pay.store']);
                // 会话列表
                $api->get('conversations', ['uses' => 'ConversationController@index', 'as' => 'conversation.index']);
                // 发送消息（未有会话则自动创建会话
                $api->post('conversations', ['uses' => 'ConversationController@store', 'as' => 'conversation.store']);
                // 会话详情信息（包含该会话的聊天记录）
                $api->get('conversations/{conversation_id}', ['uses' => 'ConversationController@show', 'as' => 'conversation.show']);
                // 会话清空
                $api->delete('conversations/{conversation_id}', ['uses' => 'ConversationController@destroy', 'as' => 'conversation.destroy']);
                // 会话下的消息列表、删除
                $api->get('conversations/{conversation}/messages', ['uses' => 'MessageController@index', 'as' => 'message.index']);
                $api->delete('conversations/{conversation}/messages/{messages}', ['uses' => 'MessageController@destroy', 'as' => 'message.destroy']);
                // 我的退款列表、撤销、删除
                $api->get('my-refunds', ['uses' => 'MyRefundController@index', 'as' => 'myRefund.index']);
                $api->get('my-refunds/{refund}', ['uses' => 'MyRefundController@show', 'as' => 'myRefund.show']);
                $api->patch('my-refunds/{refund}', ['uses' => 'MyRefundController@update', 'as' => 'myRefund.update']);
                $api->delete('my-refunds/{refund}', ['uses' => 'MyRefundController@destroy', 'as' => 'myRefund.destroy']);
                // 申请退款
                $api->post('my-orders/{order}/refunds', ['uses' => 'MyRefundController@store', 'as' => 'myRefund.store']);
                // 优惠券：我的优惠券列表、优惠券激活
                $api->get('coupons', ['uses' => 'CouponController@index', 'as' => 'coupon.index']);
                $api->post('coupons', ['uses' => 'CouponController@store', 'as' => 'coupon.store']);
                // 优惠券计算
                $api->post('calculate-price', ['uses' => 'CalculateController@store', 'as' => 'calculate-price.store']);
                // 开始考试(提供本次考试的所有题目)
                $api->get('tasks/{task}/start-test', ['uses' => 'TestController@start', 'as' => 'tests.start-test']);
                // 提交答案
                $api->post('tasks/{task}/results', ['uses' => 'QuestionResultController@store', 'as' => 'tasks.results.store']);
                // 充值额度列表
                $api->get('recharging', ['uses' => 'RechargingController@index', 'as' => 'recharging.index']);
                $api->get('recharging/{recharging}', ['uses' => 'RechargingController@show', 'as' => 'recharging.show']);
            });

            // 教师路由
            $api->group(['prefix' => 'manage', 'namespace' => 'Manage', 'as' => 'manage', 'middleware' => ['auth', 'is-updated-password', 'role:teacher|admin|super-admin',]], function ($api) {
                // 课程添加、更新、删除、发布与取消
                $api->patch('courses/{course}/publish', ['uses' => 'CourseController@publish', 'as' => 'courses.publish']);
                $api->resource('courses', 'CourseController');
                // 版本添加、更新、删除、发布
                $api->patch('courses/{course}/plans/{plan}/publish', ['uses' => 'PlanController@publish', 'as' => 'courses.plans.publish']);
                $api->patch('courses/{course}/plans/{plan}/lock', ['uses' => 'PlanController@lock', 'as' => 'courses.plans.lock']);
                // 版本订单
                $api->get('plans/{plan}/orders', ['uses' => 'OrderController@index', 'as' => 'plans.orders.index']);

                $api->resource('courses.plans', 'PlanController');
                // 设置教师、删除教师、更新排序
                $api->get('plans/{plan}/teachers', ['uses' => 'PlanTeacherController@index', 'as' => 'plans.teachers.index']);
                $api->post('plans/{plan}/teachers', ['uses' => 'PlanTeacherController@store', 'as' => 'plans.teachers.store']);
                $api->put('plans/{plan}/teachers/{user}', ['uses' => 'PlanTeacherController@update', 'as' => 'plans.teachers.update']);
                $api->delete('plans/{plan}/teachers/{user}', ['uses' => 'PlanTeacherController@destroy', 'as' => 'plans.teachers.destroy']);
                // 章节管理列表、添加、更新、移除、排序
                $api->patch('plans/{plan}/chapters/sort', ['uses' => 'ChapterController@sort', 'as' => 'chapter.sort']);
                $api->resource('plans.chapters', 'ChapterController');
                // 任务发布、添加、更新、删除
                $api->patch('chapters/{chapter}/tasks/{task}/publish', ['uses' => 'TaskController@publish', 'as' => 'task.publish']);
                $api->resource('chapters.tasks', 'TaskController');
                // 版本公告增加、更新、删除
                $api->resource('plans.notices', 'PlanNoticeController');
                // 题目 CRUD
                $api->resource('courses.questions', 'QuestionController');
                // 考试 CRUD
                $api->resource('courses.tests', 'TestController');
                // 考题
                $api->resource('tests.questions', 'TestQuestionController');
                // 考试记录
                $api->get('tests/{test}/results', ['uses' => 'TestResultController@index', 'as' => 'tests.results.index']);
                // 班级、班级关联课程、班级关联教师、班级关联学员
                $api->patch('classrooms/{classroom}/publish', ['uses' => 'ClassroomController@publish', 'as' => 'classrooms.publish']);
                $api->resource('classrooms', 'ClassroomController');
                $api->resource('classrooms.courses', 'ClassroomCourseController');
                $api->resource('classrooms.members', 'ClassroomMemberController');
                $api->resource('classrooms.teachers', 'ClassroomTeacherController');
            });
        });

        // 支付异步回调
        $api->post('pay/{type}/notify', ['uses' => 'PayController@notify', 'as' => 'pay.notify']);
    });
});