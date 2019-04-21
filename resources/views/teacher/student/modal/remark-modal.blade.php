<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">添加学员</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form method="post" action="{{ route('manage.plans.member.remark', $member) }}"
          id="remark-add-form">
        <div class="row mt-4 m-0 ml-8 input-content justify-content-center">
            <div class="col-9 mb-3">
                <div class="form-group">
                    <div class="input-group input-group-transparent">
                        <label class="control-label col-md-2 col-lg-2 text-right p-0">备注</label>
                        <textarea type="text" name="remark"
                               class="form-control col-md-9 col-lg-9" placeholder="请输入备注"></textarea>
                    </div>
                    <div class="input_topic">
                        为学员设置备注
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
            <button type="submit" class="btn btn-primary primary-btn" id="remark-add-btn">创建</button>
        </div>
    </form>
</div>
<script>
    $(function () {
        /**
         * 添加备注
         */
        var form = $('#remark-add-form');
        FormValidator.init(form, {
            rules: {
                remark: {
                    required: true,
                    maxlength: 20
                },
            },
            messages: {
                remark: {
                    required: "备注不能为空！",
                    maxlength: '备注长度不能超过20'
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