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
                                    <a class="nav-link   active show"
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
                                                        <label class="col-md-2 form-control-label">状态
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <div class="radio-custom radio-default radio-inline">
                                                                <input type="radio" id="inputBasicMale1" name="on"
                                                                       value="1" @if($setting['on']) checked="" @endif>
                                                                <label for="inputBasicMale1">开启</label>
                                                            </div>
                                                            <div class="radio-custom radio-default radio-inline">
                                                                <input type="radio" id="inputBasicFemale1" name="on"
                                                                       value="0" @if(!$setting['on']) checked="" @endif>
                                                                <label for="inputBasicFemale1">关闭</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">应用ID
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="app_id"
                                                                   placeholder="应用ID"
                                                                   value="{{ $setting['app_id'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">私钥
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="private_key"
                                                                   placeholder="私钥"
                                                                   value="{{ $setting['private_key'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">公钥
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control"
                                                                   name="ali_public_key"
                                                                   placeholder="公钥"
                                                                   value="{{ $setting['ali_public_key'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">支付返回地址
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="return_url"
                                                                   placeholder="支付返回地址"
                                                                   value="{{ $setting['return_url'] }}">
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
                on: {
                    validators: {
                        notEmpty: {
                            message: '状态不能为空.'
                        },
                    }
                },
                app_id: {
                    validators: {
                        notEmpty: {
                            message: '应用ID不能为空.'
                        }
                    }
                },
                private_key: {
                    validators: {
                        notEmpty: {
                            message: '私钥不能为空.'
                        }
                    }
                },
                ali_public_key: {
                    validators: {
                        notEmpty: {
                            message: '公钥不能为空.'
                        }
                    }
                },
                return_url: {
                    validators: {
                        notEmpty: {
                            message: '支付宝回调地址不能为空.'
                        }
                    }
                }
            }, function ($form) {
                return serializeObject($form)
            }, "{{ route('backstage.settings.update', compact('namespace')) }}", 'PUT', true, true, false, false)
        })();
    </script>
@stop










