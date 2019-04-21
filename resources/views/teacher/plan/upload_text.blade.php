<div class="video_upload test_upload exam_upload content_upload" style="display:none"
     id="{{ $editStatus }}content-text">
    <div class="row justify-content-center">
        <div class="col-10">
            <div class="form-group">
                <label class="form-control-label col-2">
                    <i class="iconfont">
                        &#xe62f;
                    </i>
                    标题名称
                </label>
                <input class="form-control col-9 form-con" id="{{ $editStatus }}task-title-text" type="text" placeholder=""
                       value="{{ !empty($task) ? $task->title : ''  }}">
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-10">
            <div class="form-group">
                <label class="form-control-label col-2">
                    <i class="iconfont">
                        &#xe62f;
                    </i>
                    图文内容
                </label>
                <div class="col-9 p-0">
                    <script id="{{ $editStatus }}editor" name="content" type="text/plain"
                           >{!! !empty($task) ? $task->target->body : '' !!}</script>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-10">
            <div class="form-group">
                <label class="form-control-label col-2">
                    <i class="iconfont">
                        &#xe62f;
                    </i>
                    建议学习
                </label>
                <input class="form-control col-6" id="{{ $editStatus }}task-length-text" type="number"
                       value="{{ !empty($task) ? $task->length/60 : 0 }}">
                <span class="float-left" style="line-height: 40px;margin: 0 10px;">分</span>
            </div>
        </div>
    </div>
</div>
@if(empty($editStatus))
    <script src="/vendor/ueditor/ueditor.config.js"></script>
    <script src="/vendor/ueditor/ueditor.all.js"></script>
@endif
<script>
    if({{ $editStatus }}ue) {
        {{ $editStatus }}ue.destroy();
    }
    var {{ $editStatus }}ue = UE.getEditor('{{ $editStatus }}editor', {
        UEDITOR_HOME_URL: '/vendor/ueditor/',
        serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
    });
</script>
