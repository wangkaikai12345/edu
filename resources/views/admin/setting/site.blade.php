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
                                    <a class="nav-link  active show" href="{{ route('backstage.settings.index') }}">基本信息
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" href="{{ route('backstage.settings.show', ['namespace' => 'email']) }}">邮件服务器设置
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
                                                        <label class="col-md-2 form-control-label">网站名称
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="title"
                                                                   placeholder="网站名称" value="{{ $setting['title'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">网站副标题
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="sub_title"
                                                                   placeholder="网站副标题"
                                                                   value="{{ $setting['sub_title'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">网站域名
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="domain"
                                                                   placeholder="网站域名" value="{{ $setting['domain'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">SEO关键词
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="keywords"
                                                                   placeholder="SEO关键词"
                                                                   value="{{ $setting['keywords'] ?? null }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">SEO描述信息
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="description"
                                                                   placeholder="SEO描述信息"
                                                                   value="{{ $setting['description'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">管理员邮箱地址
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="email"
                                                                   placeholder="管理员邮箱地址"
                                                                   value="{{ $setting['email'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">课程内容版权方
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="copyright"
                                                                   placeholder="课程内容版权方"
                                                                   value="{{ $setting['copyright'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">ICP备案号
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control" name="icp"
                                                                   placeholder="ICP备案号" value="{{ $setting['icp'] }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">统计分析代码
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-10">
                                                            <textarea class="form-control" name="stat_code"
                                                                      rows="5">{{ $setting['stat_code'] }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">网站ico图标
                                                        </label>
                                                        <div class="col-md-3">
                                                            <input type="hidden" name="ico"
                                                                   value="{{ $setting['ico']  }}">
                                                            <div class="dropify-wrapper"
                                                                 data-target="#imageIcoModal" data-toggle="modal"
                                                                 id="openCropperIco">
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
                                                                        <img id="image_ico" src="{{ http_format($setting['ico']) }}"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label">网站logo图标
                                                        </label>
                                                        <div class="col-md-3">
                                                            <input type="hidden" name="logo"
                                                                   value="{{ $setting['logo']  }}">
                                                            <div class="dropify-wrapper"
                                                                 data-target="#imageLogoModal" data-toggle="modal"
                                                                 id="openCropperLogo">
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
                                                                        <img id="image_logo"
                                                                             src="{{ http_format($setting['logo']) }}"></span>
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
            {{ $setting['ico'] }}
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
            $('input[name="ico"]').val(image);
            $('.close').trigger('click');
            $('#imageIcoModal .reset').trigger('click');
            imageBtn.attr('src', image);
        @endslot
        @slot('imageInput')
            input[name="ico"]
        @endslot
        @slot('localSuccessCallback')
            const image = "{{$domain}}" +  this.savedPath;
            $('#image_ico').attr('src', image);
            $('input[name="ico"]').val(image);
            $('.close').trigger('click');
            $('#imageIcoModal .reset').trigger('click');
        @endslot
    @endcomponent

    @component('admin.layouts.image_format')
        @slot('modalId')
            imageLogoModal
        @endslot
        @slot('imageUrl')
            {{ $setting['logo'] }}
        @endslot
        @slot('imageHeight')
            500
        @endslot
        @slot('aspectRatio')
             36 / 9
        @endslot
        @slot('openCropper')
            openCropperLogo
        @endslot
        @slot('imageScript')
            const image = "{{$domain}}" + '/' + imageName;
            $('#image_logo').attr('src', image);
            $('input[name="logo"]').val(image);
            $('.close').trigger('click');
            $('#imageLogoModal .reset').trigger('click');
            imageBtn.attr('src', image);
        @endslot
        @slot('imageInput')
            input[name="logo"]
        @endslot
        @slot('localSuccessCallback')
            const image = "{{$domain}}" +  this.savedPath;
            $('#image_logo').attr('src', image);
            $('input[name="logo"]').val(image);
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
                title: {
                    validators: {
                        notEmpty: {
                            message: '网站名称不能为空.'
                        },
                        stringLength: {
                            min: 2,
                            max: 30,
                            message: '网站名称最短2个字符,最长30字符.'
                        }
                    }
                },
                sub_title: {
                    validators: {
                        notEmpty: {
                            message: '网站副标题不能为空.'
                        }
                    }
                },
                domain: {
                    validators: {
                        notEmpty: {
                            message: '网站域名不能为空.'
                        },
                        uri: {
                            message: '网站域名格式错误.'
                        }
                    }
                },
                keywords: {
                    validators: {
                        notEmpty: {
                            message: '关键字不能为空.'
                        }
                    }
                },
                description: {
                    validators: {
                        notEmpty: {
                            message: 'SEO描述信息不能为空.'
                        }
                    }
                },
                email: {
                    validators: {
                        notEmpty: {
                            message: '邮箱不能为空.'
                        },
                        email: {
                            message: '邮箱格式错误.'
                        },
                    }
                },
                copyright: {
                    validators: {
                        notEmpty: {
                            message: '课程内容版权方不能为空.'
                        }
                    }
                },
                icp: {
                    validators: {
                        notEmpty: {
                            message: 'ICP备案号不能为空.'
                        }
                    }
                },
                stat_code: {
                    validators: {
//                        notEmpty: {
//                            message: '统计分析代码不能为空.'
//                        }
                    }
                },
                ico: {
                    validators: {
                        notEmpty: {
                            message: 'ico不能为空.'
                        }
                    }
                },
                logo: {
                    validators: {
                        notEmpty: {
                            message: 'logo不能为空.'
                        }
                    }
                }
            }, function ($form) {
                return serializeObject($form)
            }, "{{ route('backstage.settings.update', compact('namespace')) }}", 'PUT', true, true, false, false)
        })();
    </script>
@stop










