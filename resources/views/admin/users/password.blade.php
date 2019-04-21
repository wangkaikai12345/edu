<form class="modal-content form-horizontal" id="exampleStandardForm" autocomplete="off" action="javaScript:"
      method="POST">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="mySimpleModal">重置密码</h4>
    </div>
    {{ csrf_field() }}
    <div class="modal-body">
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
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="validateButton">提交</button>
    </div>
</form>
<script>
    (function () {
        formValidationAjax('#exampleStandardForm', '#validateButton', {
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
            const password = $form.find('input[name="password"]').val();
            const password_confirmation = $form.find('input[name="password_confirmation"]').val();
            return {password, password_confirmation};
        }, "{{ route('backstage.users.reset', ['user' => $user->id]) }}", 'PATCH', true)
    })();
</script>