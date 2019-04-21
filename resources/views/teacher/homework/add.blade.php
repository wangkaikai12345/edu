@extends('teacher.homework.homework_layout')

@section('title', '添加作业')

@section('homework_style')
    <link rel="stylesheet" href="{{ mix('/css/teacher/homework/add.css') }}">
    <link rel="stylesheet" href="/vendor/select2/dist/css/select2.min.css">
@endsection

@section('homework_content')
    <div class="czh_add_con col-md-9 p-0">
        <div class="add_head">
            <p>添加作业</p>
        </div>
        <div class="add_con">
            <form action="{{ route('manage.homework.store') }}" method="post" id="homework-create-form">
                <div class="work_czh row">
                    <div class="col-md-2 view_left"><span style="color:red">* </span></span>标题</div>
                    <div class="col-md-10 view_right">
                        <input class="view_r_input form-control" name="title" type="text">
                    </div>
                </div>
                <div class="work_czh row">
                    <div class="col-md-2 view_left">标签</div>
                    <div class="col-md-10 view_right">
                        <select id="label" name="tags[]" type="text"
                                class="form-control col-md-12 col-lg-9 col-xl-9"></select>
                    </div>
                </div>
                <div class="work_czh row">
                    <div class="col-md-2 view_left"><span style="color:red">* </span>分类</div>
                    <div class="col-md-10 view_right">
                        <div class="view_r_radio custom-control custom-radio">
                            <input type="radio" name="type" checked class="custom-control-input"
                                   id="type1" value="{{ \App\Enums\HomeworkType::HOMEWORK }}">
                            <label class="custom-control-label" for="type1">作业</label>
                        </div>
                        <div class="view_r_radio custom-control custom-radio">
                            <input type="radio" name="type" class="custom-control-input"
                                   id="type2" value="{{ \App\Enums\HomeworkType::EXERCISE }}">
                            <label class="custom-control-label" for="type2">练习</label>
                        </div>
                    </div>
                </div>
                <div class="work_czh row">
                    <div class="col-md-2 view_left"><span style="color:red">* </span>是否发布</div>
                    <div class="col-md-10 view_right">
                        <div class="view_r_radio custom-control custom-radio">
                            <input type="radio" name="status" checked class="custom-control-input"
                                   id="status1" value="{{ \App\Enums\Status::PUBLISHED }}">
                            <label class="custom-control-label" for="status1">发布</label>
                        </div>
                        <div class="view_r_radio custom-control custom-radio">
                            <input type="radio" name="status" class="custom-control-input"
                                   id="status2" value="{{ \App\Enums\Status::DRAFT }}">
                            <label class="custom-control-label" for="status2">不发布</label>
                        </div>
                    </div>
                </div>
                <div class="work_czh row">
                    <div class="col-md-2 view_left"><span style="color:red">* </span>提交类型</div>
                    <div class="col-md-10 view_right">
                        <div class="view_r_radio custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="post_type1"
                                   name="post_type[]" value="img">
                            <label class="custom-control-label" for="post_type1">图片</label>
                        </div>
                        <div class="view_r_radio custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="post_type2"
                                   name="post_type[]" checked value="zip">
                            <label class="custom-control-label" for="post_type2">附件</label>
                        </div>
                        <div class="view_r_radio custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="post_type3"
                                   name="post_type[]" value="code">
                            <label class="custom-control-label" for="post_type3">代码</label>
                        </div>
                    </div>
                </div>
                <div class="work_czh row">
                    <div class="col-md-2 view_left"><span style="color:red">* </span>作业描述</div>
                    <div class="col-md-10 view_right">
                        <div class="batV">
                            <script id="editor" name="about" type="text/plain"></script>
                        </div>
                    </div>
                </div>
                <div class="work_czh row">
                    <div class="col-md-2 view_left"><span style="color:red">* </span>解题提示</div>
                    <div class="col-md-10 view_right">
                        <div class="batV">
                            <script id="editor2" name="hint" type="text/plain"></script>
                        </div>
                    </div>
                </div>

                <div class="work_czh row">
                    <div class="col-md-2 view_left">讲解视频</div>
                    <div class="col-md-10 view_right">
                        <div class="float-left upload_wrap">
                            <input data-token="{{ route('manage.qiniu.token.hash') }}" type="file" name="" id="video"
                                   class="custom-input-file" accept="video/*"/>
                            <input type="hidden" name="video" id="video-key">
                            <input type="hidden" name="video_hash" id="video-hash">
                            <label for="video" class="file_upload">
                                <span>选择文件</span>
                            </label>

                            <div class="progress-wrapper" id="progress-div-video" style="display: none">
                                <h4 class="progress-tooltip" style="left: 0%;" id="video_progress_show">0%</h4>
                                <div class="progress" style="height: 3px;">
                                    <div class="progress-bar bg-primary"
                                         id="video_progress_color"
                                         role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                         aria-valuemax="100" style="width:0%;"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="work_czh row">
                    <div class="col-md-2 view_left">资料包</div>
                    <div class="col-md-10 view_right">
                        <div class="float-left upload_wrap">
                            <input data-token="{{ route('manage.qiniu.token.hash') }}" type="file" name="" id="package"
                                   class="custom-input-file"
                                   accept="application/x-zip-compressed,application/octet-stream"/>
                            <input type="hidden" name="package" id="package-key">
                            <input type="hidden" name="package_hash" id="package-hash">
                            <label for="package" class="file_upload">
                                <span>选择文件</span>
                            </label>
                            <div class="progress-wrapper" id="progress-div-file" style="display: none">
                                <h4 class="progress-tooltip" style="left: 0%;" id="file_progress_show">0%</h4>
                                <div class="progress" style="height: 3px;">
                                    <div class="progress-bar bg-primary"
                                         id="file_progress_color"
                                         role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                         aria-valuemax="100" style="width:0%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="work_czh row">
                    <div class="col-md-2 view_left"><span style="color:red">* </span>预计时间</div>
                    <div class="col-md-10 view_right">
                        <input class="view_r_input2 form-control" onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" onafterpaste="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" type="number" value="" name="time">
                        <p class="view_r_p">分钟</p>
                    </div>
                </div>
                <div class="work_czh row">
                    <div class="col-md-2 view_left"><span style="color:red">* </span>评分标准</div>
                    <div class="col-md-10 view_right">
                        <button type="button" class="btn btn-primary view_r_add_btn" data-toggle="modal"
                                data-target="#scoreNormal_modal">添加新的批改标准
                        </button>
                        <table class="scoringStandard table table-hover align-items-center">
                            <thead>
                            <tr>
                                <th scope="col" width="70">
                                    选择
                                </th>
                                <th scope="col">评分标准</th>
                                <th scope="col">设置分值</th>
                                <th scope="col">相关评语</th>
                                <th scope="col">创建人</th>
                            </tr>
                            </thead>
                            <tbody id="show-grades-list">
                                @foreach($grades as $grade)
                                <tr class="bg-white" scope="row">
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input select-grades" name="grades[]" value="{{ $grade->id }}"
                                                   id="grades{{ $grade->id }}">
                                            <label class="custom-control-label" for="grades{{ $grade->id }}"></label>
                                        </div>
                                    </td>
                                    <td id="grade_title_{{ $grade->id }}">{{ $grade->title }}</td>
                                    <td><input name="grade_num_{{ $grade->id }}" id="grade_num_{{ $grade->id }}" class="form-control num_inp" type="number"></td>
                                    <td>
                                        <button type="button" class="btn btn-link p_l_b" data-toggle="modal"
                                                data-target="#modal" data-url="{{ route('manage.homework.grade.show', $grade) }}">查看评语
                                        </button>
                                    </td>
                                    <td>{{ $grade->user->username }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="work_czh row">
                    <div class="col-md-2 view_left">批改标准</div>
                    <div class="col-md-10 view_right">
                        <ol class="view_r_ol" id="show-grade-content">

                        </ol>
                    </div>
                </div>
                <div class="work_czh">
                    <div class="view_right2">
                        <button class="btn btn-primary" type="submit" id="homework-create-btn">保存</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('teacher.homework.scoreNormal-modal')
@endsection

@section('homework_script')
    <script src="/vendor/ueditor/ueditor.config.js"></script>
    <script src="/vendor/ueditor/ueditor.all.js"></script>
    <script src="/vendor/select2/dist/js/select2.min.js"></script>

    <script type="text/javascript">
        var ue = UE.getEditor('editor', {
            UEDITOR_HOME_URL: '/vendor/ueditor/',
            serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
        });

        var ue = UE.getEditor('editor2', {
            UEDITOR_HOME_URL: '/vendor/ueditor/',
            serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
        });

        //默认显示内容必须在实例化编辑器之后才能显示
        ue.ready(function () {
            var $content = '';
            //默认显示内容
            ue.setContent($content);
            //文本框获取焦点时清空默认显示的内容
            ue.addListener("focus", function () {
                var nowText = ue.getContent();
                if (nowText == $content) {
                    ue.setContent('');
                }
            });
            //文本框是去焦点时,若内容为空则显示默认显示的内容
            ue.addListener("blur", function () {
                if (!ue.getContent()) {
                    ue.setContent('');
                }
            });
        });
        /**
         * 标签相关的select2搜索+多选
         */
        var labels = {!! $labels !!};

        $("#label").select2({
            maximumSelectionSize: 20,
            placeholder: '请输入标签名称',
            createSearchChoice: function (term, data) {
                if ($(data).filter(function () {
                        return this.text.localeCompare(term) === 0;
                    }).length === 0) {
                    return {id: term, text: term};
                }
            },
            multiple: true,
            data: labels,
        });
    </script>

    <script>
        /**
         * TODO 这些JS以后提取出成为单文件
         */
        window.onload = function () {
            // 指定表单
            var form = $('#homework-create-form');

            FormValidator.init(form, {
                rules: {
                    title: {
                        required: true,
                        maxlength: 20
                    },
                    tags: {
                        required: true,
                    },
                    post_type: {
                        required: true,
                    },
                    time: {
                        required: true,
                        min: 0,
                    }
                },
                messages: {
                    title: {
                        required: "标题不能为空！",
                        maxlength: '标题长度不能超过20'
                    },
                    tags: {
                        required: "标签不能为空！",
                    },
                    post_type: {
                        required: '必须选择至少一个提交类型',
                    },
                    time: {
                        required: '预计时间不能为空',
                        min: "预计时间最少为0",
                    }
                },
            }, function () {
                $.ajax({
                    url: form.attr('action'),
                    type: 'post',
                    data: form.serialize(),
                    success: function (result) {
                        if (result.status == '200') {
                            edu.alert('success', '作业添加成功!')
                            window.location.reload();
                        } else {
                            edu.alert('danger', result.message)
                        }
                    }
                });

                return false;
            });

            /**
             * 讲解视频上传触发的函数
             */
            $('#video').change(function (event) {
                var files = event.target.files;
                $('#progress-div-video').show();
                uploadFile(files, $(this).data('token'), 'zip', '', function (type, res) {
                    if (type == 'complete') {
                        // 上传成功之后将文件key放入隐藏id
                        $('#video-key').val(res.key);
                        $('#video-hash').val(res.hash);
                        edu.alert('success', '视频上传成功!');
                    } else if (type == 'next') {
                        var perc = res.total.percent.toFixed(2) + '%';
                        $('#video_progress_show').text(perc);
                        $('#video_progress_show').css('left', perc)
                        $('#video_progress_color').css('width', perc)
                    } else if (type == 'exist') {
                        $('#video-key').val(res.data.name);
                        $('#video-hash').val(res.data.hash);
                        $('#video_progress_show').text('100%');
                        $('#video_progress_show').css('left', '100%')
                        $('#video_progress_color').css('width', '100%')
                        edu.alert('success', '视频秒传成功!');
                    } else if (type == 'err') {
                        edu.alert('danger', res.message);
                    }
                });
            });

            /**
             * 讲解资料内容
             */
            $('#package').change(function (event) {
                var files = event.target.files;
                $('#progress-div-file').show();
                uploadFile(files, $(this).data('token'), 'zip', '', function (type, res) {
                    if (type == 'complete') {
                        // 上传成功之后将文件key放入隐藏id
                        $('#package-key').val(res.key);
                        $('#package-hash').val(res.hash);
                        edu.alert('success', '文档上传成功!');
                    } else if (type == 'next') {
                        var perc = res.total.percent.toFixed(2) + '%';
                        $('#file_progress_show').text(perc);
                        $('#file_progress_show').css('left', perc)
                        $('#file_progress_color').css('width', perc)
                    }  else if (type == 'exist') {
                        $('#package-key').val(res.data.name);
                        $('#package-hash').val(res.data.hash);
                        $('#file_progress_show').text('100%');
                        $('#file_progress_show').css('left', '100%')
                        $('#file_progress_color').css('width', '100%')
                        edu.alert('success', '文件秒传成功!');
                    } else if (type == 'err') {
                        edu.alert('danger', res.message);
                    }
                });
            });

            /**
             * 添加作业批改标准相关的JS
             */
                // 指定表单
            var gradeForm = $('#grade-create-form');

            FormValidator.init(gradeForm, {
                rules: {
                    title: {
                        required: true,
                        maxlength: 20
                    },
                    comment_bad: {
                        required: true,
                    },
                    comment_middle: {
                        required: true,
                    },
                    comment_good: {
                        required: true,
                    },
                    remarks: {
                        required: true,
                    },
                },
                messages: {
                    title: {
                        required: "标题不能为空！",
                        maxlength: '标题长度不能超过20'
                    },
                    comment_bad: {
                        required: "差评不能为空！",
                    },
                    comment_middle: {
                        required: "中评不能为空！",
                    },
                    comment_good: {
                        required: "好评不能为空！",
                    },
                    remarks: {
                        required: "备注不能为空！",
                    },
                },
            }, function () {
                $.ajax({
                    url: gradeForm.attr('action'),
                    type: 'post',
                    data: gradeForm.serialize(),
                    success: function (result) {
                        if (result.status == '200') {
                            edu.alert('success', '批改标准添加成功!');
                            var data = result.data;
                            var str = ' <tr class="bg-white" scope="row">\n' +
                                '                                <td>\n' +
                                '                                    <div class="custom-control custom-checkbox">\n' +
                                '                                        <input type="checkbox" class="custom-control-input select-grades" name="grades[]"\n' +
                                '                                               id="grades'+data.id+'" value="'+ data.id +'">\n' +
                                '                                        <label class="custom-control-label" for="grades'+ data.id +'"></label>\n' +
                                '                                    </div>\n' +
                                '                                </td>\n' +
                                '                                <td id="grade_title_'+ data.id +'">'+ data.title +'</td>\n' +
                                '                                <td><input name="grade_num_'+ data.id +'" id="grade_num_'+ data.id +'" class="form-control num_inp" type="number"></td>\n' +
                                '                                <td>\n' +
                                '                                    <button type="button" class="btn btn-link p_l_b" data-toggle="modal"\n' +
                                '                                            data-target="#seeComment_modal">查看评语\n' +
                                '                                    </button>\n' +
                                '                                </td>\n' +
                                '                                <td>' + data.username + '</td>\n' +
                                '                            </tr>';
                            $('#show-grades-list').append(str);
                        } else {
                            edu.alert('danger', '批改标准添加失败!')
                        }
                    }
                });

                return false;
            });

            /**
             * 处理勾选的评分标准显示
             */
//        <ol class="view_r_ol" id="show-grade-content">
//                <li>评分标准123123123123: 10分 <input type="hidden" name="grades_content[]" value="评分标准123123123123:10分"></li>
//                </ol>
            $('#show-grades-list').on('change', '.select-grades, .num_inp', function () {
                var str = '';
                // 不管选择还是取消, 全部重写显示评分标准
                $('.select-grades').each(function () {
                    if ($(this).is(':checked')) {
                        var id = $(this).val();
                        // 获取标题和分数
                        var title = $('#grade_title_' + id).text();
                        var num = $('#grade_num_' + id).val();
                        var show = title + ': ' + num + '分';
                        str += '<li>'+show+'<input type="hidden" name="grades_content[]" value="'+show+'"></li>';
                        $('#show-grade-content').html(str);
                    }
                })
            })
        }
    </script>
@endsection