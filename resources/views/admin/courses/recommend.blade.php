<form class="modal-content form-horizontal" id="updateUserForm" autocomplete="off" action="javaScript:"
      method="POST">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myLgModal">课程推荐</h4>
    </div>
    {{ csrf_field() }}
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                <label class="form-control-label" for="title">
                    课程名称
                </label>
                <input type="text" class="form-control" id="title" name="title" disabled
                       value="{{ $course->title }}" autocomplete="off">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label class="form-control-label" for="name">
                    推荐序号
                    <span class="required">*</span>
                </label>
                <input type="hidden" value="1" name="is_recommended">
                <input type="number" class="form-control" id="recommended_seq" name="recommended_seq" placeholder="推荐序号"
                       autocomplete="off" value="{{ $course->recommended_seq ?? 0 }}">
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="validateUserButton">提交</button>
    </div>
</form>
<script>
    (function () {
        formValidationAjax('#updateUserForm', '#validateUserButton', {
            recommended_seq: {
                validators: {
                    notEmpty: {
                        message: '推荐序号不能为空.'
                    }
                }
            },
        }, function ($form) {
            return serializeObject($form)
        }, "{{ route('backstage.courses.recommend', ['course' => $course->hashId]) }}", 'PATCH', true)
    })();
</script>