@extends('frontend.default.layouts.app')
@section('title', '用户登陆')
@section('style')
    <link href="{{ asset('dist/login/css/index.css') }}" rel="stylesheet">
@endsection
@section('content')
    <section class="view intro-2">
        <div class="mask h-100 d-flex justify-content-center align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-5 col-lg-6 col-md-10 col-sm-12 mx-auto mt-5">

                        <!--Form with header-->
                        <div class="card wow fadeIn" data-wow-delay="0.3s">

                            <div class="card-body">
                                <form action="{{ route('login.store') }}" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <!--Header-->
                                <h4 class="text-center mt-3 mb-5">用户登录</h4>


                                <!--Body-->
                                <div class="md-form">
                                    <i class="fas fa-user prefix font-small"></i>
                                    <input type="text" id="orangeForm-name" class="form-control" name="username" required>
                                    <label for="orangeForm-name">请输入用户名/手机号/邮箱</label>
                                </div>

                                <div class="md-form">
                                    <i class="fas fa-lock prefix font-small"></i>
                                    <input type="password" id="orangeForm-pass" class="form-control" name="password" required>
                                    <label for="orangeForm-pass">请输入你的密码</label>
                                </div>

                                <div class="text-center">
                                    <button class="btn btn-primary" type="submit">登录</button>
                                </div>

                                <div class="form-check float-left mt-3">
                                    <input type="checkbox" class="form-check-input" id="materialIndeterminate">
                                    <label class="form-check-label" for="materialIndeterminate">自动登录</label>
                                </div>

                                <div class="go float-right mt-3">
                                    <a href="{{ route('register') }}">注册账户</a>
                                    <span class="ml-1 mr-1">|</span>
                                    <a href="{{ route('password.reset') }}">忘记密码</a>
                                </div>
                                </form>
                            </div>

                        </div>
                        <!--/Form with header-->

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('dist/login/js/index.js') }}"></script>
@endsection
