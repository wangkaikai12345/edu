@extends('frontend.review.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/register/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/personal/security.css') }}">
@endsection

@section('content')
    <div class="xh_plan_content">
        <div class="container" style="padding-bottom: 100px">
            <div class="row padding-content">
                @include('frontend.review.personal.navBar')
                <div class="col-xl-9 col-md-12 col-12 form_content p-0">
                    <!-- Attach a new card -->
                    <div class="form-default zh_course">
                        <div class="card student_style">
                            <div class="card-body row_content">
                                <div class="row_div">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <h6>安全设置</h6>
                                        </div>
                                    </div>
                                    <hr class="personal_hr m-0">
                                </div>
                                <div class="row">
                                    {{-- 原来密码修改登陆密码 --}}
                                    <form action="#" onsubmit="return false" class="form-horizontal p-20 row col-12" id="reset_password" data-route="{{ route('users.safe.password') }}" method="post">

                                        <input type="reset"  style="display: none;" />
                                        <div class="col-md-12 col-sm-12">
                                            <div class="col-md-12 row">
                                                <div class="form-group col-md-7">
                                                    <label class="form-control-label">登录密码修改 <span class="text-success">(已设置)</span></label>
                                                    <div class="input-group input-group-transparent">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="iconfont">&#xe636;</i></span>
                                                        </div>
                                                        <input type="password" class="form-control" id="old_password" name="old_password"
                                                               placeholder="请输入当前密码">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 row">
                                                <div class="form-group col-md-7">
                                                    <div class="input-group input-group-transparent">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="iconfont">&#xe636;</i></span>
                                                        </div>
                                                        <input type="password" class="form-control" id="password" name="password"
                                                               placeholder="请输入新密码">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 row">
                                                <div class="form-group col-md-7">
                                                    <div class="input-group input-group-transparent">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="iconfont">&#xe636;</i></span>
                                                        </div>
                                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                                                               placeholder="请再次输入新密码">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0 col-sm-12 col-lg-5">
                                                    <button class="btn btn-primary item-btn">确定</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    {{-- 绑定手机 --}}
                                    <form action="#" onsubmit="return false" class="form-horizontal p-20 row col-12" id="bind_phone" data-route="{{ route('users.safe.bind_phone') }}" method="post">
                                        <input type="reset"  style="display: none;" />
                                        <div class="col-md-12 col-sm-12">
                                        <div class="col-md-12 row">
                                            <div class="form-group col-md-7">
                                                <label class="form-control-label">手机绑定
                                                    @if(auth('web')->user()['phone'])
                                                        <span class="text-success" id="result">已绑定手机：{{ auth('web')->user()['phone'] }}</span>
                                                    @else
                                                        <span class="text-danger">未绑定</span>
                                                    @endif
                                                </label>
                                                <div class="input-group input-group-transparent">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="iconfont">&#xe636;</i></span>
                                                    </div>
                                                    <input type="password" class="form-control" id="phone_password" name="password"
                                                           placeholder="请输入当前密码">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="form-group col-md-7">
                                                <div class="input-group input-group-transparent">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="iconfont">&#xe633;</i></span>
                                                    </div>
                                                    <input type="number" class="form-control" id="phone" name="phone"
                                                           placeholder="11位手机号">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="form-group col-md-7">
                                                <div class="input-group input-group-transparent">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="iconfont">&#xe635;</i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="captcha" name="captcha"
                                                           placeholder="请输入图片验证码">
                                                    <img class="input-group-append code_img" width="100" src="{{ captcha_src() }}" onclick="this.src='/captcha?'+Math.random()" title="点击图片重新获取验证码">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="form-group col-md-7">
                                                <div class="input-group input-group-transparent">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="iconfont">&#xe635;</i></span>
                                                    </div>
                                                    <input type="hidden" id="sms_key" name="sms_key">
                                                    <input type="text" class="form-control sms_code" id="sms_code" name="sms_code" placeholder="请输入验证码">
                                                    <div class="input-group-append code_img">
                                                        <button class="btn btn-sm btn-primary" id="send_sms_code" data-route="{{ route('sms.send', 'verify') }}"
                                                                type="button">发送验证码
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 p-0 col-sm-12 col-lg-5">
                                                <button class="btn btn-primary item-btn" type="submit">确定</button>
                                            </div>
                                        </div>
                                    </div>
                                    </form>

                                    {{-- 绑定邮箱 --}}
                                    <form action="#" onsubmit="return false" class="form-horizontal p-20 row col-12" id="bind_email" data-route="{{ route('email.send', 'bind') }}" method="post">
                                        <input type="reset"  style="display: none;" />
                                        <div class="col-md-12 col-sm-12">
                                            <div class="col-md-12 row">
                                                <div class="form-group col-md-7">
                                                    <label class="form-control-label">邮箱设置
                                                        @if(auth('web')->user()['email'])
                                                            @if (auth('web')->user()['is_email_verified'])
                                                                <span class="text-success">已绑定邮箱：{{ auth('web')->user()['email'] }}</span>
                                                            @else
                                                                <span class="text-warning">已发送邮箱：{{ auth('web')->user()['email'] }}</span>
                                                            @endif
                                                        @else
                                                            <span class="text-danger">未绑定</span>
                                                        @endif
                                                    </label>
                                                    <div class="input-group input-group-transparent">
                                                        <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                                class="iconfont">&#xe636;</i></span>
                                                        </div>
                                                        <input type="password" class="form-control" id="email_password" name="password"
                                                               placeholder="请输入当前密码">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 row">
                                                <div class="form-group col-md-7">
                                                    <div class="input-group input-group-transparent">
                                                        <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                                class="iconfont">&#xe635;</i></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="email_captcha" name="captcha"
                                                               placeholder="请输入图片验证码">
                                                        <img class="input-group-append code_img" width="100" src="{{ captcha_src() }}" onclick="this.src='/captcha?'+Math.random()" title="点击图片重新获取验证码">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 row">
                                                <div class="form-group col-md-7">
                                                    <div class="input-group input-group-transparent">
                                                        <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                                class="iconfont">&#xe648;</i></span>
                                                        </div>
                                                        <input type="email" class="form-control" id="email" name="email"
                                                               placeholder="请输入邮箱">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0 col-sm-12 col-lg-5">
                                                    <button class="btn btn-primary item-btn">确定</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

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
    <script>
        //  ----------------------------------修改登陆密码
        // 指定表单
        var passwordForm = $('#reset_password');
        // 请求地址
        var route = $('#reset_password').data('route');

        FormValidator.init(passwordForm, {
            rules: {
                old_password: {
                    required: true,
                },
                password:{
                    required: true,
                    equalTo:"#password_confirmation"
                },
                password_confirmation:{
                    required: true,
                    equalTo:"#password"
                }
            },
            messages: {
                old_password: {
                    required: "请输入旧密码！",
                },
                password: {
                    required: "请输入新密码！",
                    equalTo: "请再次输入新密码"
                },
                password_confirmation: {
                    required: "再次输入新密码！",
                    equalTo: "两次输入的密码不一致"
                }
            }
        }, function () {
            $.ajax({
                url: route,
                type:'post',
                data:passwordForm.serialize(),
                success: function (result) {
                    if (result.status == '200') {
                        alert('密码重置成功');
                        // 清空表单数据
                        $("input[type=reset]").trigger("click");

                    } else {
                        alert(result.message);
                    }
                }
            });

            return false;
        });

        //  ----------------------------------绑定手机
        // 指定表单
        var phoneForm = $('#bind_phone');
        // 请求地址
        var phoneRoute = $('#bind_phone').data('route');

        FormValidator.init(phoneForm, {
            rules: {
                password: {
                    required: true,
                },
                sms_key:{
                    required: true,
                },
                sms_code:{
                    required: true,
                },
                phone:{
                    required: true,
                },
                captcha:{
                    required: true,
                }
            },
            messages: {
                password: {
                    required: "请输入旧密码！",
                },
                sms_key: {
                    required: "请验证您的手机！",
                },
                sms_code: {
                    required: "请输入手机验证码！",
                },
                phone: {
                    required: "请输入绑定手机号！",
                },
                captcha: {
                    required: "请输入图片验证码！",
                }
            }
        }, function () {
            $.ajax({
                url: phoneRoute,
                type:'post',
                data:phoneForm.serialize(),
                success: function (result) {
                    if (result.status == '200') {
                        alert('绑定手机成功');

                        $('#result').text('已绑定手机:'+$('#phone').val());
                        // 清空表单数据
                        $("input[type=reset]").trigger("click");

                        $('#sms_key').val('');
                    } else {
                        alert(result.message);
                    }
                }
            });

            return false;
        });

        // 发送手机验证码
        $("#send_sms_code").click(function (){
            sendyzm($("#send_sms_code"));
            return false;
        });

        // 发送短信的函数
        function sendyzm(obj){
            // 数据验证
            var password = $('#phone_password').val();
            if (!password) {
                alert('请输入原密码'); return false;
            }
            var phone = $('#phone').val();
            if (!phone) {
                alert('请输入手机号'); return false;
            }
            var captcha = $('#captcha').val();
            if (!captcha) {
                alert('请输入图片验证码'); return false;
            }

            if (isPhoneNum()) {
                // 请求发送验证码
                $.ajax({
                    url: obj.data('route'),
                    method:'post',
                    data:{
                        phone:phone, captcha:captcha,password:password,
                    },
                    success:function(res){
                        if (res.status == '200') {
                            $('#sms_key').val(res.data.verification_key);
                            setTime(obj);//开始倒计时
                        } else {
                            alert(res.message);
                        }

                    }
                })
            }

        }

        // 校验手机号是否合法
        function isPhoneNum(){
            var phonenum = $("#phone").val();
            var reg = /^1[345678]\d{9}$/;
            if(!reg.test(phonenum)){
                alert('请输入有效的手机号码！');
                return false;
            }else{
                return true;
            }
        }

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
                obj.text("("+countdown+"s)后重发") ;
                countdown--;
            }
            setTimeout(function() { setTime(obj) },1000) //每1000毫秒执行一次
        }

        //  ----------------------------------绑定邮箱
        // 指定表单
        var emailForm = $('#bind_email');
        // 请求地址
        var emailRoute = $('#bind_email').data('route');

        FormValidator.init(emailForm, {
            rules: {
                password: {
                    required: true,
                },
                email:{
                    required: true,
                },
                captcha:{
                    required: true,
                }
            },
            messages: {
                password: {
                    required: "请输入旧密码！",
                },
                email: {
                    required: "请验证您的邮箱！",
                },
                captcha: {
                    required: "请输入图片验证码！",
                }
            }
        }, function () {
            $.ajax({
                url: emailRoute,
                type:'post',
                data:emailForm.serialize(),
                success: function (result) {
                    if (result.status == '200') {
                        edu.alert('success','验证邮件已经发送，请验证'); return false;
                        $("input[type=reset]").trigger("click");
                    } else {
                        edu.alert('danger',result.message); return false;
                    }
                },
                error: function () {
                    edu.alert('danger','图片验证码不正确'); return false;
                }
            });

            return false;
        });
    </script>
@endsection

