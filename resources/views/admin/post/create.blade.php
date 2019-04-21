@extends('admin.layouts.app')
@section('style')
    <link rel="stylesheet" href="/backstage/assets/examples/css/uikit/modals.css">
    <link rel="stylesheet" href="/backstage/global/vendor/blueimp-file-upload/jquery.fileupload.css">
    <link rel="stylesheet" href="/backstage/global/vendor/dropify/dropify.css">
    <link rel="stylesheet" href="/backstage/global/vendor/cropper/cropper.css">
    <link rel="stylesheet" href="/backstage/global/vendor/switchery/switchery.css">
    <link rel="stylesheet" href="{{ asset('/backstage/global/vendor/bootstrap-select/bootstrap-select.css') }}">
    <style>
        .required {
            color: red;
        }

        .ck-editor__editable {
            min-height: 300px;
        }
    </style>
@stop
@section('page-title', '文章添加')
@section('content')
    <div class="panel">
        <div class="panel-body container-fluid" style="padding-bottom: 0px">
            <div class="row row-lg">
                <div class="col-xl-12">
                    <!-- Example Tabs -->
                    <div class="example-wrap">
                        <div class="nav-tabs-horizontal" data-plugin="tabs">

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
                                                    <input type="hidden" name="_method" value="POST">
                                                    <div class="form-group row">
                                                        <label class="col-md-2  form-control-label">标题
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control" name="title"
                                                                   placeholder="文章标题"
                                                                   value="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2  form-control-label">副标题
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control" name="subtitle"
                                                                   placeholder="文章副标题"
                                                                   value="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2  form-control-label">分类
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-8">
                                                            <select id="selectpicker" name="category_id">
                                                                <option value="">请选择分类</option>
                                                                @foreach($categories as $category)
                                                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2  form-control-label">标签
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-8">
                                                            <select id="selectpicker2" multiple name="tags[]">
                                                                @foreach($tags as $tag)
                                                                    <option value="{{$tag->id}}">{{$tag->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2  form-control-label">内容
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-8">
                                                            <textarea name="body" id="editor"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2  form-control-label">状态
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-8">
                                                            <div class="radio-custom radio-default radio-inline">
                                                                <input type="radio" id="inputBasicMale1"
                                                                       name="status"
                                                                       value="published">
                                                                <label for="inputBasicMale1">发布</label>
                                                            </div>
                                                            <div class="radio-custom radio-default radio-inline">
                                                                <input type="radio" id="inputBasicFemale1"
                                                                       name="status"
                                                                       value="draft"
                                                                       checked>
                                                                <label for="inputBasicFemale1">草稿</label>
                                                            </div>
                                                            <div class="radio-custom radio-default radio-inline">
                                                                <input type="radio" id="inputBasicFemale1"
                                                                       name="status"
                                                                       value="closed">
                                                                <label for="inputBasicFemale1">关闭</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-2  form-control-label">属性
                                                        </label>
                                                        <div class="col-md-8">
                                                            <div class="checkbox-custom checkbox-default checkbox-inline">
                                                                <input type="checkbox" id="inputBasicMale1"
                                                                       name="is_essence"
                                                                       value="1">
                                                                <label for="inputBasicMale1">精华</label>
                                                            </div>
                                                            <div class="checkbox-custom checkbox-default checkbox-inline">
                                                                <input type="checkbox" id="inputBasicMale1"
                                                                       name="is_stick"
                                                                       value="1">
                                                                <label for="inputBasicMale1">置顶</label>
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
    <script src="{{ asset('/backstage/global/vendor/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('/backstage/global/js/Plugin/bootstrap-select.js') }}"></script>
    <script src="{{ asset('js/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
    <script src="{{ asset('js/ckeditor5-build-classic/build/translations/zh-cn.js') }}"></script>
    <script src="{{ asset('tools/qiniu/qiniu2.min.js') }}"></script>
    <script src="{{ asset('tools/qiniu/qiniu-luwnto.js') }}"></script>
    <script>
        class MyUploadAdapter {
            constructor(loader) {
                // 上传过程中要使用的文件加载器实例.
                this.loader = loader;
            }

            // 启动上传过程.
            upload() {
                return new Promise((resolve, reject) => {
                    const file = this.loader.file;
                    $.ajax({
                        url: '{{route('backstage.qi_niu.token')}}',
                        type: 'POST',
                        data: {_token: '{{csrf_token()}}'},
                        dataType: 'JSON',
                        success: function (response) {
                            const data = {};
                            data.token = response.uptoken
                            file.then(file => {
                                qiniuUpload(file, data, null, function (status, res) {
                                    switch (status) {
                                        case 'complete':
                                            resolve({default: '{{$domain}}' + '/' + res.key})
                                            break;
                                    }
                                });
                            })
                        }
                    });
                });
            }

            // 终止上传过程.
            abort() {
            }
        }

        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new MyUploadAdapter(loader);
            };
        }

        // 富文本初始化
        ClassicEditor
            .create(document.querySelector('#editor'), {
                extraPlugins: [MyCustomUploadAdapterPlugin],
                language: 'zh-cn',
                toolbar: ['heading',
                    '|',
                    'bold',
                    'italic',
                    'fontSize',
                    'link',
                    'bulletedList',
                    'numberedList',
                    'imageUpload',
                    'blockQuote',
                    'insertTable',
                    'mediaEmbed',
                    'undo',
                    'redo'],
            })
            .catch(error => {
                console.error(error);
            });


        $('#selectpicker').selectpicker({
            width: '100%',
            liveSearch: true
        });
        $('#selectpicker2').selectpicker({
            width: '100%',
            liveSearch: true
        });
    </script>

    <script>
        (function () {
            formValidationAjax('#exampleStandardForm', '#validateButton2', {
                title: {
                    validators: {
                        notEmpty: {
                            message: '标题不能为空.'
                        },
                        stringLength: {
                            min: 1,
                            max: 100,
                            message: '标题最短1个字符,最长100字符.'
                        }
                    }
                },
                subtitle: {
                    validators: {
                        notEmpty: {
                            message: '副标题不能为空.'
                        },
                        stringLength: {
                            min: 1,
                            max: 150,
                            message: '副标题最短1个字符,最长150字符.'
                        }
                    }
                },
                body: {
                    validators: {
                        notEmpty: {
                            message: '内容不能为空.'
                        }
                    }
                },
                status: {
                    validators: {
                        notEmpty: {
                            message: '文章状态不能为空.'
                        }
                    }
                },
                categories: {
                    validators: {
                        notEmpty: {
                            message: '文章分类不能为空.'
                        }
                    }
                },
                tags: {
                    validators: {
                        notEmpty: {
                            message: '文章标签不能为空.'
                        }
                    }
                }
            }, function ($form) {
                return serializeObject($form)
            }, "{{ route('backstage.posts.store') }}", 'POST', true, true)
        })();
    </script>
@stop










