<form class="modal-content form-horizontal" id="createCategoryGroupForm" autocomplete="off" action="javaScript:"
      method="POST">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        @if(empty($permission->id))
            <h4 class="modal-title" id="mySimpleModal">添加权限组</h4>
        @else
            <h4 class="modal-title" id="mySimpleModal">添加权限</h4>
        @endif
    </div>
    {{ csrf_field() }}
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-md-3 form-control-label  text-right">
                名称
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="title"/>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label  text-right">
                编码
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="name"/>
            </div>
        </div>

        @if(!empty($permission->id))
            <input type="hidden" class="form-control" name="parent_id" value="{{ $permission->id }}"/>
        @endif
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="validateCreateCategoryGroupButton">提交</button>
    </div>
</form>

<script>
    (function () {
        formValidationAjax('#createCategoryGroupForm', '#validateCreateCategoryGroupButton', {
            title: {
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
                        url: "{{ route('backstage.permissions.verify') }}",
                        type: 'POST',
                        data: {
                            'key': 'title',
                            '_token': "{{ csrf_token() }}"
                        },
                        validkey: 'valid',
                        delay: 1000,
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
                        url: "{{ route('backstage.permissions.verify') }}",
                        type: 'POST',
                        data: {
                            'key': 'name',
                            '_token': "{{ csrf_token() }}"
                        },
                        validkey: 'valid',
                        delay: 1000,
                        message: '编码重复.'
                    }
                }
            }
        }, function ($form) {
            return serializeObject($form)
        }, "{{ route('backstage.permissions.store') }}", 'POST', true, false, function (response) {
            const parent_id = $('input[name="parent_id"]').val();
            console.log(parent_id)
            let key = null;
            if (parent_id) {
                key = '#children_' + '{{ $permission->hashId  }}';
            } else {
                key = '#permission_content';
            }

            $(key).append(response.child);
        })
    })();
</script>
