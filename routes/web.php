<?php

// 微信公众号
Route::post('/wechat', 'Web\WeChatController@serve');

// 获取微信登录二维码
Route::get('/wechat-login-code', 'Web\WeChatController@getWxPic')->name('wx.pic');

// 验证登录
Route::get('/wechat-login-check', 'Web\WeChatController@wechatLoginCheck')->name('wechat.login.check');

// 验证登录
Route::get('/wechat-login', 'Web\WeChatController@wechatLogin')->name('wechat.login');

// 微信用户绑定页面
Route::get('/wechat-user/bind', 'Web\WeChatController@wechatUserBindShow')->name('wechat.user.bind.show');

// 微信用户绑定
Route::post('/wechat-user/bind', 'Web\WeChatController@wechatUserBind')->name('wechat.user.bind');


// 七牛切片后回调
Route::any('qiniucallback', 'Front\Manage\QiniuController@sliceCallback');

// 测试路由
Route::get('test', 'TestController@index');

// 测试七牛hls加密
Route::get('qiniu/hls', 'Front\Manage\QiniuController@hlsKey');

Route::namespace('Front')->group(function () {

    // 首页
    Route::get('/', 'IndexController@index')->name('index');

    // 登陆页面
    Route::get('login', 'LoginController@showLoginForm')->name('login');

    // 登陆
    Route::post('login', 'LoginController@login')->name('login.store');

    // 注册页面
    Route::get('register', 'RegisterController@showRegistrationForm')->name('register');

    // 注册
    Route::post('register', 'RegisterController@register')->name('register.store');

    // 密码重置页面
    Route::get('password/reset', 'PasswordController@reset')->name('password.reset');

    // 密码重置
    Route::post('password/reset', 'PasswordController@password')->name('password.store');

    // 短信服务
    Route::post('sms/{type}', 'SmsController@send')->name('sms.send');

    // 邮件服务
    Route::post('email/{type}', 'EmailController@send')->name('email.send');

    // 课程
    Route::resource('courses', 'CourseController', ['only' => ['index', 'show']]);

    // 班级
    if (config('app.model') == 'classroom') {
        Route::resource('classrooms', 'ClassroomController', ['only' => ['index', 'show']]);
    }

    // 用户主页
    Route::get('users/{user}', 'UserController@show')->name('users.show');
    Route::get('page/{content}', 'PageController@show')->name('page.show');

    // 搜索首页
    Route::get('search', 'SearchController@index')->name('search.index');

    // 登陆认证
    Route::group(['middleware' => ['login']], function () {

        // 其他资源上传需要的token-验证hash并确定是否上传
        Route::post('manage/qiniu/token/hash', 'Manage\QiniuController@upTokenHash')->name('manage.qiniu.token.hash');


        // 退出登陆
        Route::get('logout', 'LoginController@logout')->name('logout');

        /**
         * 个人资料
         */

        // 用户个人信息编辑
        Route::get('users/{user}/edit', 'UserController@edit')->name('users.edit');

        // 更新个人信息
        Route::put('users/{user}', 'UserController@update')->name('users.update');

        // 用户关注和取消关注
        Route::post('users', 'UserController@store')->name('users.store');

        /**
         * 安全设置
         */

        // 我的安全设置
        Route::get('my/safe', 'MyController@safe')->name('users.safe');

        // 绑定手机
        Route::post('my/safe/phone', 'MyController@bindPhone')->name('users.safe.bind_phone');

        // 重置密码
        Route::post('my/safe/password', 'MyController@password')->name('users.safe.password');

        // 绑定邮箱
        Route::get('my/safe/email', 'MyController@bindEmail')->name('users.safe.email');

        /**
         * 我的订单
         *
         */

        // 订单列表
        Route::get('my/order', 'MyOrderController@index')->name('users.order');

        // 查询订单详情
        Route::get('my/order/{order}', 'MyOrderController@show')->name('users.order.show');

        // 订单详情模态框
        Route::get('my/order/{order}/edit', 'MyOrderController@edit')->name('users.order.edit');

        // 创建订单
        Route::post('my/order', 'MyOrderController@store')->name('users.order.store');

        // 获取商品的订单信息
        Route::get('order/{id}/info', 'MyOrderController@info')->name('users.order.info');

        // 请求支付二维码
        Route::post('pay', 'PayController@store')->name('pay.store');

        // 虚拟币请求支付二维码
        Route::post('pay/coin', 'PayController@coinStore')->name('pay.coin.store');

        // 取消订单
        Route::put('my/order/{order_id}', 'MyOrderController@update')->name('users.order.update');

        // 删除订单
        Route::delete('my/order/{order_id}', 'MyOrderController@destroy')->name('users.order.destroy');

        /**
         * 我的退款
         *
         */

        // 退款列表
        Route::get('my/refund', 'MyRefundController@index')->name('users.refund');

        // 退款详情
        Route::get('my/refund/{refund}', 'MyRefundController@show')->name('users.refund.show');

        // 取消退款
        Route::patch('my/refund/{refund}', 'MyRefundController@update')->name('users.refund.update');

        // 删除退款
        Route::delete('my/refund/{refund}', 'MyRefundController@destroy')->name('users.refund.destroy');

        // 申请退款
        Route::post('my/order/{order}/refund', 'MyRefundController@store')->name('users.refund.store');

        // 我的虚拟币
        Route::get('my/coin', 'MyController@coin')->name('users.coin');

        // 虚拟币购买页面
        Route::get('my/coin/{coin}', 'MyController@coinShop')->name('users.coin.shopping');

        /**
         * 我的学习
         *
         */

        // 我的课程
        Route::get('my/courses', 'MyController@courses')->name('users.courses');

        // 我的班级
        Route::get('my/classrooms', 'MyController@classrooms')->name('users.classrooms');

        // 我的笔记
        Route::get('my/notes', 'MyController@notes')->name('users.notes');

        // 我的话题
        Route::get('my/topics', 'MyController@topics')->name('users.topics');

        // 我的问答
        Route::get('my/questions', 'MyController@questions')->name('users.questions');

        // 我的作业
        Route::get('my/jobs', 'MyController@jobs')->name('users.jobs');

        // 我的作业详情
        Route::get('my/jobs/{homeworkPost}/homework/info', 'MyController@homeworkInfo')->name('users.jobs.homework.info');

        // 我的考试
        Route::get('my/exams', 'MyController@exams')->name('users.exams');

        // 我的考试详情
        Route::get('my/exams/{result}/details', 'MyController@examsDetails')->name('users.exams.details');

        // 文件上传
        Route::post('file-upload', 'UploadController@upload')->name('file.upload');

        // 获取文件地址
        Route::post('file-url', 'UploadController@fileUrl')->name('file.url');

        // 本地文件上传前的准备
        Route::post('file-upload-preprocess', 'UploadController@preprocess')->name('file.upload.preprocess');


        // 课程管理
        Route::group(['prefix' => 'manage', 'namespace' => 'Manage', 'middleware' => ['manage'], 'as' => 'manage.'], function () {
            /**
             * 七牛上传相关
             */

            /**
             * 标签相关
             */
//            Route::get('label/search', 'CourseController@labelSearch')->name('label.search');

            /**
             * 教师再交课程
             */
            // 在教课程
            Route::get('teach_course', 'TeachingController@teachCourse')->name('users.teach_course');

            // 在教班级
            Route::get('teach_class', 'TeachingController@teachClass')->name('users.teach_class');


            /**
             * 课程管理
             */

            // 创建课程的页面
            Route::get('/courses/create', 'CourseController@create')->name('courses.create');

            // 创建课程
            Route::post('/courses', 'CourseController@store')->name('courses.store');

            // 课程基本信息-编辑课程信息页面
//            Route::get('/courses/{course}/edit', 'CourseController@edit')->name('courses.edit');
            Route::get('/course/{course}/edit', 'CourseController@edit')->name('courses.edit');

            // 课程详细信息
            Route::get('/courses/{course}/detail', 'CourseController@detail')->name('courses.detail');

            // 课程更新-保存编辑的课程的信息
//            Route::put('/courses/{course}', 'CourseController@update')->name('courses.update');
            Route::put('/courses/{course}', 'CourseController@update')->name('courses.update');

            // 课程发布
            Route::patch('/courses/{course}/publish', 'CourseController@publish')->name('courses.publish');

            // 课程题目 CRUD
//            Route::resource('courses.questions', 'QuestionController');

            // 课程考试 CRUD
            Route::resource('courses.tests', 'TestController');

            // 考试题目
            Route::resource('tests.questions', 'TestQuestionController');

            // 题目答题记录

            /**
             * 版本管理
             */

            // 版本列表
            Route::get('/courses/{course}/plans', 'PlanController@index')->name('plans.index');

            // 创建版本
            Route::post('/courses/{course}/plans', 'PlanController@store')->name('plans.store');

            // 版本复制
            Route::post('/courses/{course}/plans/{plan}/copy', 'PlanController@copy')->name('plans.copy');

            // 版本详情-章节列表
            Route::get('/courses/{course}/plans/{plan}', 'PlanController@show')->name('plans.show');

            // 版本编辑
            Route::get('/courses/{course}/plans/{plan}/edit', 'PlanController@edit')->name('plans.edit');

            // 版本更新
            Route::put('/courses/{course}/plans/{plan}', 'PlanController@update')->name('plans.update');

            // 版本发布和关闭
            Route::patch('courses/{course}/plans/{plan}/publish', 'PlanController@publish')->name('plans.publish');

            /**
             * 教师设置
             */
            // 教师列表
            Route::get('/courses/{course}/plans/{plan}/teachers', 'PlanController@teachers')->name('plans.teachers');
            // 删除教师
            Route::delete('/courses/plans/teachers/{teacher}', 'PlanController@deleteTeachers')->name('plans.teachers.delete');
            // 教师添加
            Route::post('/courses/{course}/plans/{plan}/teachers', 'PlanController@storeTeachers')->name('plans.store_teachers');
            // 教师排序
            Route::post('/courses/plans/teachers/sort', 'PlanController@sortTeacher')->name('plans.teacher.sort');
            // 教师设置隐藏或者显示
            Route::patch('/courses/plans/teachers/{teacher}/show', 'PlanController@teacherShow')->name('plans.teachers.show');

            /**
             * 学员管理
             */
            // 学员列表
            Route::get('/courses/{course}/plans/{plan}/member', 'PlanMemberController@index')->name('plans.member.index');
            // 学员添加
            Route::post('/courses/{course}/plans/{plan}/member', 'PlanMemberController@store')->name('plans.member.store');
            // 学员移除
            Route::delete('/courses/plans/member/{member}', 'PlanMemberController@destroy')->name('plans.member.destroy');
            // 学员设置备注
            Route::match(['get', 'post'], '/courses/plans/member/{member}/remark', 'PlanMemberController@remark')->name('plans.member.remark');
            // 学员发送私信
            Route::match(['get', 'post'], '/courses/plans/member/{member}/message', 'PlanMemberController@message')->name('plans.member.message');
            // 学员发送私信
            Route::get('/courses/plans/member/{member}/userinfo', 'PlanMemberController@userinfo')->name('plans.member.userinfo');

            // 版本订单列表
            Route::get('/courses/{course}/plans/{plan}/orders', 'PlanController@orders')->name('plans.orders');

            /**
             * 版本公告增加、更新、删除
             **/
            // 版本列表
            Route::get('/courses/{course}/plans/{plan}/notice', 'NoticeController@index')->name('notices.index');
            // 公告发布页面
            Route::get('/courses/plans/{plan}/notice/add', 'NoticeController@create')->name('notices.create');
            // 执行公告添加
            Route::post('/courses/plans/{plan}/notice/add', 'NoticeController@store')->name('notices.store');
            // 公告编辑页面
            Route::get('/plans/notice/{notice}/edit', 'NoticeController@edit')->name('notices.edit');
            // 执行公告编辑
            Route::put('/plans/notice/{notice}/edit', 'NoticeController@update')->name('notices.update');
            // 删除公告
            Route::delete('/plans/notice/{notice}/edit', 'NoticeController@destroy')->name('notices.destroy');

//            Route::resource('plans.notices', 'NoticeController');
//            Route::resource('notices', 'NoticeController');


            /**
             * 章节管理
             */
            // 章节排序
//            Route::patch('plans/{plan}/chapters/sort', 'ChapterController@sort')->name('chapters.lock');

            // 章节
//            Route::resource('plans.chapters', 'ChapterController');

            // 添加章节
            Route::post('/plans/{plan}/chapters/add', 'ChapterController@store')->name('chapters.store');

            // 章节更新
            Route::put('/plans/{plan}/chapters/{chapter}', 'ChapterController@update')->name('chapters.update');

            // 章节删除
            Route::delete('/plans/{plan}/chapters/{chapter}/delete', 'ChapterController@destroy')->name('chapters.delete');

            // 章节编辑
            Route::get('/plans/{plan}/chapters/{chapter}/edit', 'ChapterController@edit')->name('chapters.edit');

            // 章节和任务排序
            Route::patch('chapters/task/type/{type}/sort', 'ChapterController@sort')->name('chapters.sort');

            /**
             * 任务管理
             */
            // 添加任务
            Route::post('chapters/{chapter}/tasks/store', 'TaskController@store')->name('tasks.store');

            // 编辑任务
            Route::get('chapters/{chapter}/tasks/{task}/edit', 'TaskController@edit')->name('tasks.edit');

            Route::put('chapters/{chapter}/tasks/{task}', 'TaskController@update')->name('tasks.update');

            // 任务发布
            Route::patch('chapters/{chapter}/tasks/{task}/publish', 'TaskController@publish')->name('tasks.publish');

            // 删除任务
            Route::delete('chapters/{chapter}/tasks/{task}/delete', 'TaskController@delete')->name('tasks.delete');

            /**
             * 视频弹题管理
             */
            // 答题统计页面
            Route::get('course/{course}/plan/{plan}/video/question/count', 'VideoQuestionController@index')->name('task.video.question.count');
            // 创建视频答题页面
            Route::get('course/{course}/task/{task}/video/question/create', 'VideoQuestionController@create')->name('task.video.question.create');
            // 保存视频答题
            Route::post('course/{course}/task/{task}/video/question/store', 'VideoQuestionController@store')->name('task.video.question.store');
            // 查看一个试卷的详情
            Route::get('video/question/paper/{paper}', 'VideoQuestionController@show')->name('task.video.question.paper.info');
            // 删除一个弹题关联
            Route::delete('video/question/paper/{videoQuestion}', 'VideoQuestionController@destroy')->name('task.video.question.paper.delete');


            /**
             * 题目管理   试卷管理  阅卷管理
             */
            // 题目管理
            Route::resource('question', 'QuestionController')->except([
                'destroy'
            ]);
            // 添加试卷的时候获取题目列表-含分页搜索
            Route::get('question/list/json', 'QuestionController@questionJson')->name('question.list.json');
            // 添加视频弹题的时候获取题目列表-含分页搜索
            Route::get('question/list/video', 'QuestionController@questionVideo')->name('question.list.video');

            // 试卷管理
            Route::resource('paper', 'PaperController')->except([
                'destroy'
            ]);

            // 阅卷管理
            Route::resource('paper/mark/result', 'PaperResultController')->names([
                'index' => 'paper.result.index',
                'show' => 'paper.result.show',
            ]);
            Route::post('paper/{paper}/mark/{paperResult}', 'PaperResultController@store')->name('paper.result.store');


            /**
             * 作业管理
             */
            // 作业 CURD
            Route::resource('homework', 'HomeworkController');
            // 添加作业评分标准
            Route::post('homework/grade/add', 'HomeworkController@gradeAdd')->name('homework.grade.add');
            // 发布和取消发布作业
            Route::patch('homework/{homework}/status', 'HomeworkController@status')->name('homework.status');
            // 查看评语
            Route::get('homework/grade/{grade}/show', 'HomeworkController@gradeShow')->name('homework.grade.show');
            // 作业批改列表
            Route::get('homework/post/index', 'HomeworkPostController@index')->name('homework.post.index');
            // 作业批改列表
            Route::get('homework/post/{homeworkPost}/show', 'HomeworkPostController@show')->name('homework.post.show');
            // 提交批改的作业
            Route::post('homework/post/{homeworkPost}/read', 'HomeworkPostController@read')->name('homework.post.read');

            if (config('app.model') == 'classroom') {
                /**
                 * 班级管理
                 */
                // 班级管理列表页
                Route::get('classroom/list', 'ClassroomController@index')->name('classroom.list');
                // 保存班级创建
                Route::post('classroom/store', 'ClassroomController@store')->name('classroom.store');
                // 班级管理首页
                Route::get('classroom/{classroom}/show', 'ClassroomController@show')->name('classroom.show');
                // 发布和取消发布班级
                Route::patch('classroom/{classroom}/publish', 'ClassroomController@publish')->name('classroom.publish');
                // 班级教师设置
                Route::get('classroom/{classroom}/teacher', 'ClassroomController@teacher')->name('classroom.teacher');
                // 删除教师
                Route::delete('classroom/{classroom}/teacher/{user}', 'ClassroomController@deleteTeachers')->name('classroom.teacher.delete');
                // 教师添加
                Route::post('classroom/{classroom}/teachers', 'ClassroomController@storeTeachers')->name('classroom.teacher.store');
                // 教师排序
                Route::post('classroom/teacher/sort', 'ClassroomController@sortTeacher')->name('classroom.teacher.sort');
                // 服务设置
                Route::match(['get', 'post'], 'classroom/{classroom}/service', 'ClassroomController@service')->name('classroom.service');
                // 价格设置
                Route::match(['get', 'patch'], 'classroom/{classroom}/price', 'ClassroomController@price')->name('classroom.price');
                // 基本信息设置
                Route::get('classroom/{classroom}/base', 'ClassroomController@base')->name('classroom.base');
                // 执行基本信息更新
                Route::put('classroom/{classroom}/update', 'ClassroomController@update')->name('classroom.update');

                /**
                 * 班级课程管理
                 */
                // 课程管理列表页
                Route::get('classroom/{classroom}/course/list', 'ClassroomCourseController@index')->name('classroom.course.list');
                // 选择课程页面
                Route::get('classroom/{classroom}/course/create', 'ClassroomCourseController@create')->name('classroom.course.create');
                // 保存给班级的选择的课程
                Route::post('classroom/{classroom}/course/store', 'ClassroomCourseController@store')->name('classroom.course.store');
                // 删除课程
                Route::delete('classroom/course/{course}/delete', 'ClassroomCourseController@destroy')->name('classroom.course.delete');
                // 排序课程
                Route::patch('classroom/{classroom}/course/sort', 'ClassroomCourseController@sort')->name('classroom.course.sort');
                // 取消课程同步
                Route::patch('classroom/course/{course}/sync', 'ClassroomCourseController@sync')->name('classroom.course.sync');


                /**
                 * 班级学员管理
                 */
                // 学员列表页
                Route::get('classroom/{classroom}/member/list', 'ClassroomMemberController@index')->name('classroom.member.list');
                // 学员添加
                Route::post('classroom/{classroom}/member/store', 'ClassroomMemberController@store')->name('classroom.member.store');
                // 学员移除
                Route::delete('classroom/member/{member}', 'ClassroomMemberController@destroy')->name('classroom.member.destroy');
                // 学员设置备注
                Route::match(['get', 'post'], 'classroom/member/{member}/remark', 'ClassroomMemberController@remark')->name('classroom.member.remark');
                // 学员发送私信
                Route::match(['get', 'post'], 'classroom/member/{member}/message', 'ClassroomMemberController@message')->name('classroom.member.message');
                // 学员信息
                Route::get('classroom/member/{member}/userinfo', 'ClassroomMemberController@userinfo')->name('classroom.member.userinfo');

            }


        });

        /**
         * 我的通知
         *
         */

        // 全部已读
        Route::post('my/notifications/read-all', 'NotificationController@readAll')->name('users.notification.read_all');

        // 我的通知
        Route::get('my/notifications', 'NotificationController@index')->name('users.notification');

        // 通知详情
        Route::get('my/notifications/{notification}', 'NotificationController@show')->name('users.notification.show');

        // 删除通知
        Route::delete('my/notifications/{notification}', 'NotificationController@destroy')->name('users.notification.destroy');

        /**
         * 我的对话，私信
         *
         */

        // 我的对话列表
        Route::get('my/message', 'ConversationController@index')->name('users.message');

        // 我的对话详情
        Route::get('my/message/{conversation}', 'ConversationController@show')->name('users.message.show');

        // 发送私信
        Route::post('my/message', 'ConversationController@store')->name('users.message.store');

        // 清空会话
        Route::delete('my/message/{conversation}', 'ConversationController@destroy')->name('users.message.delete');

        // 删除会话消息
        Route::delete('my/message/{conversation}/messages/{messages}', 'ConversationController@destroyMessage')->name('users.message.show.delete');


        /**
         * 版本详情
         *
         */

        // 版本介绍
        Route::get('courses/{course}/plans/{plan}/intro', 'PlanController@intro')->name('plans.intro');
        // 版本目录
        Route::get('courses/{course}/plans/{plan}/chapter', 'PlanController@chapter')->name('plans.chapter');
        // 版本笔记
        Route::get('courses/{course}/plans/{plan}/note', 'PlanController@note')->name('plans.note');
        // 版本评价
        Route::get('courses/{course}/plans/{plan}/review', 'PlanController@review')->name('plans.review');
        // 版本话题
        Route::get('courses/{course}/plans/{plan}/topic', 'PlanController@topic')->name('plans.topic');
        // 版本问答
        Route::get('courses/{course}/plans/{plan}/question', 'PlanController@question')->name('plans.question');

        // 版本发表评价
        Route::post('courses/{course}/plans/{plan}/review', 'PlanController@storeReview')->name('plans.store.review');

        // 版本创建话题
        Route::post('courses/{course}/plans/{plan}/topic', 'PlanController@storeTopic')->name('plans.store.topic');

        // 话题下的回复
        Route::get('/plans/{plan}/topic/{topic}/reply', 'PlanController@reply')->name('plans.topics.reply');

        // 话题添加回复
        Route::post('/plans/{plan}/topic/{topic}/reply', 'PlanController@storeReply')->name('plans.store.reply');

        // 版本购买
        Route::get('courses/{course}/plans/{plan}/shopping', 'PlanController@shopping')->name('plans.shopping');

        if (config('app.model') == 'classroom') {
            // 班级购买
            Route::get('classrooms/{classroom}/shopping', 'ClassroomController@shopping')->name('classrooms.shopping');

            // 班级 版本学习
            Route::get('classrooms/{classroom}/plans', 'ClassroomController@plans')->name('classrooms.plans');
        }


        // 使用优惠券
        Route::post('coupon', 'CouponController@store')->name('coupon.use');

        /**
         * 任务页面
         *
         */

        // 任务详情
        Route::get('chapters/{chapter}/tasks', 'TaskController@show')->name('tasks.show');

        // 任务添加笔记
        Route::post('tasks/{task}/notes', 'NoteController@store')->name('tasks.notes.store');

        // 任务的笔记列表
        Route::get('tasks/{task}/notes', 'NoteController@index')->name('tasks.notes.index');

        // 笔记点赞
        Route::put('tasks/{task}/notes/{note}', 'NoteController@update')->name('tasks.notes.update');

        // 任务的问答列表
        Route::get('tasks/{task}/topics', 'TaskController@getTopic')->name('tasks.topics.show');

        // 任务的问答的搜索
        Route::get('tasks/{task}/topics/search', 'TaskController@searchTopic')->name('tasks.topics.search');

        // 任务添加问答
        Route::post('tasks/{task}/topics', 'TaskController@storeTopic')->name('tasks.topics.store');

        // 任务问答的详情和回复
        Route::get('tasks/{task}/topics/{topic}/reply', 'TaskController@getReply')->name('tasks.reply.show');

        // 问答添加回复
        Route::post('tasks/{task}/topics/{topic}/reply', 'TaskController@storeReply')->name('tasks.reply.store');

        // 更新学习任务进度
        Route::put('tasks/{task}/result', 'TaskResultController@update')->name('tasks.result.store');

        // 考试
        Route::post('tasks/{task}/paper', 'TaskResultController@paper')->name('tasks.result.paper');

        // 弹题考试
        Route::post('tasks/{task}/video/paper', 'TaskResultController@videoPaper')->name('tasks.result.video.paper');

        // 作业提交
        Route::post('tasks/{task}/homework', 'TaskResultController@homework')->name('tasks.result.homework');

        // 点赞收藏
        Route::post('favorites', 'FavoriteController@store')->name('favorites.store');

        // 七牛上传获取token
        Route::get('qiniu/token', 'QiniuController@token')->name('qiniu.token');


    });

    // 版本切换
    Route::get('courses/{course}/plans/{plan}', 'PlanController@show')->name('plans.show');

    // 七牛回调
    Route::post('qiniu/callback', 'QiniuController@callBack')->name('qiniu.callback');

    // 支付异步回调
    Route::post('pay/{type}/notify', 'PayController@notify')->name('pay.notify');


    // 图片上传需要的token
    Route::post('qiniu/token/img', '\App\Http\Controllers\Front\Manage\QiniuController@homeworkToken')->name('qiniu.token.homework');

    // 文章页
    Route::get('posts', 'PostController@index')->name('posts.index');
    // 文章详情页
    Route::get('posts/{post}', 'PostController@show')->name('posts.show');
    // 文章点赞
    Route::post('posts/{post}/vote', 'PostController@vote')->name('posts.vote');
    // 取消点赞
    Route::delete('posts/{post}/vote', 'PostController@unVote')->name('posts.un-vote');
});
