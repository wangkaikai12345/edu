@extends('frontend.default.layouts.app')
@section('title', '忘记密码')
@section('style')
    <link href="{{ asset('dist/forgot_password/css/index.css') }}" rel="stylesheet">
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

                                <!--Header-->
                                <h4 class="text-center mt-3 mb-4">重置密码</h4>

                                    <div class="tab-pane" id="panel666">
                                        <div class="md-form">
                                            <i class="fas fa-envelope prefix"></i>
                                            <input type="text" id="email_phone" class="form-control">
                                            <label for="email">请输入您的邮箱/手机号</label>
                                        </div>

                                        <div class="md-form col-xl-7 pl-0 float-left col-md-7 col-7 mt-0 mb-3">
                                            <i class="fas fa-key prefix"></i>
                                            <input type="text" id="captcha" class="form-control">
                                            <label for="email-pic-code">请输入图片验证码</label>
                                        </div>
                                        <img class="col-5 pr-2 pl-4 ml-1" src="{{ captcha_src() }}" onclick="this.src='/captcha?'+Math.random()" title="点击图片重新获取验证码">

                                        <div class="md-form col-xl-7 pl-0 float-left col-md-7 col-7 mt-0 mb-3">
                                            <i class="fas fa-key prefix"></i>
                                            <input type="text" id="code" class="form-control">
                                            <input type="hidden" id="key" class="form-control" name="key">
                                            <label for="email-code">请输入验证码</label>
                                        </div>
                                        <button class="btn btn-primary float-right col-xl-4 btn-sm col-md-3 col-4"
                                                id="get_code">获取验证码</button>
                                    </div>

                                    <div class="md-form" style="clear: both;">
                                        <i class="fas fa-lock prefix"></i>
                                        <input type="password" id="password" class="form-control" >
                                        <label for="password">请输入您的新密码，区分大小写</label>
                                    </div>
                                    <div class="md-form">
                                        <i class="fas fa-lock prefix"></i>
                                        <input type="password" id="confirm-password" class="form-control">
                                        <label for="confirm-password">确认您的新密码</label>
                                    </div>

                                    <!-- Tab panels -->
                                    <div class="text-center">
                                        <button class="btn btn-primary" type="button" id="reset" data-route="{{ route('password.store') }}">重置</button>
                                        <a href="{{ route('login') }}" class="float-right existing-account">已有账户？</a>
                                    </div>
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
    <script type="text/javascript" src="{{ asset('dist/forgot_password/js/index.js') }}"></script>
    <script>
        // 获取验证码
        $('#get_code').click(function(){

            var captcha = $('#captcha').val();
            if (!captcha) {
                edu.toastr.error('请填写图片验证码'); return false;
            }

            var email_phone = $('#email_phone').val();

            if (!email_phone) {
                edu.toastr.error('请填写邮箱或者手机号'); return false;
            }

            // 正则验证
            var mailReg = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;
            var phoneReg = /^1[34578]\d{9}$/;

            if (mailReg.test(email_phone)) {
                var route = '/email/password';
                var data = {email:email_phone, captcha:captcha};
            } else if (phoneReg.test(email_phone)){
                var route = '/sms/password';
                var data = {phone:email_phone, captcha:captcha};
            } else {
                edu.toastr.error('邮箱或者手机号格式不正确'); return false;
            }

            // 发送验证码
            edu.ajax({
                url: route,
                method:'post',
                data:data,
                callback:function(res){
                    if (res.status == 'success') {
                        $('#key').val(res.data.verification_key);
                        setTime($("#get_code"));//开始倒计时
                    }

                },
                elm: '#get_code',
            })

        })

        // 60s倒计时实现逻辑
        var countdown = 60;
        function setTime(obj) {
            if (countdown == 0) {
                obj.prop('disabled', false);
                obj.text("点击获取验证码");
                countdown = 60;//60秒过后button上的文字初始化,计时器初始化;
                return;
            } else {
                obj.prop('disabled', true);
                obj.text("("+countdown+"s)后重新发送") ;
                countdown--;
            }
            setTimeout(function() { setTime(obj) },1000) //每1000毫秒执行一次
        }

        // 重置按钮
        $('#reset').click(function(){

            // 数据验证
            var code = $('#code').val();
            var key = $('#key').val();
            var password = $('#password').val();
            var password_confirmation = $('#confirm-password').val();

            if (!code || !key || !password || !password_confirmation) {
                edu.toastr.error('参数欠缺'); return false;
            }

            // 执行注册  username password verification_key verification_code password_confirmation
            edu.ajax({
                url: $(this).data('route'),
                method:'post',
                data:{
                    verification_code:code,
                    verification_key:key,
                    password:password,
                    password_confirmation:password_confirmation,
                },
                callback:function(res){
                    if (res.status == 'success') {

                        setTimeout(function () {
                            location.href = '/login';
                        }, 1000)

                    }

                },
                elm: '#reset',
            })

        })
    </script>
@endsection
