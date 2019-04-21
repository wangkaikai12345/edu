@extends('admin.layouts.app')
@section('style')
    <link rel="stylesheet" href="/backstage/assets/examples/css/uikit/modals.css">
    <link rel="stylesheet" href="{{ asset('/backstage/global/vendor/bootstrap-datepicker/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('/backstage/global/vendor/bootstrap-select/bootstrap-select.css') }}">
    <link rel="stylesheet" href="/backstage/global/vendor/cropper/cropper.css">
    <link rel="stylesheet" href="/backstage/global/vendor/blueimp-file-upload/jquery.fileupload.css">
    <link rel="stylesheet" href="/backstage/global/vendor/dropify/dropify.css">

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
@section('page-title', '轮播图管理')
@section('content')

    {{--大的modal--}}
    <div class="modal fade" id="myLgModal" aria-labelledby="myLgModalLabel" role="dialog" tabindex="-1"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-lg">
            <form class="modal-content form-horizontal">
            </form>
        </div>
    </div>

    {{--大的modal--}}
    <div class="modal fade" id="myCrateLgModal" aria-labelledby="myCrateLgModalLabel" role="dialog" tabindex="-1"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-lg">
            <form class="modal-content form-horizontal" id="createuserForm" autocomplete="off" action="javaScript:"
                  method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="mySimpleModal">添加轮播图</h4>
                </div>
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">
                            标题
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-9">
                            <input name="title" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">
                            描述
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-9">
                            <input name="description" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">
                            链接
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-9">
                            <input name="link" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">
                            seq
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-9">
                            <input name="seq" type="number" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">
                            图片
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-9">
                            <input name="image" type="hidden" class="form-control">
                            <div class="dropify-wrapper"
                                 data-target="#imageModal" data-toggle="modal"
                                 id="openCropper">
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
                                <img id="image" src="">
                           </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="validateCreateUserButton">提交</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="panel">
                <header class="panel-heading">
                    <h3 class="panel-title" style="display: inline-block"></h3>
                    <button type="button" class="btn btn-sm btn-outline btn-primary"
                            style="float: right;margin-top: 10px;margin-right: 30px;margin-bottom: 10px"
                            data-target="#myCrateLgModal" data-toggle="modal">
                        <i class="icon wb-plus" aria-hidden="true"></i> 添加轮播图
                    </button>
                </header>
                <div class="panel-body ">
                    <table class="table table-bordered table-hover toggle-circle"
                           data-paging="false" data-plugin="selectable" data-row-selectable="true" id="messageTable">
                        <thead>
                        <tr>
                            <th class="text-center">标题</th>
                            <th class="text-center">预览图</th>
                            <th class="text-center">描述</th>
                            <th class="text-center">序号</th>
                            <th class="text-center">创建时间</th>
                            <th class="text-center">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($slides as $slide)
                            <tr>
                                <td style="line-height: 62px" class="text-center">{{ $slide->title }}</td>
                                <td style="line-height: 62px" class="text-center">
                                    <img src="{{ $domain . $slide->image }}" style="width: 150px;">
                                </td>
                                <td style="line-height: 62px" class="text-center">{{ $slide->description }}</td>
                                <td style="line-height: 62px" class="text-center">{{ $slide->seq }}</td>
                                <td style="line-height: 62px" class="text-center">{{ $slide->created_at }}</td>
                                <td style="line-height: 62px" class="text-center">
                                    <button class="btn btn-sm btn-primary"
                                            data-target="#myLgModal" data-toggle="modal"
                                            onclick="showLgModal('{{route('backstage.slides.edit', ['notice' =>$slide->id])}}')"
                                    >编辑
                                    </button>
                                    <button class="btn btn-sm btn-danger"
                                            onclick="deleteSlides('{{  route('backstage.slides.destroy', ['notice' =>$slide->id])}}')">
                                        删除
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @component('admin.layouts.image_format')
        @slot('modalId')
            imageModal
        @endslot
        @slot('imageHeight')
            500
        @endslot
        @slot('openCropper')
            openCropper
        @endslot
        @slot('imageScript')
            const image = "{{$domain}}" + imageName;
            $('#image').attr('src', image);
            $('input[name="image"]').val(imageName);
            $('#image-close').trigger('click');
            $('#imageModal .reset').trigger('click');
            imageBtn.attr('src', image);
            imageBtn.cropper('reset')
        @endslot
        @slot('imageInput')
            input[name="image"]
        @endslot
        @slot('aspectRatio')
            16 / 9
        @endslot
        @slot('imageWidth')
            846
        @endslot
        @slot('imageHeight')
            389
        @endslot
        @slot('localSuccessCallback')
            const image = "{{$domain}}" +  this.savedPath;
            $('#image').attr('src', image);
            $('input[name="image"]').val(this.savedPath);
            $('#image-close').trigger('click');
            $('#imageIcoModal .reset').trigger('click');
        @endslot
    @endcomponent
@stop


@section('script')
    @include('admin.layouts.validation')
    <script src="/backstage/global/vendor/cropper/cropper.min.js"></script>
    <script>
        // 展示modal
        function showSimpleModal(url) {
            $("#mySimpleModal .modal-content").load(url);
        }

        // 展示modal
        function showLgModal(url) {
            $("#myLgModal .modal-content").load(url);
        }

        (function () {
            formValidationAjax('#createuserForm', '#validateCreateUserButton', {
                description: {
                    validators: {
                        notEmpty: {
                            message: '轮播简介不能为空.'
                        },
                        stringLength: {
                            max: 130,
                            message: '简介最长130个字符'
                        }
                    }
                },
                title: {
                    validators: {
                        notEmpty: {
                            message: '轮播标题不能为空.'
                        },
                        stringLength: {
                            max: 30,
                            message: '标题最长30个字符'
                        }
                    }
                },
                image: {
                    validators: {
                        notEmpty: {
                            message: '图片不能为空.'
                        }
                    }
                },
                link: {
                    validators: {
                        notEmpty: {
                            message: '跳转链接不能为空.'
                        }
                    }
                },
                seq: {
                    validators: {
                        notEmpty: {
                            message: '排列序号不能为空.'
                        }
                    }
                }
            }, function ($form) {
                return serializeObject($form)
            }, "{{ route('backstage.slides.store') }}", 'POST', true, true)

        })();


        // 用户状态操作
        function deleteSlides(fetchUrl) {
            alertify.theme("bootstrap");
            alertify
                .okBtn("确定")
                .cancelBtn("取消")
                .confirm("确认删除?", function () {
                    // 进行AJAX请求
                    $.ajax({
                        url: fetchUrl,
                        type: 'DELETE',
                        dataType: 'JSON',
                        data: {"_token": "{{csrf_token()}}"},
                        success: function (response) {
                            // 提示操作成功
                            notie.alert({'type': 1, 'text': '操作成功', 'time': 1.5});

                            window.location.reload();
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
                                    message = !error.responseJSON.message ? '操作失败' : error.responseJSON.message;
                                    break;
                            }

                            // 弹出提示
                            notie.alert({'type': 3, 'text': message, 'time': 1.5});
                        }
                    });
                }, function () {

                });
        }
    </script>
@stop








