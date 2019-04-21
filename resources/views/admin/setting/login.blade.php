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
                                    <a class="nav-link "
                                       href="{{ route('backstage.settings.show', ['namespace' => 'sms']) }}">短信设置</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link"
                                       href="{{ route('backstage.settings.show', ['namespace' => 'message']) }}">私信设置</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link  active show"
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
                                                        <label class="col-md-2 form-control-label">开启微信登录
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <div class="radio-custom radio-default radio-inline">
                                                                <input type="radio" id="inputBasicMalewx1" name="is_wx_login"
                                                                       value="1" @if($setting['is_wx_login']) checked="" @endif>
                                                                <label for="inputBasicMalewx1">开启</label>
                                                            </div>
                                                            <div class="radio-custom radio-default radio-inline">
                                                                <input type="radio" id="inputBasicMalewx2" name="is_wx_login"
                                                                       value="0" @if(!$setting['is_wx_login']) checked="" @endif>
                                                                <label for="inputBasicMalewx2">关闭</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">用户登录限制
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <div class="radio-custom radio-default radio-inline">
                                                                <input type="radio" id="inputBasicMale1" name="is_limit_ip"
                                                                       value="1" @if($setting['is_limit_ip']) checked="" @endif>
                                                                <label for="inputBasicMale1">开启</label>
                                                            </div>
                                                            <div class="radio-custom radio-default radio-inline">
                                                                <input type="radio" id="inputBasicFemale2" name="is_limit_ip"
                                                                       value="0" @if(!$setting['is_limit_ip']) checked="" @endif>
                                                                <label for="inputBasicFemale2">关闭</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">邮箱验证限制
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <div class="radio-custom radio-default radio-inline">
                                                                <input type="radio" id="inputBasicMale3" name="only_allow_verified_email_to_login"
                                                                       value="1" @if($setting['only_allow_verified_email_to_login']) checked="" @endif>
                                                                <label for="inputBasicMale3">开启</label>
                                                            </div>
                                                            <div class="radio-custom radio-default radio-inline">
                                                                <input type="radio" id="inputBasicFemale4" name="only_allow_verified_email_to_login"
                                                                       value="0" @if(!$setting['only_allow_verified_email_to_login']) checked="" @endif>
                                                                <label for="inputBasicFemale4">关闭</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">用户登录保护
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <div class="radio-custom radio-default radio-inline">
                                                                <input type="radio" id="inputBasicMale5" name="is_limit_user"
                                                                       value="1" @if($setting['is_limit_user']) checked="" @endif>
                                                                <label for="inputBasicMale5">开启</label>
                                                            </div>
                                                            <div class="radio-custom radio-default radio-inline">
                                                                <input type="radio" id="inputBasicFemale6" name="is_limit_user"
                                                                       value="0" @if(!$setting['is_limit_user']) checked="" @endif>
                                                                <label for="inputBasicFemale6">关闭</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-md-2">

                                                        </div>
                                                        <div class="col-md-10">
                                                            用户连续输入错误密码 <input type="number" class="form-control col-md-1" value="{{$setting['password_error_times_for_user']}}" style="display: inline-block">次，将暂时封禁用户
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-md-2">

                                                        </div>
                                                        <div class="col-md-10">
                                                            同一IP连续输入错误密码 <input type="number" class="form-control col-md-1" value="{{$setting['password_error_times_for_ip']}}" style="display: inline-block">次，将暂时封禁IP
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-md-2">

                                                        </div>
                                                        <div class="col-md-10">
                                                            经过 <input class="form-control  col-md-1" type="number" value="{{$setting['expires']}}" style="display: inline-block">分钟后，解锁用户/IP
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

            }, function ($form) {
                return serializeObject($form)
            }, "{{ route('backstage.settings.update', compact('namespace')) }}", 'PUT', true, true, false, false)
        })();
    </script>
@stop










