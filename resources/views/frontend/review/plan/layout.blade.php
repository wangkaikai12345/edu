{{--{{ dd($task) }}--}}
@extends('frontend.review.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/article/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/plan/index.css') }}">
@endsection

@section('content')
    <div class="zh_course">
        <div class="container">
            {{--面包屑--}}
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pl-0">
                            <li class="breadcrumb-item"><a href="/">首页</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">课程列表</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('courses.show', $course) }}">{{ $course['title'] }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $plan['title'] }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            {{--头部导航--}}
            <div class="course_card student_style">
                <h2 class="course_title">
                    <p>
                        {{ $plan['title'] }}
                    </p>
                    @if ($control)
                        <span class="float-right">
                         <a href="{{ route('manage.plans.show', [$course, $plan]) }}">
                            <i class="iconfont" style="font-size: 15px;">
                                &#xe639;
                            </i>
                            管理
                         </a>
                        </span>
                    @endif
                </h2>
                <div class="row">
                    <div class="col-xl-3 pr-0">
                        <div class="course-progress m-auto">
                            <div class="cricle-progress" id="freeprogress" data-percent="{{ $plan_member && $plan['tasks_count'] ? (ytof($plan_member['learned_count']/$plan['tasks_count'])) :0 }}">
                                <span class="percent"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 pl-xl-5 p-0">
                        <div class="p_item p_first_item">
                            <div class="p_mb">
                                {{ $plan_member ? $plan_member['learned_count'] : 0}} / {{ $plan['tasks_count'] }}
                                <span>个</span>
                            </div>
                            <div class="p_title">
                                <i class="iconfont">
                                    &#xe609;
                                </i>
                                <span>
                                    已完成任务
                                </span>
                            </div>
                        </div>
                        <div class="p_item">
                            <div class="p_mb">
                                <span>
                                {{--@switch($plan['expiry_mode'])--}}
                                    {{--@case('forever')--}}
                                    {{--永久有效--}}
                                    {{--@break--}}

                                    {{--@case('valid')--}}
                                    {{--{{ $plan['expiry_days']}} 天--}}
                                    {{--@break--}}

                                    {{--@case('period')--}}
                                    {{--{{ $plan['expiry_started_at']->toDateString().'-'.$plan['expiry_ended_at']->toDateString()}}--}}
                                    {{--@break--}}

                                    {{--@default--}}

                                {{--@endswitch--}}
                                    {{ $plan_member && $plan_member['deadline']? $plan_member['deadline'] : '永久有效' }}
                                </span>
                            </div>
                            <div class="p_title">
                                <i class="iconfont">
                                    &#xe62d;
                                </i>
                                <span>
                                    学习有效期
                                </span>
                            </div>
                        </div>
                        <div class="p_item mr-0 border-right-0">
                            <div class="p_mb">
                                <span>{{ $task ? $task['title'] : '还没有任务...' }}</span>
                            </div>
                            <div class="p_title">
                                <i class="iconfont">
                                    &#xe62d;
                                </i>
                                <span>
                                    下一学习任务
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 btn-group btn-group-justified p-0 shopping">

                        @if ($plan->status != 'published')
                            <a class="btn btn-primary btn-sm btn-circle m-xl-auto"
                               href="javascript:;"> 预览模式</a>
                            @else

                            @if ($plan['tasks_count'] && $task)
                                @if($control)
                                    <a class="btn btn-primary btn-sm btn-circle m-xl-auto"
                                       href="{{ route('tasks.show', $task->chapter) }}">我的课程</a>
                                @else
                                        @if($plan->learn_mode == 'lock')
                                            <a class="btn btn-primary btn-sm btn-circle m-xl-auto"
                                               href="javascript:;">闯关学习</a>
                                        @else
                                        <a class="btn btn-primary btn-sm btn-circle m-xl-auto"
                                           href="{{ route('tasks.show', [$task->chapter, 'task' => $task->id]) }}">继续学习</a>
                                        @endif
                                @endif
                            @else
                                <a class="btn btn-primary btn-sm btn-circle m-xl-auto"
                                   href="javascript:;">暂无任务</a>
                            @endif
                        @endif

                    </div>
                </div>
            </div>
            <div class="course-tabs">
                <div class="row">

                    <div class="col-xl-9 mb-xl-30">
                        {{-- 公告--}}
                        @if ($notices->count())
                            <div class="card card_shadow notice">
                                <div class="card-body p-0">
                                    <div class="swiper-container">
                                        <div class="swiper-wrapper">
                                            @foreach($notices as $notice)
                                                <div class="swiper-slide" data-toggle="modal" data-target="#{{ $notice['id'] }}">
                                                    <i class="iconfont">
                                                        &#xe646;
                                                    </i>
                                                    <span>
                                                {{ $notice['content'] }}
                                            </span>
                                                </div>

                                                {{-- 公告的模态框 --}}
                                                <div class="modal modal-danger fade" id="{{ $notice['id'] }}" tabindex="-1" role="dialog" aria-labelledby="{{ $notice['id'] }}" aria-hidden="true">
                                                    <div class="modal-dialog" role="document" style="background: #FFFFFF;border-radius: 8px;box-shadow: 0px 5px 6px 0px rgba(0,0,0,0.10);">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title float-left text-center w-100" id="modal_title_6">公告详情</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="py-3" style="background: #FAFAFA;font-size: 14px; color: #666;height: 226px;position: relative;overflow-y: auto;">
                                                                    <p style="position: absolute;top: 10px;left: 20px;right: 20px;bottom: 10px;">
                                                                        {!! $notice['content'] !!}
                                                                    </p>
                                                                </div>
                                                                <div class="notice_time">
                                                                    {{ now() }}
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer justify-content-center" style="border-top: 0;">
                                                                <button type="button" class="btn btn-sm btn-primary btn-circle" style="padding: 5px 26px;" data-dismiss="modal">关闭</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="card card_shadow student_style">
                            <div class="card-body card-body-cascade">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <!-- Classic tabs -->
                                        <div class="classic-tabs">
                                            <ul class="nav" id="myClassicTab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link  waves-light {{ plan_detail_active('intro') }}"
                                                        href="{{ route('plans.intro', [$plan->course, $plan]) }}"
                                                    >介绍</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link waves-light {{ plan_detail_active('chapter') }}"
                                                        href="{{ route('plans.chapter', [$plan->course, $plan]) }}"
                                                    >目录</a>
                                                </li>
                                                <li class="nav-item hide_note">
                                                    <a class="nav-link waves-light {{ plan_detail_active('note') }}"
                                                       href="{{ route('plans.note', [$plan->course, $plan]) }}"
                                                    >笔记</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link waves-light {{ plan_detail_active('question') }}"
                                                       href="{{ route('plans.question', [$plan->course, $plan]) }}"
                                                    >问答</a>
                                                </li>
                                                <li class="nav-item hide_huati">
                                                    <a class="nav-link waves-light {{ plan_detail_active('topic') }}"
                                                       href="{{ route('plans.topic', [$plan->course, $plan]) }}"
                                                    >话题</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link waves-light {{ plan_detail_active('review') }}"
                                                       href="{{ route('plans.review', [$plan->course, $plan]) }}"
                                                    >评价</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content rounded-bottom">
                                                <div>
                                                    @yield('leftBody')
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Classic tabs -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- 教师成员--}}
                    <div class="col-xl-3">
                        <div class="card card_shadow student_style">
                            <div class="card-header">
                                授课教师
                            </div>
                            @foreach($teachers as $teacher)
                                <div class="card-body text-center">
                                    <div class="teacher_avatar mx-auto" data-toggle="popover" data-content='<div class="popover_card">
                                        <div class="teacher_header">
                                            <img src="{{ render_cover($teacher['user']['avatar'], 'avatar') }}" alt="" class="teacher_avatar">
                                            <div class="teacher_info">
                                                <span class="teacher_name">{{ $teacher['user']['username'] }}</span>
                                                <span class="teacher_job">{{ $teacher['user']['signature']??'特邀讲师' }}</span>
                                            </div>
                                        </div>
                                        <div class="teacher_fans">
                                            <div class="fans_item">
                                                <span>{{ $teacher->user->managePlans()->count() }}</span>
                                                <span class="fans_title">
                                                    在教
                                                </span>
                                            </div>
                                            <div class="fans_item">
                                                <span>{{ $teacher->user->followers()->count() }}</span>
                                                <span class="fans_title">
                                                    关注
                                                </span>
                                            </div>
                                            <div class="fans_item mr-0">
                                                <span>{{ $teacher->user->fans()->count() }}</span>
                                                <span class="fans_title">
                                                    粉丝
                                                </span>
                                            </div>
                                        </div>
                                        @auth('web')
                                    @if (auth('web')->id() != $teacher['user']['id'])

                                            <div class="teacher_controls">
                                               <a href="#" class="btn btn-primary btn-sm btn-circle float-left follow" data-id="{{ $teacher['user']['id'] }}">
                                                       {{ $teacher->user->isFollow() ? '取消关注' : '关注' }}
                                            </a>
                                             <a href="#" class="btn btn-primary btn-sm btn-circle float-right message"
                                             data-id="{{ $teacher['user']['id'] }}" data-username="{{ $teacher['user']['username'] }}"
                                                        >私信</a>
                                                    </div>


                                                @endif
                                    @else
                                            <div class="teacher_controls">
                                                <a href="{{ route('login') }}" class="btn btn-primary btn-sm btn-circle float-left">关注</a>
                                                <a href="{{ route('login') }}" class="btn btn-primary btn-sm btn-circle float-right">私信</a>
                                            </div>

                                        @endAuth
                                            </div>' data-html="true" data-trigger="click" data-placement="auto">
                                        <img src="{{ render_cover($teacher['user']['avatar'], 'avatar') }}"
                                             class="rounded-circle z-depth-1"
                                             alt="Sample avatar">
                                    </div>
                                    <div class="teacher_info">
                                        <h5 class="">讲师：{{ $teacher['user']['username'] }}</h5>
                                        <p class=""> {{ $teacher['user']['signature']??'特邀讲师' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="card card_shadow member student_style">
                            <div class="card-header">
                                最新成员
                            </div>
                            <div class="card-body text-center pl-0 pr-0">

                                @if ($members->count())
                                    @foreach($members as $member)
                                        <img src="{{ render_cover($member['user']['avatar'], 'avatar') }}"
                                             class="rounded-circle z-depth-1"
                                             alt="Sample avatar" data-toggle="popover" data-content='<div class="popover_card">
                                        <div class="teacher_header">
                                            <img src="{{ render_cover($member['user']['avatar'], 'avatar') }}" alt="" class="teacher_avatar">
                                            <div class="teacher_info">
                                                <span class="teacher_name">{{ $member['user']['username'] }}</span>
                                                <span class="teacher_job">{{ $member['user']['signature'] ?? '学习是一种习惯' }}</span>
                                            </div>
                                        </div>
                                        <div class="teacher_fans">
                                            <div class="fans_item">
                                                <span>{{ $member->user->plans()->count() }}</span>
                                                <span class="fans_title">
                                                    在学
                                                </span>
                                            </div>
                                            <div class="fans_item">
                                                <span>{{ $member->user->followers()->count() }}</span>
                                                <span class="fans_title">
                                                    关注
                                                </span>
                                            </div>
                                            <div class="fans_item mr-0">
                                                <span>{{ $member->user->fans()->count() }}</span>
                                                <span class="fans_title">
                                                    粉丝
                                                </span>
                                            </div>
                                        </div>
                                        @auth('web')
                                        @if (auth('web')->id() != $member['user']['id'])

                                                <div class="teacher_controls">
                                                   <a href="#" class="btn btn-primary btn-sm btn-circle float-left follow" data-id="{{ $member['user']['id'] }}">
                                                               {{ $member->user->isFollow() ? '取消关注' : '关注' }}
                                                </a>
                                                 <a href="#" class="btn btn-primary btn-sm btn-circle float-right message"
                                                 data-id="{{ $member['user']['id'] }}" data-username="{{ $member['user']['username'] }}"
                                                                >私信</a>
                                                            </div>


                                            @endif
                                        @else
                                                <div class="teacher_controls">
                                                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm btn-circle float-left">关注</a>
                                                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm btn-circle float-right">私信</a>
                                                    </div>

                                        @endAuth
                                                </div>' data-html="true" data-trigger="click" data-placement="auto">
                                    @endforeach
                                @else
                                    <span class="no_data">
                                        还没有成员...
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- 发送私信的模态框 --}}
    <div class="modal modal-danger fade send_modal" id="modal_6" tabindex="-1" role="dialog" aria-labelledby="modal_6" aria-hidden="true">
        <div class="modal-dialog" role="document" style="background: #FFFFFF;border-radius: 8px;box-shadow: 0px 5px 6px 0px rgba(0,0,0,0.10);">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title float-left text-center w-100" id="modal_title_6">发送私信</h5>
                </div>
                <div class="modal-body">
                                        <span class="to_sender">
                                            TO：<span id="username"></span>
                                            <input type="hidden" id="user_id">
                                        </span>
                    <div class="py-3" style="background: #FAFAFA;font-size: 14px; color: #666;height: 226px;position: relative;overflow-y: auto;">
                        <textarea name="" id="message" cols="30" rows="10"></textarea>
                    </div>
                    <div class="notice_time">
                        {{ now() }}
                    </div>
                </div>
                <div class="modal-footer justify-content-center" style="border-top: 0;">
                    <button type="button" class="btn btn-sm btn-primary btn-circle" style="padding: 5px 26px;margin-right: 30px;" data-dismiss="modal" id="sendMessage">发送</button>
                    <button type="button" class="btn btn-sm btn-primary btn-circle" style="padding: 5px 26px;" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ '/vendor/easy-pie-chart-master/dist/jquery.easypiechart.min.js' }}"></script>
    <script src="{{  mix('/js/front/plan.js')  }}"></script>
    <script>
        // 收藏课程
        $(document).on('click', '.collect', function () {
            var favourite = $(this);
            var id = $(this).data('id');

            if (!id) {edu.alert('danger','收藏出错');return false;}

            $.ajax({
                url: '/favorites',
                method:'post',
                data:{'model_type':'course', 'model_id': id, _token:"{{ csrf_token() }}"},
                success:function(res){

                    if (res.status == '200') {

                        edu.alert('success', '操作成功');
                        if(res.data.length == 0){
                            favourite.removeClass('active');
                        } else {
                            favourite.removeClass('active').addClass('active');
                        }
                    }
                }
            })

        })

        // 关注
        $(document).on('click', '.follow', function () {

            var follow = $(this);
            var id = $(this).data('id');

            follow.removeClass('follow');

            $.ajax({
                url: '/users',
                method:'post',
                data:{follow_id: id},
                success:function(res){

                    if (res.status == '200') {

                        if(res.data.length == 0){

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

                        edu.alert('success','发送成功');

                        $('#modal_6').modal('hide');
                    }
                }
            })
        })

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
    </script>
    @yield('partScript')
@endsection
