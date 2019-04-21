<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Plan;
use App\Models\Task;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * 测试主页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function test_index()
    {
        return frontend_view('task.test.index');
    }

    /**
     * 测试结果页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function test_result()
    {
        return frontend_view('task.test.result');
    }

    /**
     *
     * 测试详解页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function test_analysis()
    {
        return frontend_view('task.test.analysis');
    }

    /**
     * 视频播放页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function video()
    {
        return frontend_view('task.video');
    }

    /**
     * 课程信息页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function courseInformation()
    {
        return view('teacher.course.information');
    }

    /**
     * 版本管理页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function coursePlan()
    {
        return view('teacher.course.plan');
    }

    /**
     * 首页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return frontend_view('index');
    }

    /**
     * 在教课程
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function teachingCourse()
    {
        return view('teacher.teaching');
    }

    /**
     * 注册
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function register()
    {
        return frontend_view('register');
    }

    /**
     * 登录
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        return frontend_view('login');
    }

    /**
     * 列表页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list()
    {
        return frontend_view('course.list');
    }

    /**
     * 详情页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function article()
    {
        return frontend_view('_article');
    }

    /**
     * 版本任务
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function planSetTeacher()
    {
        return view('teacher.plan.setTeacher');
    }

    public function notice()
    {
        return view('teacher.plan.notice-list');
    }

    /**
     * 版本详情
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function plan()
    {
        return frontend_view('plan');
    }


    /**
     * 版本任务
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function planTaskindex()
    {
        return view('teacher.plan.plan_task');
    }

    /**
     * 版本基础设置
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function planSetBasics()
    {
        return view('teacher.plan.setBasics');
    }


    /**
     * 个人中心页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function personalSecurity()
    {
        return frontend_view('personal.security');
    }

    public function personalInformation()
    {
        return frontend_view('personal.information');
    }

    /**
     * 支付订单页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orderPayment()
    {
        return frontend_view('order.payment');
    }

    /**
     * 我的订单页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myOrder()
    {
        return frontend_view('personal.order');
    }

    /**
     * 退款管理
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function refundManage()
    {
        return frontend_view('personal.refund');
    }

    /**
     * 虚拟币管理
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function virtualManage()
    {
        return frontend_view('personal.virtualCurrency');
    }

    /**
     * 我的学习下我的课程
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myCourse()
    {
        return frontend_view('learn.course');
    }

    /**
     * 开篇页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function opening()
    {
        return frontend_view('task.opening');
    }

    /**
     * 我的话题页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myTopic()
    {
        return frontend_view('learn.topic');
    }

    /**
     * 我的问题页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myQaa()
    {
        return frontend_view('learn.question');
    }

    /**
     * 我的作业页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myTask()
    {
        return frontend_view('learn.homework');
    }


    /**
     * 我的考试列表页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myExam()
    {
        return frontend_view('learn.exam');
    }

    /**
     * 我的学习下我的学习圈
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myCircle()
    {
        return frontend_view('learn.circle');
    }

    /**
     * 我的学习下我的笔记
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myNote()
    {
        return frontend_view('learn.note');
    }

    /**
     * 考试的详细信息
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function examDetails()
    {
        return frontend_view('learn.exam-details');
    }

    /**
     * 通知
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function my_notice()
    {
        return frontend_view('notice');
    }

    /**
     * 私信
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function my_message()
    {
        return frontend_view('message');
    }

    /**
     * 私信详情
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function messagedetails()
    {
        return frontend_view('message_details');
    }

    public function order()
    {
        return frontend_view('order._payment');
    }

    /**
     * 我的个人信息页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function introduction()
    {
        return frontend_view('my.my');

    }

    /**
     * 我的学习下考试详情页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myExamDetails()
    {
        return frontend_view('learn.details');
    }

    /**
     * 题库设置
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function examSubject()
    {
        return view('teacher.exam.index');
    }

    /**
     * 题目预览
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function subjectPreview()
    {
        return view('teacher.exam.subject_preview');
    }

    /*
     * 作业列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function homeworkList()
    {
        return view('teacher.homework.list');
    }

    /**
     * 添加作业
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function homeworkAdd()
    {
        return view('teacher.homework.add');
    }

    /**
     * 作业批阅
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function homeworkRating()
    {
        return view('teacher.homework.rating');
    }

    /**
     * 作业批阅
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function homeworkRatingInfo()
    {
        return view('teacher.homework.info');
    }

    /**
     * 添加题目
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function insertSubject()
    {
        return view('teacher.exam.insert_subject');
    }

    /**
     * 修改题目
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateSubject()
    {
        return view('teacher.exam.update_subject');
    }

    /**
     * 试卷列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function examPaper()
    {
        return view('teacher.exam.paper');
    }

    /**
     * 添加试卷
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function insertPaper()
    {
        return view('teacher.exam.insert_paper');
    }

    /**
     * 试卷批阅列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function paperMarklist()
    {
        return view('teacher.exam.paper_mark_list');
    }

    /**
     * 试卷批阅
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function paperMark()
    {
        return view('teacher.exam.paper_mark');
    }

    /**
     * 试卷预览
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function paperPreview()
    {
        return view('teacher.exam.paper_preview');
    }

    /**
     * 学员管理列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function studentManagement()
    {
        return view('teacher.student.index');
    }

    /**
     * 订单查询
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orderInquiry()
    {
        return view('teacher.plan.orderInquiry');
    }

    /**
     * 在教班级学员管理
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function classroomStudent()
    {
        return view('teacher.classroom.student.index');
    }

    /**
     * 在教班级教师设置
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function teacherSetting()
    {
        return view('teacher.classroom.teacher.index');
    }

    /**
     * 在教班级服务设置
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function serviceSetting()
    {
        return view('teacher.classroom.service.index');
    }

    /**
     * 在教班级价格设置
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function priceSetting()
    {
        return view('teacher.classroom.price.index');
    }

    /**
     * 在教班级基本信息
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function basicMessage()
    {
        return view('teacher.classroom.basic.index');
    }

    /**
     * 在教班级管理首页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function managementHome()
    {
        return view('teacher.classroom.management.index');
    }

    /**
     * 在教班级创建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('teacher.classroom.create.index');
    }

    /**
     * 在教班级磕碜管理
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function courseManagement()
    {
        return view('teacher.classroom.course.index');
    }

    /**
     * 双员班作业页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function homework()
    {
        return frontend_view('classroom.homework.index');
    }

    /*
     * 弹题统计
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function questionCount(Course $course, Plan $plan)
    {
        return view('teacher.plan.question_count', compact('course', 'plan'));
    }

    /**
     * 弹题
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function questionManage(Course $course, Plan $plan)
    {
        return view('teacher.plan.question_manage', compact('course','plan'));
    }

    /**
     * 双元首页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function classroomIndex()
    {
        return view('frontend.review.classroom.plan_index');
    }

    /**
     * 课程版本页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function classroomPlan()
    {
        return frontend_view('classroom.show');
    }
}
