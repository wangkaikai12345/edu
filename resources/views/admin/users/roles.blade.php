<form class="modal-content form-horizontal" id="updateUserRoleForm" autocomplete="off" action="javaScript:"
      method="POST">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="mySimpleModal">设置用户组</h4>
    </div>
    {{ csrf_field() }}
    <div class="modal-body">
        @foreach($roles->chunk(5) as $k => $item)
            <div class="form-group">
                <div>
                    @foreach($item as $key => $value)
                        <div class="radio-custom radio-default radio-inline">
                            <input type="radio" id="inputBasicMale{{ $k }}{{$key}}" name="role_id"
                                   value="{{ $value->id }}"
                                   @if($value->user_has_role) checked @endif>
                            <label for="inputBasicMale{{ $k }}{{$key}}">{{ $value->title }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="validateRoleButton">提交</button>
    </div>
</form>
<script>
    (function () {
        formValidationAjax('#updateUserRoleForm', '#validateRoleButton', {}, function ($form) {
            const role_id = $form.find('input[name="role_id"]:checked').val();
            return {role_id}
        }, "{{ route('backstage.users.roles.update', ['user' => $user->id]) }}", 'PUT', true)
    })();
</script>