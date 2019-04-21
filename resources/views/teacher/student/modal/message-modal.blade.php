<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">添加学员</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form method="post" action="{{ route('manage.plans.member.message', $member) }}"
          id="message-add-form">
        <div class="row mt-4 m-0 ml-8 input-content justify-content-center">
            <div class="col-9 mb-3">
                <div class="form-group">
                    <div class="input-group input-group-transparent">
                        <label class="control-label col-md-2 col-lg-2 text-right p-0">私信</label>
                        <input type="text" name="message"
                               class="form-control col-md-9 col-lg-9" placeholder="输入私信">

                        <input type="hidden" name="user_id" value="{{ $member->user_id }}">
                    </div>
                    <div class="input_topic">
                        为学员发送私信
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
            <button type="submit" class="btn btn-primary primary-btn" id="message-add-btn">创建</button>
        </div>
    </form>
</div>
<script>
    $(function () {
        /**
         * 添加私信
         */
        var form = $('#message-add-form');
        FormValidator.init(form, {
            rules: {
                message: {
                    required: true,
                    maxlength: 20
                },
            },
            messages: {
                message: {
                    required: "私信不能为空！",
                    maxlength: '私信长度不能超过20'
                },
            },
        }, function () {
            // 请求ajax, 进行教师排序
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                success: function (res) {
                    if (res.status == 200) {
                        edu.alert('success', res.message);
                        window.location.reload();
                    } else {
                        edu.alert('danger', res.message);
                    }
                }
            });

            return false;
        });
    })

</script>