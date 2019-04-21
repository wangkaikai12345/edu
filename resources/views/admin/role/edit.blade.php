<form class="modal-content form-horizontal" id="createuserForm" autocomplete="off" action="javaScript:"
      method="POST">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="mySimpleModal">编辑角色</h4>
    </div>
    {{ csrf_field() }}
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-md-3 form-control-label text-right">
                名称
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="title" value="{{$role['title']}}"/>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label  text-right">
                编码
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="name"  value="{{$role['name']}}"/>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label  text-right">
                权限
            </label>
            <div class="col-md-9">

                <div class="panel-group" id="exampleAccordionDefault" aria-multiselectable="true"
                     role="tablist">
                    @foreach($permissions as $permission)
                        <div class="panel" style="border-radius: 0px; border-bottom: 1px solid #c0c5ce">
                            <div class="panel-heading" id="exampleHeadingDefault{{$permission->hashId}}" role="tab">
                                <a class="panel-title collapsed" data-toggle="collapse"
                                   href="#exampleCollapseDefault{{$permission->hashId}}"
                                   data-parent="#exampleAccordionDefault" aria-expanded="false"
                                   aria-controls="exampleCollapseDefault{{$permission->hashId}}">
                                    {{ $permission->title }}
                                </a>
                            </div>
                            <div class="panel-collapse collapse" id="exampleCollapseDefault{{$permission->hashId}}"
                                 aria-labelledby="exampleHeadingDefault{{$permission->hashId}}" role="tabpanel"
                                 style="">
                                <div class="panel-body">
                                    @foreach($permission->children as $child)
                                        <div class="col-md-3 checkbox-custom checkbox-default"
                                             style="display: inline-block;padding-right: 15px">
                                            <input type="checkbox" id="inputBasicRemember{{$child->hashId}}"
                                                   name="permissions[]"
                                                   value="{{$child->id}}"
                                                   autocomplete="off" @if(in_array($child->id, $role_has_permissions)) checked="checked" @endif>
                                            <label for="inputBasicRemember1">{{$child->title}}
                                            </label>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    @endforeach
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
                        url: "{{ route('backstage.roles.verify') }}",
                        type: 'POST',
                        data: {
                            'key': 'title',
                            'role_id': '{{ $role->id }}',
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
                        url: "{{ route('backstage.roles.verify') }}",
                        type: 'POST',
                        data: {
                            'key': 'name',
                            'role_id': '{{ $role->id }}',
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
        }, "{{ route('backstage.roles.update', ['role' => $role->hashId]) }}", 'PUT', true, true)
    })();
</script>

