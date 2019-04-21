<form class="modal-content form-horizontal" id="createCategoryGroupChildForm" autocomplete="off" action="javaScript:"
      method="POST">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="mySimpleModal">添加标签</h4>
    </div>
    {{ csrf_field() }}
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                名称
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="name"/>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="validateCreateCategoryGroupChildButton">提交</button>
    </div>
</form>
<script>
    (function () {
        formValidationAjax('#createCategoryGroupChildForm', '#validateCreateCategoryGroupChildButton', {
            name: {
                validators: {
                    notEmpty: {
                        message: '名称不能为空.'
                    },
                    stringLength: {
                        max: 30,
                        message: '名称最长30字符.'
                    },
                    remote: {
                        url: "{{ route('backstage.tags.verify', ['tagGroup' => $tagGroup->hashId]) }}",
                        type: 'POST',
                        data: {
                            'key': 'name',
                            '_token': "{{ csrf_token() }}"
                        },
                        validkey: 'valid',
                        delay: 2000,
                        message: '名称重复.'
                    }
                }
            }
        }, function ($form) {
            return serializeObject($form)
        }, "{{ route('backstage.tags.store', ['tagGroup' => $tagGroup->hashId]) }}", 'POST', true, true)
    })();
</script>