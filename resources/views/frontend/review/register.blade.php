@extends('frontend.review.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/register/index.css') }}">
@endsection

@section('content')

    @inject('index', 'App\Handlers\IndexHandler')

    <div class="zh_register">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-5">
                    <div class="card shadow">
                        <div class="card-body px-md-5 py-5">

                            @if ($index->register() && $index->register()['register_mode'] == 'closed')
                                <div class="text-center mb-5 t_title">
                                    <h6 class="h3">网站注册已关闭</h6>
                                </div>
                            @else
                                <div class="text-center mb-5 t_title">
                                    <h6 class="h3">免费创建账户</h6>

                                    <p class="text-muted mb-0">正确填写以下内容开始注册</p>
                                </div>
                                <span class="clearfix"></span>


                                <div class="form-group">
                                    <label class="form-control-label">用户名</label>
                                    <div class="input-group input-group-transparent">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="iconfont">
                                                    &#xe637;
                                                </i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="username" name="username"
                                               placeholder="2到32位（数字，字母，中文，下划线）">
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="form-control-label">密码</label>
                                    <div class="input-group input-group-transparent">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="iconfont">
                                                    &#xe636;
                                                </i>
                                            </span>
                                        </div>
                                        <input type="password" class="form-control" id="password" name="password"
                                               placeholder="至少6位密码，区分大小写">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">确认密码</label>
                                    <div class="input-group input-group-transparent">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="iconfont">
                                                    &#xe636;
                                                </i>
                                            </span>
                                        </div>
                                        <input type="password" class="form-control" id="confirm-password"
                                               name="confirm-password"
                                               placeholder="请再次输入您的密码">
                                    </div>
                                </div>

                                @if ($index->register()['register_mode']=='email_phone')
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
                                            <input type="text" class="form-control" id="email_phone"
                                                   placeholder="手机号／邮箱">
                                        </div>
                                    </div>
                                @endif
                                @if ($index->register()['register_mode']=='phone')
                                    <div class="form-group">
                                        <label class="form-control-label">手机号</label>
                                        <div class="input-group input-group-transparent">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="iconfont">
                                                    &#xe633;
                                                </i>
                                            </span>
                                            </div>
                                            <input type="text" class="form-control" id="phone" placeholder="11位手机号">
                                        </div>
                                    </div>
                                @endif
                                @if ($index->register()['register_mode']=='email')
                                    <div class="form-group">
                                        <label class="form-control-label">邮箱</label>
                                        <div class="input-group input-group-transparent">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="iconfont">
                                                    &#xe633;
                                                </i>
                                            </span>
                                            </div>
                                            <input type="text" class="form-control" id="email" placeholder="正确的邮箱">
                                        </div>
                                    </div>
                                @endif
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
                                        <img class="input-group-append code_img" id="code_captcha" src="{{ captcha_src() }}"
                                             onclick="this.src='/captcha?'+Math.random()" title="点击图片重新获取验证码">
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
                                            <button class="btn btn-sm btn-primary" id="get_code" type="button">发送验证码
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center" style="margin-top: 30px;">
                                    <div class="col-sm-7">
                                        <button id="register" data-route="{{ route('register.store') }}" type="button"
                                                class="btn btn-primary mb-3 mb-sm-0 btn-circle"
                                                style="padding: 8px 45px;font-size: 14px;">注册
                                        </button>
                                    </div>
                                    <div class="col-sm-5 text-sm-right">
                                        <span class="small d-sm-block d-md-inline">已经注册了？</span>
                                        <a href="{{ route('login') }}" class="small font-weight-bold">点击登录</a>
                                    </div>
                                </div>

                            @endif
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
        setTimeout(function () {
            $(document).scrollTop(65);
        }, 300)

        // 获取验证码
        $('#get_code').click(function () {

            var captcha = $('#captcha').val();
            if (!captcha) {
                edu.alert('danger', '请填写图片验证码');
                return false;
            }

            var email = $('#email').val();
            var phone = $('#phone').val();
            var email_phone = $('#email_phone').val();

            // 正则验证
            var mailReg = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;
            var phoneReg = /^1[345678]\d{9}$/;

            // 发送验证码
            if (email && mailReg.test(email)) {
                var route = '/email/register';
                var data = {email: email, captcha: captcha};
            }
            if (phone && phoneReg.test(phone)) {
                var route = '/sms/register';
                var data = {phone: phone, captcha: captcha};
            }
            if (email_phone) {
                if (mailReg.test(email_phone)) {
                    var route = '/email/register';
                    var data = {email: email_phone, captcha: captcha};
                } else if (phoneReg.test(email_phone)) {
                    var route = '/sms/register';
                    var data = {phone: email_phone, captcha: captcha};
                }
            }
            if (!route || !data) {
                edu.alert('danger', '请输入正确的手机号或邮箱');
                return false;
            }

            // 发送验证码
            $.ajax({
                url: route,
                method: 'post',
                data: data,
                success: function (res) {
                    if (res.status == '200') {
                        $('#key').val(res.data.verification_key);
                        setTime($("#get_code"));//开始倒计时

                    } else {
                        edu.alert('danger', res.message);
                    }

                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {

                    // 数据验证未通过
                    if (XMLHttpRequest.status == '422') {

                        var result = XMLHttpRequest.responseJSON;

                        for(let i in result.errors) {
                            message = result.errors[i][0];
                            break;
                        }

                        edu.alert('danger', message);

                    } else {
                        edu.alert('danger', '请求服务器失败，请确认您的网络可用！');
                    }

                    $('#code_captcha').trigger('click');
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
                obj.text("(" + countdown + "s)后重试");
                countdown--;
            }
            setTimeout(function () {
                setTime(obj)
            }, 1000) //每1000毫秒执行一次
        }

        // 注册按钮
        $('#register').click(function () {

            // 数据验证
            var username = $('#username').val();
            var code = $('#code').val();
            var key = $('#key').val();
            var password = $('#password').val();
            var password_confirmation = $('#confirm-password').val();

            if (!username) {
                edu.alert('danger', '请填写正确的用户名...');
                return false;
            }

            if (!code || !key) {
                edu.alert('danger', '请填写正确的验证码...');
                return false;
            }

            if (!password || !password_confirmation) {
                edu.alert('danger', '请填写正确的密码...');
                return false;
            }

            // 执行注册  username password verification_key verification_code password_confirmation
            $.ajax({
                url: $(this).data('route'),
                method: 'post',
                data: {
                    username: username,
                    verification_code: code,
                    verification_key: key,
                    password: password,
                    password_confirmation: password_confirmation,
                },
                success: function (res) {
                    console.log(res);
                    if (res.status == '200') {
                        edu.alert('success', '注册成功');
                        location.href = '/';
                    } else {
                        edu.alert('danger', res.message);
                    }
                }
            })

        })


    </script>
@endsection