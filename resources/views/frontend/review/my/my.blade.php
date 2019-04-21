@extends('frontend.review.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/my/my.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/article/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/index.css') }}">
@endsection
@section('content')
    <div class="xh_my_header bg_img">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="header_content">
                        <div class="my_information">
                            <div class="information_avatar">
                                <img src="{{ render_cover($user['avatar'], 'avatar') }}" alt="">
                            </div>
                            @if (auth('web')->id())
                                @if (auth('web')->id() != $user['id'])
                                    @if (in_array($user['id'], $user['follows']))
                                        <div class="follow_btn_content">
                                            <button class="follow btn btn-sm btn-primary m-0"
                                                    data-auth="{{ auth('web')->id() }}" data-follow="{{ $user['id'] }}">
                                                取消关注
                                            </button>
                                        </div>
                                    @else
                                        <div class="follow_btn_content">
                                            <button class="follow btn btn-sm btn-primary m-0"
                                                    data-auth="{{ auth('web')->id() }}" data-follow="{{ $user['id'] }}">
                                                关注Ta
                                            </button>
                                        </div>
                                    @endif
                                @endif
                            @else
{{--                                <div class="follow_btn_content">--}}
                                    <a href="{{ route('login') }}" class="btn btn-sm btn-primary m-0" style="left: 44.5%;top:10px">关注</a>
{{--                                </div>--}}
                            @endif
                            <div class="my_content">
                                <div class="fans_item">
                                    <div>粉丝</div>
                                    <div>{{ $user->fans()->count() }}</div>
                                </div>
                                <div class="my_message">
                                    <div class="my_name" data-toggle="tooltip" data-placement="top"
                                         title="{{ $user->signature ?? '为成长积蓄力量' }}">{{ $user['username'] }}</div>
                                    <div class="my_touxian" data-toggle="tooltip" data-placement="bottom"
                                         title="{{ $user->signature ?? '为成长积蓄力量' }}">{{ $user->profile->title ?? '一直努力的人' }}</div>

                                </div>
                                <div class="fans_item">
                                    <div>关注</div>
                                    <div>{{ $user->followers()->count() }}</div>
                                </div>
                            </div>
                            <div class="my_signature">
                                {{ $user['signature'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="xh_my_nav">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="classic-tabs">
                        <div class="nav_wrap">
                            <ul class="nav" id="myClassicTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link waves-light {{ active_class(if_query('rel', '')) }}"
                                       href="{{ route('users.show', $user) }}">个人介绍</a>
                                </li>
                                @if (!$user->isStudent())
                                    <li class="nav-item">
                                        <a class="nav-link waves-light {{ active_class(if_query('rel', 'teaching')) }}"
                                           href="{{ route('users.show', [$user, 'rel' => 'teaching']) }}"
                                           class="{{ active_class(if_query('rel', 'teaching')) }}">在教课程</a>
                                    </li>
                                @endif
                                {{--<li class="nav-item">--}}
                                {{--<a class="nav-link waves-light" id="contact-tab-classic" data-toggle="tab"--}}
                                {{--href="#online-learning-circle" role="tab" aria-controls="contact-classic"--}}
                                {{--aria-selected="false">在教学习圈</a>--}}
                                {{--</li>--}}
                                <li class="nav-item">
                                    <a class="nav-link waves-light {{ active_class(if_query('rel', 'learning')) }}"
                                       href="{{ route('users.show', [$user, 'rel' => 'learning']) }}">在学课程</a>
                                </li>

                                <?php if(config('app.model') == 'classroom'): ?>
                                    <li class="nav-item">
                                        <a class="nav-link waves-light {{ active_class(if_query('rel', 'classroom')) }}"
                                           href="{{ route('users.show', [$user, 'rel' => 'classroom']) }}">在学班级</a>
                                    </li>
                                <?php endif; ?>

                                {{--<li class="nav-item">--}}
                                {{--<a class="nav-link waves-light" id="awesome-tab-classic" data-toggle="tab"--}}
                                {{--href="#learning-circle" role="tab" aria-controls="awesome-classic"--}}
                                {{--aria-selected="false">在学学习圈</a>--}}
                                {{--</li>--}}
                                <li class="nav-item">
                                    <a class="nav-link waves-light {{ active_class(if_query('rel', 'collect')) }}"
                                       href="{{ route('users.show', [$user, 'rel' => 'collect']) }}">收藏课程</a>
                                </li>
                                <!-- 暂时关闭我的小组 -->
                                {{--<li class="nav-item">--}}
                                {{--<a class="nav-link waves-light" id="awesome-tab-classic" data-toggle="tab"--}}
                                {{--href="#join-group" role="tab" aria-controls="awesome-classic" aria-selected="false">加入小组</a>--}}
                                {{--</li>--}}
                                <li class="nav-item">
                                    <a class="nav-link waves-light focus-fans {{ active_class(if_query('rel', 'fans') || if_query('rel', 'follows')) }}"
                                       href="{{ route('users.show', [$user, 'rel' => 'follows']) }}">关注/粉丝</a>
                                </li>
                            </ul>

                        </div>
                        <!-- 关注粉丝显示按钮 -->
                        <ul class="nav second" id="myClassicTab" role="tablist"
                            style="display:{{ (if_query('rel', 'fans') || if_query('rel', 'follows')) ? 'block' : 'none' }}">
                            <li class="nav-item second">
                                <a class="nav-link waves-light {{ active_class(if_query('rel', 'follows'))}} show btn btn-primary focus-fans"
                                   href="{{ route('users.show', [$user, 'rel' => 'follows']) }}">关注</a>
                            </li>
                            <li class="nav-item second">
                                <a class="nav-link waves-light {{ active_class(if_query('rel', 'fans'))}} btn btn-primary focus-fans"
                                   href="{{ route('users.show', [$user, 'rel' => 'fans']) }}">粉丝</a>
                            </li>
                        </ul>
                        {{-- 个人介绍 --}}
                        <div class="tab-content rounded-bottom" id="myClassicTabContent">
                            <div class="tab-pane {{ active_class(if_query('rel', '')) }} show"
                                 id="personal-introduction" role="tabpanel"
                                 aria-labelledby="profile-tab-classic">
                                <div>
                                    <div class="my_introduce_content">
                                        @if ($user['about'])
                                            <div class="introduce_body" style="text-align: left;">
                                                {!! $user['about'] !!}
                                            </div>
                                        @else
                                            {{-- 暂无介绍 --}}
                                            <div class="no_introduce_body"
                                                 style="text-align: center;overflow: visible;color:#999;padding-top:80px;">
                                                暂无介绍....
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{--<div class="tab-pane {{ active_class(if_query('rel', '')) }} show" id="personal-introduction" role="tabpanel"--}}
                            {{--aria-labelledby="profile-tab-classic">--}}
                            {{--<div>--}}
                            {{--<div class="my_introduce_content">--}}
                            {{--<div class="introduce_body" style="text-align: center;">--}}
                            {{--暂无介绍....--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{-- 在教课程 --}}
                            @includeWhen(if_query('rel', 'teaching') && !$user->isStudent(), 'frontend.review.my.line_course')
                            {{-- 在教学习圈 --}}
                            {{--@include('frontend.review.my.taching_learning_circle')--}}
                            {{-- 在学课程 --}}
                            @includeWhen(if_query('rel', 'learning'), 'frontend.review.my.learning_course')

                            {{-- 在学班级 --}}
                            @includeWhen(if_query('rel', 'classroom'), 'frontend.review.my.learning_classroom')

                            {{--@include('frontend.review.my.learning_course')--}}
                            {{-- 在学学习圈 --}}
                            {{--@include('frontend.review.my.learning_circle')--}}
                            {{-- 收藏课程 --}}
                            @includeWhen(if_query('rel', 'collect'), 'frontend.review.my.collection_course')
                            {{--@include('frontend.review.my.collection_course')--}}
                            {{-- 关注/粉丝 --}}
                            @includeWhen((if_query('rel', 'fans') || if_query('rel', 'follows')), 'frontend.review.my.focus_fans')
                            {{--@include('frontend.review.my.focus_fans')--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ mix('/js/front/my/my.js') }}"></script>
    <script>
        $('[data-toggle="tooltip"]').tooltip()
        $('.follow').click(function () {

            var that = $(this);

            var auth = $(this).data('auth');

            if (!auth) {
                alert('请登陆！');
                location.href = '/login';
                return false;
            }
            var follow = $(this).data('follow');
            if (!follow) {
                alert('请选择');
                return false;
            }

            $.ajax({
                url: '/users',
                type: 'post',
                data: {
                    'follow_id': follow,
                },
                success: function (res) {

                    if (res.status == '200') {
                        if (res.data.length) {
                            that.html('取消关注')
                        } else {
                            that.html('关注')
                        }
                    }
                }
            })
        })


    </script>
@endsection
