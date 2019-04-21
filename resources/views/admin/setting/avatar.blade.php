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
                                    <a class="nav-link "
                                       href="{{ route('backstage.settings.show', ['namespace' => 'register']) }}">注册设置</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active show"
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
                                                        <label class="col-md-2 form-control-label">用户默认头像
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-3">
                                                            <input type="hidden" name="image"
                                                                   value="{{ $setting['image']  }}">
                                                            <div class="dropify-wrapper"
                                                                 data-target="#imageIcoModal" data-toggle="modal"
                                                                 id="openCropperIco" style="height: 304px;">
                                                                <div class="dropify-message"><span
                                                                            class="file-icon"></span>
                                                                    <p class="dropify-error">
                                                                        appended.</p></div>
                                                                <div class="dropify-loader"
                                                                     style="display: none;"></div>
                                                                <div class="dropify-errors-container">
                                                                    <ul></ul>
                                                                </div>
                                                                <div class="dropify-preview" style="display: block;">
                                                                    <span class="dropify-render">
                                                                        <img id="image_ico"
                                                                             src="{{ $setting['image'] }}"
                                                                             style="width: 100%"></span>
                                                                </div>
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

    @component('admin.layouts.image_format')
        @slot('modalId')
            imageIcoModal
        @endslot
        @slot('imageUrl')
            {{ $setting['image'] }}
        @endslot
        @slot('imageHeight')
            500
        @endslot
        @slot('openCropper')
            openCropperIco
        @endslot
        @slot('imageScript')
            const image = "{{$domain}}" + '/' + imageName;
            $('#image_ico').attr('src', image);
            $('input[name="image"]').val(image);
            $('.close').trigger('click');
            $('#imageIcoModal .reset').trigger('click');
            imageBtn.attr('src', image);
        @endslot
        @slot('imageInput')
            input[name="image"]
        @endslot
        @slot('localSuccessCallback')
            const image = "{{$domain}}" +  this.savedPath;
            $('#image_ico').attr('src', image);
            $('input[name="image"]').val(image);
            $('.close').trigger('click');
            $('#imageIcoModal .reset').trigger('click');
        @endslot
    @endcomponent
@stop

@section('script')
    @include('admin.layouts.validation')
    <script src="/backstage/global/vendor/cropper/cropper.min.js"></script>
    <script>
        (function () {
            formValidationAjax('#exampleStandardForm', '#validateButton2', {
                image: {
                    validators: {
                        notEmpty: {
                            message: '用户头像不能为空.'
                        }
                    }
                }
            }, function ($form) {
                return serializeObject($form)
            }, "{{ route('backstage.settings.update', compact('namespace')) }}", 'PUT', true, true, false, false)
        })();
    </script>
@stop







{{--<!DOCTYPE html>--}}
{{--<html lang="en-us">--}}
{{--<head>--}}
    {{--<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>--}}
    {{--<title></title>--}}
    {{--<meta name="viewport" content="width=device-width, initial-scale=1.0"/>--}}
    {{--<meta name="csrf-token" content="{{ csrf_token() }}"><!--需要csrf token-->--}}
    {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"--}}
          {{--integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">--}}
{{--</head>--}}
{{--<body>--}}

{{--<div class="container">--}}
    {{--<div class="page-header">--}}
        {{--<h1>This is an example page.</h1>--}}
        {{--<i>view the source code in <a href="/aetherupload/example_source" target="_blank">vendor/peinhu/aetherupload-laravel/views/example.blade.php</a></i>--}}
    {{--</div>--}}

    {{--<div class="row">--}}
        {{--<form method="post" action="/aetherupload">--}}
            {{--<div class="form-group " id="aetherupload-wrapper"><!--组件最外部需要一个名为aetherupload-wrapper的id，用以包装组件-->--}}
                {{--<label>文件1(带回调)：</label>--}}
                {{--<div class="controls">--}}
                    {{--<input type="file" id="aetherupload-resource" onchange="aetherupload(this,'file').success(someCallback).upload()"/>--}}
                    {{--<!--需要一个名为aetherupload-resource的id，用以标识上传的文件，aetherupload(...)中第二个参数为分组名，success(...)可用于声名上传成功后的回调方法名。默认为选择文件后触发上传，也可根据需求手动更改为特定事件触发，如点击提交表单时-->--}}
                    {{--<div class="progress " style="height: 6px;margin-bottom: 2px;margin-top: 10px;width: 200px;">--}}
                        {{--<div id="aetherupload-progressbar" style="background:blue;height:6px;width:0;"></div><!--需要一个名为aetherupload-progressbar的id，用以标识进度条-->--}}
                    {{--</div>--}}
                    {{--<span style="font-size:12px;color:#aaa;" id="aetherupload-output"></span><!--需要一个名为aetherupload-output的id，用以标识提示信息-->--}}
                    {{--<input type="hidden" name="file1" id="aetherupload-savedpath"><!--需要一个名为aetherupload-savedpath的id，以及一个自定义名称的name值, 用以标识文件储存路径-->--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<div class="form-group " id="aetherupload-wrapper">--}}
                {{--<label>文件2(无回调)：</label>--}}
                {{--<div class="controls">--}}
                    {{--<input type="file" id="aetherupload-resource" onchange="aetherupload(this,'file').upload()"/>--}}
                    {{--<div class="progress " style="height: 6px;margin-bottom: 2px;margin-top: 10px;width: 200px;">--}}
                        {{--<div id="aetherupload-progressbar" style="background:blue;height:6px;width:0;"></div>--}}
                    {{--</div>--}}
                    {{--<span style="font-size:12px;color:#aaa;" id="aetherupload-output"></span>--}}
                    {{--<input type="hidden" name="file2" id="aetherupload-savedpath">--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--{{ storage_host_field() }} <!--（可选）需要标识资源服务器host地址的field，用以支持分布式部署-->--}}
        {{--{{ csrf_field() }} <!--需要标识csrf token的field-->--}}
            {{--<button type="submit" class="btn btn-primary">点击提交</button>--}}
        {{--</form>--}}

        {{--<hr/>--}}

        {{--<div id="result"></div>--}}

    {{--</div>--}}
{{--</div>--}}
{{--<script src="{{ URL::asset('vendor/aetherupload/js/spark-md5.min.js') }}"></script><!--（可选）需要引入spark-md5.min.js，用以支持秒传功能-->--}}
{{--<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script><!--需要引入jquery-->--}}
{{--<script src="{{ URL::asset('/js/upload/sha1.js') }}"></script>--}}
{{--<script src="{{ URL::asset('/js/upload/qetag.js') }}"></script>--}}
{{--<script src="{{ URL::asset('/js/upload/aetherupload.js') }}"></script>--}}
{{--<script>--}}
    {{--// success(someCallback)中声名的回调方法需在此定义，参数someCallback可为任意名称，此方法将会在上传完成后被调用--}}
    {{--// 可使用this对象获得resourceName,resourceSize,resourceTempBaseName,resourceExt,groupSubdir,group,savedPath等属性的值--}}
    {{--someCallback = function () {--}}
        {{--// Example--}}
        {{--$('#result').append(--}}
            {{--'<p>执行回调 - 文件已上传，原名：<span >' + this.resourceName + '</span> | 大小：<span >' + parseFloat(this.resourceSize / (1000 * 1000)).toFixed(2) + 'MB' + '</span> | 储存名：<span >' + this.savedPath.substr(this.savedPath.lastIndexOf('_') + 1) + '</span></p>'--}}
        {{--);--}}
    {{--}--}}

{{--</script>--}}
{{--</body>--}}
{{--</html>--}}


