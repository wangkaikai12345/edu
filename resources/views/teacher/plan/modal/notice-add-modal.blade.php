<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">添加公告</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body pb-4">
    <form id="notice-add-form" action="{{ route('manage.notices.store', $plan) }}" method="post">
    <div class="row mt-3 m-0 ml-8 input-content">
        <div class="col-md-10">
            <div class="form-group">
                <div class="input-group input-group-transparent">
                    <label class="control-label col-md-2 text-center">教学版本</label>
                    <input type="text" value="{{ $plan->title }}" disabled readonly class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3 m-0 ml-8 input-content">
        <div class="col-md-10">
            <div class="form-group">
                <div class="input-group input-group-transparent">
                    <label class="control-label col-md-2 text-center">发布时间</label>
                    <input type="date" placeholder="开始日期" name="started_at" class="form_datetime form-control col-md-10">
                </div>
            </div>
        </div>
    </div>
        <div class="row mt-3 m-0 ml-8 input-content">
            <div class="col-md-10">
                <div class="form-group">
                    <div class="input-group input-group-transparent">
                        <label class="control-label col-md-2 text-center">结束日期</label>
                        <input type="date" placeholder="结束日期" name="ended_at" class="form_datetime form-control col-md-10">
                    </div>
                </div>
            </div>
        </div>
    <div class="row mt-3 m-0 ml-8 input-content">
        <div class="col-md-10">
            <div class="form-group">
                <div class="input-group input-group-transparent">
                    <label class="control-label col-md-2 text-center">公告内容</label>
                    <div class="col-md-10 no-fixed p-0">
                        <script id="editor-add" name="content" type="text/plain">这里写你的初始化内容</script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
    <button type="button" class="btn btn-primary primary-btn" id="notice-add-btn">确定</button>
</div>

<script>
    $(function(){
        var editor = UE.getEditor('editor-add', {
            UEDITOR_HOME_URL: '/vendor/ueditor/',
            serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
        });

        $("#modal").on("hidden.bs.modal", function() {
            if (editor != undefined) {
                editor.destroy()
            }
        });

        $('#notice-add-btn').click(function() {
            var $form = $('#notice-add-form');
            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                dataType: 'json',
                success: function (res) {
                    if (res.status == 200) {
                        edu.alert('success', res.message);
                        window.location.reload();
                    } else {
                        edu.alert('danger', res.message);
                    }
                }
            })
        })

    })
</script>
