<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">编辑任务</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body" style="padding-top: 30px;">
    {{-- 任务类型 --}}
    {{--<div class="controls content-control">--}}
        {{--<div class="edit_control_item control_item {{ $task->target_type == 'text' ? 'active' : '' }}" data-type="text">--}}
            {{--<i class="iconfont">--}}
                {{--&#xe60c;--}}
            {{--</i>--}}
            {{--<span class="item_title">图文</span>--}}
        {{--</div>--}}
        {{--<div class="edit_control_item control_item {{ $task->target_type == 'video' ? 'active' : '' }}" data-type="video">--}}
            {{--<i class="iconfont">--}}
                {{--&#xe623;--}}
            {{--</i>--}}
            {{--<span class="item_title">视频</span>--}}
        {{--</div>--}}
        {{--<div class="edit_control_item control_item {{ $task->target_type == 'audio' ? 'active' : '' }}" data-type="audio">--}}
            {{--<i class="iconfont">--}}
                {{--&#xe66a;--}}
            {{--</i>--}}
            {{--<span class="item_title">音频</span>--}}
        {{--</div>--}}
        {{--<div class="edit_control_item control_item {{ $task->target_type == 'doc' ? 'active' : '' }}" data-type="doc">--}}
            {{--<i class="iconfont">--}}
                {{--&#xe63b;--}}
            {{--</i>--}}
            {{--<span class="item_title">文档</span>--}}
        {{--</div>--}}
        {{--<div class="edit_control_item control_item {{ $task->target_type == 'ppt' ? 'active' : '' }}" data-type="ppt">--}}
            {{--<i class="iconfont">--}}
                {{--&#xe721;--}}
            {{--</i>--}}
            {{--<span class="item_title">PPT</span>--}}
        {{--</div>--}}

        {{--<div class="edit_control_item control_item task-type {{ $task->target_type == 'paper' ? 'active' : '' }}" data-type="paper">--}}
            {{--<i class="iconfont">--}}
                {{--&#xe8b3;--}}
            {{--</i>--}}
            {{--<span class="item_title">试卷</span>--}}
        {{--</div>--}}
        {{--<div class="edit_control_item control_item task-type {{ $task->target_type == 'homework' && $task->target->type == 'homework' ? 'active' : '' }}" data-type="homework">--}}
            {{--<i class="iconfont">--}}
                {{--&#xe60e;--}}
            {{--</i>--}}
            {{--<span class="item_title">作业</span>--}}
        {{--</div>--}}
        {{--<div class="control_item task-type {{ $task->target_type == 'homework' && $task->target->type == 'practice' ? 'active' : '' }}" data-type="practice">--}}
            {{--<i class="iconfont">--}}
                {{--&#xe622;--}}
            {{--</i>--}}
            {{--<span class="item_title">练习</span>--}}
        {{--</div>--}}
        {{--<div class="control_item task-type {{ $task->target_type == 'zip' ? 'active' : '' }}" data-type="zip">--}}
            {{--<i class="iconfont">--}}
                {{--&#xe622;--}}
            {{--</i>--}}
            {{--<span class="item_title">下载</span>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--<div class="video_upload" style="display: block;">--}}
        {{--<div class="row justify-content-center">--}}
            {{--<div class="col-10">--}}
                {{--<div class="form-group">--}}
                    {{--<label class="form-control-label col-2">--}}
                        {{--<i class="iconfont">--}}
                            {{--&#xe62f;--}}
                        {{--</i>--}}
                        {{--教学手段--}}
                    {{--</label>--}}
                    {{--<div class="col-9 pl-0">--}}
                        {{--教学手段--}}
                        {{--@foreach(\App\Enums\TaskType::toSelectArray() as $typeValue => $typeName)--}}
                            {{--<div class="custom-control custom-radio mb-3 float-left" style="margin-top: 0.5rem;margin-right: 0.8rem;line-height: 27px;">--}}
                                {{--<input {{ $typeValue == $task->type ? 'checked' : '' }} type="radio" name="edit-task-type" disabled--}}
                                       {{--value="{{ $typeValue }}" class="custom-control-input" id="edit-customRadio-{{ $typeValue }}">--}}
                                {{--<label class="custom-control-label" for="edit-customRadio-{{ $typeValue }}">{{ $typeName }}</label>--}}
                            {{--</div>--}}
                        {{--@endforeach--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--图文--}}
    {{--@include('teacher.plan.upload_text', ['editStatus' => 'edit_', 'type' => ''])--}}
    {{--视频--}}
    @include('teacher.plan.upload_video', ['editStatus' => 'edit_', 'type' => 'video'])
    {{--音频--}}
    {{--@include('teacher.plan.upload_audio', ['editStatus' => 'edit_', 'type' => 'audio'])--}}
    {{--资料--}}
    {{--@include('teacher.plan.upload_doc', ['editStatus' => 'edit_', 'type' => 'doc'])--}}
    {{--ppt--}}
    {{--@include('teacher.plan.upload_ppt', ['editStatus' => 'edit_', 'type' => 'ppt'])--}}
    {{--作业--}}
    {{--@include('teacher.plan.upload_homework', ['editStatus' => 'edit_', ''] )--}}
    {{--考试--}}
    {{--@include('teacher.plan.upload_exam', ['editStatus' => 'edit_', ''] )--}}
    {{--练习--}}
    {{--@include('teacher.plan.upload_practice', ['editStatus' => 'edit_', ''] )--}}
    {{--文件下载--}}
    {{--@include('teacher.plan.upload_zip', ['editStatus' => 'edit_', 'type' => 'zip'] )--}}

    {{--<div class="video_upload content_upload">--}}
        {{--<div class="row justify-content-center">--}}
            {{--<div class="col-10">--}}
                {{--<div class="form-group">--}}
                    {{--<label class="form-control-label col-2">--}}
                        {{--<i class="iconfont">--}}
                            {{--&#xe62f;--}}
                        {{--</i>--}}
                        {{--完成条件--}}
                    {{--</label>--}}
                    {{--<span class="col-9 audio_condition p-0">听完音频</span>--}}
                    {{--<select class="form-control col-3 video_select" id="edit-task-finish-type">--}}

                        {{--@foreach(\App\Enums\FinishType::toSelectArray() as $typeValue => $typeName)--}}
                            {{--<option value="{{ $typeValue }}" {{ $typeValue == $task->finish_type ? 'selected':'' }}>{{ $typeName }}</option>--}}
                        {{--@endforeach--}}

                        {{--  作业  --}}
                        {{--<option>提交及完成</option>--}}
                        {{--<option>批阅完成</option>--}}
                        {{--  考试  --}}
                        {{--<option>提交试卷</option>--}}
                        {{--<option>分数达标</option>--}}
                    {{--</select>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="row justify-content-center" style="display:{{ $task->finish_type == 'time' ? 'flex' :'none'}}"--}}
             {{--id="edit-task-finish-detail-con">--}}
            {{--<div class="col-10">--}}
                {{--<div class="form-group">--}}
                    {{--<label class="form-control-label col-2">--}}
                        {{--<i class="iconfont">--}}
                            {{--&#xe62f;--}}
                        {{--</i>--}}
                        {{--至少学习--}}
                    {{--</label>--}}
                    {{--<input class="form-control col-3" id="edit-task-finish-detail" type="number" placeholder="默认为0"--}}
                           {{--style="min-width: 300px;" value="{{ $task->finish_detail }}">--}}
                    {{--<span class="float-left" style="line-height: 40px;margin: 0 10px;">分</span>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="row justify-content-center">--}}
            {{--<div class="col-10">--}}
                {{--<div class="form-group">--}}
                    {{--<label class="form-control-label col-2">--}}
                        {{--SEO关键字--}}
                    {{--</label>--}}
                    {{--<input class="form-control col-9" type="text" placeholder="" id="edit_seo_key"--}}
                           {{--value="{{ $task->keyword }}">--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="row justify-content-center">--}}
            {{--<div class="col-10">--}}
                {{--<div class="form-group">--}}
                    {{--<label class="form-control-label col-2">--}}
                        {{--SEO描述--}}
                    {{--</label>--}}
                    {{--<div class="col-9 p-0">--}}
                        {{--<textarea name="" cols="10" rows="3" id="edit_seo_des">{{ $task->describe }}</textarea>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="row justify-content-center">--}}
            {{--<div class="col-10">--}}
                {{--<div class="form-group">--}}
                    {{--<label class="form-control-label col-2">--}}
                        {{--免费设置--}}
                    {{--</label>--}}
                    {{--<div class="col-9 p-0" style="margin-top: 0.2rem;">--}}
                        {{--<div class="custom-control custom-checkbox">--}}
                            {{--<input type="checkbox"--}}
                                   {{--class="custom-control-input"--}}
                                   {{--name="edit-task-is-free" value="1"--}}
                                   {{--id="customCheck2"--}}
                                    {{--{{ $task->is_free ? 'checked' :'' }}--}}
                            {{-->--}}
                            {{--<label class="custom-control-label" for="customCheck2">是否免费</label>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--  练习  --}}
    {{--<div class="row justify-content-center">--}}
    {{--<div class="col-10">--}}
    {{--<div class="form-group">--}}
    {{--<label class="form-control-label col-2">--}}
    {{--练习总分--}}
    {{--</label>--}}
    {{--<span class="col-9 audio_condition p-0" style="line-height: 32px;">100分</span>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="row justify-content-center">--}}
    {{--<div class="col-10">--}}
    {{--<div class="form-group">--}}
    {{--<label class="form-control-label col-2">--}}
    {{--练习分数--}}
    {{--</label>--}}
    {{--<span class="col-9 audio_condition p-0" style="line-height: 32px;">60分</span>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="row justify-content-center">--}}
    {{--<div class="col-10">--}}
    {{--<div class="form-group">--}}
    {{--<label class="form-control-label col-2">--}}
    {{--练习时长--}}
    {{--</label>--}}
    {{--<span class="col-9 audio_condition p-0" style="line-height: 32px;">40分</span>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--  作业  --}}
    {{--<div class="row justify-content-center">--}}
    {{--<div class="col-10">--}}
    {{--<div class="form-group">--}}
    {{--<label class="form-control-label col-2">--}}
    {{--作业总分--}}
    {{--</label>--}}
    {{--<span class="col-9 audio_condition p-0" style="line-height: 32px;">100分</span>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="row justify-content-center">--}}
    {{--<div class="col-10">--}}
    {{--<div class="form-group">--}}
    {{--<label class="form-control-label col-2">--}}
    {{--及格分数--}}
    {{--</label>--}}
    {{--<span class="col-9 audio_condition p-0" style="line-height: 32px;">60分</span>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="row justify-content-center">--}}
    {{--<div class="col-10">--}}
    {{--<div class="form-group">--}}
    {{--<label class="form-control-label col-2">--}}
    {{--作业时长--}}
    {{--</label>--}}
    {{--<span class="col-9 audio_condition p-0" style="line-height: 32px;">40分</span>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="row justify-content-center">--}}
    {{--<div class="col-10">--}}
    {{--<div class="form-group">--}}
    {{--<label class="form-control-label col-2">--}}
    {{--SEO关键字--}}
    {{--</label>--}}
    {{--<input class="form-control col-9" type="text" placeholder="">--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--  考试  --}}
    {{--<div class="row justify-content-center">--}}
    {{--<div class="col-10">--}}
    {{--<div class="form-group">--}}
    {{--<label class="form-control-label col-2">--}}
    {{--试卷总分--}}
    {{--</label>--}}
    {{--<span class="col-9 audio_condition p-0" style="line-height: 32px;">100分</span>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="row justify-content-center">--}}
    {{--<div class="col-10">--}}
    {{--<div class="form-group">--}}
    {{--<label class="form-control-label col-2">--}}
    {{--及格分数--}}
    {{--</label>--}}
    {{--<span class="col-9 audio_condition p-0" style="line-height: 32px;">80分</span>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="row justify-content-center">--}}
    {{--<div class="col-10">--}}
    {{--<div class="form-group">--}}
    {{--<label class="form-control-label col-2">--}}
    {{--考试时长--}}
    {{--</label>--}}
    {{--<span class="col-9 audio_condition p-0" style="line-height: 32px;">6</span>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}


    {{--<div class="row justify-content-center">--}}
    {{--<div class="col-10">--}}
    {{--<div class="form-group">--}}
    {{--<label class="form-control-label col-2">--}}

    {{--</label>--}}
    {{--<div class="col-9 p-0">--}}
    {{--<div class="custom-control custom-checkbox">--}}
    {{--<input type="checkbox" class="custom-control-input" value="1" name="task-is-optional" id="customCheck1">--}}
    {{--<label class="custom-control-label" for="customCheck1">设为选修</label>--}}
    {{--</div>--}}
    {{--<p class="xuanxiu_warring">--}}
    {{--选修任务是否学习，不会影响下一任务的解锁，学习结果不会计入学习进度、学习统计中。在课程页面的目录中，将不会显示选修任务。--}}
    {{--</p>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}


</div>
<div class="modal-footer">
    <button type="button" class="btn btn-sm btn-outline-primary m-0" data-dismiss="modal" style="display: block;">取消</button>
    <button type="button"
            class="btn btn-sm btn-primary m-0"
            id="edit_task" data-route="{{ route('manage.tasks.update', [$chapter, $task]) }}"
            data-type="{{ $task->target_type == 'homework' ? $task->target->type : $task->target_type}}"
            data-task="{{ $task->type }}"
            data-media="{{ $task->target_id }}"
            style="display: block;"
    >确定
    </button>
</div>
<script>
    $(function () {
        // 富文本编辑器
//        if ($('#editor').data('con')) {
//            ue.setContent($('#editor').data('con'));
//        }

        // 完成条件的选择
        // $('#edit-task-finish-type').change(function () {
        //
        //
        //     if ($(this).val() == 'time') {
        //
        //         $('#edit-task-finish-detail-con').css('display', 'flex');
        //
        //     } else {
        //         $('#edit-task-finish-detail-con').css('display', 'none');
        //     }
        // })

        /**
         * 视频上传触发的函数
         */
        $('#edit_video-up').change(function (event) {
            var files = event.target.files;

            var driver = $('#content-body').data('driver');

            if (driver == 'local') {

                aetherupload(files[0], 'video').success(function(){

                    $('#edit_file-video-key').val(this.savedPath);
                    $('#edit_file-video-hash').val(this.resourceHash);

                }).upload();

            } else {
                uploadFile(files, $(this).data('token'), 'video', '', function (type, res) {
                    $('.progress-wrapper').show();
                    if (type == 'complete') {
                        // 上传成功之后将文件key放入隐藏id
                        $('#edit_file-video-key').val(res.key);
                        $('#edit_file-video-hash').val(res.hash);
                        edu.alert('success', '视频上传成功!');
                    } else if (type == 'next') {
                        $('#edit_videoprogress').html(res.total.percent.toFixed(2)+'%');
                        $('#edit_videoprogress').css('left', res.total.percent.toFixed(2)+'%');
                        $('#edit__videoprogress').css('width', res.total.percent.toFixed(2)+'%');

                    } else if (type == 'exist') {
                        $('#edit_file-video-key').val(res.data.url);
                        $('#edit_file-video-hash').val(res.data.hash);

                        $('#edit_videoprogress').html('100%');
                        $('#edit_videoprogress').css('left', '100%');
                        $('#edit__videoprogress').css('width', '100%');

                        edu.alert('success', '视频上传成功!');

                    } else if (type == 'err') {
                        edu.alert('danger', res.message);
                    }
                });
            }

        });

        /**
         * 音频上传触发的函数
         */
        // $('#edit_audio-up').change(function (event) {
        //     var files = event.target.files;
        //     var driver = $('#content-body').data('driver');
        //
        //     if (driver == 'local') {
        //
        //         aetherupload(files[0], 'file').success(function(){
        //             $('#edit_file-audio-key').val(this.savedPath);
        //             $('#edit_file-audio-hash').val(this.resourceHash);
        //
        //         }).upload();
        //
        //     } else {
        //         uploadFile(files, $(this).data('token'), 'audio', '', function (type, res) {
        //             $('.progress-wrapper').show();
        //             if (type == 'complete') {
        //                 // 上传成功之后将文件key放入隐藏id
        //                 $('#edit_file-audio-key').val(res.key);
        //                 $('#edit_file-audio-hash').val(res.hash);
        //                 edu.alert('success', '音频上传成功!');
        //             } else if (type == 'next') {
        //                 $('#edit_audioprogress').html(res.total.percent.toFixed(2)+'%');
        //                 $('#edit_audioprogress').css('left', res.total.percent.toFixed(2)+'%');
        //                 $('#edit__audioprogress').css('width', res.total.percent.toFixed(2)+'%');
        //
        //             } else if (type == 'exist') {
        //                 $('#edit_file-audio-key').val(res.data.url);
        //                 $('#edit_file-audio-hash').val(res.data.hash);
        //
        //                 $('#edit_audioprogress').html('100%');
        //                 $('#edit_audioprogress').css('left', '100%');
        //                 $('#edit__audioprogress').css('width', '100%');
        //
        //                 edu.alert('success', '音频上传成功!');
        //
        //             } else if (type == 'err') {
        //                 edu.alert('danger', res.message);
        //             }
        //         });
        //     }
        //
        // });

        /**
         * 文档上传触发的函数
         */
        // $('#edit_doc-up').change(function (event) {
        //     var files = event.target.files;
        //     var driver = $('#content-body').data('driver');
        //
        //     if (driver == 'local') {
        //
        //         aetherupload(files[0], 'file').success(function(){
        //             $('#edit_file-doc-key').val(this.savedPath);
        //             $('#edit_file-doc-hash').val(this.resourceHash);
        //
        //         }).upload();
        //
        //     } else {
        //         uploadFile(files, $(this).data('token'), 'doc', '', function (type, res) {
        //             $('.progress-wrapper').show();
        //             if (type == 'complete') {
        //                 // 上传成功之后将文件key放入隐藏id
        //                 $('#edit_file-doc-key').val(res.key);
        //                 $('#edit_file-doc-hash').val(res.hash);
        //                 edu.alert('success', '文档上传成功!');
        //             } else if (type == 'next') {
        //                 $('#edit_docprogress').html(res.total.percent.toFixed(2)+'%');
        //                 $('#edit_docprogress').css('left', res.total.percent.toFixed(2)+'%');
        //                 $('#edit__docprogress').css('width', res.total.percent.toFixed(2)+'%');
        //
        //             } else if (type == 'exist') {
        //                 $('#edit_file-doc-key').val(res.data.url);
        //                 $('#edit_file-doc-hash').val(res.data.hash);
        //
        //                 $('#edit_docprogress').html('100%');
        //                 $('#edit_docprogress').css('left', '100%');
        //                 $('#edit__docprogress').css('width', '100%');
        //
        //                 edu.alert('success', '文档上传成功!');
        //
        //             } else if (type == 'err') {
        //                 edu.alert('danger', res.message);
        //             }
        //         });
        //     }
        //
        // });

        /**
         * PPT上传触发的函数
         */
        // $('#edit_ppt-up').change(function (event) {
        //     var files = event.target.files;
        //     var driver = $('#content-body').data('driver');
        //
        //     if (driver == 'local') {
        //
        //         aetherupload(files[0], 'file').success(function(){
        //             $('#edit_file-ppt-key').val(this.savedPath);
        //             $('#edit_file-ppt-hash').val(this.resourceHash);
        //
        //         }).upload();
        //
        //     } else {
        //         uploadFile(files, $(this).data('token'), 'ppt', '', function (type, res) {
        //             $('.progress-wrapper').show();
        //             if (type == 'complete') {
        //                 // 上传成功之后将文件key放入隐藏id
        //                 $('#edit_file-ppt-key').val(res.key);
        //                 $('#edit_file-ppt-hash').val(res.hash);
        //                 edu.alert('success', 'ppt上传成功!');
        //             } else if (type == 'next') {
        //                 $('#edit_pptprogress').html(res.total.percent.toFixed(2)+'%');
        //                 $('#edit_pptprogress').css('left', res.total.percent.toFixed(2)+'%');
        //                 $('#edit__pptprogress').css('width', res.total.percent.toFixed(2)+'%');
        //
        //             } else if (type == 'exist') {
        //                 $('#edit_file-ppt-key').val(res.data.url);
        //                 $('#edit_file-ppt-hash').val(res.data.hash);
        //
        //                 $('#edit_pptprogress').html('100%');
        //                 $('#edit_pptprogress').css('left', '100%');
        //                 $('#edit__pptprogress').css('width', '100%');
        //
        //                 edu.alert('success', 'PPT上传成功!');
        //
        //             } else if (type == 'err') {
        //                 edu.alert('danger', res.message);
        //             }
        //         });
        //     }
        //
        // });

        /**
         * zip上传触发的函数
         */
        // $('#edit_zip-up').change(function (event) {
        //     var files = event.target.files;
        //     var driver = $('#content-body').data('driver');
        //
        //     if (driver == 'local') {
        //
        //         aetherupload(files[0], 'file').success(function(){
        //             $('#edit_file-zip-key').val(this.savedPath);
        //             $('#edit_file-zip-hash').val(this.resourceHash);
        //
        //         }).upload();
        //
        //     } else {
        //         uploadFile(files, $(this).data('token'), 'ppt', '', function (type, res) {
        //             $('.progress-wrapper').show();
        //             if (type == 'complete') {
        //                 // 上传成功之后将文件key放入隐藏id
        //                 $('#edit_file-zip-key').val(res.key);
        //                 $('#edit_file-zip-hash').val(res.hash);
        //                 edu.alert('success', 'ppt上传成功!');
        //             } else if (type == 'next') {
        //                 $('#edit_zipprogress').html(res.total.percent.toFixed(2)+'%');
        //                 $('#edit_zipprogress').css('left', res.total.percent.toFixed(2)+'%');
        //                 $('#edit__zipprogress').css('width', res.total.percent.toFixed(2)+'%');
        //
        //             } else if (type == 'exist') {
        //                 $('#edit_file-zip-key').val(res.data.url);
        //                 $('#edit_file-zip-hash').val(res.data.hash);
        //
        //                 $('#edit_zipprogress').html('100%');
        //                 $('#edit_zipprogress').css('left', '100%');
        //                 $('#edit__zipprogress').css('width', '100%');
        //
        //                 edu.alert('success', '文件上传成功!');
        //
        //             } else if (type == 'err') {
        //                 edu.alert('danger', res.message);
        //             }
        //         });
        //     }
        //
        // });

        // 展示不同的资源上传
        function showTwoPage(type) {
            $('.content_upload:not(:last-child)').css('display', 'none');
            $('#edit_content-' + type).css('display', 'block');
        }

        // 选择教学类型（图文，音频，视频）
        // $('.edit_control_item').click(function () {
        //     $('#edit_task').data('type', $(this).data('type'));
        //     showTwoPage($(this).data('type'));
        // });

        // 初始化教学类型的选择
        showTwoPage($('#edit_task').data('type'));

        // 选择教学手段的
        $('.custom-control-input').change(function(){
            $('#edit_task').attr('data-task', $(this).val());
        });

        // 编辑
        $('#edit_task').click(function () {

            var route = $(this).data('route');

            // 数据验证
            var target_type = $('#edit_task').data('type');

            if (!target_type) {
                edu.alert('danger', '请选择教学内容');
                return false;
            }

            var type = $(this).data('task');

            if (!type) {
                edu.alert('danger', '请选择教学手段');
                return false;
            }


            // 组装参数
            var data = {
                target_type: target_type,
                // 教学类型
                type: type, // 教学类型-从枚举里面循环

                // 是否免费
                is_free: $("input[name='edit-task-is-free']:checked").val(),
                // 完成类型
                finish_type: $('#edit-task-finish-type option:selected').val(),
                // 时间类型，完成限制
                finish_detail: $('#edit-task-finish-detail').val(),

                // 资源id
                target_id : $('#edit_task').data('media'),
            };

            $('#edit_seo_key').val() ? data.keyword = $('#edit_seo_key').val() :'';
            $('#edit_seo_des').val() ? data.describe = $('#edit_seo_des').val() :'';

            if (target_type == 'text') {
                data.title = $('#edit_task-title-text').val();
                data.body = edit_ue.getContent();
                data.length = $('#edit_task-length-text').val();

            } else if (target_type == 'video') {

                data.media_uri = $('#edit_file-video-key').val();

                data.hash = $('#edit_file-video-hash').val();

                data.title = $('#edit_task-title-video').val();

            } else if (target_type == 'audio') {

                data.media_uri = $('#edit_file-audio-key').val();
                data.hash = $('#edit_file-audio-hash').val();
                data.title = $('#edit_task-title-audio').val();

            } else if (target_type == 'doc') {

                data.media_uri = $('#edit_file-doc-key').val();
                data.hash = $('#edit_file-doc-hash').val();
                data.title = $('#edit_task-title-doc').val();
                if ($('#edit_task-length-doc').val()) {
                    data.length = $('#edit_task-length-doc').val();
                }

            } else if (target_type == 'ppt') {

                data.media_uri = $('#edit_file-ppt-key').val();
                data.hash = $('#edit_file-ppt-hash').val();
                data.title = $('#edit_task-title-ppt').val();
                if ($('#edit_task-length-ppt').val()) {
                    data.length = $('#edit_task-length-ppt').val();
                }

            } else if (target_type == 'paper') {

                data.media_uri = $('#edit_file-paper-key').val();
                data.title = $('#edit_task-title-paper').val();
                if ($('#edit_task-length-paper').val()) {
                    data.length = $('#edit_task-length-paper').val();
                }

            }else if (target_type == 'homework') {

                data.media_uri = $('#edit_file-homework-key').val();
                data.title = $('#edit_task-title-homework').val();
                if ($('#edit_task-length-homework').val()) {
                    data.length = $('#edit_task-length-homework').val();
                }

            }else if (target_type == 'practice') {

                data.media_uri = $('#edit_file-practice-key').val();
                data.title = $('#edit_task-title-practice').val();
                if ($('#edit_task-length-practice').val()) {
                    data.length = $('#edit_task-length-practice').val();
                }

            }else if (target_type == 'zip') {

                data.media_uri = $('#edit_file-zip-key').val();
                data.hash = $('#edit_file-zip-hash').val();
                data.title = $('#edit_task-title-zip').val();
                if ($('#edit_task-length-zip').val()) {
                    data.length = $('#edit_task-length-zip').val();
                }

            }else {
                edu.alert('danger', '非法类型!');
                return false;
            }

            $.ajax({
                url: route,
                type: 'put',
                data: data,
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
