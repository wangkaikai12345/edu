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
                                    <a class="nav-link"
                                       href="{{ route('backstage.settings.show', ['namespace' => 'login']) }}">登录设置</a>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <a class="nav-link   active show"
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
                                                    <div class="row">
                                                        <div class="col-md-1">
                                                            注册设置:
                                                        </div>
                                                        <div class="col-md-9">
                                                            <div class="example-wrap">
                                                                <div class="nav-tabs-horizontal" data-plugin="tabs">
                                                                    <ul class="nav nav-tabs" role="tablist">
                                                                        <li class="nav-item" role="presentation"
                                                                            data-value=""
                                                                            onclick="selectRegisterModel('email')">
                                                                            <a class="nav-link @if($setting['register_mode'] == 'email') active show @endif"
                                                                               data-toggle="tab" href="#exampleTabsOne"
                                                                               aria-controls="exampleTabsOne" role="tab"
                                                                               aria-selected="true">
                                                                                邮箱注册
                                                                            </a>
                                                                        </li>
                                                                        <li class="nav-item" role="presentation"
                                                                            data-value="phone"
                                                                            onclick="selectRegisterModel('phone')">
                                                                            <a class="nav-link  @if($setting['register_mode'] == 'phone') active show @endif"
                                                                               data-toggle="tab"
                                                                               href="#exampleTabsTwo"
                                                                               aria-controls="exampleTabsTwo"
                                                                               role="tab" aria-selected="false">
                                                                                手机注册
                                                                            </a>
                                                                        </li>
                                                                        <li class="nav-item" role="presentation"
                                                                            data-value="all"
                                                                            onclick="selectRegisterModel('email_phone')">
                                                                            <a class="nav-link  @if($setting['register_mode'] == 'email_phone') active show @endif"
                                                                               data-toggle="tab"
                                                                               href="#exampleTabsThree"
                                                                               aria-controls="exampleTabsThree"
                                                                               role="tab"
                                                                               aria-selected="false">
                                                                                邮箱或手机注册
                                                                            </a>
                                                                        </li>
                                                                        <li class="nav-item" role="presentation"
                                                                            data-value="closed"
                                                                            onclick="selectRegisterModel('closed')">
                                                                            <a class="nav-link  @if($setting['register_mode'] == 'closed') active show @endif"
                                                                               data-toggle="tab"
                                                                               href="#exampleTabsFour"
                                                                               aria-controls="exampleTabsFour"
                                                                               role="tab" aria-selected="false">
                                                                                关闭
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                    <div class="tab-content pt-20">
                                                                        <div class="tab-pane register_email @if($setting['register_mode'] == 'email') active show @endif"
                                                                             id="exampleTabsOne" role="tabpanel">
                                                                            {{--<div class="form-group row">--}}
                                                                                {{--<label class="col-md-2 form-control-label">邮件激活标题--}}

                                                                                {{--</label>--}}
                                                                                {{--<div class="col-md-10">--}}
                                                                                    {{--<input type="text"--}}
                                                                                           {{--class="form-control"--}}
                                                                                           {{--name="email_title"--}}
                                                                                           {{--placeholder="新用户激活邮件标题"--}}
                                                                                           {{--value="{{ $setting['email_title'] }}">--}}
                                                                                {{--</div>--}}
                                                                            {{--</div>--}}
                                                                            {{--<div class="form-group row">--}}
                                                                                {{--<label class="col-md-2 form-control-label">邮件激活内容--}}
                                                                                    {{--<i class="icon wb-help-circle"--}}
                                                                                       {{--aria-hidden="true"--}}
                                                                                       {{--data-content="变量说明： @{{nickname}} 为接收方用户昵称 @{{sitename}} 为网站名称 @{{siteurl}} 为网站的地址 @{{verifyurl}} 为邮箱验证地址"--}}
                                                                                       {{--data-trigger="hover"--}}
                                                                                       {{--data-toggle="popover"--}}
                                                                                       {{--data-original-title="变量说明"--}}
                                                                                       {{--tabindex="0" title="">--}}
                                                                                    {{--</i>--}}

                                                                                {{--</label>--}}
                                                                                {{--<div class="col-md-10">--}}
                                                                                    {{--<textarea rows="5"--}}
                                                                                              {{--class="form-control"--}}
                                                                                              {{--name="email_content">{{$setting['email_content']}}</textarea>--}}
                                                                                {{--</div>--}}
                                                                            {{--</div>--}}
                                                                            <div class="form-group row">
                                                                                <label class="col-md-2 form-control-label">注册防护机制

                                                                                </label>
                                                                                <div class="col-md-2">
                                                                                    <input type="number"
                                                                                           class="form-control"
                                                                                           name="register_expires"
                                                                                           value="{{ $setting['register_expires'] }}">
                                                                                </div>
                                                                                <label class="col-md-2 form-control-label"
                                                                                       style="margin-left:-65px">分钟之内，注册
                                                                                </label>
                                                                                <div class="col-md-2"
                                                                                     style="padding-left: 0px">
                                                                                    <input type="number"
                                                                                           class="form-control"
                                                                                           name="register_limit"
                                                                                           value="{{ $setting['register_limit'] }}">
                                                                                </div>
                                                                                <label class="col-md-2 form-control-label text-left"
                                                                                       style="padding-left: 0px">次
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="tab-pane register_phone  @if($setting['register_mode'] == 'phone') active show @endif"
                                                                             id="exampleTabsTwo"
                                                                             role="tabpanel">
                                                                            <div class="form-group row">
                                                                                <label class="col-md-2 form-control-label">注册防护机制

                                                                                </label>
                                                                                <div class="col-md-2">
                                                                                    <input type="number"
                                                                                           class="form-control"
                                                                                           name="register_expires"
                                                                                           value="{{ $setting['register_expires'] }}">
                                                                                </div>
                                                                                <label class="col-md-2 form-control-label"
                                                                                       style="margin-left:-65px">分钟之内，注册
                                                                                </label>
                                                                                <div class="col-md-2"
                                                                                     style="padding-left: 0px">
                                                                                    <input type="number"
                                                                                           class="form-control"
                                                                                           name="register_limit"
                                                                                           value="{{ $setting['register_limit'] }}">
                                                                                </div>
                                                                                <label class="col-md-2 form-control-label text-left"
                                                                                       style="padding-left: 0px">次
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="tab-pane register_all  @if($setting['register_mode'] == 'all') active show @endif"
                                                                             id="exampleTabsThree"
                                                                             role="tabpanel">
                                                                            {{--<div class="form-group row">--}}
                                                                                {{--<label class="col-md-2 form-control-label">邮件激活标题--}}

                                                                                {{--</label>--}}
                                                                                {{--<div class="col-md-10">--}}
                                                                                    {{--<input type="text"--}}
                                                                                           {{--class="form-control"--}}
                                                                                           {{--name="email_title"--}}
                                                                                           {{--placeholder="新用户激活邮件标题"--}}
                                                                                           {{--value="{{ $setting['email_title'] ?? " " }}">--}}
                                                                                {{--</div>--}}
                                                                            {{--</div>--}}
                                                                            {{--<div class="form-group row">--}}
                                                                                {{--<label class="col-md-2 form-control-label">邮件激活内容--}}
                                                                                    {{--<i class="icon wb-help-circle"--}}
                                                                                       {{--aria-hidden="true"--}}
                                                                                       {{--data-content="变量说明： @{{nickname}} 为接收方用户昵称 @{{sitename}} 为网站名称 @{{siteurl}} 为网站的地址 @{{verifyurl}} 为邮箱验证地址"--}}
                                                                                       {{--data-trigger="hover"--}}
                                                                                       {{--data-toggle="popover"--}}
                                                                                       {{--data-original-title="变量说明"--}}
                                                                                       {{--tabindex="0" title="">--}}
                                                                                    {{--</i>--}}

                                                                                {{--</label>--}}
                                                                                {{--<div class="col-md-10">--}}
                                                                                    {{--<textarea rows="5"--}}
                                                                                              {{--class="form-control"--}}
                                                                                              {{--name="email_content">{{$setting['email_content'] ?? " "}}</textarea>--}}
                                                                                {{--</div>--}}
                                                                            {{--</div>--}}
                                                                            <div class="form-group row">
                                                                                <label class="col-md-2 form-control-label">注册防护机制

                                                                                </label>
                                                                                <div class="col-md-2">
                                                                                    <input type="number"
                                                                                           class="form-control"
                                                                                           name="register_expires"
                                                                                           value="{{ $setting['register_expires'] }}">
                                                                                </div>
                                                                                <label class="col-md-2 form-control-label"
                                                                                       style="margin-left:-65px">分钟之内，注册
                                                                                </label>
                                                                                <div class="col-md-2"
                                                                                     style="padding-left: 0px">
                                                                                    <input type="number"
                                                                                           class="form-control"
                                                                                           name="register_limit"
                                                                                           value="{{ $setting['register_limit'] }}">
                                                                                </div>
                                                                                <label class="col-md-2 form-control-label text-left"
                                                                                       style="padding-left: 0px">次
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="tab-pane register_closed  @if($setting['register_mode'] == 'closed') active show @endif"
                                                                             id="exampleTabsFour"
                                                                             role="tabpanel">
                                                                            <div class="form-group row">
                                                                                <label class="col-md-2 form-control-label">注册防护机制

                                                                                </label>
                                                                                <div class="col-md-2">
                                                                                    <input type="number"
                                                                                           class="form-control"
                                                                                           name="register_expires"
                                                                                           value="{{ $setting['register_expires'] }}">
                                                                                </div>
                                                                                <label class="col-md-2 form-control-label"
                                                                                       style="margin-left:-65px">分钟之内，注册
                                                                                </label>
                                                                                <div class="col-md-2"
                                                                                     style="padding-left: 0px">
                                                                                    <input type="number"
                                                                                           class="form-control"
                                                                                           name="register_limit"
                                                                                           value="{{ $setting['register_limit'] }}">
                                                                                </div>
                                                                                <label class="col-md-2 form-control-label text-left"
                                                                                       style="padding-left: 0px">次
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row"
                                                         style="margin-top: 50px;padding-top: 50px;border-top: 1px solid #ccc;">
                                                        <div class="col-md-2">
                                                            欢迎信息设置:
                                                        </div>
                                                        <div class="col-md-10">
                                                            <div class="form-group row">
                                                                <label class="col-md-2 form-control-label">发送欢迎信息

                                                                </label>
                                                                <div class="col-md-10">
                                                                    <div class="radio-custom radio-default radio-inline">
                                                                        <input type="radio" id="inputBasicMale1"
                                                                               name="is_sent_welcome_notification"
                                                                               value="1"
                                                                               @if($setting['is_sent_welcome_notification']) checked="" @endif>
                                                                        <label for="inputBasicMale1">开启</label>
                                                                    </div>
                                                                    <div class="radio-custom radio-default radio-inline">
                                                                        <input type="radio" id="inputBasicFemale1"
                                                                               name="is_sent_welcome_notification"
                                                                               value="0"
                                                                               @if(!$setting['is_sent_welcome_notification']) checked="" @endif>
                                                                        <label for="inputBasicFemale1">关闭</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-md-2 form-control-label">欢迎信息标题

                                                                </label>
                                                                <div class="col-md-10">
                                                                    <input type="text" class="form-control"
                                                                           name="welcome_title"
                                                                           value="{{ $setting['welcome_title'] }}">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-md-2 form-control-label">欢迎信息内容

                                                                </label>
                                                                <div class="col-md-10">
                                                                    <textarea rows="5" class="form-control"
                                                                              name="welcome_content">{{ $setting['welcome_content'] }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" value="{{ $setting['register_mode'] }}"
                                                           name="register_mode">
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
                // 获取注册模式
                const register_mode = $('input[name="register_mode"]').val();
                let email_content, email_title, register_limit, register_expires;
                // 判断注册模式
                switch (register_mode) {
                    case 'email':
                        email_content = $('.register_email textarea[name="email_content"]').val();
                        email_title = $('.register_email input[name="email_title"]').val();
                        register_limit = $('.register_email input[name="register_limit"]').val();
                        register_expires = $('.register_email input[name="register_expires"]').val();
                        break;
                    case 'phone':
                        email_content = $('.register_email textarea[name="email_content"]').val();
                        email_title = $('.register_email input[name="email_title"]').val();
                        register_limit = $('.register_phone input[name="register_limit"]').val();
                        register_expires = $('.register_phone input[name="register_expires"]').val();
                        break;
                    case 'all':
                        email_content = $('.register_all textarea[name="email_content"]').val();
                        email_title = $('.register_all input[name="email_title"]').val();
                        register_limit = $('.register_all input[name="register_limit"]').val();
                        register_expires = $('.register_all input[name="register_expires"]').val();
                        break;
                    case 'closed':
                        email_content = $('.register_email textarea[name="email_content"]').val();
                        email_title = $('.register_email input[name="email_title"]').val();
                        register_limit = $('.register_closed input[name="register_limit"]').val();
                        register_expires = $('.register_closed input[name="register_expires"]').val();
                        break;
                }
                let is_sent_welcome_notification = $('input[name="is_sent_welcome_notification"]').val();

                if (is_sent_welcome_notification === '1') {
                    is_sent_welcome_notification = 1;
                } else {
                    is_sent_welcome_notification = 0;
                }
                const welcome_title = $('input[name="welcome_title"]').val();
                const welcome_content = $('textarea[name="welcome_content"]').val();


                return {
                    email_content,
                    email_title,
                    register_limit,
                    register_expires,
                    register_mode,
                    is_sent_welcome_notification,
                    welcome_title,
                    welcome_content
                };
            }, "{{ route('backstage.settings.update', compact('namespace')) }}", 'PUT', true, true, false, false)
        })();


        function selectRegisterModel(model) {
            $('input[name="register_mode"]').val(model);
        }
    </script>
@stop










