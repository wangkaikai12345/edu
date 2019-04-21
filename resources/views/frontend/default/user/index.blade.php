@extends('frontend.default.layouts.app')

@section('style')
    @yield('partStyle')
@endsection

@section('content')
    <div class="wrap">
        <nav class="slide">
            <div id="slide-out">
                <ul class="custom-scrollbar">
                    <!--/.Search Form-->
                    <!-- Side navigation links -->
                    <li class="container">
                        <div class="card ul_box">
                            <ul class="collapsible collapsible-accordion ul_list">
                                <li>
                                    <a class="collapsible-header waves-effect arrow-r list_title active">
                                        <i class="fas fa-chevron-right"></i>账户中心
                                        <i class="fas fa-angle-down rotate-icon"></i>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul>
                                            <li class="{{ user_active('order') }}">
                                                <a href="{{ route('users.order') }}" class="waves-effect">我的订单</a>
                                            </li>
                                            <li class="{{ user_active('refund') }}">
                                                <a href="{{ route('users.refund') }}" class="waves-effect">我的退款</a>
                                            </li>
                                            <li class="{{ user_active('coin') }}">
                                                <a href="{{ route('users.coin') }}" class="waves-effect">虚&nbsp;拟&nbsp;币</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                            <ul class="collapsible collapsible-accordion ul_list">
                                <li>
                                    <a class="collapsible-header waves-effect arrow-r list_title active">
                                        <i class="fas fa-chevron-right"></i>个人设置
                                        <i class="fas fa-angle-down rotate-icon"></i>
                                    </a>
                                    <div class="collapsible-body">
                                        <ul>
                                            <li class="{{ user_active('edit') }}">
                                                <a href="{{ route('users.edit', auth('web')->id()) }}" class="waves-effect">个人信息</a>
                                            </li>
                                            <li class="{{ user_active('safe') }}">
                                                <a href="{{ route('users.safe') }}" class="waves-effect">安全设置</a>
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
