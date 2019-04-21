@extends('admin.layouts.app')
@section('style')
    <link rel="stylesheet" href="/backstage/assets/examples/css/uikit/modals.css">
    <link rel="stylesheet" href="/backstage/global/vendor/blueimp-file-upload/jquery.fileupload.css">
    <link rel="stylesheet" href="/backstage/global/vendor/dropify/dropify.css">
    <link rel="stylesheet" href="/backstage/global/vendor/cropper/cropper.css">
    <link rel="stylesheet" href="/backstage/global/vendor/switchery/switchery.css">
    <style>
        .table a {
            text-decoration: none;
        }

        .required {
            color: red;
        }

        td {
            line-height: 36px
        }

        .panel > .table-bordered, .panel > .table-responsive > .table-bordered {
            border: 1px solid #e4eaec;
        !important;
        }
    </style>
@stop
@section('page-title', '站点配置')
@section('content')
    <div class="panel">
        <div class="panel-body container-fluid" style="padding-bottom: 0px">
            <div class="row row-lg">
                <div class="col-xl-12">
                    <!-- Example Tabs -->
                    <div class="example-wrap">
                        <div class="nav-tabs-horizontal" data-plugin="tabs">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" href="{{ route('backstage.settings.index') }}">基本信息
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link"
                                       href="{{ route('backstage.settings.show', ['namespace' => 'email']) }}">邮件服务器设置
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link"
                                       href="{{ route('backstage.settings.show', ['namespace' => 'qiniu']) }}">存储设置</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link"
                                       href="{{ route('backstage.settings.show', ['namespace' => 'ali_pay']) }}">支付宝设置</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link "
                                       href="{{ route('backstage.settings.show', ['namespace' => 'wechat_pay']) }}">微信设置</a>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <a class="nav-link  active show"
                                       href="{{ route('backstage.settings.show', ['namespace' => 'sms']) }}">短信设置</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link"
                                       href="{{ route('backstage.settings.show', ['namespace' => 'message']) }}">私信设置</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link"
                                       href="{{ route('backstage.settings.show', ['namespace' => 'login']) }}">登录设置</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link "
                                       href="{{ route('backstage.settings.show', ['namespace' => 'register']) }}">注册设置</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link "
                                       href="{{ route('backstage.settings.show', ['namespace' => 'avatar']) }}">头像设置</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link"
                                       href="{{ route('backstage.settings.head') }}">顶部导航</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link"
                                       href="{{ route('backstage.settings.footer') }}">底部导航</a>
                                </li>
                            </ul>
                            <div class="tab-content pt-20">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="panel">
                                            <div class="panel-body" style="padding:1px 30px;">
                                                <form class="form-horizontal fv-form fv-form-bootstrap4"
                                                      id="exampleStandardForm" autocomplete="off"
                                                      novalidate="novalidate"
                                                      action="javaScript:">
                                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                    <input type="hidden" name="_method" value="PUT">
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">阿里云通信配置 AK
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="ak"
                                                                   placeholder="阿里云通信配置 AK"
                                                                   value="{{ $setting['ak'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">阿里云通信配置 SK
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="sk"
                                                                   placeholder="阿里云通信配置 SK"
                                                                   value="{{ $setting['sk'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">签名
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control"
                                                                   name="sign_name"
                                                                   placeholder="签名"
                                                                   value="{{ $setting['sign_name'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">注册验证模板
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control"
                                                                   name="register_template_code"
                                                                   placeholder="注册验证模板"
                                                                   value="{{ $setting['register_template_code'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">修改密码模板
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control"
                                                                   name="password_template_code"
                                                                   placeholder="修改密码模板"
                                                                   value="{{ $setting['password_template_code'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">登录验证模板
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control"
                                                                   name="login_template_code"
                                                                   placeholder="登录验证模板"
                                                                   value="{{ $setting['login_template_code'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">身份验证模板
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control"
                                                                   name="verify_template_code"
                                                                   placeholder="身份验证模板"
                                                                   value="{{ $setting['verify_template_code'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">有效期
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control"
                                                                   name="expires"
                                                                   placeholder="有效期"
                                                                   value="{{ $setting['expires'] }}">
                                                        </div>
                                                    </div>
                                                    {{--<div class="form-group row">--}}
                                                        {{--<label class="col-md-2 form-control-label">模板变量--}}
                                                            {{--<span class="required">*</span>--}}
                                                        {{--</label>--}}
                                                        {{--<div class="col-md-5">--}}
                                                            {{--<input type="text" class="form-control"--}}
                                                                   {{--name="variable[key]"--}}
                                                                   {{--placeholder="模板变量"--}}
                                                                   {{--value="{{ $setting['expires'] }}">--}}

                                                        {{--</div>--}}
                                                        {{--<div class="col-md-5">--}}
                                                            {{--<input type="text" class="form-control"--}}
                                                                   {{--name="variable[key]"--}}
                                                                   {{--placeholder="模板变量"--}}
                                                                   {{--value="{{ $setting['expires'] }}">--}}

                                                        {{--</div>--}}
                                                    {{--</div>--}}
                                                    <div class="text-right">
                                                        <button type="submit" class="btn btn-primary"
                                                                id="validateButton2">保存
                                                        </button>
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
            </div>
        </div>
    </div>
@stop

@section('script')
    @include('admin.layouts.validation')
    <script src="/backstage/global/vendor/cropper/cropper.min.js"></script>
    <script>
        (function () {
            formValidationAjax('#exampleStandardForm', '#validateButton2', {
                ak: {
                    validators: {
                        notEmpty: {
                            message: '短信AK不能为空.'
                        }
                    }
                },
                sk: {
                    validators: {
                        notEmpty: {
                            message: '短信SK不能为空.'
                        }
                    }
                },
                sign_name: {
                    validators: {
                        notEmpty: {
                            message: '签名不能为空.'
                        }
                    }
                },
                login_template_code: {
                    validators: {
                        notEmpty: {
                            message: '登录模板不能为空.'
                        }
                    }
                },
                verify_template_code: {
                    validators: {
                        notEmpty: {
                            message: '验证信息模板不能为空.'
                        }
                    }
                },
                password_template_code: {
                    validators: {
                        notEmpty: {
                            message: '密码模板不能为空.'
                        }
                    }
                },
                register_template_code: {
                    validators: {
                        notEmpty: {
                            message: '注册模板不能为空.'
                        }
                    }
                }
            }, function ($form) {
                return serializeObject($form)
            }, "{{ route('backstage.settings.update', compact('namespace')) }}", 'PUT', true, true, false, false)
        })();
    </script>
@stop










