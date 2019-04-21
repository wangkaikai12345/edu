<link rel="stylesheet" href="{{ mix('/css/front/header/index.css') }}">

<div class="zh_header bg-white">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-secondary bg-white rounded p-0">
            <a class="navbar-brand" href="{{ $index->site()['domain'] }}">
                <img src="{{ http_format($index->site()['logo'])}}" alt="" height="50" width="150">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_example_2" aria-controls="navbar_example_2" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar_example_2" style="height: 100%;">
                <ul class="navbar-nav header_center">
                    @foreach($index->header_nav() as $header)

                        @if ($header->status)
                            <li class="nav-item">
                                <a class="nav-link"
                                   href="{{ $header['link'] }}"
                                   target=" {{ $header->target ?  '_blank' : ''}}"
                                >{{ $header['name'] }}</a>
                            </li>
                        @endif

                    @endforeach
                    @auth('web')
                        <li class="nav-item d-lg-none {{ auth('web')->user()->unreadNotifications->count() ? 'new_message' : '' }}" data-route="{{ route('users.notification') }}">
                            <a class="nav-link">
                                通知
                            </a>
                        </li>
                        <li class="nav-item d-lg-none {{ auth('web')->user()->messages->count() ? 'new_message' : '' }}" data-route="{{ route('users.message') }}">
                            <a class="nav-link">
                                私信
                            </a>
                        </li>
                        @if(auth('web')->user()->isSuperAdmin() || auth('web')->user()->isAdmin())
                            <li class="nav-item d-lg-none">
                                <a class="nav-link" href="{{ route('backstage.index') }}">后台管理</a>
                            </li>
                        @endif
                        <li class="nav-item d-lg-none">
                            <a class="nav-link" href="{{ route('users.order') }}">账户中心</a>
                        </li>
                        <li class="nav-item d-lg-none">
                            <a class="nav-link" href="{{ route('users.edit', auth('web')->user()) }}">个人设置</a>
                        </li>
                        @if(!auth('web')->user()->isStudent())
                            <li class="nav-item d-lg-none">
                                <a class="nav-link" href="{{ route('manage.users.teach_course') }}">我的教学</a>
                            </li>
                        @endif
                        <li class="nav-item d-lg-none">
                            <a class="nav-link" href="{{ route('users.courses') }}">我的学习</a>
                        </li>
                        <li class="nav-item d-lg-none">
                            <a class="nav-link" href="{{ route('users.show', auth('web')->user()) }}">我的主页</a>
                        </li>
                        <li class="nav-item d-lg-none">
                            <a class="nav-link" href="{{ route('logout') }}">退出登陆</a>
                        </li>
                    @endauth
                </ul>
                <ul class="navbar-nav ml-auto right_controls" style="height: 100%;">
                    @auth('web')
                        {{--<li class="nav-item d-lg-block notice {{ (auth('web')->user()->unreadNotifications->count() || auth('web')->user()->messages->count()) ? 'new_message' : '' }}">--}}
                            {{--<i class="iconfont">--}}
                                {{--&#xe63f;--}}
                            {{--</i>--}}
                            {{--<div class="notice_wrap">--}}
                                {{--<div class="notice_nav">--}}
                                    {{--<div class="nav_item active {{ auth('web')->user()->unreadNotifications->count() ? 'new_message' : '' }}" data-route="{{ route('users.notification') }}">--}}
                                        {{--通知--}}
                                    {{--</div>--}}
                                    {{--<div class="nav_item {{ auth('web')->user()->messages->count() ? 'new_message' : '' }}" data-route="{{ route('users.message') }}">--}}
                                        {{--私信--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="notice_lists active">--}}
                                    {{--@if (auth('web')->user()->unreadNotifications->count())--}}
                                        {{--@foreach(auth('web')->user()->unreadNotifications as $key => $notification)--}}
                                        {{--<div class="notice_item">--}}
                                            {{--<i class="iconfont float-left" style="line-height: 20px;font-size: 15px;margin-right: 10px;">--}}
                                                {{--&#xe646;--}}
                                            {{--</i>--}}
                                            {{--<div class="notice_content">--}}
                                                {{--<div class="notice_name">--}}
                                                   {{--{{ $notification['data']['title'] }}--}}
                                                {{--</div>--}}
                                                {{--<div class="notice_p">--}}
                                                    {{--{!! $notification['data']['content'] !!}--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="line"></div>--}}

                                        {{--@if ($key == 3)--}}
                                            {{--@break--}}
                                        {{--@endif--}}
                                        {{--@endforeach--}}
                                    {{--@else--}}
                                        {{--<span class="no_data">--}}
                                            {{--<img src="/imgs/notice_no_data.png" alt="">--}}
                                            {{--~ 空空如也 ~--}}
                                        {{--</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}

                                {{--<div class="notice_lists message">--}}
                                    {{--@if (auth('web')->user()->messages->count())--}}
                                        {{--@foreach(auth('web')->user()->messages->unique('uuid')->sortByDesc('created_at') as $key => $message)--}}
                                            {{--<div class="notice_item">--}}
                                                {{--<img src="{{ render_cover($message->sender->avatar, 'avatar') }}" alt="">--}}
                                                {{--<div class="notice_content">--}}
                                                    {{--<div class="notice_name">--}}
                                                       {{--{{ $message->sender->username }}--}}
                                                    {{--</div>--}}
                                                    {{--<div class="notice_p">--}}
                                                       {{--{{ $message->body }}--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="line"></div>--}}
                                            {{--@if ($key == 3)--}}
                                                {{--@break--}}
                                            {{--@endif--}}
                                        {{--@endforeach--}}
                                    {{--@else--}}
                                        {{--<span class="no_data">--}}
                                            {{--<img src="/imgs/notice_no_data.png" alt="">--}}
                                            {{--~ 空空如也 ~--}}
                                        {{--</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                                {{--<hr>--}}
                                {{--<a href="{{ route('users.notification') }}" class="all_notice" id="all">查看全部</a>--}}
                            {{--</div>--}}
                        {{--</li>--}}
                        <li class="nav-item dropdown">
                            <div class="user_avatar" data-toggle="dropdown" role="button">
                                <img src="{{ render_cover(auth('web')->user()['avatar'], 'avatar') ?? '/imgs/avatar.png' }}" alt="">
                            </div>
                            <a class="nav-link nav-link-icon" href="#" id="navbar_2_dropdown_3" aria-haspopup="true" aria-expanded="true"><i class="fas fa-user"></i></a>
                            <div class="dropdown-menu dropdown-menu-right user_info d-lg-block">
                                <h6 class="dropdown-header">{{ auth('web')->user()['username'] }}</h6>
                                <ul>
                                    @if(auth('web')->user()->isSuperAdmin() || auth('web')->user()->isAdmin())
                                        <li>
                                            <a href="{{ route('backstage.index') }}">后台管理</a>
                                        </li>
                                    @endif
                                        {{--<li>--}}
                                            {{--<a href="{{ route('users.order') }}">账户中心</a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="{{ route('users.edit', auth('web')->user()) }}">个人设置</a>--}}
                                        {{--</li>--}}
                                    @if(!auth('web')->user()->isStudent())
                                            <li>
                                                <a href="{{ route('manage.users.teach_course') }}">我的教学</a>
                                            </li>
                                    @endif
                                    {{--<li>--}}
                                        {{--<a href="{{ route('users.courses') }}">我的学习</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="{{ route('users.show', auth('web')->user()) }}">我的主页</a>--}}
                                    {{--</li>--}}
                                    <li>
                                        <a href="{{ route('logout') }}">退出登陆</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-white btn-sm btn-outline-dark btn-circle font-weight-400 mr-3">登录</a>
                        </li>
                        <li class="nav-item mr-3">
                            <a href="{{ route('register') }}" class="btn btn-white btn-sm btn-outline-dark btn-circle font-weight-400">注册</a>
                        </li>
                    @endauth

                </ul>
            </div>
        </nav>
    </div>
</div>
