@extends('frontend.default.layouts.app')

@section('style')
    @yield('partStyle')
@endsection

@section('content')
    <div class="wrap">
        <nav class="slide">
            <div id="slide-out">
                <ul class="custom-scrollbar" style="height: 100%;">
                    <!-- Side navigation links -->
                    <li class="container view" style="height: 100%;">
                        <div class="card ul_box">
                            <ul class="collapsible collapsible-accordion ul_list d-none">
                                <li>
                                    <a class="collapsible-header waves-effect arrow-r list_title active">
                                        <i class="fas fa-chevron-right"></i>通知 & 私信
                                        <i class="fas fa-angle-down rotate-icon"></i>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul>
                                            <li class="{{ user_active('notification') }}">
                                                <a href="{{ route('users.notification') }}" class="waves-effect">我的通知 <span class="text-danger">（0）</span></a>
                                            </li>
                                            <li class="{{ user_active('message') }}">
                                                <a href="{{ route('users.message') }}" class="waves-effect">我的私信 <span class="text-danger">（0）</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="mask flex-center rgba-white-strong" id="LeftLoading">
                            <div class="preloader-wrapper big active">
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
