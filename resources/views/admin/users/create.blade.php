<form class="modal-content form-horizontal" id="createuserForm" autocomplete="off" action="javaScript:"
      method="POST">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="mySimpleModal">添加用户</h4>
    </div>
    {{ csrf_field() }}
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                昵称
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="username"/>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                Email
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="email"/>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                密码
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input type="password" class="form-control" name="password"/>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                重复密码
                <span class="required">*</span>
            </label>
            <div class="col-md-9">
                <input type="password" class="form-control" name="password_confirmation"/>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 form-control-label">
                用户角色
                <span class="required">*</span>
            </label>
            <div class="col-md-8" style="margin-left: 15px">
                @foreach($roles->chunk(5) as $item)
                    <div class="row">
                        @foreach($item as $key => $value)
                            <div class="radio-custom radio-default radio-inline">
                                <input type="radio" id="inputBasicMale{{$key}}" name="role" value="{{ $value->id }}">
                                <label for="inputBasicMale{{$key}}">{{ $value->title }}</label>
                            </div>
                        @endforeach
                    </div>
                @endforeach
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
            username: {
                validators: {
                    notEmpty: {
                        message: '昵称不能为空.'
                    },
                    stringLength: {
                        min: 2,
                        max: 30,
                        message: '昵称最短2个字符,最长30字符.'
                    },
                    remote: {
                        url: "{{ route('backstage.users.verify') }}",
                        type: 'POST',
                        data: {
                            'key': 'username',
                            '_token': "{{ csrf_token() }}"
                        },
                        validkey: 'valid',
                        delay: 2000,
                        message: '昵称重复.'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: '邮箱不能为空.'
                    },
                    emailAddress: {
                        message: '邮箱格式错误.'
                    },
                    remote: {
                        url: "{{ route('backstage.users.verify') }}",
                        type: 'POST',
                        data: {
                            'key': 'email',
                            '_token': "{{ csrf_token() }}"
                        },
                        validkey: 'valid',
                        delay: 2000,
                        message: '邮箱重复.'
                    }
                }
            },
            role: {
                validators: {
                    notEmpty: {
                        message: '角色不能为空.'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: '密码不能为空.'
                    },
                    stringLength: {
                        min: 6,
                        max: 30,
                        message: '密码最短6个字符,最长30字符.'
                    }
                }
            },
            password_confirmation: {
                validators: {
                    notEmpty: {
                        message: '确认密码不能为空.'
                    },
                    stringLength: {
                        min: 6,
                        max: 30,
                        message: '确认密码最短6个字符,最长30字符.'
                    },
                    identical: {
                        field: 'password',
                        message: '两次密码输入不一致.'
                    }
                }
            }
        }, function ($form) {
            return serializeObject($form)
        }, "{{ route('backstage.users.store') }}", 'POST', true, true)
    })();
</script>