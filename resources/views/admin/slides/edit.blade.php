<form class="modal-content form-horizontal" id="updateuserForm" autocomplete="off" action="javaScript:"
      method="POST">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="mySimpleModal">修改轮播图</h4>
    </div>
    {{ csrf_field() }}
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                标题
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input name="title" type="text" class="form-control" value="{{ $slide['title'] }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                描述
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input name="description" type="text" class="form-control" value="{{ $slide['description'] }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                链接
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input name="link" type="text" class="form-control" value="{{ $slide['link'] }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                seq
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input name="seq" type="number" class="form-control" value="{{ $slide['seq'] }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                图片
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input name="image" type="hidden" class="form-control" value="{{$slide['image'] }}">
                <div class="dropify-wrapper"
                     data-target="#updateImageModal" data-toggle="modal"
                     id="updateOpenCropper">
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
                                <img id="updateImagePerview" src="{{ http_format($domain . $slide['image']) }}">
                           </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="validateCreateslideButton">提交</button>
    </div>
</form>

@component('admin.layouts.image_format')
    @slot('modalId')
        updateImageModal
    @endslot
    @slot('imageHeight')
        500
    @endslot
    @slot('openCropper')
        updateOpenCropper
    @endslot
    @slot('imageScript')
        const image = "{{$domain}}" + '/' + imageName;
        $('#updateImagePerview').attr('src', image);
        $('input[name="image"]').val(imageName);
        $('#updateImageModal').hide();
        $('#updateImageModal').removeClass('show');
        $('#updateImageModal .reset').trigger('click');
        imageBtn.attr('src', image);
    @endslot
    @slot('imageInput')
        #updateImageModal input[name="image"]
    @endslot
    @slot('aspectRatio')
        16 / 9
    @endslot
    @slot('simple')
       true
    @endslot
    @slot('localSuccessCallback')
        const image = "{{$domain}}" +  this.savedPath;
        $('#updateImagePerview').attr('src', image);
        $('input[name="image"]').val(this.savedPath);
        $('#updateImageModal').hide();
        $('#updateImageModal').removeClass('show');
        $('#imageIcoModal .reset').trigger('click');
    @endslot
@endcomponent
<script src="/backstage/global/vendor/cropper/cropper.min.js"></script>



@include('admin.layouts.validation')　　
<script>
    (function () {
        formValidationAjax('#updateuserForm', '#validateCreateslideButton', {
            description: {
                validators: {
                    notEmpty: {
                        message: '轮播简介不能为空.'
                    },
                    stringLength:{
                        max:130,
                        message:'简介最长130个字符'
                    }
                }
            },
            title: {
                validators: {
                    notEmpty: {
                        message: '轮播标题不能为空.'
                    },
                    stringLength:{
                        max:30,
                        message:'标题最长30个字符'
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
        }, "{{ route('backstage.slides.update', ['slide' => $slide->id]) }}", 'PUT', true, true)

    })();
</script>