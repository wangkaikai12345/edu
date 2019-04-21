@extends('frontend.review.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/register/index.css') }}">
@endsection

@section('content')
    <div class="zh_register">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow">
                        <div class="card-body px-md-5 py-5">

                                <div class="text-center mb-5 t_title">
                                    <h6 class="h3">重置密码</h6>

                                    <p class="text-muted mb-0">正确填写以下内容开始重置</p>
                                </div>
                                <span class="clearfix"></span>
                                <div class="form-group">
                                    <label class="form-control-label">手机号／邮箱</label>
                                    <div class="input-group input-group-transparent">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="iconfont">
                                                &#xe633;
                                            </i>
                                        </span>
                                        </div>
                                        <input type="text" class="form-control" id="email_phone" placeholder="手机号／邮箱">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label">图片验证码</label>
                                    <div class="input-group input-group-transparent">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="iconfont">
                                                    &#xe635;
                                                </i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="captcha" placeholder="图片验证码">
                                        <img class="input-group-append code_img" id="code_img" src="{{ captcha_src() }}" onclick="this.src='/captcha?'+Math.random()" title="点击图片重新获取验证码">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">验证码</label>
                                    <div class="input-group input-group-transparent">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="iconfont">
                                                    &#xe635;
                                                </i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="code" placeholder="验证码">
                                        <input type="hidden" id="key" class="form-control" name="key">
                                        <div class="input-group-append code_img">
                                            <button class="btn btn-sm btn-primary" id="get_code" type="button">发送验证码</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-control-label">您的新密码</label>
                                    <div class="input-group input-group-transparent">
                                        <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="iconfont">
                                                        &#xe636;
                                                    </i>
                                                </span>
                                        </div>
                                        <input type="password" class="form-control" id="password" name="password"
                                               placeholder="请输入您的新密码">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">确认新密码</label>
                                    <div class="input-group input-group-transparent">
                                        <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="iconfont">
                                                        &#xe636;
                                                    </i>
                                                </span>
                                        </div>
                                        <input type="password" class="form-control" id="confirm-password" name="confirm-password"
                                               placeholder="请再次输入您的新密码">
                                    </div>
                                </div>


                                <div class="row align-items-center" style="margin-top: 30px;">
                                    <div class="col-sm-7">
                                        <button id="reset" data-route="{{ route('register.store') }}" type="button"
                                                class="btn btn-primary mb-3 mb-sm-0 btn-circle" style="padding: 8px 45px;font-size: 14px;">重置
                                        </button>
                                    </div>
                                    <div class="col-sm-5 text-sm-right">
                                        <a href="{{ route('login') }}" class="small font-weight-bold">已有账户</a>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{--<script src="{{ mix('/js/front/register/index.js') }}"></script>--}}
    <script>
        // 获取验证码
        $('#get_code').click(function(){

            var captcha = $('#captcha').val();
            if (!captcha) {
                edu.alert('danger','请填写图片验证码'); return false;
            }

            var email_phone = $('#email_phone').val();

            if (!email_phone) {
                edu.alert('danger','请填写邮箱或者手机号'); return false;
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
                edu.alert('danger','邮箱或者手机号格式不正确'); return false;
            }

            // 发送验证码
            $.ajax({
                url: route,
                type:'post',
                data:data,
                success:function(res){
                    if (res.status == '200') {
                        $('#key').val(res.data.verification_key);
                        setTime($("#get_code"));//开始倒计时
                    } else {
                        $('#code_img').trigger('click');
                        edu.alert('danger', res.message);
                    }

                }
            })

        })

        // 60s倒计时实现逻辑
        var countdown = 60;
        function setTime(obj) {
            if (countdown == 0) {
                obj.prop('disabled', false);
                obj.text("发送验证码");
                countdown = 60;//60秒过后button上的文字初始化,计时器初始化;
                return;
            } else {
                obj.prop('disabled', true);
                obj.text("("+countdown+"s)后重试") ;
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
                edu.alert('danger', '请完善重置密码信息'); return false;
            }

            // 执行注册  username password verification_key verification_code password_confirmation
             $.ajax({
                url: $(this).data('route'),
                type:'post',
                data:{
                    verification_code:code,
                    verification_key:key,
                    password:password,
                    password_confirmation:password_confirmation,
                },
                success:function(res){
                    if (res.status == '200') {
                        edu.alert('success', '重置密码成功');

                        setTimeout(function () {
                            location.href = '/login';
                        }, 1000)

                    } else {
                        edu.alert('danger', res.message);
                    }

                }
            })

        })
    </script>
@endsection