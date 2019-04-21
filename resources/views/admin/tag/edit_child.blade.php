<form class="modal-content form-horizontal" id="updateCategoryForm" autocomplete="off" action="javaScript:"
      method="POST">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeCategoryModal()">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="mySimpleModal">修改标签</h4>
    </div>
    {{ csrf_field() }}
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                名称
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="name" value="{{ $tag->name }}"/>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="validateUpdateCategoryButton">提交</button>
    </div>
</form>
<script>
    (function () {
        formValidationAjax('#updateCategoryForm', '#validateUpdateCategoryButton', {
            name: {
                validators: {
                    notEmpty: {
                        message: '名称不能为空.'
                    },
                    stringLength: {
                        min: 1,
                        max: 30,
                        message: '名称最短1个字符,最长30字符.'
                    },
                    remote: {
                        url: "{{ route('backstage.tags.verify', ['tagGroup' => $tagGroup->hashId]) }}",
                        type: 'POST',
                        data: {
                            'key': 'name',
                            'tag_group_id': "{{ $tagGroup->id }}",
                            'tag_id': "{{ $tag->id }}",
                            '_token': "{{ csrf_token() }}"
                        },
                        validkey: 'valid',
                        delay: 2000,
                        message: '名称重复.'
                    }
                }
            },
        }, function ($form) {
            return serializeObject($form)
        }, "{{ route('backstage.tags.update', ['tagGroup' => $tagGroup->hashId,'tag' =>  $tag->id]) }}", 'PUT', true, true, true)
    })();

    function closeCategoryModal() {
        $('#myLgTableModal').show();
    };

</script>