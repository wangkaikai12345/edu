<form class="modal-content form-horizontal" id="updateCategoryGroupForm" autocomplete="off" action="javaScript:"
      method="POST">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="mySimpleModal">修改标签分组</h4>
    </div>
    {{ csrf_field() }}
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                名称
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="description" value="{{ $tagGroup->description }}"/>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                编码
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="name" value="{{ $tagGroup->name }}"/>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="validateUpdateCategoryGroupButton">提交</button>
    </div>
</form>
<script>
    (function () {
        formValidationAjax('#updateCategoryGroupForm', '#validateUpdateCategoryGroupButton', {
            description: {
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
                        url: "{{ route('backstage.tagGroups.verify') }}",
                        type: 'POST',
                        data: {
                            'key': 'description',
                            'tag_group_id': "{{ $tagGroup->id }}",
                            '_token': "{{ csrf_token() }}"
                        },
                        validkey: 'valid',
                        delay: 2000,
                        message: '名称重复.'
                    }
                }
            },
            name: {
                validators: {
                    notEmpty: {
                        message: '编码不能为空.'
                    },
                    stringLength: {
                        max: 30,
                        message: '编码最长30字符.'
                    },
                    remote: {
                        url: "{{ route('backstage.tagGroups.verify') }}",
                        type: 'POST',
                        data: {
                            'key': 'name',
                            'tag_group_id': "{{ $tagGroup->id }}",
                            '_token': "{{ csrf_token() }}"
                        },
                        validkey: 'valid',
                        delay: 2000,
                        message: '编码重复.'
                    }
                }
            }
        }, function ($form) {
            return serializeObject($form)
        }, "{{ route('backstage.tagGroups.update', ['tagGroup' => $tagGroup->hashId]) }}", 'PUT', true, true, true)
    })();
</script>