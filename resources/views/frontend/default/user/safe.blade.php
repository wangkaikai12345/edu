@extends('frontend.default.user.index')
@section('title', '安全设置')

@section('partStyle')
    <link href="{{ asset('dist/profile_setting/css/index.css') }}" rel="stylesheet">
    @endsection

@section('rightBody')
    <div class="col-xl-9 profile">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">安全设置</h6>
                <hr>
                <div class="row">
                    <div class="col-xl-12 mr-auto">
                        <ul class="list-group list-group-flush pl-4">
                            <li class="list-group-item pl-3">
                                <div class="float-left">
                                    <h6>登录密码</h6>
                                    <span class="font-small text-success">经常更改密码有助于保护您的帐号安全</span>
                                </div>
                                <div class="float-right">
                                    <span class="font-small text-success">已设置</span>
                                    <span class="lines">
                                            &nbsp;|&nbsp;
                                        </span>
                                    <button class="btn btn-primary btn-sm waves-effect waves-light"
                                            data-toggle="modal"
                                            data-target="#changePasswordModal"
                                            id="reset_password_modal"
                                    >修改</button>
                                </div>
                            </li>
                            <li class="list-group-item pl-3">
                                <div class="float-left">
                                    <h6>手机绑定</h6>
                                    @if(auth('web')->user()['phone'])
                                        <span class="font-small text-success">已绑定手机：{{ auth('web')->user()['phone'] }}</span>
                                        @else
                                        <span class="font-small text-danger">未绑定</span>
                                        @endif

                                </div>
                                <div class="float-right">
                                    @if(auth('web')->user()['phone'])
                                        <span class="font-small text-success">已绑定</span>
                                    @else
                                        <span class="font-small text-danger">未绑定</span>
                                    @endif
                                    <span class="lines">
                                            &nbsp;|&nbsp;
                                        </span>
                                    <button
                                            class="btn btn-primary btn-sm waves-effect waves-light"
                                            data-toggle="modal"
                                            data-target="#changeTelModal"
                                            id="bind_phone_modal"
                                    >修改</button>
                                </div>
                            </li>
                            <li class="list-group-item pl-3">
                                <div class="float-left">
                                    <h6>邮箱绑定</h6>
                                    @if(auth('web')->user()['email'])
                                        @if (auth('web')->user()['is_email_verified'])
                                            <span class="font-small text-success">已绑定邮箱：{{ auth('web')->user()['email'] }}</span>
                                            @else
                                            <span class="font-small text-warning">已发送邮箱：{{ auth('web')->user()['email'] }}</span>
                                            @endif
                                    @else
                                        <span class="font-small text-danger">未绑定</span>
                                    @endif
                                </div>
                                <div class="float-right">
                                    @if(auth('web')->user()['is_email_verified'])
                                        <span class="font-small text-success">已绑定</span>
                                    @else
                                        <span class="font-small text-danger">未绑定</span>
                                    @endif
                                    <span class="lines">
                                            &nbsp;|&nbsp;
                                        </span>
                                    <button class="btn btn-primary btn-sm waves-effect waves-light"
                                            data-toggle="modal"
                                            data-target="#changeEmailModal"
                                            id="bind_email_modal"
                                    >修改</button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 修改登陆密码 --}}
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
        <div class="modal-dialog modal-dialog-centered" role="document">


            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">登录密码修改</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="md-form mt-3">
                        <input type="password" id="old-password" class="form-control">
                        <label for="old-password"><i class="fas fa-lock font-small mr-3"></i>请输入当前密码</label>
                    </div>
                    <div class="md-form mt-3">
                        <input type="password" id="new-password" class="form-control">
                        <label for="new-password"><i class="fas fa-lock font-small mr-3"></i>请输入新密码</label>
                    </div>
                    <div class="md-form mt-3">
                        <input type="password" id="new-password-2" class="form-control">
                        <label for="new-password-2"><i class="fas fa-lock font-small mr-3"></i>请再次输入新密码</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary btn-sm" id="reset_password" data-route="{{ route('users.safe.password') }}">确定</button>
                </div>
            </div>
        </div>
    </div>
    {{-- 手机绑定 --}}
    <div class="modal fade" id="changeTelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">


                <div class="modal-header">
                    <h5 class="modal-title" id="changeTelModalTitle">手机绑定</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="md-form mt-3">
                        <input type="password" id="password" class="form-control" name="password">
                        <label for="password"><i class="fas fa-lock font-small mr-3"></i>请输入当前密码</label>
                    </div>
                    <div class="md-form mt-3">
                        <input type="text" id="phone" class="form-control" name="phone">
                        <label for="phone"><i class="fas fa-phone font-small mr-3"></i>请输入手机号码</label>
                    </div>
                    <div class="md-form mt-0 float-left col-xl-8 pl-0">
                        <input type="text" id="captcha" class="form-control" name="captcha">
                        <label for="captcha"><i class="fas fa-image font-small mr-3"></i>请输入图片验证码</label>
                    </div>
                    <img class="float-right col-xl-4" src="{{ captcha_src() }}" onclick="this.src='/captcha?'+Math.random()" title="点击图片重新获取验证码">
                    <div class="md-form mt-0 float-left col-xl-8 pl-0" style="clear: both;">
                        <input type="text" id="sms_code" class="form-control" name="sms_code">
                        <input type="hidden" id="sms_key" class="form-control" name="sms_key">
                        <label for="new-password-2"><i class="fas fa-key font-small mr-3"></i>请输入手机验证码</label>
                    </div>

                    <button class="float-right btn col-xl-3 btn-sm btn-primary mt-0" id="send_sms_code" data-route="{{ route('sms.send', 'verify') }}">发送验证码</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">取消</button>
                    <button type="button" id="bind_phone" class="btn btn-primary btn-sm" data-route="{{ route('users.safe.bind_phone') }}">确定</button>
                </div>

            </div>
        </div>
    </div>
    {{-- 邮箱绑定 --}}
    <div class="modal fade" id="changeEmailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleEmailTitle">邮箱绑定</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="md-form mt-3">
                        <input type="password" id="email-password" class="form-control">
                        <label for="email-password"><i class="fas fa-lock font-small mr-3"></i>请输入当前密码</label>
                    </div>
                    <div class="md-form mt-0 float-left col-xl-8 pl-0">
                        <input type="text" id="email-captcha" class="form-control">
                        <label for="email-pic"><i class="fas fa-image font-small mr-3"></i>请输入图片验证码</label>
                    </div>
                    <img class="float-right col-xl-4" src="{{ captcha_src() }}" onclick="this.src='/captcha?'+Math.random()" title="点击图片重新获取验证码">
                    <div class="md-form mt-3" style="clear: both;">
                        <input type="text" id="email" class="form-control">
                        <label for="email-password"><i class="fas fa-envelope font-small mr-3"></i>请输入邮箱</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary btn-sm" id="send_email_code" data-route="{{ route('email.send', 'bind') }}">确定</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('partScript')
    <script type="text/javascript" src="{{ asset('dist/profile_setting/js/index.js') }}"></script>
    <script>
        // 绑定手机 表单初始化
        $('#bind_phone_modal').click(function(){

            $('#password').val('');
            $('#phone').val('');
            $('#sms_key').val('');
            $('#sms_code').val('');
            $('#captcha').val('');
        })

        // 绑定邮箱 表单初始化
        $('#bind_email_modal').click(function(){

            $('#email-password').val('');
            $('#email-captcha').val('');
            $('#email').val('');
        })

        // 重置密码 表单初始化
        $('#reset_password_modal').click(function(){

            $('#old-password').val('');
            $('#new-password').val('');
            $('#new-password-2').val('');
        })

        // 发送手机验证码
        $("#send_sms_code").click(function (){
            sendyzm($("#send_sms_code"));
            return false;
        });

        // 绑定手机
        $('#bind_phone').click(function(){
            // 数据验证
            var password = $('#password').val();
            var sms_key = $('#sms_key').val();
            var sms_code = $('#sms_code').val();
            if (!password || !sms_key || !sms_code) {
                edu.toastr.error('参数错误'); return false;
            }

            // 请求绑定手机
            edu.ajax({
                url: $(this).data('route'),
                method:'post',
                data:{
                    sms_key:sms_key, sms_code:sms_code,password:password,
                },
                callback:function(res){
                    if (res.status == 'success') {
                        $('#changeTelModal').modal('hide');
                        edu.toastr.success('绑定成功');
                    }

                },
                elm: '#send_sms_code'
            })

            return false;
        })

        // 发送短信的函数
        function sendyzm(obj){
            // 数据验证
            var password = $('#password').val();
            var phone = $('#phone').val();
            var captcha = $('#captcha').val();
            if (!password || !phone || !captcha) {
                edu.toastr.error('参数错误'); return false;
            }

            if (isPhoneNum()) {
                // 请求发送验证码
                edu.ajax({
                    url: obj.data('route'),
                    method:'post',
                    data:{
                        phone:phone, captcha:captcha,password:password,
                    },
                    callback:function(res){
                        if (res.status == 'success') {
                            $('#sms_key').val(res.data.verification_key);
                            setTime(obj);//开始倒计时
                        }

                    },
                    elm: '#send_sms_code'
                })
            }

        }

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

        // 校验手机号是否合法
        function isPhoneNum(){
            var phonenum = $("#phone").val();
            var reg = /^1[34578]\d{9}$/;
            if(!reg.test(phonenum)){
                edu.toastr.error('请输入有效的手机号码！');
                return false;
            }else{
                return true;
            }
        }

        // 发送邮件验证
        $("#send_email_code").click(function (){

            // 数据验证
            var email_password = $('#email-password').val();
            var email_captcha = $('#email-captcha').val();
            var email = $('#email').val();
            if (!email_password || !email_captcha || !email) {
                edu.toastr.error('参数错误'); return false;
            }

            // 发送验证
            edu.ajax({
                url: $(this).data('route'),
                method:'post',
                data:{
                    email:email, captcha:email_captcha,password:email_password,
                },
                callback:function(res){
                    if (res.status == 'success') {
                        edu.toastr.success('验证邮件发送成功，请验证');
                        $('#changeEmailModal').modal('hide');
                    }

                },
                elm: '#send_email_code',
            })

            return false;
        });

        // 重置密码，发送请求
        $('#reset_password').click(function(){
            // 数据验证
            var old_password = $('#old-password').val();
            var new_password = $('#new-password').val();
            var new_password_2 = $('#new-password-2').val();

            if (!old_password || !new_password || !new_password_2) {
                edu.toastr.error('参数错误'); return false;
            }

            // 发送验证
            edu.ajax({
                url: $(this).data('route'),
                method:'post',
                data:{
                    old_password:old_password, password:new_password, password_confirmation:new_password_2,
                },
                callback:function(res){
                    if (res.status == 'success') {
                        edu.toastr.success('密码重置成功');
                        $('#changePasswordModal').modal('hide');
                    }

                },
                elm: '#reset_password',
            })


        })

    </script>
@endsection







