$(function () {
    /**
     * 创建阶段
     */
    $('#chapter-add-btn').click(function () {

        if(! $('#chapter-title').val()) {
            edu.alert('danger', '请输入阶段的标题'); return false;
        }

        if(! $('#chapter-goals').val()) {
            edu.alert('danger', '请输入阶段的目标'); return false;
        }

        var data = {
            title: $('#chapter-title').val(),
            goals: $('#chapter-goals').val(),
        };
        $.ajax({
            url: $(this).data('url'),
            type: 'post',
            data: data,
            success: function (res) {
                if (res.status == 200) {
                    edu.alert('success', '阶段创建成功');
                    window.location.reload();
                } else {
                    edu.alert('danger', res.message);
                }
            }
        });
    });

    /**
     * 创建关
     */
    $('#chapter-add-btn-p').click(function () {

        if(! $('#chapter-title-p').val()) {
            edu.alert('danger', '请输入关的标题'); return false;
        }

        if(! $('#chapter-goals-p').val()) {
            edu.alert('danger', '请输入关的目标'); return false;
        }

        if(! $(this).data("pid")) {
            edu.alert('danger', '请选择阶段'); return false;
        }

        var data = {
            title: $('#chapter-title-p').val(),
            goals: $('#chapter-goals-p').val(),
            parent_id: $(this).data("pid"), // 创建关的时候pid不为0
        };
        $.ajax({
            url: $(this).data('url'),
            type: 'post',
            data: data,
            success: function (res) {
                if (res.status == 200) {
                    edu.alert('success', '关创建成功');
                    window.location.reload();
                } else {
                    edu.alert('danger', res.messsage);
                }
            }
        });
    });

    /**
     * 打开创建关的模态框的时候把按钮的id富裕模态框中的提交按钮
     */
    $('.chapter-open-btn-p').click(function () {
        // 为创建按钮设置pid
        $('#chapter-add-btn-p').data('pid', $(this).data('pid'));
    });

    /**
     * 点击不同类型, 设置不同的类型, 便于打开不同的页面
     */
    $('.task-type').click(function () {
        // 为下一步按钮类型
        $('#next-step').data('type', $(this).data('type'));
        $('#next-step').data('step', 2);
    });

    /**
     * 点击下一步, 显示相关的页面 type  step相关
     */
    $('#next-step').click(function () {

        // 如果step 是2, 返回对应类型的页面
        var step = $(this).data('step');
        var type = $(this).data('type');
        // data 缓存策略，是 1 不通过
        if (step == 1) {
            edu.alert('danger', '请选择教学形式!');
            return false;
        }

        if($('input[name=task-type]:checked').val() === undefined) {
            edu.alert('danger', '请选择教学类型!');
            return false;
        }

        // 如果step是2 ,进行第二步骤
        if (step == 2) {

            showTwoPage(type);
            // 设置按钮为第三步
            $(this).data('step', 3);
        }

        // 如果step 是3, 走到第三步骤
        if (step == 3) {

            // 任务主体数据验证
            if (!$('#task-title-'+type).val()) {
                edu.alert('danger', '请输入任务的标题!');
                return false;
            }

            if (type == 'text') {
                if (!ue.getContent()) {
                    edu.alert('danger', '请完善资源内容!');
                    return false;
                }
            } else {
                if (!$('#file-'+type+'-key').val()) {
                    edu.alert('danger', '请完善资源内容!');
                    return false;
                }
            }



            showLastPage(type);
            $(this).data('step', 4);
        }

    });

    /**
     * 下一步显示第二步骤的内容
     * @param type
     */
    function showTwoPage(type) {
        $('.content-control').hide();
        $('#content-' + type).show();
        $('#step-one').removeClass('active');
        $('#step-one').addClass('pass');
        $('#step-three').removeClass('active');
        $('#step-three').addClass('pass');
        $('#step-two').addClass('active');
        $('#pre-step').show();
    }

    $('.append_cut').on({
        click: function (e) {
            var chapterId = $(this).data('chapter');

            // 为模态框设置任务和章节的id
            $('#now-chapter-id').val(chapterId);


            $('#task-add-btn').show();
            $('#task-add-btn').attr('data-type', 'video');

            showTwoPage('video');

            // 清空内容
//                    $('.form-con').val('');
//                    ue.setContent('');

            $('#modal_5').modal('toggle');
            return false;
        }
    });

    /**
     * 下一步显示第三步骤的内容
     */
    function showLastPage(type) {
        $('#content-' + type).hide();
        $('#last_step').show();
        $('#step-two').removeClass('active');
        $('#step-two').addClass('pass');
        $('#step-three').addClass('active');
        $('#next-step').hide();
        $('#task-add-btn').show();
    }

    $('#task-finish-type').change(function(){


        if ($(this).val() == 'time') {

            $('#task-finish-detail-con').css('display', 'flex');

        } else {
            $('#task-finish-detail-con').css('display', 'none');
        }
    })

    /**
     * 保存要创建的任务
     */
    $('#task-add-btn').click(function () {
        var nextBtn = $('#task-add-btn');

        var type = nextBtn.data('type');
        if (!type) {
            edu.alert('danger', '您需要重新添加！');
            return false;
        }
        if (!$('#task-title-video').val()) {
            edu.alert('danger', '请输入标题');
            return false;
        }

        if (!$('#file-video-key').val() || !$('#file-video-hash').val()) {
            edu.alert('danger', '请上传资源');
            return false;
        }

        // 封装要上传的数据
        var data = {
            // 教学类型
            type: 'c-task', // 教学类型-从枚举里面循环
            // 是否免费
            is_free: 1,
            // 完成类型
            finish_type: 'end',
            // 时间类型，完成限制
            finish_detail: 0,
            // 资源类型
            target_type: nextBtn.data('type'),
            // seo关键词
            keyword:$('#seo_key').val(),
            // seo描述
            describe:$('#seo_des').val(),
        };

        var chapterId = $('#now-chapter-id').val();

        if (!chapterId) {
            edu.alert('danger', '请选择版本！');return false;
        }

        var url = '/manage/chapters/' + chapterId + '/tasks/store';

        if (type == 'video') {
            data.media_uri = $('#file-video-key').val();
            data.hash = $('#file-video-hash').val();
            data.title = $('#task-title-video').val();

        }else {
            edu.alert('danger', '非法类型!');
            return false;
        }


        $.ajax({
            url: url,
            type: 'post',
            data: data,
            success: function (res) {
                if (res.status == '200') {
                    edu.alert('success', '任务创建成功');
                    window.location.reload();
                } else {
                    edu.alert('danger', res.message);
                }

            }
        });
    });

    /**
     * 点击上一步, 显示相关的页面 type  step相关
     */
    $('#pre-step').click(function () {
        // 如果step 是2, 返回对应类型的页面
        var nextBtn = $('#next-step');
        var step = nextBtn.data('step');
        var type = nextBtn.data('type');
        $('#task-add-btn').hide();
        // 如果step是4 ,进行第二步骤
        if (step == 4) {
            showTwoPagePre(type);
            // 设置按钮为第三步
            nextBtn.data('step', 3);
        }

        // 如果step 是3, 走到第一步骤
        if (step == 3) {
            showOnePagePre(type);
            nextBtn.data('step', 2);
        }

    });

    /**
     * 上一步的时候显示第二个页面
     */
    function showTwoPagePre(type) {
        $('#last_step').hide();
        $('#content-' + type).show();
        $('#next-step').show();
        $('#step-three').removeClass('active');
        $('#step-two').removeClass('pass');
        $('#step-two').addClass('active');
    }

    /**
     * 上一步显示第一个页面
     */
    function showOnePagePre(type) {
        $('#content-' + type).hide();
        $('.content-control').show();
        $('#pre-step').hide();
        $('#step-two').removeClass('active');
        $('#step-one').removeClass('pass');
        $('#step-one').addClass('active');
    }

    /**
     * 视频上传触发的函数
     */
    $('#video-up').change(function (event) {
        var files = event.target.files;
        var driver = $('#content-body').data('driver');

        if (driver == 'local') {

                aetherupload(files[0], 'video').success(function(){

                    $('#file-video-key').val(this.savedPath);
                    $('#file-video-hash').val(this.resourceHash);

                }).upload();

        } else {
            uploadFile(files, $(this).data('token'), 'video', '', function (type, res) {
                $('.progress-wrapper').show();
                if (type == 'complete') {
                    // 上传成功之后将文件key放入隐藏id
                    $('#file-video-key').val(res.key);
                    $('#file-video-hash').val(res.hash);
                    edu.alert('success', '视频上传成功!');
                } else if (type == 'next') {
                    $('#videoprogress').html(res.total.percent.toFixed(2)+'%');
                    $('#videoprogress').css('left', res.total.percent.toFixed(2)+'%');
                    $('#_videoprogress').css('width', res.total.percent.toFixed(2)+'%');

                } else if (type == 'exist') {
                    $('#file-video-key').val(res.data.url);
                    $('#file-video-hash').val(res.data.hash);

                    $('#videoprogress').html('100%');
                    $('#videoprogress').css('left', '100%');
                    $('#_videoprogress').css('width', '100%');

                    edu.alert('success', '视频上传成功!');

                } else if (type == 'err') {
                    edu.alert('danger', res.message);
                }
            });
        }

    });

    /**
     * 发布任务的操作
     */
    $('.task-plush').click(function () {
        if (!confirm('确认修改这个任务的状态吗?')) {
            return false;
        }

        $.ajax({
            url: $(this).data('plush'),
            data: {status: $(this).data('status')},
            type: 'patch',
            success: function (res) {
                if (res.status == 200) {
                    edu.alert('success', '任务操作成功!');
                    window.location.reload();
                } else {
                    edu.alert('danger', res.message);
                }
            }
        })
    });

});
