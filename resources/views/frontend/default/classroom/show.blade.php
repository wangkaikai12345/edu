@extends('frontend.default.layouts.app')
@section('title', '班级学习')

@section('style')
    <link href="{{ asset('dist/classroom/css/index.css') }}" rel="stylesheet">
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a class="" href="/">首页</a>
                <span class="ml-2">/</span>
            </li>
            <li class="breadcrumb-item">
                <a class="" href="{{ route('classrooms.index') }}">班级中心</a>
                <span class="ml-2">/</span>

            </li>
            <li class="breadcrumb-item active">{{ $classroom['title'] }}</li>
        </ol>
    </nav>
    <div class="course-card">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body card-body-cascade">
                        <div class="row">
                            <div class="col-xl-5 position-relative">
                                <div class="view overlay">
                                    <img src="{{ render_cover($classroom['cover'], 'classroom') }}" class="card-img-top" alt="">
                                    <a href="#!">
                                        <div class="mask waves-effect waves-light"></div>
                                    </a>
                                    <span class="position-absolute user-join white-text">
                                            <i class="fas fa-user-plus mr-2"></i>
                                             {{ $classroom['members_count'] }} 人加入学习
                                        </span>
                                    <span class="position-absolute evaluation white-text">

                                        </span>
                                </div>
                            </div>
                            <div class="col-xl-6 course-info row">
                                <ul class="list-group list-group-flush" style="height: 170px;">
                                    <li class="list-group-item text-left pb-0">
                                        <h5 class="card-title">
                                            {{ $classroom['title'] }}
                                            @if ($classroom->isControl())
                                                <a href="{{ config('app.manage_url').'/home/manage/class/'.$classroom->id }}" class="badge badge-light ml-3 mr-3"><i class="fas fa-cog"></i></a>
                                            @endif
                                        </h5>
                                        {{--<h6 class="card-description">--}}
                                        {{--</h6>--}}
                                    </li>

                                </ul>
                                <div class="col-xl-12 pl-0 row" style="margin-top: -50px;color: #666666;">
                                    <div class="col-xl-4 col-md-6 col-sm-6 text-center mb-2">
                                        <i class="fas fa-users" style="font-size: 35px;"></i>

                                        <span class="font-small d-block mt-3 text-center">学员（{{ $classroom['members_count'] }}）</span>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-sm-6 text-center mb-2">
                                        <i class="fas fa-copy" style="font-size: 35px;"></i>

                                        <span class="font-small d-block mt-3 text-center">课程（{{ $classroom['courses_count'] }}）</span>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-sm-6 text-center mb-2">
                                        <i class="fas fa-yen-sign" style="font-size: 35px;"></i>

                                        <span class="font-small d-block mt-3 text-center text-danger"> {{ $classroom['price'] ? ftoy($classroom['price']).'元' : '免费' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                         @if (!$classroom->isControl())
                            @if ($classroom->isMember())
                                <button type="button" class="btn btn-danger btn-rounded join-study btn-sm">已加入</button>
                             @else
                                <a type="button"
                                   class="btn btn-primary btn-rounded join-study btn-sm"
                                   href="{{ route('classrooms.shopping', $classroom) }}"
                                >加入学习</a>
                            @endif
                         @endif


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
                                                <a class="nav-link  waves-light active show"
                                                   id="profile-tab-classic" data-toggle="tab" href="#profile-classic"
                                                   role="tab" aria-controls="profile-classic"
                                                   aria-selected="true">介绍</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link waves-light" id="follow-tab-classic"
                                                   data-toggle="tab" href="#follow-classic" role="tab"
                                                   aria-controls="follow-classic" aria-selected="false">课程</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content rounded-bottom" id="myClassicTabContent">
                                            <div class="tab-pane fade active show" id="profile-classic" role="tabpanel"
                                                 aria-labelledby="profile-tab-classic">
                                                <div>
                                                    <div class="es_piece">
                                                        <div class="piece_header">课程介绍</div>
                                                        <div class="piece_body" style="text-align: left;">
                                                            {{ $classroom->description }}
                                                        </div>
                                                        <div class="goals">
                                                            <ul></ul>
                                                        </div>
                                                        <div class="goals">
                                                            <ul></ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="follow-classic" role="tabpanel"
                                                 aria-labelledby="follow-tab-classic">
                                                <div class="classic-tabs pl-3 pr-3">
                                                    <div class="row mb-3 mt-3">
                                                        <div class="col-xl-12 p-0">
                                                            <!--Accordion wrapper-->
                                                            <div class="accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
                                                                @foreach($classroom->plans as $plan)
                                                                    <div class="card">
                                                                        <div class="card-body text-center" role="tab" id="headingOne1">
                                                                            <a data-toggle="collapse" data-parent="#accordionEx" href="#collapseOne1" aria-expanded="true"
                                                                               aria-controls="collapseOne1" class="row">
                                                                                <div class="col-xl-3 col-md-4">
                                                                                    <img class="card-img-top" src="{{ render_cover($plan->course->cover, 'course') }}"
                                                                                         alt="Card image cap">
                                                                                </div>
                                                                                <div class="col-xl-6 text-left col-md-5">
                                                                                    <h6 class="font-weight-bold mb-2 text-truncate mt-3">{{ $plan->course_title }}</h6>
                                                                                    <p class="text-uppercase font-small" style="color: #666;">课程原价：{{ ftoy($plan->price) }}元</p>
                                                                                    <p class="text-uppercase font-small" style="color: #666;">教学版本：{{ $plan->title }}</p>
                                                                                </div>
                                                                                {{--@if (!$classroom->isControl())--}}

                                                                                <div class="col-xl-3 text-center col-md-3"
                                                                                     style="flex-direction: column-reverse;display: flex;">
                                                                                    @if ($classroom->isMember() || $classroom->isControl())
                                                                                    <a type="button"
                                                                                        class="btn align-items-center btn-primary btn-rounded btn-sm waves-effect waves-light mb-5"
                                                                                       href="{{ route('plans.intro', [ $plan->course, $plan ]) }}"
                                                                                       target="_blank"
                                                                                    >查看教学版本
                                                                                    </a>
                                                                                    @else
                                                                                        <button type="button" class="btn btn-primary btn-rounded join-study btn-sm">加入学习</button>
                                                                                    @endif
                                                                                </div>

                                                                                {{--@endif--}}

                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                @endforeach


                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
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
                        班主任
                    </div>
                    <div class="card-body text-center">
                        <div class="avatar mx-auto">
                            <a href="{{ route('users.show', $classroom->user) }}" target="_blank">
                                <img src="{{ render_cover($classroom->user['avatar'], 'avatar') }}" class="rounded-circle z-depth-1"
                                     alt="Sample avatar">
                            </a>
                        </div>
                        <h5 class="font-weight-bold mt-4 mb-3">{{ $classroom->user['username'] }}</h5>
                        <p class="text-uppercase">特邀讲师</p>
                        <p class="grey-text">
                            {{ $classroom->user['signature'] }}
                        </p>
                        <ul class="list-unstyled mb-0">
                            @auth('web')
                                @if (auth('web')->id() != $classroom->user['id'])

                                    <a class="like p-2 fa-lg tw-ic material-tooltip-main"
                                       data-toggle="tooltip" data-placement="bottom" title="关注他／她"
                                    >
                                        <i class="{{ $classroom->user->isFollow() ? 'fas' : 'far' }} fa-star blue-text" data-id="{{ $classroom->user['id'] }}"></i>
                                    </a>

                                    <a class="p-2 fa-lg tw-ic material-tooltip-main"
                                       data-toggle="tooltip" data-placement="bottom" title="发送私信"
                                    >
                                        <i class="far fa-comment blue-text" data-id="{{ $classroom->user['id'] }}" data-username="{{ $classroom->user['username'] }}"></i>
                                    </a>
                                @endif
                                @else
                                    <a class="like p-2 fa-lg tw-ic material-tooltip-main" href="{{ route('login') }}"
                                       data-toggle="tooltip" data-placement="bottom" title="关注他／她"
                                    >
                                        <i class="far fa-star blue-text"></i>
                                    </a>

                                    <a class="p-2 fa-lg tw-ic material-tooltip-main" href="{{ route('login') }}"
                                       data-toggle="tooltip" data-placement="bottom" title="发送私信"
                                    >
                                        <i class="far fa-comment blue-text"></i>
                                    </a>
                                    @endAuth
                        </ul>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        班级教师
                    </div>
                    <div class="card-body text-center">
                      @foreach($classroom->teachers as $teacher)

                            <div class="avatar mx-auto">
                                <a href="{{ route('users.show', $teacher->user) }}" target="_blank">
                                <img src="{{ render_cover($teacher['user']['avatar'], 'avatar') }}" class="rounded-circle z-depth-1"
                                     alt="Sample avatar">
                                </a>
                            </div>
                            <h5 class="font-weight-bold mt-4 mb-3">{{ $teacher['user']['username'] }}</h5>
                            <p class="text-uppercase">特邀讲师</p>
                            <p class="grey-text">
                                {{ $teacher['user']['signature'] }}
                            </p>
                            <ul class="list-unstyled mb-0">
                                @auth('web')
                                    @if (auth('web')->id() != $teacher['user']['id'])

                                    <a class="like p-2 fa-lg tw-ic material-tooltip-main"
                                       data-toggle="tooltip" data-placement="bottom" title="关注他／她"
                                    >
                                        <i class="{{ $teacher->user->isFollow() ? 'fas' : 'far' }} fa-star blue-text" data-id="{{ $teacher['user']['id'] }}"></i>
                                    </a>

                                    <a class="p-2 fa-lg tw-ic material-tooltip-main"
                                       data-toggle="tooltip" data-placement="bottom" title="发送私信"
                                    >
                                        <i class="far fa-comment blue-text" data-id="{{ $teacher['user']['id'] }}" data-username="{{ $teacher['user']['username'] }}"></i>
                                    </a>
                                    @endif
                                @else
                                    <a class="like p-2 fa-lg tw-ic material-tooltip-main" href="{{ route('login') }}"
                                       data-toggle="tooltip" data-placement="bottom" title="关注他／她"
                                    >
                                        <i class="far fa-star blue-text"></i>
                                    </a>

                                    <a class="p-2 fa-lg tw-ic material-tooltip-main" href="{{ route('login') }}"
                                       data-toggle="tooltip" data-placement="bottom" title="发送私信"
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
                        @foreach($classroom->members as $member)
                        <!-- Title -->
                        <div class="member"
                                 data-toggle="popover"
                                 data-content='<div class="popover-content">
                                    <div class="avatar mx-auto">
                                        <img src="{{ render_cover($member['user']['avatar'], 'avatar') }}" class="rounded-circle z-depth-1" alt="">
                                    </div>
                                    <h5 class="font-weight-bold mt-4 mb-3">{{ $member['user']['username'] }}</h5>
                                    <p class="grey-text">{{ $member['user']['signature'] ?? '学习是一种习惯' }}</p>
                                    <ul class="list-unstyled mb-0">
                                          @auth('web')
                                               @if (auth('web')->id() != $member['user']['id'])

                                                     <a class="like p-2 fa-lg tw-ic material-tooltip-main"
                                                        data-toggle="tooltip" data-placement="bottom" title="关注他／她"
                                                     >
                                                         <i class="{{ $member->user->isFollow() ? 'fas' : 'far' }} fa-star blue-text" data-id="{{ $member['user']['id'] }}"></i>
                                                </a>

                                                <a class="p-2 fa-lg tw-ic material-tooltip-main"
                                                   data-toggle="tooltip" data-placement="bottom" title="发送私信"
                                                >
                                                    <i class="far fa-comment blue-text" data-id="{{ $member['user']['id'] }}" data-username="{{ $member['user']['username'] }}"></i>
                                                </a>
                                                @endif
                                          @else
                                                     <a class="like p-2 fa-lg tw-ic material-tooltip-main" href="{{ route('login') }}"
                                                   data-toggle="tooltip" data-placement="bottom" title="关注他／她"
                                                >
                                                    <i class="far fa-star blue-text"></i>
                                                </a>

                                                <a class="p-2 fa-lg tw-ic material-tooltip-main" href="{{ route('login') }}"
                                                   data-toggle="tooltip" data-placement="bottom" title="发送私信"
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
                                <img src="{{ render_cover($member['user']['avatar'], 'avatar') }}" class="rounded-circle z-depth-1"
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
    <script type="text/javascript" src="{{ asset('dist/classroom/js/index.js') }}"></script>
    <script>
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
@endsection
