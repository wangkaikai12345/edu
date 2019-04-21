<form class="modal-content form-horizontal" id="createCategoryGroupForm" autocomplete="off" action="javaScript:"
      method="POST">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        @if(empty($permission->parent_id))
            <h4 class="modal-title" id="mySimpleModal">修改权限组</h4>
        @else
            <h4 class="modal-title" id="mySimpleModal">修改权限</h4>
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
                <input type="text" class="form-control" name="title" value="{{ $permission->title }}"/>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label  text-right">
                编码
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="name" value="{{ $permission->name }}"/>
            </div>
        </div>
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
                            'permission_id': '{{ $permission->id }}',
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
                        url: "{{ route('backstage.permissions.verify') }}",
                        type: 'POST',
                        data: {
                            'key': 'name',
                            'permission_id': '{{ $permission->id }}',
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
        }, "{{ route('backstage.permissions.update', ['permission' => $permission->hashId]) }}", 'PUT', true, false, function (response) {
            const key = '#children_content_' + '{{$permission->hashId}}';
            const titleKey = '#children_title_' + '{{$permission->hashId}}';
            console.log(response)
            $(key).empty();
            $(key).append(response.child);
            $(titleKey).empty();
            $(titleKey).append('权限组名:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + response.title);
        })
    })();
</script>
