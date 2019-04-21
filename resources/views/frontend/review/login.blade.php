@extends('frontend.review.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/register/index.css') }}">
@endsection

@section('content')
    <div class="zh_register" style="padding-bottom: 134px;margin-top: 116px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-5">
                    <div class="card shadow">
                        <div class="card-body px-md-5 py-5">
                            <div class="text-center mb-5 t_title">
                                <h6 class="h3">欢迎回来</h6>
                                <p class="text-muted mb-0">登录您的账户，或 点击注册
                                </p>
                            </div>
                            <span class="clearfix"></span>
                            <form action="{{ route('login.store') }}" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <label class="form-control-label">手机号/邮箱/用户名</label>
                                    <div class="input-group input-group-transparent">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="iconfont">
                                                    &#xe637;
                                                </i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="input-email"
                                               value="{{ old('username') }}"
                                               placeholder="2到32位（数字，字母，中文，下划线）" name="username" required>
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
                                        <input type="password" class="form-control" id="input-password" name="password"
                                               required
                                               placeholder="至少6位密码，区分大小写">
                                    </div>
                                </div>
                                <div class="row align-items-center" style="margin-top: 30px;">
                                    <div class="col-sm-12">
                                        <button type="submit"
                                                class="btn btn-primary mb-3 mb-sm-0 btn-circle d-block w-100"
                                                style="padding: 8px 45px;font-size: 14px;">登录
                                        </button>
                                    </div>
                                    <div class="col-6 text-left mt-3">
                                        <span class="small d-sm-block d-md-inline">没有账户？</span>
                                        <a href="{{ route('register') }}" class="small font-weight-bold">点击注册</a>
                                    </div>
                                    <div class="col-6 text-right mt-3">
                                        <span class="float-right"
                                              style="font-size: 12px;color: #999999;margin-top: 5px;">
                                            <a href="{{ route('password.reset') }}"
                                               class="small font-weight-bold">忘记密码？</a>
                                        </span>
                                    </div>
                                </div>
                                <div class="row other_login">
                                    <div class="col-sm-12">
                                        <span class="title">
                                            其它方式
                                        </span>
                                    </div>
                                    <div class="col-sm-12 text-center" style="margin-top: -10px;">
                                        <i class="iconfont wechat_icon" id="wechat-login">&#xe681;</i>
                                    </div>
                                </div>
                                {{--<div class="my-4" style="line-height: 30px;">--}}
                                {{--<div class="custom-control custom-checkbox">--}}
                                {{--<input class="custom-control-input" id="customCheckRegister" type="checkbox">--}}
                                {{--<label class="custom-control-label" for="customCheckRegister">--}}
                                {{--<span style="font-size: 12px;color: #999999;">自动登录</span>--}}
                                {{--</label>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="basicExampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true" style="background: rgba(0,0,0,0.6);">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">扫码登录</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="code" class="text-center pt-5 pb-5"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    {{--<script src="{{ mix('/js/front/register/index.js') }}"></script>--}}
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script type="text/javascript">
        setTimeout(function () {
            $(document).scrollTop(65);
        }, 300);

        let timer = null;

        $(document).on('click', '#wechat-login', function () {
            $.ajax({
                url: '{{ route('wx.pic') }}',
                type: 'GET',
                dataType: 'JSON',
                success: function (response) {
                    $('#code').html('').append(`<img src="${response.url}" style="display: block;width: 100%;">`);
                    $('#basicExampleModal').modal('show');
                    $('.modal-backdrop').remove();
                    timer = setInterval(() => {
                        $.ajax({
                            url: '{{ route('wechat.login.check') }}',
                            type: 'GET',
                            dataType: 'JSON',
                            data: {wechat_flag: response.weChatFlag},
                            success: function (response) {
                                // 清除定时器
                                clearInterval(timer);
                                // 需要进行绑定操作
                                if (response.status_code === 201) {
                                    window.location.href = '{{ route('wechat.user.bind.show') }}';
                                }

                                // 登录成功
                                if (response.status_code === 200) {
                                    window.location.href = response.url;
                                }
                            }
                        });
                    }, 2000)
                },
                error: function (error) {
                    console.log(error);
                },
            });
        });

        $('#basicExampleModal').bind('hide.bs.modal', function () {
            clearInterval(timer);
        });

        // 返回时清除轮询
        $('.wechat-back').click(function () {
            clearInterval(timer);
        })
    </script>
@endsection