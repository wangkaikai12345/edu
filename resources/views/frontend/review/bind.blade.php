@extends('frontend.review.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/register/index.css') }}">
@endsection

@section('content')
    <div class="zh_register" style="padding-bottom: 134px;margin-top: 116px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-5">
                    <div class="card shadow zindex-100">
                        <div class="card-body px-md-5 py-5">
                            <div class="text-center mb-5 t_title">
                                <h6 class="h3">绑定账号</h6>
                                <p>请在30分钟内完成绑定</p>
                            </div>
                            <span class="clearfix"></span>
                            <form id="exampleStandardForm" autocomplete="off" action="javaScript:">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <label class="form-control-label">手机号</label>
                                    <div class="input-group input-group-transparent">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="iconfont">
                                                    &#xe637;
                                                </i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="input-email"
                                               value="{{ old('phone') }}"
                                               placeholder="请输入手机号" name="phone" required>
                                    </div>
                                </div>
                                <div class="form-group  mb-4">
                                    <label class="form-control-label">图片验证码</label>
                                    <div class="input-group input-group-transparent">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="iconfont">
                                                    &#xe635;
                                                </i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="captcha" name="captcha"
                                               placeholder="图片验证码">
                                        <img class="input-group-append code_img" id="code_captcha"
                                             src="{{ captcha_src() }}"
                                             onclick="this.src='/captcha?'+Math.random()" title="点击图片重新获取验证码">
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="form-control-label">验证码</label>
                                    <div class="input-group input-group-transparent">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="iconfont">
                                                    &#xe635;
                                                </i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="code" name="verification_code"
                                               placeholder="验证码">
                                        <input type="hidden" id="key" class="form-control" name="verification_key">
                                        <div class="input-group-append code_img">
                                            <button class="btn btn-sm btn-primary" id="get_code" type="button">发送验证码
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center" style="margin-top: 30px;">
                                    <div class="col-sm-12">
                                        <button type="submit" id="validateButton2"
                                                class="btn btn-primary mb-3 mb-sm-0 btn-circle d-block w-100"
                                                style="padding: 8px 45px;font-size: 14px;">绑定
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script src="{{ asset('backstage/global/vendor/formvalidation/formValidation.js') }}"></script>
    <script src="{{ asset('backstage/global/vendor/formvalidation/framework/bootstrap4.min.js') }}"></script>
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
            // 获取手机号码
            const phone = $('input[name="phone"]').val();
            // 手机号规则
            const phoneReg = /^1\d{10}$/;

            if (phone && phoneReg.test(phone)) {
                var route = '/sms/register';
                var data = {phone: phone, captcha: captcha};
            }

            if (!route || !data) {
                edu.alert('danger', '请输入正确的手机号');
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

                        for (let i in result.errors) {
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

        (function () {
            const fv = $("#exampleStandardForm").formValidation({
                framework: "bootstrap4",
                button: {
                    selector: "#validateButton2",
                    disabled: 'disabled'
                },
                icon: null,
                fields: {
                    phone: {
                        validators: {
                            notEmpty: {
                                message: '手机号不能为空.'
                            },
                        }
                    },
                    verification_code: {
                        validators: {
                            notEmpty: {
                                message: '验证码不能为空.'
                            },
                        }
                    },
                    captcha: {
                        validators: {
                            notEmpty: {
                                message: '图片验证码不能为空.'
                            },
                        }
                    },
                },
                err: {
                    clazz: 'invalid-feedback'
                },
                control: {
                    // The CSS class for valid control
                    valid: 'is-valid',
                    // The CSS class for invalid control
                    invalid: 'is-invalid'
                },
                row: {
                    invalid: 'has-danger'
                }
            }).on('success.form.fv', function (e) {
                e.preventDefault();

                const $form = $(e.target);
                // 获取数据
                const data = serializeObject($form);
                // 组装数据
                const formData = Object.assign({}, data, {"_token": "{{csrf_token()}}", "_method": 'POST'});

                // 进行AJAX请求
                $.ajax({
                    url: '{{ route('wechat.user.bind') }}',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    success: function (response) {
                        window.location.href = response.url;
                    },
                    error: function (error) {

                        // 获取返回的状态码
                        const statusCode = error.status;

                        // 提示信息
                        let message = null;
                        // 状态码判断
                        switch (statusCode) {
                            case 422:
                                message = getFormValidationMessage(error.responseJSON.errors);
                                break;
                            default:
                                message = error.responseJSON.message == null ? '操作失败' : error.responseJSON.message;
                                break;
                        }

                        edu.alert('danger', message);
                    }
                });
            });
        })();


        function serializeObject($form) {
            var o = {};
            var a = $form.serializeArray();
            $.each(a, function () {
                if (o[this.name]) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        };
    </script>
@endsection