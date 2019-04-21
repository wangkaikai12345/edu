<form class="modal-content form-horizontal" id="updateUserForm" autocomplete="off" action="javaScript:"
      method="POST">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myLgModal">文章推荐</h4>
    </div>
    {{ csrf_field() }}
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                <label class="form-control-label" for="username">
                    文章标题
                </label>
                <input type="text" class="form-control" id="username" name="username" disabled
                       value="{{ $post->title }}" autocomplete="off">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label class="form-control-label" for="name">
                    推荐序号
                    <span class="required">*</span>
                </label>
                <input type="number" class="form-control" id="recommend_seq" name="recommend_seq" placeholder="推荐序号"
                       autocomplete="off" value="{{ $post->recommend_seq ?? 0 }}">
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="validateUserButton">提交</button>
    </div>
</form>
<script>
    (function () {
        formValidationAjax('#updateUserForm', '#validateUserButton', {
            recommended_seq: {
                validators: {
                    notEmpty: {
                        message: '推荐序号不能为空.'
                    }
                }
            },
        }, function ($form) {
            return serializeObject($form)
        }, "{{ route('backstage.posts.recommend', ['post' => $post->id]) }}", 'POST', true, false, function(){
            // 提示操作成功
            notie.alert({'type': 1, 'text': '操作成功', 'time': 1.5});
            $('#recommend-' + '{{$post->id}}').hide();
            $('#un-recommend-' + '{{$post->id}}').show();
        })
    })();
</script>