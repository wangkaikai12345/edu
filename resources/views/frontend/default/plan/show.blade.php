@extends('frontend.default.layouts.app')
@section('title', '版本学习')
@section('style')
    <link href="{{ asset('dist/plan/css/index.css') }}" rel="stylesheet">
    <style>
        .tab {
            padding: 2rem 1rem 1rem;
        }
    </style>

@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a class="" href="/">首页</a>
                <span class="ml-2">/</span>
            </li>
            <li class="breadcrumb-item">
                <a class="" href="{{ route('courses.index') }}">课程中心</a>
                <span class="ml-2">/</span>
            </li>
            <li class="breadcrumb-item">
                <a class="" href="{{ route('courses.show', $course) }}">{{ $course['title'] }}</a>
                <span class="ml-2">/</span>
            </li>
            <li class="breadcrumb-item active">{{ $plan['title'] }}</li>
        </ol>
    </nav>
    <div class="course-card">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body card-body-cascade">
                        <div class="row">
                            <div class="col-xl-12 row">
                                <h5 class="card-title mt-3 col-xl-9 pl-4">
                                    {{ $plan['title'] }}
                                </h5>
                                <div class="col-xl-3 right-icon text-right">
                                    @if($control)
                                        <a href="{{ config('app.manage_url').'/home/manage/information/'.$plan->course_id.'/'.$plan->id }}" target="_blank" class="badge badge-light ml-3 mr-3"><i
                                                    class="fas fa-cog"></i></a>
                                    @endif
                                    <span class="people-num mr-5">
                                       <i class="fas fa-user-plus mr-1"></i>{{ $plan['students_count'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-3 position-relative">
                                <div class="view overlay">
                                    <div class="text-center mt-4">
                                        <span class="min-chart" id="chart-sales"
                                              data-percent="{{ $plan_member && $plan['tasks_count'] ? ($plan_member['learned_count']/$plan['tasks_count']) * 100 :0 }}">
                                            <h6><i class="fas fa-graduation-cap mr-2"></i>学习进度</h6>
                                            <span class="percent"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 text-center">
                                <h6 class="ls-three"><i class="fas fa-check-square mr-2"></i>已完成</h6>
                                <div>{{ $plan_member ? $plan_member['learned_count'] : 0}} / {{ $plan['tasks_count'] }}
                                    任务
                                </div>
                            </div>
                            <div class="col-xl-2 text-center">
                                <h6 class="ls-three"><i class="fas fa-calendar-alt mr-2"></i>学习有效期</h6>
                                <div>
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
                                </div>
                            </div>
                            <div class="col-xl-2 text-center">
                                <h6 class="ls-three"><i class="fas fa-tasks mr-2"></i>学习任务</h6>
                                <div>
                                    {{ $task['title'] }}
                                </div>
                            </div>
                            <div class="col-xl-3 text-center">
                                @if($control)
                                    <a type="button"
                                       href="{{ route('tasks.show', $task) }}"
                                       class="btn btn-primary btn-rounded join-study btn-sm">我的课程
                                    </a>
                                @else
                                    @if ($plan['tasks_count'])

                                    <a type="button" class="btn btn-primary btn-rounded join-study btn-sm"
                                       href="{{ route('tasks.show', $task) }}">继续学习</a>
                                        @else
                                        <a type="button" class="btn btn-primary btn-rounded join-study btn-sm"
                                           href="#">暂无任务</a>
                                    @endif
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="course-tabs">
        <div class="row">
            <div class="col-xl-9" style="padding-right: 0px;">
                <div class="card">
                    <div class="card-body card-body-cascade">
                        <div class="row">
                            <div class="col-xl-12">
                                <!-- Classic tabs -->
                                <div class="classic-tabs">
                                    <ul class="nav" id="myClassicTab" role="tablist">
                                        <li class="nav-item ml-0">
                                            <a class="nav-link  waves-light {{ plan_detail_active('intro') }}"
                                               href="{{ route('plans.intro', [$plan['course_id'], $plan['id']]) }}"
                                               role="tab"
                                            >介绍</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link waves-light {{ plan_detail_active('chapter') }}"
                                               href="{{ route('plans.chapter', [$plan['course_id'], $plan['id']]) }}"
                                               role="tab"
                                            >目录</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link waves-light {{ plan_detail_active('note') }}"
                                               href="{{ route('plans.note', [$plan['course_id'], $plan['id']]) }}"
                                               role="tab">笔记</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link waves-light {{ plan_detail_active('topic') }}"
                                               href="{{ route('plans.topic', [$plan['course_id'], $plan['id']]) }}"
                                               role="tab">话题</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link waves-light {{ plan_detail_active('review') }}"
                                               href="{{ route('plans.review', [$plan['course_id'], $plan['id']]) }}"
                                               role="tab">评价</a>
                                        </li>
                                    </ul>
                                    <div class="rounded-bottom tab">

                                        @yield('leftBody')
                                    </div>
                                </div>
                                <!-- Classic tabs -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3" style="padding-left: 20px;">
                <div class="card">
                    <div class="card-header">
                        授课教师

                    </div>
                    <div class="card-body text-center">

                        @foreach($teachers as $teacher)
                            <div class="avatar mx-auto">
                                <img src="{{ render_cover($teacher['user']['avatar'], 'avatar') }}"
                                     class="rounded-circle z-depth-1"
                                     alt="Sample avatar">
                            </div>
                            <h5 class="font-weight-bold mt-4 mb-3">{{ $teacher['user']['username'] }}</h5>
                            <p class="text-uppercase blue-text">特邀讲师</p>
                            <p class="grey-text">
                                {{ $teacher['user']['signature'] }}
                            </p>
                            <ul class="list-unstyled mb-0">
                                @auth('web')
                                    @if (auth('web')->id() != $teacher['user']['id'])

                                        <a class="like p-2 fa-lg tw-ic material-tooltip-main"
                                           data-toggle="tooltip" data-placement="auto" title="关注他／她"
                                        >
                                            <i class="{{ $teacher->user->isFollow() ? 'fas' : 'far' }} fa-star blue-text" data-id="{{ $teacher['user']['id'] }}"></i>
                                        </a>

                                        <a class="p-2 fa-lg tw-ic material-tooltip-main"
                                           data-toggle="tooltip" data-placement="auto" title="发送私信"
                                        >
                                            <i class="far fa-comment blue-text" data-id="{{ $teacher['user']['id'] }}" data-username="{{ $teacher['user']['username'] }}"></i>
                                        </a>
                                    @endif
                                    @else
                                        <a class="like p-2 fa-lg tw-ic material-tooltip-main" href="{{ route('login') }}"
                                           data-toggle="tooltip" data-placement="auto" title="关注他／她"
                                        >
                                            <i class="far fa-star blue-text"></i>
                                        </a>

                                        <a class="p-2 fa-lg tw-ic material-tooltip-main" href="{{ route('login') }}"
                                           data-toggle="tooltip" data-placement="auto" title="发送私信"
                                        >
                                            <i class="far fa-comment blue-text"></i>
                                        </a>
                                @endAuth
                            </ul>
                            <hr>
                        @endforeach

                    </div>
                </div>
                <div class="card mt-3">
                    <!-- Card content -->
                    <div class="card-header">
                        最新成员
                    </div>
                    <div class="card-body">
                    @foreach($members as $member)
                        <!-- Title -->

                            <div class="member"
                                 data-toggle="popover"
                                 data-content='<div class="popover-content">
                                    <div class="avatar mx-auto">
                                        <img src="{{ render_cover($member['user']['avatar'], 'avatar') }}" class="rounded-circle z-depth-1" alt="">
                                    </div>
                                    <h5 class="font-weight-bold mt-4 mb-3">{{ $member['user']['username'] }}</h5>
                                    <p class="grey-text">{{ $member['user']['signature'] }}</p>
                                    <ul class="list-unstyled mb-0">
                                        @auth('web')
                                         @if (auth('web')->id() != $member['user']['id'])

                                                 <a class="like p-2 fa-lg tw-ic material-tooltip-main"
                                                    data-toggle="tooltip" data-placement="auto" title="关注他／她"
                                                 >
                                                     <i class="{{ $member->user->isFollow() ? 'fas' : 'far' }} fa-star blue-text" data-id="{{ $member['user']['id'] }}"></i>
                                                        </a>

                                                        <a class="p-2 fa-lg tw-ic material-tooltip-main"
                                                           data-toggle="tooltip" data-placement="auto" title="发送私信"
                                                        >
                                                            <i class="far fa-comment blue-text" data-id="{{ $member['user']['id'] }}" data-username="{{ $member['user']['username'] }}"></i>
                                                        </a>
                                                        @endif
                                         @else
                                         <a class="like p-2 fa-lg tw-ic material-tooltip-main" href="{{ route('login') }}"
                                                   data-toggle="tooltip" data-placement="auto" title="关注他／她"
                                                >
                                                    <i class="far fa-star blue-text"></i>
                                                </a>

                                                <a class="p-2 fa-lg tw-ic material-tooltip-main" href="{{ route('login') }}"
                                                   data-toggle="tooltip" data-placement="auto" title="发送私信"
                                                >
                                                    <i class="far fa-comment blue-text"></i>
                                                </a>
                                          @endAuth
                                    </ul>
                                    <div class="mt-5 row user-info">
                                        <div class="col-xl-4">
                                            <div>在学</div>
                                            <i class="far fa-eye mr-1"></i>{{ $member->user->plans->count() }}
                                        </div>
                                        <div class="col-xl-4">
                                            <div>关注</div>
                                            <i class="fas fa-star mr-1"></i>{{ $member->user->followers->count() }}
                                        </div>
                                        <div class="col-xl-4">
                                            <div>粉丝</div>
                                            <i class="fas fa-users mr-1"></i>{{ $member->user->fans->count() }}
                                        </div>
                                    </div>
                                </div>'>
                                <img src="{{ render_cover($member['user']['avatar'], 'avatar') }}"
                                     class="rounded-circle z-depth-1"
                                     alt="">
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- 发送私信的模态框 --}}
    <div class="modal fade right" id="basicExampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-side modal-bottom-right view" role="document">
            <div class="mask flex-center rgba-white-strong d-none" id="sendNewMessageLoading" style="z-index: 9999999;">
                <div class="preloader-wrapper active">
                    <div class="spinner-layer spinner-blue-only">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
                        </div>
                        <div class="gap-patch">
                            <div class="circle"></div>
                        </div>
                        <div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">发送私信</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" value="" disabled class="form-control" id="username">
                    <input type="hidden" id="user_id">
                    <div class="form-group shadow-textarea mt-4">
                        <textarea class="form-control z-depth-1" id="message" rows="5" placeholder="请输入您要发送的消息..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-sm btn-primary" id="sendMessage">发送</button>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('dist/plan/js/index.js') }}"></script>
    <script>
        // 收藏课程
        $(document).on('click', '.fa-heart', function () {
            var favourite = $(this);
            var id = $(this).data('id');

            edu.ajax({
                url: '/favorites',
                method:'post',
                data:{'model_type':'course', 'model_id': id},
                callback:function(res){

                    if (res.status == 'success') {
                        if(res.data.length == 0){
                            favourite.removeClass('fas').addClass('far');
                        } else {
                            favourite.removeClass('far').addClass('fas');
                        }
                    }

                    if (res.status == 'html') {
                        edu.toastr.error('请登陆...');
                        location.href = '/login';
                    }

                },
                elm: '.fa-heart',
            })
            return false;
        })

        // 关注
        $(document).on('click', '.fa-star', function () {
            var follow = $(this);
            var id = $(this).data('id');

            edu.ajax({
                url: '/users',
                method:'post',
                data:{follow_id: id},
                callback:function(res){

                    if (res.status == 'success') {
                        if(res.data.length == 0){
                            follow.removeClass('fas').addClass('far');
                        } else {
                            follow.removeClass('far').addClass('fas');
                        }
                    }
                },
                elm: '.fa-star',
            })
        })

        // 发送私信的模态框
        $(document).on('click', '.fa-comment', function () {
            var id = $(this).data('id');

            var username = $(this).data('username');

            $('#username').val(username);

            $('#user_id').val(id);

            $('#basicExampleModal').modal('show');
        })

        // 发送私信
        $('#sendMessage').click(function(){

            // 数据验证
            var id = $('#user_id').val();

            var message = $('#message').val();

            if (!id || !message) {
                edu.toastr.error('请完善私信信息');
                return false;
            }

            edu.ajax({
                url: '/my/message',
                method:'post',
                data:{user_id: id, message:message},
                callback:function(res){

                    if (res.status == 'success') {
                        // 清空信息
                        $('#username').val('');

                        $('#user_id').val('');

                        $('#message').val('');

                        $('#basicExampleModal').modal('hide');
                    }
                },
                elm: '#sendMessage',
            })
        })

    </script>
    @yield('partScript')
@endsection
