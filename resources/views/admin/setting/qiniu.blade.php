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
                                    <a class="nav-link "
                                       href="{{ route('backstage.settings.show', ['namespace' => 'email']) }}">邮件服务器设置
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link  active show"
                                       href="{{ route('backstage.settings.show', ['namespace' => 'qiniu']) }}">存储设置</a>
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

                                                <div class="alert alert-warning" role="alert">
                                                    温馨提示：储存驱动更改之后，会导致部分资源无法找到，请谨慎执行
                                                </div>

                                                <form class="form-horizontal fv-form fv-form-bootstrap4"
                                                      id="exampleStandardForm" autocomplete="off"
                                                      novalidate="novalidate"
                                                      action="javaScript:">
                                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                    <input type="hidden" name="_method" value="PUT">
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">驱动
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <div class="radio-custom radio-default radio-inline">
                                                                <input type="radio" id="inputBasicMale1" name="driver"
                                                                       value="local"
                                                                       @if($setting['driver'] == 'local' || empty($setting['driver'])) checked="" @endif>
                                                                <label for="inputBasicMale1">本地</label>
                                                            </div>
                                                            <div class="radio-custom radio-default radio-inline">
                                                                <input type="radio" id="inputBasicFemale1" name="driver"
                                                                       value="qiniu"
                                                                       @if($setting['driver'] == 'qiniu') checked="" @endif>
                                                                <label for="inputBasicFemale1">七牛云</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="qin-niu-div"
                                                         @if($setting['driver'] != 'qiniu') style="display: none" @endif>
                                                        <div class="form-group row">
                                                            <label class="col-md-2 form-control-label">七牛 AK

                                                            </label>
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control" name="ak"
                                                                       placeholder="七牛 AK" value="{{ $setting['ak'] }}"
                                                                >
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-md-2 form-control-label">七牛 SK

                                                            </label>
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control" name="sk"
                                                                       placeholder="七牛 SK"
                                                                       value="{{ $setting['sk'] }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-md-2 form-control-label">七牛多媒体队列

                                                            </label>
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control" name="queue"
                                                                       placeholder="七牛多媒体队列"
                                                                       value="{{ $setting['queue'] }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-md-2 form-control-label">七牛切片回调地址

                                                            </label>
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control" name="callback"
                                                                       placeholder="七牛切片回调地址"
                                                                       value="{{ $setting['callback'] }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-md-2 form-control-label">公共库 bucket

                                                            </label>
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control"
                                                                       name="public_bucket"
                                                                       placeholder="公共库 bucket"
                                                                       value="{{ $setting['public_bucket'] }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-md-2 form-control-label">公共库域名 domain

                                                            </label>
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control"
                                                                       name="public_domain"
                                                                       placeholder="公共库域名 domain"
                                                                       value="{{ $setting['public_domain'] }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-md-2 form-control-label">私有库 bucket

                                                            </label>
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control"
                                                                       name="private_bucket"
                                                                       placeholder="私有库 bucket"
                                                                       value="{{ $setting['private_bucket'] }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-md-2 form-control-label">私有库 domain
                                                            </label>
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control"
                                                                       name="private_domain"
                                                                       placeholder="切片库 domain"
                                                                       value="{{ $setting['private_domain'] }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-md-2 form-control-label">切片库 bucket

                                                            </label>
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control"
                                                                       name="slice_bucket"
                                                                       placeholder="切片库 bucket"
                                                                       value="{{ $setting['slice_bucket'] }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-md-2 form-control-label">切片库 domain
                                                            </label>
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control"
                                                                       name="slice_domain"
                                                                       placeholder="切片库 domain"
                                                                       value="{{ $setting['slice_domain'] }}">
                                                            </div>
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

        $('input[name="driver"]').change(function () {
            const driver = $(this).val();

            switch (driver) {
                case 'local':
                    $('.qin-niu-div').hide();
                    break;
                case 'qiniu':
                    $('.qin-niu-div').show();
                    break;
                default:
                    $('.qin-niu-div').hide();
                    break;
            }
        });

        (function () {
            formValidationAjax('#exampleStandardForm', '#validateButton2', {}, function ($form) {
                return serializeObject($form)
            }, "{{ route('backstage.settings.update', compact('namespace')) }}", 'PUT', true, true, false, false)
        })();
    </script>
@stop










