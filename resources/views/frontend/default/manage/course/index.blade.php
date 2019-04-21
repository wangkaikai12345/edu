@extends('frontend.default.layouts.app')

@section('style')
    <link href="{{ asset('dist/course_manage_basic/css/index.css') }}" rel="stylesheet">
    @yield('partStyle')
@endsection

@section('content')
    <main class="py-6">
        <div class="container">
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
                    <li class="breadcrumb-item">{{ $course['title'] }} <span class="ml-2">/</span></li>
                    <li class="breadcrumb-item active">
                        管理
                    </li>
                </ol>
            </nav>
            <div class="course-card">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body card-body-cascade">
                                <div class="row">
                                    <div class="col-xl-2 position-relative">
                                        <div class="view overlay">
                                            <img src="{{ render_cover($course['cover'], 'course') }}" class="card-img-top" alt="">
                                            <a href="#!">
                                                <div class="mask waves-effect waves-light"></div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-xl-5">
                                        <div class="mt-3">
                                            <h5 style="display: inline-block;">{{ $course['title'] }}</h5>
                                            <span class="ml-1 mr-1">-</span>
                                            <span class="bg-warning pt-1 pb-1 pl-2 pr-2 text-white rounded" style="font-size: 12px;">{{ render_status($course['status']) }}</span>
                                        </div>
                                        <p style="font-size: 12px;color: #666;" class="mt-3">教师：{{ $course->user->username }}</p>
                                    </div>
                                </div>

                                @if ($course['status'] == 'published')
                                    <a type="button" href="{{ route('courses.show', $course) }}" class="btn btn-primary btn-rounded join-study btn-sm" style="bottom: 15px;">返回课程主页</a>
                                @else
                                    <form action="{{ route('manage.courses.publish', $course) }}" method="post">
                                        {{ csrf_field() }}
                                        {{ method_field('PATCH') }}
                                        <input type="hidden" name="status" value="published">
                                        <button type="submit" class="btn btn-primary btn-rounded join-study btn-sm" style="bottom: 65px;">发布课程</button>
                                    </form>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wrap" style="margin: 30px 15px 0 15px;">
            <nav class="slide">
                <div id="slide-out">
                    <ul class="custom-scrollbar">
                        <!-- Logo -->
                        <li class="slide-nav-logo">
                            <div class="logo-wrapper waves-light">
                                <a href="#"><img src="https://images.rooyun.com/logo-sm-2017-12-01.png"
                                                 class="img-fluid flex-center"></a>
                            </div>
                        </li>
                        <!--/. Logo -->
                        <!--Search Form-->
                        <li class="slide-nav-form">
                            <form class="search-form" role="search">
                                <div class="form-group md-form mt-0 pt-1 waves-light">
                                    <input type="text" class="form-control" placeholder="Search">
                                </div>
                            </form>
                        </li>
                        <!--/.Search Form-->
                        <!-- Side navigation links -->
                        <li class="container pl-0">
                            <div class="card ul_box">
                                <ul class="collapsible collapsible-accordion ul_list">
                                    <li>
                                        <a class="collapsible-header waves-effect arrow-r list_title active">
                                            课程管理
                                        </a>
                                        <div class="collapsible-body">
                                            <ul>
                                                <li class="{{ active_class(if_route('manage.courses.edit'), 'list_item_active', 'list_item') }}">
                                                    <a href="{{ route('manage.courses.edit', $course) }}" class="waves-effect">基本信息</a>
                                                </li>
                                                <li class="{{ active_class(if_route('manage.courses.detail'), 'list_item_active', 'list_item') }}">
                                                    <a href="{{ route('manage.courses.detail', $course) }}" class="waves-effect">详细信息</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <div class="sidenav-bg rgba-blue-strong"></div>
                </div>
            </nav>
            @yield('rightBody')

        </div>
    </main>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('dist/course_manage_basic/js/index.js') }}"></script>
    @yield('partScript')
@endsection