<link rel="stylesheet" href="{{ asset('/backstage/global/vendor/bootstrap-select/bootstrap-select.css') }}">
<form class="modal-content form-horizontal" id="createuserForm" autocomplete="off" action="javaScript:"
      method="POST">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="mySimpleModal">添加站内通知</h4>
    </div>
    {{ csrf_field() }}
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                通知标题
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input name="title" type="text" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                通知内容
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <script id="editor" name="content" type="text/plain"></script>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                接收人
            </label>
            <div class="col-md-9">
                <select id="selectpicker" multiple name="user_ids[]">
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="validateCreateUserButton">提交</button>
    </div>
</form>
<script src="/vendor/timepicker/js/bootstrap-datetimepicker.js"></script>
<script src="/vendor/ueditor/ueditor.config.js"></script>
<script src="/vendor/ueditor/ueditor.all.js"></script>
<script src="{{ asset('/backstage/global/vendor/bootstrap-select/bootstrap-select.js') }}"></script>
<script src="{{ asset('/backstage/global/js/Plugin/bootstrap-select.js') }}"></script>
<script>
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
                        message: '通知内容不能为空.'
                    }
                }
            },
            title: {
                validators: {
                    notEmpty: {
                        message: '通知内容标题.'
                    }
                }
            }
        }, function ($form) {
            return serializeObject($form)
        }, "{{ route('backstage.notifications.store') }}", 'POST', true, true)


        $('#selectpicker').selectpicker({
            width: '100%',
            liveSearch: true
        });

        $('.bs-searchbox').find('input').keyup(throttle(function () {
            const username = $('.bs-searchbox').find('input').val();
            $.ajax({
                url: "{{ route('backstage.notifications.search.users') }}",
                type: "get",
                async: true,
                data: {username},
                success: function (data) {
                    var str = "";
                    for (var i = 0; i < data.length; i++) {
                        str += '<option value="' + data[i].id + '">' + data[i].username + '</option>'
                    }
                    $("#selectpicker").html(str);

                    $("#selectpicker").selectpicker('refresh');
                }
            });
        }, 2000));

        function throttle(func, wait = 500, mustRun = 1000) {
            var timeout,
                startTime = new Date();
            return function () {
                var context = this,
                    args = arguments,
                    curTime = new Date();
                clearTimeout(timeout);
                if (curTime - startTime >= mustRun) {
                    func.apply(context, args);
                    startTime = curTime;
                } else {
                    timeout = setTimeout(func, wait);
                }
            };
        };
    })();
</script>