{{--{{ dd($task) }}--}}
@extends('frontend.review.layouts.app')

@section('title', '班级详情')

{{--@section('keywords')--}}
{{--@parent--}}
{{--<meta name="keywords" content="{{ $course['title'] }}" />--}}
{{--@endsection--}}

{{--@section('description')--}}
{{--@parent--}}
{{--<meta name="description" content="{{ $course['title'] }}" />--}}
{{--@endsection--}}

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/classroom/plan.css') }}">
@endsection

@section('content')
    <div class="zh_course">
        <div class="container">
            <div class="row">
                <div class="col-10">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pl-0">
                            <li class="breadcrumb-item"><a href="/">首页</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('classrooms.index') }}">班级列表</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $classroom->title }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="course_card student_style">
                <h2 class="course_title">
                    {{ $classroom->title }}
                    @if ($classroom->isControl())
                        <span class="float-right">
                            <a href="{{ route('manage.classroom.show', $classroom) }}">
                            <i class="iconfont" style="font-size: 15px;">
                                &#xe639;
                            </i>
                            管理
                            </a>
                        </span>
                    @endif
                </h2>
                <div class="row">
                    <div class="col-xl-4 pr-0">
                        <img src="{{ render_cover($classroom->cover,'classroom') }}" alt="">
                        <div class="cover_btn">

                            <div class="study_num">
                                <i class="iconfont">
                                    &#xe631;
                                </i>
                                <span>
                                {{ $classroom->members_count }}人加入学习
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
                                {{ $classroom->coin_price ? $classroom->coin_price. '虚拟币':  ($classroom->price ?  ftoy($classroom->price) .'元': '免费') }}
                            </div>
                        </div>
                        <div class="course_item plan_wrap">

                            <div class="course_title">
                                承诺服务
                            </div>
                            @foreach($classroom->services as $service)
                                @if ($classroom->show_services)
                                    <div class="plan_item">
                                        <a href="javascript:;">
                                            未展示
                                        </a>
                                    </div>
                                    @break
                                @endif
                                <div class="plan_item">
                                    <a href="javascript:;">
                                        {{ $service }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="course_item">
                            <div class="course_title">
                                学习有效期
                            </div>
                            <span class="validity">
                            @switch($classroom['expiry_mode'])
                                    @case('forever')
                                    永久有效
                                    @break

                                    @case('valid')
                                    {{ $classroom['expiry_days']}} 天
                                    @break

                                    @case('period')
                                    {{ $classroom['expiry_started_at']->toDateString().'-'.$classroom['expiry_ended_at']->toDateString()}}
                                    @break

                                    @default

                                @endswitch
                            </span>
                        </div>
                    </div>
                    <div class="col-xl-3 btn-group btn-group-justified p-0 shopping">

                        @if ($classroom->status != 'published')
                            <button class="btn btn-primary btn-sm btn-circle m-xl-auto">预览模式</button>
                        @else
                            @if ($classroom->isControl())
                                <button class="btn btn-primary btn-sm btn-circle m-xl-auto">我的班级</button>
                            @else
                                @if ($classroom->isMember())
                                    <button class="btn btn-primary btn-sm btn-circle m-xl-auto">
                                        <a href="{{ route('classrooms.plans', $classroom) }}">开始学习</a>
                                    </button>
                                @else
                                    @if($classroom->is_buy)
                                        <button class="btn btn-primary btn-sm btn-circle m-xl-auto">
                                            <a href="{{ route('classrooms.shopping', $classroom) }}">加入班级</a>
                                        </button>
                                    @else
                                        <button class="btn btn-primary btn-sm btn-circle m-xl-auto" disabled="disabled" style="cursor: not-allowed;">
                                            <a href="javascript:;" style="cursor: not-allowed;">暂未开放</a>
                                        </button>
                                    @endif
                                @endif
                            @endif
                        @endif

                        {{--<button class="btn btn-primary btn-sm btn-circle m-xl-auto">--}}
                        {{--<a href="#">购买</a>--}}
                        {{--</button>--}}
                        {{--<button class="btn btn-primary btn-sm btn-circle m-xl-auto">版本未开放购买</button>--}}

                    </div>
                </div>
            </div>
            <div class="course-tabs">
                <div class="row">
                    <div class="col-xl-9 mb-xl-30">

                        <div class="card card_shadow student_style">
                            <div class="card-body card-body-cascade">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <!-- Classic tabs -->
                                        <div class="classic-tabs">
                                            <ul class="nav" id="myClassicTab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link  waves-light active show"
                                                       id="profile-tab-classic" data-toggle="tab"
                                                       href="#profile-classic"
                                                       role="tab" aria-controls="profile-classic"
                                                       aria-selected="true">介绍</a>
                                                </li>
                                                {{--<li class="nav-item">--}}
                                                    {{--<a class="nav-link waves-light" id="contact-tab-classic"--}}
                                                       {{--data-toggle="tab" href="#contact-classic" role="tab"--}}
                                                       {{--aria-controls="contact-classic" aria-selected="false">预览视频</a>--}}
                                                {{--</li>--}}
                                                <li class="nav-item">
                                                    <a class="nav-link waves-light" id="follow-tab-classic"
                                                       data-toggle="tab" href="#course-plan" role="tab"
                                                       aria-controls="course-plan" aria-selected="false">课程</a>
                                                </li>
                                                {{--<li class="nav-item">--}}
                                                    {{--<a class="nav-link waves-light" id="contact-tab-evaluate"--}}
                                                       {{--data-toggle="tab" href="#contact-evaluate" role="tab"--}}
                                                       {{--aria-controls="contact-evaluate" aria-selected="false">评价</a>--}}
                                                {{--</li>--}}
                                            </ul>
                                            <div class="tab-content rounded-bottom" id="myClassicTabContent">

                                                <div class="tab-pane fade folder active show" id="profile-classic"
                                                     role="tabpanel"
                                                     aria-labelledby="follow-tab-classic">
                                                    <!--Accordion wrapper-->
                                                    <div class="accordion md-accordion" id="accordionEx" role="tablist"
                                                         aria-multiselectable="true">
                                                        {!! $classroom->description !!}
                                                    </div>
                                                    <!-- Accordion wrapper -->
                                                </div>
                                                {{--<div class="tab-pane fade folder active " id="contact-classic"--}}
                                                     {{--role="tabpanel"--}}
                                                     {{--aria-labelledby="follow-tab-classic">--}}
                                                    {{--<!--Accordion wrapper-->--}}
                                                    {{--<div class="accordion md-accordion" id="accordionEx" role="tablist"--}}
                                                         {{--aria-multiselectable="true">--}}
                                                        {{--@if($classroom->preview)--}}
                                                            {{--<video src="{{ render_cover($classroom->preview, '') }}"--}}
                                                                   {{--controls></video>--}}
                                                        {{--@else--}}
                                                            {{--暂无预览视频--}}
                                                        {{--@endif--}}
                                                    {{--</div>--}}
                                                    {{--<!-- Accordion wrapper -->--}}
                                                {{--</div>--}}

                                                <div class="tab-pane fade folder " id="course-plan"
                                                     role="tabpanel"
                                                     aria-labelledby="follow-tab-classic">
                                                    <!--Accordion wrapper-->
                                                    <div class="accordion md-accordion" id="accordionEx" role="tablist"
                                                         aria-multiselectable="true">

                                                        @if ($classroom->chapters()->count())
                                                            <div class="card">
                                                                @foreach($classroom->chapters() as $chapter)
                                                                    <div class="card-header">
                                                                        <a href="{{ $classroom->isMember() ? route('classrooms.plans', $classroom) : 'javascript:;' }}">

                                                                            <div class="course_title">
                                                                                {{ $chapter->title }}
                                                                            </div>

                                                                            <div class="course_desc">
                                                                                {{ $chapter->goals }}
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <span class="no_data">
                                                                还没有课程...
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <!-- Accordion wrapper -->
                                                </div>
                                                {{--评价--}}
                                                {{--<div class="tab-pane fade folder active " id="contact-evaluate"--}}
                                                     {{--role="tabpanel"--}}
                                                     {{--aria-labelledby="follow-tab-evaluate">--}}
                                                    {{--123123--}}
                                                {{--</div>--}}
                                            </div>
                                        </div>
                                        <!-- Classic tabs -->
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-xl-3">
                        @if(count($classroom->heads))
                            <div class="card card_shadow student_style">
                                <div class="card-header">
                                    班主任
                                </div>
                                @foreach($classroom->heads as $heads)
                                    <div class="card-body text-center">
                                        <div class="teacher_avatar mx-auto" data-toggle="popover" data-content='<div class="popover_card">
                                        <div class="teacher_header">
                                            <img src="{{ render_cover($heads->avatar, 'avatar') }}" alt="" class="teacher_avatar">
                                            <div class="teacher_info">
                                                <span class="teacher_name">{{ $heads->username }}</span>
                                                <span class="teacher_job">{{ $heads->signature ?? '班主任' }}</span>
                                            </div>
                                        </div>
                                        <div class="teacher_fans">
                                            <div class="fans_item">
                                                <span>{{ $heads->managePlans()->count() }}</span>
                                                <span class="fans_title">
                                                    在教
                                                </span>
                                            </div>
                                            <div class="fans_item">
                                                <span>{{ $heads->followers()->count() }}</span>
                                                <span class="fans_title">
                                                    关注
                                                </span>
                                            </div>
                                            <div class="fans_item mr-0">
                                                <span>{{ $heads->fans()->count() }}</span>
                                                <span class="fans_title">
                                                    粉丝
                                                </span>
                                            </div>
                                        </div>

                                        @auth('web')
                                        @if (auth('web')->id() != $heads->id)
                                                <div class="teacher_controls">
                                                   <a href="#" class="btn btn-primary btn-sm btn-circle float-left follow" data-id="{{ $heads->id }}">
                                                                               {{ $heads->isFollow() ? '取消关注' : '关注' }}
                                                </a>
                                                <a href="#" class="btn btn-primary btn-sm btn-circle float-right message"
                                             data-id="{{ $heads->id }}" data-username="{{ $heads->username }}">私信</a>
                                                                </div>
                                            @endif
                                        @else
                                                <div class="teacher_controls">
                                                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm btn-circle float-left">关注</a>
                                                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm btn-circle float-right">私信</a>
                                                    </div>

                                        @endAuth

                                                </div>' data-html="true" data-trigger="click" data-placement="auto">
                                            <img src="{{ render_cover($heads->avatar, 'avatar') }}"
                                                 class="rounded-circle z-depth-1"
                                                 alt="Sample avatar">
                                        </div>
                                        <div class="teacher_info">
                                            <h5 class="">{{ $heads->username }}</h5>
                                            <p class="">{{ $heads->signature ?? '班主任' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if(count($classroom->teachers))
                            <div class="card card_shadow student_style">
                                <div class="card-header">
                                    授课教师
                                </div>
                                @foreach($classroom->teachers as $teacher)
                                    <div class="card-body text-center">
                                        <div class="teacher_avatar mx-auto" data-toggle="popover" data-content='<div class="popover_card">
                                        <div class="teacher_header">
                                            <img src="{{ render_cover($teacher->avatar, 'avatar') }}" alt="" class="teacher_avatar">
                                            <div class="teacher_info">
                                                <span class="teacher_name">{{ $teacher->username }}</span>
                                                <span class="teacher_job">{{ $teacher->signature ?? '特邀讲师' }}</span>
                                            </div>
                                        </div>
                                        <div class="teacher_fans">
                                            <div class="fans_item">
                                                <span>{{ $teacher->managePlans()->count() }}</span>
                                                <span class="fans_title">
                                                    在教
                                                </span>
                                            </div>
                                            <div class="fans_item">
                                                <span>{{ $teacher->followers()->count() }}</span>
                                                <span class="fans_title">
                                                    关注
                                                </span>
                                            </div>
                                            <div class="fans_item mr-0">
                                                <span>{{ $teacher->fans()->count() }}</span>
                                                <span class="fans_title">
                                                    粉丝
                                                </span>
                                            </div>
                                        </div>

                                        @auth('web')
                                        @if (auth('web')->id() != $teacher->id)
                                                <div class="teacher_controls">
                                                   <a href="#" class="btn btn-primary btn-sm btn-circle float-left follow" data-id="{{ $teacher->id }}">
                                                                           {{ $teacher->isFollow() ? '取消关注' : '关注' }}
                                                </a>
                                                <a href="#" class="btn btn-primary btn-sm btn-circle float-right message"
                                             data-id="{{ $teacher->id }}" data-username="{{ $teacher->username }}">私信</a>
                                                            </div>
                                            @endif
                                        @else
                                                <div class="teacher_controls">
                                                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm btn-circle float-left">关注</a>
                                                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm btn-circle float-right">私信</a>
                                                    </div>

                                        @endAuth

                                                </div>' data-html="true" data-trigger="click" data-placement="auto">
                                            <img src="{{ render_cover($teacher->avatar, 'avatar') }}"
                                                 class="rounded-circle z-depth-1"
                                                 alt="Sample avatar">
                                        </div>
                                        <div class="teacher_info">
                                            <h5 class="">讲师：{{ $teacher->username }}</h5>
                                            <p class="">{{ $teacher->signature ?? '特邀讲师' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if(count($classroom->members))
                            <div class="card card_shadow member student_style">
                                <div class="card-header">
                                    最新成员
                                </div>
                                <div class="card-body text-center pl-0 pr-0">
                                    @foreach($classroom->members->sortByDesc('created_at')->take(10) as $member)
                                        <img src="{{ render_cover($member->avatar, 'avatar') }}"
                                             class="rounded-circle z-depth-1"
                                             alt="Sample avatar" data-toggle="popover" data-content='<div class="popover_card">
                                        <div class="teacher_header">
                                            <img src="{{ render_cover($member->avatar, 'avatar') }}" alt="" class="teacher_avatar">
                                            <div class="teacher_info">
                                                <span class="teacher_name">{{ $member->username }}</span>
                                                <span class="teacher_job">{{ $member->signature ?? '学习是一种习惯' }}</span>
                                            </div>
                                        </div>
                                        <div class="teacher_fans">
                                            <div class="fans_item">
                                                <span>{{ $member->managePlans()->count() }}</span>
                                                <span class="fans_title">
                                                    在学
                                                </span>
                                            </div>
                                            <div class="fans_item">
                                                <span>{{ $member->followers()->count() }}</span>
                                                <span class="fans_title">
                                                    关注
                                                </span>
                                            </div>
                                            <div class="fans_item mr-0">
                                                <span>{{ $member->fans()->count() }}</span>
                                                <span class="fans_title">
                                                    粉丝
                                                </span>
                                            </div>
                                        </div>

                                        @auth('web')
                                        @if (auth('web')->id() != $member->id)
                                                <div class="teacher_controls">
                                                   <a href="#" class="btn btn-primary btn-sm btn-circle float-left follow" data-id="{{ $member->id }}">
                                                                       {{ $member->isFollow() ? '取消关注' : '关注' }}
                                                </a>
                                                <a href="#" class="btn btn-primary btn-sm btn-circle float-right message"
                                             data-id="{{ $member->id }}" data-username="{{ $member->username }}">私信</a>
                                                        </div>
                                            @endif
                                        @else
                                                <div class="teacher_controls">
                                                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm btn-circle float-left">关注</a>
                                                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm btn-circle float-right">私信</a>
                                                    </div>

                                        @endAuth

                                                </div>' data-html="true" data-trigger="click" data-placement="auto">
                                        {{--<span class="no_data">--}}
                                        {{--还没有成员...--}}
                                        {{--</span>--}}
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{-- 发送私信的模态框 --}}
        <div class="modal modal-danger fade send_modal" id="modal_6" tabindex="-1" role="dialog"
             aria-labelledby="modal_6" aria-hidden="true">
            <div class="modal-dialog" role="document"
                 style="background: #FFFFFF;border-radius: 8px;box-shadow: 0px 5px 6px 0px rgba(0,0,0,0.10);">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title float-left text-center w-100" id="modal_title_6">发送私信</h5>
                    </div>
                    <div class="modal-body">
                        <span class="to_sender">
                            TO：<span id="username"></span>
                            <input type="hidden" id="user_id">
                        </span>
                        <div class="py-3"
                             style="background: #FAFAFA;font-size: 14px; color: #666;height: 226px;position: relative;overflow-y: auto;">
                            <textarea name="" id="message" cols="30" rows="10"></textarea>
                        </div>
                        <div class="notice_time">
                            {{ now() }}
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center" style="border-top: 0;">
                        <button type="button" class="btn btn-sm btn-primary btn-circle"
                                style="padding: 5px 26px;margin-right: 30px;" data-dismiss="modal" id="sendMessage">发送
                        </button>
                        <button type="button" class="btn btn-sm btn-primary btn-circle" style="padding: 5px 26px;"
                                data-dismiss="modal">取消
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{  mix('/js/front/article.js')  }}"></script>
    <script>

        function login() {
            location.href = '/login';
        }

        $('[data-toggle="popover"]').popover({}).on('shown.bs.popover', function (event) {
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

        // 关注
        $(document).on('click', '.follow', function () {

            var follow = $(this);
            var id = $(this).data('id');

            if (!id) {
                edu.alert('danger', '关注错误');
                return false;
            }
            follow.removeClass('follow');
            $.ajax({
                url: '/users',
                method: 'post',
                data: {follow_id: id},
                success: function (res) {

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
        $('#sendMessage').click(function () {

            // 数据验证
            var id = $('#user_id').val();

            var message = $('#message').val();

            if (!id || !message) {
                edu.alert('danger', '请完善私信信息');
                return false;
            }

            $.ajax({
                url: '/my/message',
                method: 'post',
                data: {user_id: id, message: message},
                success: function (res) {

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