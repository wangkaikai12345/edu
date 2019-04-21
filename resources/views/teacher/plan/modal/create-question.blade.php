<link rel="stylesheet" href="{{ mix('/css/teacher/plan/create-question.css') }}">
<link rel="stylesheet" href="/vendor/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="{{ mix('/css/theme.css') }}">

<div class="modal modal-fluid fade" id="create_question" tabindex="-1" role="dialog" aria-labelledby="modal_1"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 970px" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <p>创建题目</p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 30px !important;">
                <div class="modal_content">
                    <div class="row field_content">
                        <div class="col-md-2">
                            <p class="option_title">标签</p>
                        </div>
                        <div class="col-md-10 p-0">
                            <input class="form-control" type="text" placeholder="请输入标签">
                        </div>
                    </div>
                    <div class="row field_content">
                        <div class="col-md-2">
                            <p class="option_title">题干</p>
                        </div>
                        <div class="col-md-10 p-0">
                            <script id="editor" type="text/plain"></script>
                        </div>
                    </div>
                    <div class="row field_content">
                        <div class="col-md-2">
                            <p class="option_title">题目类型</p>
                        </div>
                        <div class="col-md-10 p-0 row">
                            <div class="col-md-3">
                                <div class="custom-control custom-radio mt-2">
                                    <input type="radio" name="type" value="{{ \App\Enums\QuestionType::SINGLE }}" class="custom-control-input"
                                           id="type1" checked>
                                    <label class="custom-control-label" for="type1">单选题</label>
                                </div>
                            </div>
                            <div class="col-md-3">

                                <div class="custom-control custom-radio mt-2">
                                    <input type="radio" name="type" value="{{ \App\Enums\QuestionType::MULTIPLE }}" class="custom-control-input"
                                           id="type2">
                                    <label class="custom-control-label" for="type2">多选题</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="options">

                    </div>
                    <div class="row field_content" style="margin-top: -12px;margin-bottom: 0;">
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-10 p-0">
                            <a href="javascript:;" class="add_option btn btn-primary">＋添加选项</a>
                        </div>
                    </div>
                    <div class="row field_content">
                        <div class="col-md-2">
                            <p class="option_title">题目难度</p>
                        </div>
                        <div class="col-md-10 p-0">
                            <div id="star" data-score="" style="margin-top: 6px;"></div>
                        </div>
                    </div>
                    <div class="row field_content">
                        <div class="col-md-2">
                            <p class="option_title">答案解析</p>
                        </div>
                        <div class="col-md-10 p-0">
                            <textarea class="option_textarea form-control" name="" id="explain" cols="30" rows="5"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="cancel_btn">
                    <button data-dismiss="modal">取消</button>
                </div>
                <div class="determine_btn">
                    <button id="add-question-btn" data-url="{{ route('manage.question.store') }}">确定</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/vendor/ueditor/ueditor.config.js"></script>
<script src="/vendor/ueditor/ueditor.all.js"></script>
<script src="{{ '/vendor/raty/jquery.raty.min.js' }}"></script>
<script src="{{ '/vendor/jquery-ui/jquery-ui.min.js' }}"></script>
<script src="/vendor/select2/dist/js/select2.min.js"></script>
<script>
    window.onload = function () {
        // 声明数据和变量 题目序号&防重复编号
        var arr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
        var ues = [];
        var option_num = 0;

        // 初始化题干富文本框
        var editor = UE.getEditor('editor', {
            UEDITOR_HOME_URL: '/vendor/ueditor/',
            serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
        });

        var rate = 0;
        // 题目星级
        $('#star').raty({
            starOff: '/imgs/star-off.svg',
            starOn: '/imgs/star-on.svg',
            size: 30,
            score: function () {
                return $(this).attr('data-score');
            },
            click: function (score, evt) {
                rate = score;
//                console.log('ID: ' + $(this).attr('id') + "\nscore: " + score + "\nevent: " + evt.type);
            }
        });

        // 删除选项
        $(document).on('click', '.del_option', function () {
            // 获取选项编号
            var seq = $(this).parent().parent().prev().children('.option_title').children('.option_n').html();

            // 删除点击的选项
            $(this).parent().parent().parent().remove();

            // 重置选项序号
            again_sort();

            // 清空编辑器代码
            UE.getEditor('editor_' + $(this).data('number')).destroy();
        });


        // 添加选项
        $(document).on('click', '.add_option', function () {
            // 当前题目类型
            var type_num = $("input[name='type']:checked").val();

            var str = '';
            str += '<div class="row field_content">';
            str += '<div class="col-md-2">';
            str += '<p class="option_title">选项<span class="option_n">' + arr[option_num] + '</span></p>';
            str += '</div>';
            str += '<div class="col-md-10 p-0">';
            str += '<script id="editor_' + option_num + '" type="text/plain"><\/script>';
            str += '<div class="options_operation">';
            str += '<div class="true_answer_btn">';
            if (type_num == 'single') {
                str += '<div class="option_true custom-control custom-radio mb-3">'
                str += '<input type="radio" name="true_option[]" class="custom-control-input" id="true' + option_num + '">';
                str += '<label class="custom-control-label" for="true' + option_num + '">正确答案</label>'
                str += '</div>';
            } else {
                str += '<div class="option_true custom-control custom-checkbox mb-3">';
                str += '<input type="checkbox" name="true_option[]" class="custom-control-input" id="true' + option_num + '">';
                str += '<label class="custom-control-label" for="true' + option_num + '">正确答案</label>';
                str += '</div>';
            }
            str += '</div>';
            str += '<a href="javascript:;" class="del_option btn btn-link" data-number="'+ option_num +'">';
            str += '<i class="iconfont">&#xe62e;</i>';
            str += '删除';
            str += '</a>';
            str += '</div>';
            str += '</div>';
            str += '</div>';

            $('#options').append(str);

            var obj = UE.getEditor('editor_' + option_num, {
                UEDITOR_HOME_URL: '/vendor/ueditor/',
                serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
            });

            ues['editor_' + option_num] = obj;

            // 重置选项编号
            again_sort();

            // 自增变量 以防选项编号重复
            option_num++;
        });

        // 当切换题目类型的时候 正确答案选框随之改变
        $("input[name='type']").change(function () {
            if ($(this).val() == 'single') {
                $('.option_true').removeClass('custom-checkbox').addClass('custom-radio');
                $('.option_true').children('input').attr('type', 'radio')
            } else {
                $('.option_true').removeClass('custom-radio').addClass('custom-checkbox');
                $('.option_true').children('input').attr('type', 'checkbox');
            }
        });

        /**
         * 重新排序
         */
        function again_sort() {
            // 重新排序
            var options = $('#options').children('.field_content');
            for (var i = 0; i < options.length; i++) {
                options.eq(i).children('.col-md-2').children('.option_title').children('.option_n').html(arr[i]);
            }
        }

        /**
         * 添加题目
         */
        $('#add-question-btn').click(function () {
            // 获取必要数据, 如果数据为空, 则提示
            var tags = ['test'];
            var title = editor.getContent();
            var titleTxt = editor.getContentTxt();
            var explain = $('#explain').val();
            var type = $('input[name="type"]:checked').val();
            var options = [];
            var answers = [];
            var optionTxt;

            $('.del_option').each(function () {
                var i = $(this).data('number');
                var index = 'editor_' + i;
                var option = ues[index].getContent();
                optionTxt = ues[index].getContentTxt();
                if (!optionTxt) {
                    return false;
                }

                if ($('#true' + i).is(':checked')) {
                    answers.push(i);
                }

                // 验证是否是正确答案
                options.push(option);
            });
            if (!titleTxt) {
                edu.alert('danger', '标题不能为空!');
                return false;
            }
            if (!optionTxt) {
                edu.alert('danger', '已加选项不能为空!');
                return false;
            }
            if (answers.length == 0) {
                edu.alert('danger', '至少选择一个正确答案!');
                return false;
            }
            if (rate == 0) {
                edu.alert('danger', '题目难度不能为空!');
                return false;
            }

            var datas = {
                type: type,
                title: title,
                tags: tags,
                answers: answers,
                options: options,
                explain: explain,
                score: rate,
                source: 'video',
            };

            $.ajax({
                url: $(this).data('url'),
                type: 'post',
                data: datas,
                success: function (res) {
                    if (res.status == 200) {
                        console.log(res);
                        edu.alert('success', res.message);
                        var question = res.data;
                        var optionsStr = '';
                        for (var i in question.options) {
                            optionsStr += '<li>'+question.options[i]+'</li>';
                        }

                        // 将题目信息的放入列表中
                        var str = '<tr class="bg-white">\n' +
                            '                                    <td>'+question.title+'</td>\n' +
                            '                                    <td>\n' +
                            '                                        <button class="btn btn-link b_t_t">展开</button>\n' +
                            '                                        <i class="iconfont b_t_t_i">&#xe616;</i>\n' +
                            '                                    </td>\n' +
                            '                                    <td>' +
                            '                                       <input class="form-control b_t_n" id="score_'+question.id+'" type="number">' +
                            '                                       <input type="hidden" class="question_select_ids" value="'+question.id+'" name="question_select_ids[]" >' +
                            '                                    </td>\n' +
                            '                                    <td>\n' +
                            '                                        <a href="javascript:;">\n' +
                            '                                            <button class="btn btn-link b_t_b">删除</button>\n' +
                            '                                        </a>\n' +
                            '                                    </td>\n' +
                            '                                </tr>\n' +
                            '                                <tr>\n' +
                            '                                    <td class="q_options" colspan="4">\n' +
                            '                                        <ul>\n' +
                            optionsStr +
                            '                                        </ul>\n' +
                            '                                    </td>\n' +
                            '                                </tr>';

                        $('#questions_body').append(str);
                        // 关闭模态框
                        $('#create_question').modal('hide');
                    } else {
                        edu.alert('danger', '添加题目失败!');
                    }
                }
            });

            console.log(datas);
        });
    };
</script>