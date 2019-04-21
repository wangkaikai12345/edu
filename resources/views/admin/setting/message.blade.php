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
                                    <a class="nav-link  active show"
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
                                                        <label class="col-md-2 form-control-label">允许学员给学员发送私信
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <div class="radio-custom radio-default radio-inline">
                                                                <input type="radio" id="inputBasicMale1"
                                                                       name="allow_user_to_user"
                                                                       value="1"
                                                                       @if($setting['allow_user_to_user']) checked="" @endif>
                                                                <label for="inputBasicMale1">开启</label>
                                                            </div>
                                                            <div class="radio-custom radio-default radio-inline">
                                                                <input type="radio" id="inputBasicFemale1"
                                                                       name="allow_user_to_user"
                                                                       value="0"
                                                                       @if(!$setting['allow_user_to_user']) checked="" @endif>
                                                                <label for="inputBasicFemale1">关闭</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">允许学员给教师发送私信
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <div class="radio-custom radio-default radio-inline">
                                                                <input type="radio" id="inputBasicMale2"
                                                                       name="allow_user_to_teacher"
                                                                       value="1"
                                                                       @if($setting['allow_user_to_teacher']) checked="" @endif>
                                                                <label for="inputBasicMale2">开启</label>
                                                            </div>
                                                            <div class="radio-custom radio-default radio-inline">
                                                                <input type="radio" id="inputBasicFemale2"
                                                                       name="allow_user_to_teacher"
                                                                       value="0"
                                                                       @if(!$setting['allow_user_to_teacher']) checked="" @endif>
                                                                <label for="inputBasicFemale2">关闭</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">允许教师给学员发送私信
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <div class="radio-custom radio-default radio-inline">
                                                                <input type="radio" id="inputBasicMale3"
                                                                       name="allow_teacher_to_user"
                                                                       value="1"
                                                                       @if($setting['allow_teacher_to_user']) checked="" @endif>
                                                                <label for="inputBasicMale3">开启</label>
                                                            </div>
                                                            <div class="radio-custom radio-default radio-inline">
                                                                <input type="radio" id="inputBasicFemale4"
                                                                       name="allow_teacher_to_user"
                                                                       value="0"
                                                                       @if(!$setting['allow_teacher_to_user']) checked="" @endif>
                                                                <label for="inputBasicFemale4">关闭</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{--<div class="form-group row">--}}
                                                        {{--<div class="col-md-10">--}}
                                                            {{--提示：支持取消上述角色间互发私信功能，其余角色不影响。设置后仅APP端不生效。--}}
                                                        {{--</div>--}}
                                                    {{--</div>--}}
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
            formValidationAjax('#exampleStandardForm', '#validateButton2', {}, function ($form) {
                return serializeObject($form)
            }, "{{ route('backstage.settings.update', compact('namespace')) }}", 'PUT', true, true, false, false)
        })();
    </script>
@stop










