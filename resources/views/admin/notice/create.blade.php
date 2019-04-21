<link rel="stylesheet" href="{{ asset('/vendor/timepicker/css/bootstrap-datetimepicker.css') }}">
<form class="modal-content form-horizontal" id="createuserForm" autocomplete="off" action="javaScript:"
      method="POST">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="mySimpleModal">添加公告</h4>
    </div>
    {{ csrf_field() }}
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                公告内容
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <script id="editor" name="content" type="text/plain"></script>
            </div>
        </div>
        <input type="hidden" name="type" value="web">
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                开始时间
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="icon wb-calendar" aria-hidden="true"></i>
                        </span>
                    </div>
                    <input type="text" name="started_at" class="form-control">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                结束时间
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="icon wb-calendar" aria-hidden="true"></i>
                        </span>
                    </div>
                    <input type="text" name="ended_at" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="validateCreateUserButton">提交</button>
    </div>
</form>
<script src="{{ asset('/vendor/timepicker/js/bootstrap-datetimepicker.js') }}"></script>
<script src="{{ asset('/backstage/global/js/Plugin/bootstrap-datepicker.js') }}"></script>
<script src="/vendor/timepicker/js/bootstrap-datetimepicker.js"></script>
<script src="/vendor/ueditor/ueditor.config.js"></script>
<script src="/vendor/ueditor/ueditor.all.js"></script>
<script>
    $('input[name="started_at"]').datetimepicker({
        autoclose: true,
        clearBtn: true, //清除按钮
        todayBtn: false, //今日按钮
        format: "yyyy-mm-dd H:i:s",
        language: "cn",
    });
    $('input[name="ended_at"]').datetimepicker({
        autoclose: true,
        clearBtn: true, //清除按钮
        todayBtn: false, //今日按钮
        format: "yyyy-mm-dd H:i:s",
        language: "cn",
    });

    (function () {
        var ue = UE.getEditor('editor', {
            UEDITOR_HOME_URL: '/vendor/ueditor/',
            serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
        });
        if ($('#editor1').length) {
            var ue1 = UE.getEditor('editor1', {
                UEDITOR_HOME_URL: '/vendor/ueditor/',
                serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
            });
        }

        formValidationAjax('#createuserForm', '#validateCreateUserButton', {
            content: {
                validators: {
                    notEmpty: {
                        message: '公告内容不能为空.'
                    }
                }
            }
        }, function ($form) {
            return serializeObject($form)
        }, "{{ route('backstage.notices.store') }}", 'POST', true, true)
    })();
</script>