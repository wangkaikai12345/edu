@extends('frontend.default.layouts.app')
@section('title', '课程学习')

@section('style')
    <link href="{{ asset('dist/course/css/index.css') }}" rel="stylesheet">
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
            <li class="breadcrumb-item active">{{ $course['title'] }}</li>
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
                                    <img src="{{ render_cover($course['cover'], 'course') }}" class="card-img-top" alt="">
                                    <a href="#!">
                                        <div class="mask waves-effect waves-light"></div>
                                    </a>
                                    <span class="position-absolute user-join white-text">
                                            <i class="fas fa-user-plus mr-2"></i>
                                             {{ $course['students_count'] }} 人加入学习
                                        </span>
                                    <span class="position-absolute evaluation white-text">
                                            <ul class="rating float-left mr-2">
                                                @for ($i = 0; $i < intval($course['rating']); $i++)
                                                    <li>
                                                    <i class="fa fa-star"></i>
                                                    </li>
                                                @endfor
                                            </ul>
                                            <span class="float-right">
                                                {{ $course['reviews_count'] }}人评价
                                            </span>
                                        </span>
                                </div>
                            </div>
                            <div class="col-xl-6 course-info row">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item text-left pb-0">
                                        <h5 class="card-title">
                                            {{ $course['title'] }}
                                            @if ($course->isControl())
                                                <a href="{{ config('app.manage_url').'/home/manage/information/'.$course->id }}" target="_blank" class="badge badge-light ml-3 mr-3"><i class="fas fa-cog"></i></a>
                                            @endif

                                            @if ($course->isFavourite())
                                            <i class="fas fa-heart material-tooltip-main" data-id="{{ $course['id'] }}"
                                               data-toggle="tooltip" data-placement="bottom" title="收藏课程"
                                            ></i>
                                                @else
                                                <i class="far fa-heart material-tooltip-main" data-id="{{ $course['id'] }}"
                                                   data-toggle="tooltip" data-placement="bottom" title="收藏课程"
                                                ></i>
                                                @endif

                                        </h5>
                                        <h6 class="card-description">{{ $course['subtitle'] }}</h6>
                                    </li>
                                </ul>
                                <div class="col-xl-10 pl-0">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item text-left row price-wrap">
                                            <span class="float-left col-xl-4">价&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;格</span>
                                            <span class="float-right price col-xl-8">
                                                    <span>
                                                        @if (!$plan['buy'])
                                                            不可购买
                                                        @else
                                                            {{ $plan['coin_price'] ? $plan['coin_price'].' 虚拟币' : ftoy($plan['price']).' 元' }}
                                                        @endif
                                                    </span>

                                                </span>
                                        </li>
                                        <li class="list-group-item row plan-wrap">
                                            <span class="float-left col-xl-4">教学版本</span>
                                            <div class="float-right col-xl-8">
                                                @foreach($course->plans as $value)
                                                    <a class="trigger ml-0 {{ plan_nav_active($plan['id'], $value['id']) }} text-white" href="{{ route('plans.show', [$course, $value]) }}">{{ $value['title'] }}</a>
                                                @endforeach
                                            </div>
                                        </li>
                                        <li class="list-group-item row">
                                            <span class="float-left col-xl-4">有效期限</span>
                                            <span class="col-xl-8">
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
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                        @if ($plan->isValid() && $plan['buy'])
                            @if ($plan->isMember() || (auth('web')->user() && auth('web')->user()->isAdmin()))
                                <a type="button"
                                   class="btn btn-primary btn-rounded join-study btn-sm"
                                   href="{{ route('plans.intro', [$plan->course, $plan]) }}">
                                    继续学习
                                </a>
                            @else
                                <a type="button"
                                   class="btn btn-primary btn-rounded join-study btn-sm"
                                   href="{{ route('plans.shopping', [$plan->course, $plan]) }}">
                                    加入学习
                                </a>
                            @endif
                        @else
                            <a type="button"
                               class="btn btn-primary btn-rounded join-study btn-sm">
                                已锁定
                            </a>
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
                                               aria-controls="follow-classic" aria-selected="false">目录</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link waves-light" id="contact-tab-classic"
                                               data-toggle="tab" href="#contact-classic" role="tab"
                                               aria-controls="contact-classic" aria-selected="false">笔记</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link waves-light" id="awesome-tab-classic"
                                               data-toggle="tab" href="#awesome-classic" role="tab"
                                               aria-controls="awesome-classic" aria-selected="false">评价</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content rounded-bottom" id="myClassicTabContent">
                                        <div class="tab-pane fade active show" id="profile-classic" role="tabpanel"
                                             aria-labelledby="profile-tab-classic">
                                            <div>
                                                <div class="es_piece">
                                                    <div class="piece_header">课程介绍</div>
                                                    <div class="piece_body" style="text-align: left;">
                                                        {!! $course['summary'] !!}
                                                    </div>
                                                    <div class="goals">
                                                        <ul>

                                                        </ul>
                                                    </div>
                                                    <div class="goals">
                                                        <ul>

                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="es_piece">
                                                    <div class="piece_header">课程目标</div>
                                                    <div class="piece_body" style="text-align: left;"></div>
                                                    <div class="goals">
                                                        <ul>
                                                            @if ($course['goals'])
                                                                @foreach($course['goals'] as $goal)
                                                                    <li>
                                                                        {{ $goal }}
                                                                    </li>
                                                                @endforeach
                                                            @endif
                                                        </ul>
                                                    </div>
                                                    <div class="goals">
                                                        <ul></ul>
                                                    </div>
                                                </div>
                                                <div class="es_piece">
                                                    <div class="piece_header">适应人群</div>
                                                    <div class="piece_body" style="text-align: left;"></div>
                                                    <div class="goals">
                                                        <ul>
                                                            @if ($course['audiences'])
                                                                @foreach($course['audiences'] as $audience)
                                                                    <li>
                                                                        {{ $audience }}
                                                                    </li>
                                                                @endforeach
                                                            @endif
                                                        </ul>
                                                    </div>
                                                    <div class="goals">
                                                        <ul></ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="follow-classic" role="tabpanel"
                                             aria-labelledby="follow-tab-classic">
                                            <!--Accordion wrapper-->
                                            <div class="accordion md-accordion" id="accordionEx" role="tablist"
                                                 aria-multiselectable="true">

                                                @foreach($chapters as $key => $chapter)
                                                    <div class="card">
                                                            <!-- Card header -->
                                                            <div class="card-header" role="tab" id="headingOne1">
                                                                <a data-toggle="collapse" data-parent="#accordionEx"
                                                                   href="#co{{ $chapter['id'] }}" aria-expanded="false"
                                                                   aria-controls="co{{ $chapter['id'] }}">
                                                                    <h6 class="mb-0">
                                                                        <i class="fas fa-bars mr-3"></i>第{{ $key+1 }}章：{{ $chapter['title'] }} <i
                                                                                class="fas fa-angle-down rotate-icon"></i>
                                                                    </h6>
                                                                </a>
                                                            </div>

                                                            @foreach($chapter['children'] as $k => $child )
                                                                <!-- Card body -->
                                                                    <div id="co{{ $chapter['id'] }}" class="collapse" role="tabpanel"
                                                                         aria-labelledby="headingOne1" data-parent="#accordionEx">
                                                                        <div class="card-body section-wrap">
                                                                            <h6 class="mb-0 task section">
                                                                                第{{ $k+1 }}节：{{ $child['title'] }}
                                                                            </h6>

                                                                            @foreach($child['tasks'] as $task)
                                                                                <h6 class="task">
                                                                                    <i class="fas {{ render_task_class($task['target_type']) }} mr-3"></i>{{ render_task_type($task['type']) }}：{{ $task['title'] }}
                                                                                </h6>
                                                                            @endforeach

                                                                        </div>
                                                                    </div>
                                                            @endforeach

                                                        </div>
                                                @endforeach

                                            </div>
                                            <!-- Accordion wrapper -->
                                        </div>
                                        <div class="tab-pane fade" id="contact-classic" role="tabpanel"
                                             aria-labelledby="contact-tab-classic">
                                            <div class="row">
                                                <!-- Grid column -->
                                                <div class="col-md-12">
                                                    <!-- Newsfeed -->
                                                    <div class="mdb-feed">
                                                        @if (count($notes))
                                                            @foreach($notes as $note)
                                                                <div class="news">
                                                                    <!-- Label -->
                                                                    <div class="label">
                                                                        <img src="{{ render_cover($note['user']['avatar'], 'avatar') }}"
                                                                             class="rounded-circle z-depth-1-half">
                                                                    </div>
                                                                    <!-- Excerpt -->
                                                                    <div class="excerpt">
                                                                        <!-- Brief -->
                                                                        <div class="brief">
                                                                            <a class="name">{{ $note['user']['username'] }}</a>&nbsp;添加了笔记
                                                                            <div class="date">{{ $note['created_at']->diffForHumans() }}</div>
                                                                        </div>
                                                                        <!-- Added text -->
                                                                        <div class="added-text">
                                                                            {{ $note['content'] }}
                                                                        </div>
                                                                    </div>
                                                                    <!-- Excerpt -->
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            暂无笔记
                                                        @endif
                                                        <!-- Fourth news -->


                                                        <!-- Fourth news -->
                                                    </div>
                                                    <!-- Newsfeed -->
                                                </div>
                                                <!-- Grid column -->
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="awesome-classic" role="tabpanel"
                                             aria-labelledby="awesome-tab-classic">
                                            <div class="col-md-12">
                                                <!-- Newsfeed -->
                                                <div class="mdb-feed">
                                                    @if (count($reviews))
                                                        @foreach($reviews as $review)
                                                            <!-- Fourth news -->
                                                                <div class="news">
                                                                    <!-- Label -->
                                                                    <div class="label">
                                                                        <img src="{{ render_cover($review['user']['avatar'], 'avatar') }}"
                                                                             class="rounded-circle z-depth-1-half">
                                                                    </div>
                                                                    <!-- Excerpt -->
                                                                    <div class="excerpt">
                                                                        <!-- Brief -->
                                                                        <div class="brief">
                                                                            <a class="name float-left mr-2">{{ $review['user']['username'] }}</a>&nbsp;
                                                                            <ul class="rating float-left">
                                                                                @for ($i = 0; $i < intval($review['rating']); $i++)
                                                                                    <li>
                                                                                        <i class="fa fa-star"></i>
                                                                                    </li>
                                                                                @endfor
                                                                            </ul>
                                                                            <div class="date">{{ $review['created_at']->diffForHumans() }}</div>
                                                                        </div>
                                                                        <!-- Added text -->
                                                                        <div class="added-text">
                                                                            {{ $review['content'] }}
                                                                        </div>
                                                                    </div>
                                                                    <!-- Excerpt -->
                                                                </div>
                                                        @endforeach
                                                    @else
                                                        暂无评价
                                                    @endif

                                                </div>
                                                <!-- Newsfeed -->
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
                        授课教师
                    </div>
                    <div class="card-body text-center">
                        @foreach($teachers as $teacher)

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
                    @foreach($members as $member)
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
    <script type="text/javascript" src="{{ asset('dist/course/js/index.js') }}"></script>
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
@endsection
