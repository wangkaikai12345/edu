@extends('frontend.review.layouts.app')

@section('title', '课程详情')

@section('keywords')
    @parent
    <meta name="keywords" content="{{ $course['title'] }}" />
@endsection

@section('description')
    @parent
    <meta name="description" content="{{ $course['title'] }}" />
@endsection

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/article/index.css') }}">
@endsection

@section('content')
    <div class="zh_course">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pl-0">
                            <li class="breadcrumb-item"><a href="/">首页</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">课程列表</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $course['title'] }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="course_card student_style">
                <h2 class="course_title">

                    <p>
                        {{ $course['title'] }}
                    </p>

                    @if ($course->isControl())
                        <span class="float-right">
                            <a href="{{ route('manage.courses.edit', $course) }}">
                            <i class="iconfont" style="font-size: 15px;">
                                &#xe639;
                            </i>
                            管理
                            </a>
                        </span>
                    @endif
                    @auth('web')
                        <span class="float-right collect" data-id="{{ $course['id'] }}">
                            <i class="iconfont  {{ $course->isFavourite() ? 'active' : '' }}" >
                                &#xe638;
                            </i>
                            收藏
                        </span>
                    @else
                        <span class="float-right" onclick="login()">
                            <i class="iconfont" >
                                &#xe638;
                            </i>
                            收藏
                        </span>
                    @endauth

                </h2>
                <div class="row">
                    <div class="col-xl-4 pr-0">
                        <img src="{{ render_cover($course['cover'], 'course') }}" alt="">
                        <div class="cover_btn">
                            <div class="star">
                                @for ($i = 0; $i < 5; $i++)
                                    @if ($i <= intval($course['rating']))
                                        <i class="iconfont">
                                            &#xe601;
                                        </i>
                                    @else
                                        <i class="iconfont">
                                            &#xe60d;
                                        </i>
                                    @endif
                                @endfor
                            </div>
                            <div class="study_num">
                                <i class="iconfont">
                                    &#xe631;
                                </i>
                                <span>
                                {{ $course['students_count'] }}人加入学习
                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5 pr-0">
                        <div class="course_item first_item">
                            <div class="course_title">
                                价格
                            </div>
                            <div class="course_price">
                                @if (!$plan['buy'])
                                    不可购买
                                @else
                                    @if (!$plan['coin_price'] && !$plan['price'])
                                        <span style="font-size:14px;color:red">免费</span>
                                    @else
                                        {{ $plan['coin_price'] ? $plan['coin_price'] : ftoy($plan['price']) }}
                                        <span>{{ $plan['coin_price'] ? '虚拟币' : '元' }}</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="course_item plan_wrap">

                            <div class="course_title">
                                教学版本
                            </div>

                            @foreach($course->plans as $value)
                                @if($value->status == 'published')
                                <div class="plan_item {{ plan_nav_active($plan['id'], $value['id']) }}">
                                    <a href="{{ route('plans.show', [$course, $value]) }}">
                                        {{ $value['title'] }}
                                    </a>
                                </div>
                                @endif
                            @endforeach

                        </div>
                        <div class="course_item">
                            <div class="course_title">
                                学习有效期
                            </div>
                            <span class="validity">
                                @switch($plan['expiry_mode'])
                                    @case('forever')
                                    永久有效
                                    @break

                                    @case('valid')
                                    {{ $plan['expiry_days']}} 天
                                    @break

                                    @case('period')
                                    {{ $plan['expiry_started_at']->toDateString().'-'.$plan['expiry_ended_at']->toDateString()}}
                                    @break

                                    @default

                                @endswitch
                            </span>
                        </div>
                    </div>
                    <div class="col-xl-3 btn-group btn-group-justified p-0 shopping">
                        {{--@if ($course['status'] !== 'published')--}}
                            {{--<button class="btn btn-primary btn-sm btn-circle m-xl-auto">预览模式</button>--}}
                        {{--@else--}}

                            {{--@if ($plan->isValid() && $plan['buy'])--}}
                                {{--@if ($plan->isMember() || (auth('web')->user() && auth('web')->user()->isAdmin()))--}}
                                    {{--<button class="btn btn-primary btn-sm btn-circle m-xl-auto">--}}
                                    {{--<a--}}
                                       {{--href="{{ route('plans.intro', [$plan->course, $plan]) }}"--}}
                                    {{-->继续学习</a>--}}
                                    {{--</button>--}}
                                {{--@else--}}
                                    {{--<button class="btn btn-primary btn-sm btn-circle m-xl-auto">--}}
                                        {{--<a href="{{ route('plans.shopping', [$plan->course, $plan]) }}">开始学习</a>--}}
                                    {{--</button>--}}
                                {{--@endif--}}
                            {{--@else--}}
                                {{--<button class="btn btn-primary btn-sm btn-circle m-xl-auto">版本未开放购买</button>--}}
                            {{--@endif--}}

                        {{--@endif--}}

                        <button class="btn btn-primary btn-sm btn-circle m-xl-auto">
                            <a href="{{ route('tasks.show', $plan->chap()) }}"
                            >查看</a>
                        </button>
                    </div>

                </div>
            </div>
            {{--<div class="course-tabs">--}}
                {{--<div class="row">--}}
                    {{--<div class="col-xl-9 mb-xl-30">--}}

                        {{--@if ($notices->count())--}}
                            {{--<div class="card card_shadow notice">--}}
                            {{--<div class="card-body p-0">--}}
                                {{--<div class="swiper-container">--}}
                                    {{--<div class="swiper-wrapper">--}}
                                        {{--@foreach($notices as $notice)--}}
                                        {{--<div class="swiper-slide" data-toggle="modal" data-target="#{{ $notice['id'] }}">--}}
                                            {{--<i class="iconfont">--}}
                                                {{--&#xe646;--}}
                                            {{--</i>--}}
                                            {{--<span>--}}
                                                 {{--{!! $notice['content'] !!}--}}
                                            {{--</span>--}}
                                        {{--</div>--}}

                                        {{-- 公告的模态框 --}}
                                        {{--<div class="modal modal-danger fade" id="{{ $notice['id'] }}" tabindex="-1" role="dialog" aria-labelledby="{{ $notice['id'] }}" aria-hidden="true">--}}
                                            {{--<div class="modal-dialog" role="document" style="background: #FFFFFF;border-radius: 8px;box-shadow: 0px 5px 6px 0px rgba(0,0,0,0.10);">--}}
                                                {{--<div class="modal-content">--}}
                                                    {{--<div class="modal-header">--}}
                                                        {{--<h5 class="modal-title float-left text-center w-100" id="modal_title_6">公告详情</h5>--}}
                                                        {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                                                            {{--<span aria-hidden="true">&times;</span>--}}
                                                        {{--</button>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="modal-body">--}}
                                                        {{--<div class="py-3" style="background: #FAFAFA;font-size: 14px; color: #666;height: 226px;position: relative;overflow-y: auto;">--}}
                                                            {{--<p style="position: absolute;top: 10px;left: 20px;right: 20px;bottom: 10px;">--}}
                                                                {{--{!! $notice['content'] !!}--}}
                                                            {{--</p>--}}
                                                        {{--</div>--}}
                                                        {{--<div class="notice_time">--}}
                                                            {{--{{ now() }}--}}
                                                        {{--</div>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="modal-footer justify-content-center" style="border-top: 0;">--}}
                                                        {{--<button type="button" class="btn btn-sm btn-primary btn-circle" style="padding: 5px 26px;" data-dismiss="modal">关闭</button>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--@endforeach--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--@endif--}}
                        {{--<div class="card card_shadow student_style">--}}
                            {{--<div class="card-body card-body-cascade">--}}
                                {{--<div class="row">--}}
                                    {{--<div class="col-xl-12">--}}
                                        {{--<!-- Classic tabs -->--}}
                                        {{--<div class="classic-tabs">--}}
                                            {{--<ul class="nav" id="myClassicTab" role="tablist">--}}
                                                {{--<li class="nav-item">--}}
                                                    {{--<a class="nav-link  waves-light active show"--}}
                                                       {{--id="profile-tab-classic" data-toggle="tab"--}}
                                                       {{--href="#profile-classic"--}}
                                                       {{--role="tab" aria-controls="profile-classic"--}}
                                                       {{--aria-selected="true">介绍</a>--}}
                                                {{--</li>--}}
                                                {{--<li class="nav-item">--}}
                                                    {{--<a class="nav-link waves-light" id="follow-tab-classic"--}}
                                                       {{--data-toggle="tab" href="#follow-classic" role="tab"--}}
                                                       {{--aria-controls="follow-classic" aria-selected="false">目录</a>--}}
                                                {{--</li>--}}
                                                {{--<li class="nav-item">--}}
                                                    {{--<a class="nav-link waves-light" id="contact-tab-classic"--}}
                                                       {{--data-toggle="tab" href="#contact-classic" role="tab"--}}
                                                       {{--aria-controls="contact-classic" aria-selected="false">笔记</a>--}}
                                                {{--</li>--}}
                                                {{--<li class="nav-item">--}}
                                                    {{--<a class="nav-link waves-light" id="awesome-tab-classic"--}}
                                                       {{--data-toggle="tab" href="#awesome-classic" role="tab"--}}
                                                       {{--aria-controls="awesome-classic" aria-selected="false">评价</a>--}}
                                                {{--</li>--}}
                                            {{--</ul>--}}
                                            {{--<div class="tab-content rounded-bottom" id="myClassicTabContent">--}}
                                                {{--<div class="tab-pane active show" id="profile-classic"--}}
                                                     {{--role="tabpanel"--}}
                                                     {{--aria-labelledby="profile-tab-classic">--}}
                                                    {{--<div>--}}
                                                        {{--<div class="es_piece">--}}
                                                            {{--<div class="piece_header">课程介绍</div>--}}
                                                            {{--<div class="piece_body" style="text-align: left;">--}}
                                                                {{--{!! $course['summary']??'&nbsp;&nbsp;还没有介绍...' !!}--}}
                                                            {{--</div>--}}
                                                            {{--<div class="goals">--}}
                                                                {{--<ul></ul>--}}
                                                            {{--</div>--}}
                                                            {{--<div class="goals">--}}
                                                                {{--<ul></ul>--}}
                                                            {{--</div>--}}
                                                        {{--</div>--}}
                                                        {{--<div class="es_piece">--}}
                                                            {{--<div class="piece_header">课程目标</div>--}}
                                                            {{--<div class="piece_body" style="text-align: left;"></div>--}}
                                                            {{--<div class="goals">--}}
                                                                {{--<ul>--}}
                                                                    {{--@if ($course['goals'])--}}
                                                                        {{--@foreach($course['goals'] as $goal)--}}
                                                                            {{--<li>--}}
                                                                                {{--{{ $goal }}--}}
                                                                            {{--</li>--}}
                                                                        {{--@endforeach--}}
                                                                    {{--@else--}}
                                                                        {{--还没有添加...--}}
                                                                    {{--@endif--}}
                                                                {{--</ul>--}}
                                                            {{--</div>--}}
                                                            {{--<div class="goals">--}}
                                                                {{--<ul></ul>--}}
                                                            {{--</div>--}}
                                                        {{--</div>--}}
                                                        {{--<div class="es_piece">--}}
                                                            {{--<div class="piece_header">适应人群</div>--}}
                                                            {{--<div class="piece_body" style="text-align: left;"></div>--}}
                                                            {{--<div class="goals">--}}
                                                                {{--<ul>--}}
                                                                    {{--@if ($course['audiences'])--}}
                                                                        {{--@foreach($course['audiences'] as $audience)--}}
                                                                            {{--<li>--}}
                                                                                {{--{{ $audience }}--}}
                                                                            {{--</li>--}}
                                                                        {{--@endforeach--}}
                                                                    {{--@else--}}
                                                                        {{--还没有添加...--}}
                                                                    {{--@endif--}}
                                                                {{--</ul>--}}
                                                            {{--</div>--}}
                                                            {{--<div class="goals">--}}
                                                                {{--<ul></ul>--}}
                                                            {{--</div>--}}
                                                        {{--</div>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                                {{--<div class="tab-pane fade folder" id="follow-classic" role="tabpanel"--}}
                                                     {{--aria-labelledby="follow-tab-classic">--}}
                                                    {{--<!--Accordion wrapper-->--}}
                                                    {{--<div class="accordion md-accordion" id="accordionEx" role="tablist"--}}
                                                         {{--aria-multiselectable="true">--}}
                                                        {{--@if ($chapters->count())--}}
                                                            {{--@foreach($chapters as $key => $chapter)--}}

                                                            {{--<div class="card">--}}

                                                                {{--<div class="card-header" role="tab" id="headingOne1">--}}
                                                                    {{--<a data-toggle="collapse" data-parent="#accordionEx"--}}
                                                                       {{--href="#co{{ $chapter['id'] }}" aria-expanded="true"--}}
                                                                       {{--aria-controls="co{{ $chapter['id'] }}">--}}
                                                                        {{--<h6 class="mb-0 float-left">--}}
                                                                            {{--<i class="iconfont">--}}
                                                                                {{--&#xe63a;--}}
                                                                            {{--</i></i>第{{ $key+1 }}章：{{ $chapter['title'] }} <i--}}
                                                                                    {{--class="fas fa-angle-down rotate-icon"></i>--}}
                                                                        {{--</h6>--}}
                                                                        {{--<i class="iconfont float-right">--}}
                                                                            {{--&#xe624;--}}
                                                                        {{--</i>--}}
                                                                    {{--</a>--}}
                                                                {{--</div>--}}

                                                                {{--@foreach($chapter['children'] as $k => $child )--}}
                                                                    {{--<div id="co{{ $chapter['id'] }}" class="collapse show sub_title" role="tabpanel"--}}
                                                                         {{--aria-labelledby="headingOne1"--}}
                                                                         {{--data-parent="#accordionEx">--}}
                                                                        {{--<div class="card-body section-wrap p-0">--}}
                                                                            {{--<h6 class="mb-0 task section">--}}
                                                                                {{--<i class="iconfont">--}}
                                                                                    {{--&#xe63e;--}}
                                                                                {{--</i>--}}
                                                                                {{--<span>--}}
                                                                                    {{--第{{ $k+1 }}节：{{ $child['title'] }}--}}
                                                                                {{--</span>--}}
                                                                            {{--</h6>--}}
                                                                            {{--@foreach($child['tasks'] as $task)--}}
                                                                                {{--<h6 class="task">--}}
                                                                                    {{--@if ($task->is_free)--}}
                                                                                        {{--<a href="{{ route('tasks.show', [$task->chapter, 'task' => $task->id]) }}">--}}
                                                                                            {{--<i class="iconfont">{{ render_task_class($task['target_type']) }}</i>--}}
                                                                                            {{--<span>--}}
                                                                                                {{--{{ render_task_type($task['type']) }}:{{ $task['title'] }}--}}
                                                                                            {{--</span>--}}
                                                                                        {{--</a>--}}
                                                                                        {{--@else--}}
                                                                                        {{--<i class="iconfont">{{ render_task_class($task['target_type']) }}</i>--}}
                                                                                        {{--<span>--}}
                                                                                            {{--{{ render_task_type($task['type']) }}:{{ $task['title'] }}--}}
                                                                                        {{--</span>--}}
                                                                                        {{--@endif--}}

                                                                                    {{--<i class="iconfont float-right" style="font-size: 15px;">--}}
                                                                                        {{--{{ $task->is_free ? '免费试学 &#xe602;' : '&#xe613;' }}--}}
                                                                                    {{--</i>--}}
                                                                                {{--</h6>--}}
                                                                            {{--@endforeach--}}
                                                                        {{--</div>--}}
                                                                    {{--</div>--}}
                                                                {{--@endforeach--}}

                                                            {{--</div>--}}
                                                        {{--@endforeach--}}
                                                        {{--@else--}}
                                                            {{--<span class="no_data">--}}
                                                                {{--还没有任务目录...--}}
                                                            {{--</span>--}}
                                                        {{--@endif--}}
                                                    {{--</div>--}}
                                                    {{--<!-- Accordion wrapper -->--}}
                                                {{--</div>--}}
                                                {{--<div class="tab-pane fade note" id="contact-classic" role="tabpanel"--}}
                                                     {{--aria-labelledby="contact-tab-classic">--}}
                                                    {{--<div class="row">--}}
                                                        {{--<!-- Grid column -->--}}
                                                        {{--<div class="col-md-12">--}}
                                                            {{--<!-- Newsfeed -->--}}
                                                            {{--<div class="mdb-feed">--}}
                                                                {{--@if (count($notes))--}}
                                                                    {{--@foreach($notes as $note)--}}
                                                                        {{--<div class="news">--}}
                                                                            {{--<!-- Label -->--}}
                                                                            {{--<div class="label">--}}
                                                                                {{--<img src="{{ render_cover($note['user']['avatar'], 'avatar') }}"--}}
                                                                                     {{--class="rounded-circle z-depth-1-half">--}}
                                                                            {{--</div>--}}
                                                                            {{--<!-- Excerpt -->--}}
                                                                            {{--<div class="excerpt pl-0 pl-sm-4">--}}
                                                                                {{--<!-- Brief -->--}}
                                                                                {{--<div class="brief">--}}
                                                                                    {{--<a class="name">{{ $note['user']['username'] }}</a>--}}
                                                                                {{--</div>--}}
                                                                                {{--<!-- Added text -->--}}
                                                                                {{--<div class="added-text">--}}
                                                                                      {{--{!! $note['content'] !!}--}}
                                                                                {{--</div>--}}
                                                                                {{--<!-- Feed footer -->--}}
                                                                                {{--<div class="feed-footer">--}}
                                                                                    {{--<span>{{ $note['created_at']->diffForHumans() }}</span>--}}
                                                                                {{--</div>--}}
                                                                            {{--</div>--}}
                                                                        {{--</div>--}}
                                                                    {{--@endforeach--}}
                                                                {{--@else--}}
                                                                    {{--<span class="no_data">--}}
                                                                        {{--还没有笔记...--}}
                                                                    {{--</span>--}}
                                                                {{--@endif--}}
                                                            {{--</div>--}}
                                                        {{--</div>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                                {{--<div class="tab-pane fade note" id="awesome-classic" role="tabpanel"--}}
                                                     {{--aria-labelledby="awesome-tab-classic">--}}
                                                    {{--<div class="col-md-12">--}}
                                                        {{--<div class="mdb-feed">--}}
                                                        {{--@if (count($reviews))--}}
                                                            {{--@foreach($reviews as $review)--}}
                                                                    {{--<div class="news">--}}
                                                                        {{--<!-- Label -->--}}
                                                                        {{--<div class="label">--}}
                                                                            {{--<img src="{{ render_cover($review['user']['avatar'], 'avatar') }}"--}}
                                                                                 {{--class="rounded-circle z-depth-1-half">--}}
                                                                        {{--</div>--}}
                                                                        {{--<!-- Excerpt -->--}}
                                                                        {{--<div class="excerpt pl-0 pl-sm-4">--}}
                                                                            {{--<!-- Brief -->--}}
                                                                            {{--<div class="brief">--}}
                                                                                {{--<a class="name">{{ $review['user']['username'] }}</a>--}}
                                                                            {{--</div>--}}
                                                                            {{--<div class="star">--}}
                                                                                {{--@for ($i = 0; $i < 5; $i++)--}}
                                                                                    {{--@if ($i <= intval($review['rating']))--}}
                                                                                        {{--<i class="iconfont">--}}
                                                                                            {{--&#xe601;--}}
                                                                                        {{--</i>--}}
                                                                                    {{--@else--}}
                                                                                        {{--<i class="iconfont">--}}
                                                                                            {{--&#xe60d;--}}
                                                                                        {{--</i>--}}
                                                                                    {{--@endif--}}
                                                                                {{--@endfor--}}
                                                                            {{--</div>--}}
                                                                            {{--<!-- Added text -->--}}
                                                                            {{--<div class="added-text">--}}
                                                                                {{--{{ $review['content'] }}--}}
                                                                            {{--</div>--}}
                                                                            {{--<!-- Feed footer -->--}}
                                                                            {{--<div class="feed-footer">--}}
                                                                                {{--<span>{{ $review['created_at']->diffForHumans() }}</span>--}}
                                                                            {{--</div>--}}
                                                                        {{--</div>--}}
                                                                        {{--<!-- Excerpt -->--}}
                                                                    {{--</div>--}}
                                                                {{--@endforeach--}}
                                                            {{--@else--}}
                                                                {{--<span class="no_data">--}}
                                                                    {{--还没有人评价...--}}
                                                                {{--</span>--}}
                                                            {{--@endif--}}
                                                        {{--</div>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<!-- Classic tabs -->--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                    {{--</div>--}}
                    {{--<div class="col-xl-3">--}}
                        {{--<div class="card card_shadow student_style">--}}
                            {{--<div class="card-header">--}}
                                {{--授课教师--}}
                            {{--</div>--}}
                            {{--@foreach($teachers as $teacher)--}}
                                {{--<div class="card-body text-center">--}}
                                    {{--<div class="teacher_avatar mx-auto" data-toggle="popover" data-content='<div class="popover_card">--}}
                                        {{--<div class="teacher_header">--}}
                                            {{--<img src="{{ render_cover($teacher['user']['avatar'], 'avatar') }}" alt="" class="teacher_avatar">--}}
                                            {{--<div class="teacher_info">--}}
                                                {{--<span class="teacher_name">{{ $teacher['user']['username'] }}</span>--}}
                                                {{--<span class="teacher_job">{{ $teacher['user']['signature']??'特邀讲师' }}</span>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="teacher_fans">--}}
                                            {{--<div class="fans_item">--}}
                                                {{--<span>{{ $teacher->user->managePlans()->count() }}</span>--}}
                                                {{--<span class="fans_title">--}}
                                                    {{--在教--}}
                                                {{--</span>--}}
                                            {{--</div>--}}
                                            {{--<div class="fans_item">--}}
                                                {{--<span>{{ $teacher->user->followers()->count() }}</span>--}}
                                                {{--<span class="fans_title">--}}
                                                    {{--关注--}}
                                                {{--</span>--}}
                                            {{--</div>--}}
                                            {{--<div class="fans_item mr-0">--}}
                                                {{--<span>{{ $teacher->user->fans()->count() }}</span>--}}
                                                {{--<span class="fans_title">--}}
                                                    {{--粉丝--}}
                                                {{--</span>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--@auth('web')--}}
                                                {{--@if (auth('web')->id() != $teacher['user']['id'])--}}

                                                    {{--<div class="teacher_controls">--}}
                                                       {{--<a href="#" class="btn btn-primary btn-sm btn-circle float-left follow" data-id="{{ $teacher['user']['id'] }}">--}}
                                                       {{--{{ $teacher->user->isFollow() ? '取消关注' : '关注' }}--}}
                                                       {{--</a>--}}
                                                        {{--<a href="#" class="btn btn-primary btn-sm btn-circle float-right message"--}}
                                                        {{--data-id="{{ $teacher['user']['id'] }}" data-username="{{ $teacher['user']['username'] }}"--}}
                                                        {{-->私信</a>--}}
                                                    {{--</div>--}}


                                                {{--@endif--}}
                                        {{--@else--}}
                                            {{--<div class="teacher_controls">--}}
                                                {{--<a href="{{ route('login') }}" class="btn btn-primary btn-sm btn-circle float-left">关注</a>--}}
                                                {{--<a href="{{ route('login') }}" class="btn btn-primary btn-sm btn-circle float-right">私信</a>--}}
                                            {{--</div>--}}

                                        {{--@endAuth--}}
                                    {{--</div>' data-html="true" data-trigger="click" data-placement="auto">--}}
                                        {{--<img src="{{ render_cover($teacher['user']['avatar'], 'avatar') }}"--}}
                                             {{--class="rounded-circle z-depth-1"--}}
                                             {{--alt="Sample avatar">--}}
                                    {{--</div>--}}
                                    {{--<div class="teacher_info">--}}
                                        {{--<h5 class="">讲师：{{ $teacher['user']['username'] }}</h5>--}}
                                        {{--<p class=""> {{ $teacher['user']['signature']??'特邀讲师' }}</p>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--@endforeach--}}
                        {{--</div>--}}
                        {{--<div class="card card_shadow member student_style">--}}
                            {{--<div class="card-header">--}}
                                {{--最新成员--}}
                            {{--</div>--}}
                            {{--<div class="card-body text-center pl-0 pr-0">--}}

                                {{--@if ($members->count())--}}
                                    {{--@foreach($members as $member)--}}
                                        {{--<img src="{{ render_cover($member['user']['avatar'], 'avatar') }}"--}}
                                             {{--class="rounded-circle z-depth-1"--}}
                                             {{--alt="Sample avatar" data-toggle="popover" data-content='<div class="popover_card">--}}
                                        {{--<div class="teacher_header">--}}
                                            {{--<img src="{{ render_cover($member['user']['avatar'], 'avatar') }}" alt="" class="teacher_avatar">--}}
                                            {{--<div class="teacher_info">--}}
                                                {{--<span class="teacher_name">{{ $member['user']['username'] }}</span>--}}
                                                {{--<span class="teacher_job">{{ $member['user']['signature'] ?? '学习是一种习惯' }}</span>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="teacher_fans">--}}
                                            {{--<div class="fans_item">--}}
                                                {{--<span>{{ $member->user->plans()->count() }}</span>--}}
                                                {{--<span class="fans_title">--}}
                                                    {{--在学--}}
                                                {{--</span>--}}
                                            {{--</div>--}}
                                            {{--<div class="fans_item">--}}
                                                {{--<span>{{ $member->user->followers()->count() }}</span>--}}
                                                {{--<span class="fans_title">--}}
                                                    {{--关注--}}
                                                {{--</span>--}}
                                            {{--</div>--}}
                                            {{--<div class="fans_item mr-0">--}}
                                                {{--<span>{{ $member->user->fans()->count() }}</span>--}}
                                                {{--<span class="fans_title">--}}
                                                    {{--粉丝--}}
                                                {{--</span>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--@auth('web')--}}
                                            {{--@if (auth('web')->id() != $member['user']['id'])--}}

                                                {{--<div class="teacher_controls">--}}
                                                   {{--<a href="#" class="btn btn-primary btn-sm btn-circle float-left follow" data-id="{{ $member['user']['id'] }}">--}}
                                                               {{--{{ $member->user->isFollow() ? '取消关注' : '关注' }}--}}
                                                {{--</a>--}}
                                                 {{--<a href="#" class="btn btn-primary btn-sm btn-circle float-right message"--}}
                                                 {{--data-id="{{ $member['user']['id'] }}" data-username="{{ $member['user']['username'] }}"--}}
                                                                {{-->私信</a>--}}
                                                            {{--</div>--}}


                                            {{--@endif--}}
                                        {{--@else--}}
                                                {{--<div class="teacher_controls">--}}
                                                    {{--<a href="{{ route('login') }}" class="btn btn-primary btn-sm btn-circle float-left">关注</a>--}}
                                                        {{--<a href="{{ route('login') }}" class="btn btn-primary btn-sm btn-circle float-right">私信</a>--}}
                                                    {{--</div>--}}

                                        {{--@endAuth--}}
                                                {{--</div>' data-html="true" data-trigger="click" data-placement="auto">--}}
                                    {{--@endforeach--}}
                                {{--@else--}}
                                    {{--<span class="no_data">--}}
                                        {{--还没有成员...--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
        {{-- 发送私信的模态框 --}}
        {{--<div class="modal modal-danger fade send_modal" id="modal_6" tabindex="-1" role="dialog" aria-labelledby="modal_6" aria-hidden="true">--}}
            {{--<div class="modal-dialog" role="document" style="background: #FFFFFF;border-radius: 8px;box-shadow: 0px 5px 6px 0px rgba(0,0,0,0.10);">--}}
                {{--<div class="modal-content">--}}
                    {{--<div class="modal-header">--}}
                        {{--<h5 class="modal-title float-left text-center w-100" id="modal_title_6">发送私信</h5>--}}
                    {{--</div>--}}
                    {{--<div class="modal-body">--}}
                        {{--<span class="to_sender">--}}
                            {{--TO：<span id="username"></span>--}}
                            {{--<input type="hidden" id="user_id">--}}
                        {{--</span>--}}
                        {{--<div class="py-3" style="background: #FAFAFA;font-size: 14px; color: #666;height: 226px;position: relative;overflow-y: auto;">--}}
                            {{--<textarea name="" id="message" cols="30" rows="10"></textarea>--}}
                        {{--</div>--}}
                        {{--<div class="notice_time">--}}
                            {{--{{ now() }}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="modal-footer justify-content-center" style="border-top: 0;">--}}
                        {{--<button type="button" class="btn btn-sm btn-primary btn-circle" style="padding: 5px 26px;margin-right: 30px;" data-dismiss="modal" id="sendMessage">发送</button>--}}
                        {{--<button type="button" class="btn btn-sm btn-primary btn-circle" style="padding: 5px 26px;" data-dismiss="modal">取消</button>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>

@endsection

@section('script')
    <script src="{{  mix('/js/front/article.js')  }}"></script>
    <script>

        function login()
        {
           location.href='/login';
        }

        $('[data-toggle="popover"]').popover({
        }).on('shown.bs.popover', function (event) {
            var that = this;
            $('body').find('div.popover').on('mouseenter', function () {
                $(that).attr('in', true);
            }).on('mouseleave', function () {
                $(that).removeAttr('in');
                $(that).popover('hide');
            });
        }).on('hide.bs.popover', function (event) {
            if ($(this).attr('in')) {
                event.preventDefault();
            }
        });

        // 收藏课程
        $(document).on('click', '.collect', function () {
            var favourite = $(this);
            var id = $(this).data('id');

            if (!id) {edu.alert('danger','收藏错误');return false;}
            favourite.removeClass('collect')
            $.ajax({
                url: '/favorites',
                method:'post',
                data:{'model_type':'course', 'model_id': parseInt(id)},
                success:function(res){

                    if (res.status == '200') {

                        if(res.data.length == 1){
                            edu.alert('success', '取消收藏成功');
                            favourite.children('.iconfont').removeClass('active');
                        } else {
                            edu.alert('success', '收藏成功');
                            favourite.children('.iconfont').removeClass('active').addClass('active');
                        }
                    }
                    favourite.addClass('collect');
                }
            })

        })

        // 关注
        $(document).on('click', '.follow', function () {

            var follow = $(this);
            var id = $(this).data('id');

            if (!id) {edu.alert('danger','关注错误');return false;}
            follow.removeClass('follow');
            $.ajax({
                url: '/users',
                method:'post',
                data:{follow_id: id},
                success:function(res) {

                    if (res.status == '200') {

                        if (res.data.length == 0) {
                            follow.html('关注');
                            edu.alert('success', '取消关注成功');
                        } else {
                            follow.html('取消关注');
                            edu.alert('success', '关注成功');
                        }

                        follow.addClass('follow');
                    }
                }
            })
            return false;
        })

        // 发送私信的模态框
        $(document).on('click', '.message', function () {
            var id = $(this).data('id');

            var username = $(this).data('username');

            $('#username').html(username);

            $('#user_id').val(id);

            $('#modal_6').modal('show');

            return false;
        })

        // 发送私信
        $('#sendMessage').click(function(){

            // 数据验证
            var id = $('#user_id').val();

            var message = $('#message').val();

            if (!id || !message) {
                 edu.alert('danger', '请完善私信信息');
                return false;
            }

            $.ajax({
                url: '/my/message',
                method:'post',
                data:{user_id: id, message:message},
                success:function(res){

                    if (res.status == '200') {
                        // 清空信息
                        $('#username').html('');

                        $('#user_id').val('');

                        $('#message').val('');

                        edu.alert('success', '发送成功');

                        $('#modal_6').modal('hide');
                    }
                }
            })
        })


    </script>
@endsection