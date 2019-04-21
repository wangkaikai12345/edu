<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">编辑公告</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body pb-4">
    <form id="notice-edit-form" action="{{ route('manage.notices.update', $notice) }}" method="put">
        <div class="row mt-3 m-0 ml-8 input-content">
            <div class="col-md-10">
                <div class="form-group">
                    <div class="input-group input-group-transparent">
                        <label class="control-label col-md-2 text-center">发布时间</label>
                        <input type="text" value="{{ $notice->started_at }}" name="started_at" class="form_datetime form-control col-md-10">
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3 m-0 ml-8 input-content">
            <div class="col-md-10">
                <div class="form-group">
                    <div class="input-group input-group-transparent">
                        <label class="control-label col-md-2 text-center">结束日期</label>
                        <input type="text" value="{{ $notice->ended_at  }}" name="ended_at" class="form_datetime form-control col-md-10">
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
                            <script id="editor-add" name="content" type="text/plain">{!! $notice->content !!}</script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
    <button type="button" class="btn btn-primary primary-btn" id="notice-edit-btn">确定</button>
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

        $('#notice-edit-btn').click(function() {
            var $form = $('#notice-edit-form');
            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
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
