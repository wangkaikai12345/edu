@extends('admin.layouts.app')
@section('style')
    <link rel="stylesheet" href="/backstage/assets/examples/css/uikit/modals.css">
    <link rel="stylesheet" href="/backstage/global/vendor/blueimp-file-upload/jquery.fileupload.css">
    <link rel="stylesheet" href="/backstage/global/vendor/dropify/dropify.css">
    <link rel="stylesheet" href="/backstage/global/vendor/cropper/cropper.css">
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
                                    <a class="nav-link " href="{{ route('backstage.settings.index') }}">基本信息
                                    </a>
                                </li>
                                <li class="nav-item " role="presentation">
                                    <a class="nav-link  active show" href="{{ route('backstage.settings.show', ['namespace' => 'email']) }}">邮件服务器设置
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" href="{{ route('backstage.settings.show', ['namespace' => 'qiniu']) }}">存储设置</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link"
                                       href="{{ route('backstage.settings.show', ['namespace' => 'ali_pay']) }}">支付宝设置</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link"
                                       href="{{ route('backstage.settings.show', ['namespace' => 'wechat_pay']) }}">微信设置</a>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <a class="nav-link"
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
                                                        <label class="col-md-2 form-control-label">类型
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="driver"
                                                                   placeholder="类型" value="{{ $setting['driver'] }}" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">加密方式
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="encryption"
                                                                   placeholder="加密方式"
                                                                   value="{{ $setting['encryption'] }}" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">有效期
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="number" class="form-control" name="expires"
                                                                   placeholder="expires" value="{{ $setting['expires'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">SMTP服务器地址
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="host"
                                                                   placeholder="SMTP服务器地址"
                                                                   value="{{ $setting['host'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">SMTP端口号
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="port"
                                                                   placeholder="SMTP端口号"
                                                                   value="{{ $setting['port'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">SMTP用户名
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="username"
                                                                   placeholder="SMTP用户名"
                                                                   value="{{ $setting['username'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">SMTP密码
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="password" class="form-control" name="password"
                                                                   placeholder="SMTP密码"
                                                                   value="{{ $setting['password'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">发信人地址
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="from[address]"
                                                                   placeholder="发信人地址" value="{{ $setting['from']['address'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">发信人名称
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="from[name]"
                                                                   placeholder="发信人名称" value="{{ $setting['from']['name'] }}">
                                                        </div>
                                                    </div>


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
                host: {
                    validators: {
                        notEmpty: {
                            message: '服务器地址不能为空.'
                        }
                    }
                },
                port: {
                    validators: {
                        notEmpty: {
                            message: '端口不能为空.'
                        }
                    }
                },
                driver : {
                    validators: {
                        notEmpty: {
                            message: '类型不能为空.'
                        }
                    }
                },
                expires: {
                    validators: {
                        notEmpty: {
                            message: '有效期不能为空.'
                        }
                    }
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: '密码不能为空.'
                        }
                    }
                },
                username: {
                    validators: {
                        notEmpty: {
                            message: '用户名不能为空.'
                        },
                    }
                },
                encryption: {
                    validators: {
                        notEmpty: {
                            message: '加密方式不能为空.'
                        }
                    }
                },
            }, function ($form) {
                return serializeObject($form)
            }, "{{ route('backstage.settings.update', compact('namespace')) }}", 'PUT', true, true, false, false)
        })();
    </script>
@stop










