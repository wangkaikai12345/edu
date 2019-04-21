<form id="createuserForm" autocomplete="off" action="javaScript:"
      method="POST">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myLgModal">更新状态</h4>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-md-3 form-control-label" style="padding-left: 25px">
                解决状态
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <div class="radio-custom radio-default radio-inline">
                    <input type="radio" id="inputBasicMale" name="is_solved" value="1" @if($feedback->is_solved == 1) checked @endif>
                    <label for="inputBasicMale">已解决</label>
                </div>
                <div class="radio-custom radio-default radio-inline">
                    <input type="radio" id="inputBasicMale" name="is_solved" value="0"  @if($feedback->is_solved == 0) checked @endif>
                    <label for="inputBasicMale">未解决</label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label" style="padding-left: 25px">
                回复状态
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <div class="radio-custom radio-default radio-inline">
                    <input type="radio" id="inputBasicMale" name="is_replied" value="1"  @if($feedback->is_replied == 1) checked @endif>
                    <label for="inputBasicMale">已回复</label>
                </div>
                <div class="radio-custom radio-default radio-inline">
                    <input type="radio" id="inputBasicMale" name="is_replied" value="0"  @if($feedback->is_replied == 0) checked @endif>
                    <label for="inputBasicMale">未回复</label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="validateCreateUserButton">提交</button>
    </div>
</form>

<script>
    (function () {
        formValidationAjax('#createuserForm', '#validateCreateUserButton', {

        }, function ($form) {
            return serializeObject($form)
        }, "{{ route('backstage.feedback.update', ['feedback' => $feedback->id]) }}", 'PUT', true, true)
    })();
</script>