<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">编辑{{ $chapter->parent_id ? '关' : '阶段' }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<form id="chapter-edit-form" action="{{ route('manage.chapters.update', [$plan, $chapter]) }}" method="put">
    <div class="modal-body">
        <div class="row mt-4 m-0 ml-8 input-content justify-content-center">
            <div class="col-md-10 col-12  p-0 mb-4">
                <div class="form-group">
                    <div class="input-group input-group-transparent">
                        <label class="control-label col-md-2 col-lg-2 col-xl-2 text-center p-0 m-0">{{ $chapter->parent_id ? '关' : '阶段' }}标题</label>
                        <input type="text" placeholder="" value="{{ $chapter->title }}" name="title" id="edit_chapter_title"
                               class="form-control col-md-9 col-lg-9 col-xl-9 ml-2">
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4 m-0 ml-8 input-content justify-content-center">
            <div class="col-md-10 col-12  p-0 mb-4">
                <div class="form-group">
                    <div class="input-group input-group-transparent">
                        <label class="control-label col-md-2 col-lg-2 col-xl-2 text-center p-0 m-0 mr-2">{{ $chapter->parent_id ? '关' : '阶段' }}目标</label>
                        <textarea type="text" class="form-control col-md-12 col-lg-9 col-xl-9"
                              name="goals"
                        >{{ $chapter->goals }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary primary-btn" id="edit-btn">确定</button>
    </div>
</form>
<script>
    $(function(){

        // 编辑
        $('#edit-btn').click(function() {
            var $form = $('#chapter-edit-form');

            if (!$('#edit_chapter_title').val()) {
                edu.alert('danger', '请输入标题'); return false;
            }

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
