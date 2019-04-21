@extends('frontend.default.layouts.app')

@section('style')
    @yield('partStyle')
@endsection

@section('content')
    <div class="wrap">
        <nav class="slide">
            <div id="slide-out">
                <ul class="custom-scrollbar">
                    <!-- Side navigation links -->
                    <li class="container">
                        <div class="card ul_box">
                            @if (!auth('web')->user()->isStudent())
                            {{--<ul class="collapsible collapsible-accordion ul_list">--}}
                                {{--<li>--}}
                                    {{--<a class="collapsible-header waves-effect arrow-r list_title active">--}}
                                        {{--<i class="fas fa-chevron-right"></i>我的教学--}}
                                        {{--<i class="fas fa-angle-down rotate-icon"></i>--}}
                                    {{--</a>--}}
                                    {{--<div class="collapsible-body">--}}
                                        {{--<ul>--}}
                                            {{--<li class="{{ user_active('teach_course') }}">--}}
                                                {{--<a href="{{ route('users.teach_course') }}" class="waves-effect">在教课程</a>--}}
                                            {{--</li>--}}
                                            {{--<li class="{{ user_active('teach_class') }}">--}}
                                                {{--<a href="{{ route('users.teach_class') }}" class="waves-effect">在教班级</a>--}}
                                            {{--</li>--}}
                                        {{--</ul>--}}
                                    {{--</div>--}}
                                {{--</li>--}}
                            {{--</ul>--}}
                            @endif
                            <ul class="collapsible collapsible-accordion ul_list">
                                <li>
                                    <a class="collapsible-header waves-effect arrow-r list_title active">
                                        <i class="fas fa-chevron-right"></i>我的学习
                                        <i class="fas fa-angle-down rotate-icon"></i>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul>
                                            <li class="{{ user_active('courses') }}">
                                                <a href="{{ route('users.courses') }}" class="waves-effect">我的课程</a>
                                            </li>
                                            <li class="{{ user_active('notes') }}">
                                                <a href="{{ route('users.notes') }}" class="waves-effect">我的笔记</a>
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
@endsection

@section('script')
    @yield('partScript')
@endsection
