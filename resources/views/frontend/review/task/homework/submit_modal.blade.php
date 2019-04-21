<div class="modal submit-modal-danger fade" id="modal_5" tabindex="-1" role="dialog" aria-labelledby="modal_5"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="homework_title">
                    提交作业
                </div>
                <hr class="m-0">
                <div class="submit_content">
                    {{--作业标题--}}
                    <div class="row submit_item">
                        <div class="form-group col-xl-12">
                            <div class="input-group input-group-transparent">
                                <span class="important"></span>
                                <label class="control-label col-xl-1 p-0 mr-4 submit_item_title text-left">
                                    作业标题
                                </label>
                                <input type="text" name="title" id="title"
                                       class="form-control col-xl-10 submit_item_input"
                                       placeholder="请输入标题">
                            </div>
                        </div>
                    </div>

                    @foreach($task->target->post_type as $type)
                        @if($type == 'zip')
                            {{--作业附件--}}
                            <div class="row submit_item">
                                <div class="form-group col-xl-12">
                                    <div class="input-group input-group-transparent">
                                        <span class="important"></span>
                                        <label class="control-label col-xl-1 p-0 mr-4 submit_item_title text-left">
                                            作业包
                                        </label>
                                        <input type="file" name="file-2[]" id="file-zip"
                                               class="custom-input-file custom-input-file--2"
                                               data-token="{{ route('qiniu.token.homework') }}"
                                               data-multiple-caption="{count} files selected"
                                               accept="application/x-zip-compressed,application/octet-stream"
                                        />
                                        <input type="hidden" name="" value="" id="post_zip">
                                        <label for="file-zip">
                                            <i class="iconfont">&#xe61f;</i>
                                            <span>选择文件 (zip or rar)</span>
                                        </label>
                                        <div class="file_limit">
                                            <i class="iconfont">&#xe618;</i>
                                            <span>只支持上传一份压缩包</span>
                                        </div>
                                        <div class="file_content col-xl-12" id="zip-content" style="display:none">
                                            <i class="iconfont">&#xe65c;</i>
                                            <span class="file_name" id="zip-name">文件名</span>
                                            <div class="course_progress">
                                                <div class="progress_now" data-width="0" id="zip-progress"></div>
                                            </div>
                                            <div class="remove_file">
                                                <a href="javascript:;" class="remove_link">删除</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($type == 'code')
                            {{--作业代码--}}
                            <div class="row submit_item">
                                <div class="form-group col-xl-12">
                                    <div class="input-group input-group-transparent">
                                        <span class="important"></span>
                                        <label class="control-label col-xl-1 p-0 mr-4 submit_item_title text-left">
                                            代码
                                        </label>
                                        <script id="code" name="code" type="text/plain" class="col-xl-10 p-0"></script>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($type == 'img')
                            {{--作业效果--}}
                            <div class="row submit_item">
                                <div class="form-group col-xl-12">
                                    <div class="input-group input-group-transparent">
                                        <span class="important"></span>
                                        <label class="control-label col-xl-1 p-0 mr-4 submit_item_title text-left">
                                            效果图
                                        </label>
                                        <input type="file" name="file-img[]" id="file-img"
                                               data-domain="{{ http_format(\Facades\App\Models\Setting::namespace(\App\Enums\SettingType::QINIU)['public_domain']) }}"
                                               data-token="{{ route('qiniu.token.homework') }}"
                                               class="custom-input-file custom-input-file--2"
                                               accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff"
                                               data-multiple-caption="{count} files selected" multiple/>
                                        <label for="file-img">
                                            <i class="iconfont">&#xe61f;</i>
                                            <span>选择文件 (jpg or png)</span>
                                        </label>
                                        <div class="file_img_content col-xl-12" id="img-content"></div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    {{-- 作业备注 --}}
                    <div class="row submit_item mb-0">
                        <div class="form-group col-xl-12">
                            <div class="input-group input-group-transparent">
                                <span class="important"></span>
                                <label class="control-label col-xl-1 p-0 mr-4 submit_item_title text-left">
                                    备注
                                </label>
                                <textarea type="text" name="student_review" id="student_review"
                                          class="form-control col-xl-10 submit_item_input text_input"
                                          placeholder="请输入作业备注"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-item" data-dismiss="modal">取消</button>
                        <button type="button" id="submit" data-post="{{ json_encode($task->target->post_type) }}"
                                {{--data-route="{{ route('tasks.result.homework', $task) }}"--}}
                                data-route="{{ renderTaskResultRoute('tasks.result.homework',[$task], $member) }}"
                                class="btn btn-primary btn-item">提交作业
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/vendor/ueditor/ueditor.config.js"></script>
<script src="/vendor/ueditor/ueditor.all.js"></script>
<script src="/tools/qiniu/qiniu2.min.js"></script>
<script src="/tools/sha1.js"></script>
<script src="/tools/qetag.js"></script>
<script src="/tools/qiniu/qiniu-luwnto.js"></script>
<script>
    // 富文本编辑器实例化
    var code = UE.getEditor('code', {
        UEDITOR_HOME_URL: '/vendor/ueditor/',
        serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
    });

    // img上传
    $('#file-img').change(function (event) {

        var files = event.target.files;
        var domain = $(this).data('domain');
        $('#img-content').empty();
        // 显示进度条并显示文件名
        $.each(files, function (index, value) {
            var arr = [value];

            $('#img-content').append(` <div class="img_item">
                        <div class="img_content">
                            <img src="/imgs/classroom.png" alt="" id="${value.size}_img">
                            <input type="hidden" name="post_img[]" value="" id="${value.size}_val">
                            <div class="file_speed" style="height:100%"></div>
                        </div>
                        <div class="img_message_content">
                            <div class="img_message_title">
                                ${value.name}
                            </div>
                            <div class="course_progress">
                                <div class="progress_now" data-width="0" id="${value.size}_progress"></div>
                            </div>
                            <div class="file_size">

                                <a href="javascript:;" class="remove_file_message">删除</a>
                            </div>
                        </div>
                    </div>`);

            uploadFile(arr, $('#file-img').data('token'), 'img', '', function (type, res) {

                if (type == 'complete') {

                    $('#' + value.size + '_val').val(res.key);
                    $('#' + value.size + '_img').attr('src', domain + '/' + res.key);

                } else if (type == 'next') {

                    $('#' + value.size + '_progress').css('width', res.total.percent.toFixed(2) + '%');
                    $('.file_speed').css('height', Math.abs(res.total.percent.toFixed(2) - 100) + '%');

                } else if (type == 'exist') {

                } else if (type == 'err') {
                    edu.alert('danger', res.message);
                }
            });
        });

    });

    // 资料包上传
    $('#file-zip').change(function (event) {

        var files = event.target.files;

        uploadFile(files, $('#file-zip').data('token'), 'img', '', function (type, res) {

            $('#zip-content').css('display', 'block');

            if (type == 'complete') {

                $('#post_zip').val(res.key);

                $('#zip-name').html(files[0].name);

            } else if (type == 'next') {

                $('#zip-progress').css('width', res.total.percent.toFixed(2) + '%');

            } else if (type == 'exist') {

            } else if (type == 'err') {
                edu.alert('danger', res.message);
            }
        });

    });

    // 效果图删除按钮
    $(document).on('click', ".remove_file_message", function () {
        $(this).parents('.img_item').remove();
    })

    // 资料包删除
    $('.remove_link').click(function () {
        $('#zip-content').css('display', 'none');
        $('#post_zip').val('');
    })

    // 确认提交作业
    $('#submit').click(function () {

        var data = {};

        var post = $(this).data('post');

        // 作业标题
        var title = $('#title').val();
        if (!title) {
            edu.alert('danger', '请填写作业标题');
            return false;
        }

        data.title = title;
        // 作业备注
        var student_review = $('#student_review').val();

        student_review ? data.student_review = student_review : '';

        var bl = true;
        // 附件或者图片或者代码
        $.each(post, function (index, value) {
            if (value == 'img') {
                console.log();
                var post_img = $("input[name='post_img[]']");

                if (!post_img.length) {
                    edu.alert('danger', '请上传效果图');
                    bl = false;
                    return false;
                }
                data['post_img[]'] = [];

                post_img.each(function (index, item) {
                    data['post_img[]'].push($(item).val());
                })
            }

            if (value == 'zip') {
                var post_zip = $('#post_zip').val();

                if (!post_zip) {
                    edu.alert('danger', '请上传zip格式资料包');
                    bl = false;
                    return false;
                }
                data.package = post_zip;
            }

            if (value == 'code') {
                var post_code = code.getContent();

                if (!post_code) {
                    edu.alert('danger', '请填写代码');
                    bl = false;
                    return false;
                }
                data.code = post_code;
            }
        })

        if (!bl) {
            return false;   //结束function
        }

        // 发送请求
        $.ajax({
            url: $(this).data('route'),
            type: 'post',
            data: data,
            success: function (res) {

                if (res.status == '200') {
                    edu.alert('success', '作业提交成功');
                    window.location.reload();
                } else {
                    edu.alert('error', res.message);
                }
            }
        })

    })
</script>