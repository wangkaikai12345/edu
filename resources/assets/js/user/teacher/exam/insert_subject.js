/************************************ 动态添加单选框开始 ************************************/
// 定义选项
var options = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
$(document).on('click', '.radio_type', function () {
    // 如果选择单选框删除复选框所有内容
    var checkbox_length = $('.checkbox_question_type').length;
    for (var i = 0;i<checkbox_length;i++) {
        UE.getEditor(`editor-${i+1}`).destroy();
    }
    $('.checkbox_question_type').remove();
    $('.add-checkbox').hide();

    var length = $('.row.radio_question_type').length;
    // 添加单选框
    if (length == 0) {
        var str = '<div class="row radio_question_type" data-num="' + (length + 1) + '">\n' +
            '                                        <div class="modal-body justify-content-center">\n' +
            '                                            <div class="row mt-3 m-0 ml-8 input-content justify-content-center">\n' +
            '                                                <div class="col-md-9">\n' +
            '                                                    <div class="form-group">\n' +
            '                                                        <div class="input-group input-group-transparent">\n' +
            '                                                            <label class="control-label col-md-1 text-right p-0">选项 ' + options[length] + '</label>\n' +
            '                                                            <script id="editor_' + (length + 1) + '" name="options[]" type="text/plain"\n' +
            '                                                                    class="col-md-10 pl-3"></script>\n' +
            '                                                        </div>\n' +
            '                                                    </div>\n' +
            '                                                    <div class="row question_type_style">\n' +
            '                                                        <div class="custom-control custom-radio col-9">\n' +
            '                                                            <input type="radio" name="answers[]"\n' +
            '                                                                   class="custom-control-input"\n' +
            '                                                                   id="answer'+ (length+1) +'" value="'+length+'">\n' +
            '                                                            <label class="custom-control-label"\n' +
            '                                                                   for="answer'+ (length+1) +'">正确选项</label>\n' +
            '                                                        </div>\n' +
            '                                                        <a href="javascript:;" class="float-right" id="del-radio">\n' +
            '                                                            <i class="iconfont">\n' +
            '                                                                &#xe62e;\n' +
            '                                                            </i>\n' +
            '                                                            删除\n' +
            '                                                        </a>\n' +
            '                                                    </div>\n' +
            '                                                </div>\n' +
            '                                            </div>\n' +
            '                                        </div>\n' +
            '                                    </div>'
        // 在元素后添加单选
        $(str).insertAfter('.row.question_type');
        $('.add-radio').show();
    }
    // 初始化编辑器
    UE.getEditor(`editor_${length + 1}`, {
        UEDITOR_HOME_URL: '/vendor/ueditor/',
        serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
    });
});

$(document).on('click', '.add-radio', () => {
    var length = $('.row.radio_question_type').length;
    var str = '<div class="row radio_question_type" data-num="' + (length + 1) + '">\n' +
        '                                        <div class="modal-body justify-content-center">\n' +
        '                                            <div class="row mt-3 m-0 ml-8 input-content justify-content-center">\n' +
        '                                                <div class="col-md-9">\n' +
        '                                                    <div class="form-group">\n' +
        '                                                        <div class="input-group input-group-transparent">\n' +
        '                                                            <label class="control-label col-md-1 text-right p-0 option_name">选项 ' + options[length] + '</label>\n' +
        '                                                            <script id="editor_' + (length + 1) + '" name="options[]" type="text/plain"\n' +
        '                                                                    class="col-md-10 pl-3"></script>\n' +
        '                                                        </div>\n' +
        '                                                    </div>\n' +
        '                                                    <div class="row question_type_style">\n' +
        '                                                        <div class="custom-control custom-radio col-9">\n' +
        '                                                            <input type="radio" name="answers[]"\n' +
        '                                                                   class="custom-control-input"\n' +
        '                                                                   id="answer'+ (length+1) +'" value="'+ length +'">\n' +
        '                                                            <label class="custom-control-label"\n' +
        '                                                                   for="answer'+ (length+1) +'">正确选项</label>\n' +
        '                                                        </div>\n' +
        '                                                        <a href="javascript:;" class="float-right" id="del-radio">\n' +
        '                                                            <i class="iconfont">\n' +
        '                                                                &#xe62e;\n' +
        '                                                            </i>\n' +
        '                                                            删除\n' +
        '                                                        </a>\n' +
        '                                                    </div>\n' +
        '                                                </div>\n' +
        '                                            </div>\n' +
        '                                        </div>\n' +
        '                                    </div>';
    if (length == 0) {
        $(str).insertAfter('.row.question_type');
    } else {
        // 在元素后添加单选
        var elm = $('.radio_question_type');
        $(str).insertAfter(elm[elm.length - 1]);
    }
    // 初始化编辑器
    UE.getEditor(`editor_${length + 1}`, {
        UEDITOR_HOME_URL: '/vendor/ueditor/',
        serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
    });
});

// 单选框删除
$(document).on('click', '#del-radio', function () {
    // 获取要删除的父元素的值，对应下面的删除对应的编辑器
    var length = $('.row.radio_question_type').length;
    var num = $(this).parent().parent().parent().parent().parent().data('num');
    // 通过事件委托对子元素进行删除
    $(this).closest('.radio_question_type').remove();
    // 删除已经初始化的编辑器
    UE.getEditor(`editor_${num}`).destroy();

    // 重新排序
    Rescheduling('.row.radio_question_type', length);
});

/************************************ 动态添加单选框结束 ************************************/

/************************************ 动态添加复选框开始 ************************************/
$(document).on('click', '.checkbox_type', function () {
    // 如果选择复选框删除单选框所有内容
    var radio_length = $('.radio_question_type').length;
    for (var i = 0;i<radio_length;i++) {
        UE.getEditor(`editor_${i+1}`).destroy();
    }
    $('.radio_question_type').remove();
    $('.add-radio').hide();
    // 添加复选框
    var length = $('.row.checkbox_question_type').length;
    if (length == 0) {
        var str = '<div class="row checkbox_question_type" data-num="' + (length + 1) + '">\n' +
            '                                        <div class="modal-body justify-content-center">\n' +
            '                                            <div class="row mt-3 m-0 ml-8 input-content justify-content-center">\n' +
            '                                                <div class="col-md-9">\n' +
            '                                                    <div class="form-group">\n' +
            '                                                        <div class="input-group input-group-transparent">\n' +
            '                                                            <label class="control-label col-md-1 text-right p-0">选项 ' + options[length] + '</label>\n' +
            '                                                            <script id="editor-' + (length + 1) + '" name="options[]" type="text/plain"\n' +
            '                                                                    class="col-md-10 pl-3"></script>\n' +
            '                                                        </div>\n' +
            '                                                    </div>\n' +
            '                                                    <div class="row question_type_style">\n' +
            '                                                        <div class="custom-control custom-checkbox col-9">\n' +
            '                                                            <input type="checkbox" name="answers[]"\n' +
            '                                                                   class="custom-control-input"\n' +
            '                                                                   id="answer'+ (length+1) +'" value="'+length+'">\n' +
            '                                                            <label class="custom-control-label"\n' +
            '                                                                   for="answer'+ (length+1) +'">正确选项</label>\n' +
            '                                                        </div>\n' +
            '                                                        <a href="javascript:;" class="float-right" id="del-checkbox">\n' +
            '                                                            <i class="iconfont">\n' +
            '                                                                &#xe62e;\n' +
            '                                                            </i>\n' +
            '                                                            删除\n' +
            '                                                        </a>\n' +
            '                                                    </div>\n' +
            '                                                </div>\n' +
            '                                            </div>\n' +
            '                                        </div>\n' +
            '                                    </div>'
        // 在元素后添加单选
        $(str).insertAfter('.row.question_type');
        $('.add-checkbox').show();
    }
    // 初始化编辑器
    UE.getEditor(`editor-${length + 1}`, {
        UEDITOR_HOME_URL: '/vendor/ueditor/',
        serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
    });
});

$(document).on('click', '.add-checkbox', () => {
    var length = $('.row.checkbox_question_type').length;
    var str = '<div class="row checkbox_question_type" data-num="' + (length + 1) + '">\n' +
        '                                        <div class="modal-body justify-content-center">\n' +
        '                                            <div class="row mt-3 m-0 ml-8 input-content justify-content-center">\n' +
        '                                                <div class="col-md-9">\n' +
        '                                                    <div class="form-group">\n' +
        '                                                        <div class="input-group input-group-transparent">\n' +
        '                                                            <label class="control-label col-md-1 text-center mr-1 p-0 option_name">选项 ' + options[length] + '</label>\n' +
        '                                                            <script id="editor-' + (length + 1) + '" name="options[]" type="text/plain"\n' +
        '                                                                    class="col-md-10 p-0"></script>\n' +
        '                                                        </div>\n' +
        '                                                    </div>\n' +
        '                                                    <div class="row question_type_style">\n' +
        '                                                        <div class="custom-control custom-checkbox col-9">\n' +
        '                                                            <input type="checkbox" name="answers[]"\n' +
        '                                                                   class="custom-control-input"\n' +
        '                                                                   id="answer'+ (length+1) +'" value="'+length+'">\n' +
        '                                                            <label class="custom-control-label"\n' +
        '                                                                   for="answer'+ (length+1) +'">正确选项</label>\n' +
        '                                                        </div>\n' +
        '                                                        <a href="javascript:;" class="float-right" id="del-checkbox">\n' +
        '                                                            <i class="iconfont">\n' +
        '                                                                &#xe62e;\n' +
        '                                                            </i>\n' +
        '                                                            删除\n' +
        '                                                        </a>\n' +
        '                                                    </div>\n' +
        '                                                </div>\n' +
        '                                            </div>\n' +
        '                                        </div>\n' +
        '                                    </div>';
    if (length == 0) {
        $(str).insertAfter('.row.question_type');
    } else {
        // 在元素后添加复选
        var elm = $('.checkbox_question_type');
        $(str).insertAfter(elm[elm.length - 1]);
    }
    // 初始化编辑器
    UE.getEditor(`editor-${length + 1}`, {
        UEDITOR_HOME_URL: '/vendor/ueditor/',
        serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
    });
});

// 复选框删除
$(document).on('click', '#del-checkbox', function () {
    // 获取要删除的父元素的值，对应下面的删除对应的编辑器
    var length = $('.row.checkbox_question_type').length;
    var num = $(this).parent().parent().parent().parent().parent().data('num');
    // 通过事件委托对子元素进行删除
    $(this).closest('.checkbox_question_type').remove();
    // 删除已经初始化的编辑器
    UE.getEditor(`editor-${num}`).destroy();

    // 重新排序
    Rescheduling('.row.checkbox_question_type', length);
});

/************************************ 动态添加复选框结束 ************************************/

/**
 * 删除后重新排序
 *
 * @param type_content
 * @constructor
 */
function Rescheduling(type_content,length) {
    for(var i = 0; i < length; i++){
        $(type_content).eq(i).find('.option_name').html('选项'+ options[i]); // 设置编号
    }
}

$(document).on('click', '.subjective_type', () => {
    // 删除复选框所有
    var checkbox_length = $('.checkbox_question_type').length;
    for (var i = 0;i<checkbox_length;i++) {
        UE.getEditor(`editor-${i+1}`).destroy();
    }
    $('.checkbox_question_type').remove();
    $('.add-checkbox').hide();

    // 删除单选框所有
    var radio_length = $('.radio_question_type').length;
    for (var i = 0;i<radio_length;i++) {
        UE.getEditor(`editor_${i+1}`).destroy();
    }
    $('.radio_question_type').remove();
    $('.add-radio').hide();
});

// 星星难度效果
$('#star').raty({
    starOff: '/imgs/star-off.svg',
    starOn: '/imgs/star-on.svg',
    size: 30,
    score: function () {
        return $(this).attr('data-score');
    },
    click: function (score, evt) {
        console.log('ID: ' + $(this).attr('id') + "\nscore: " + score + "\nevent: " + evt.type);
    }
});

// 实现table中的选题效果
$(document).on('click', '.add_question', function () {
    var id = $(this).data('id');
    // 选中这道题给tr添加背景色
    $(this).parent().parent().addClass('active');
    // 如果选中这个按钮删掉这个class，不让对其点击
    $(this).removeClass('add_question');
    $(this).css({'cursor': 'default'});

    var question_name = $.trim($(this).parent().parent().children('.question_name').text());
    var question_id = id;

    // 如果已添加的题目列表
    var arr = [];
    $('.question_ids').each(function () {
        arr.push($(this).val());
    });
    if ($.inArray(question_id, arr) >= 0) {
        edu.alert('danger', '已存在题目, 不能重复添加!');
        return false;
    }

    // 插入页面中选中题目数量
    var str = '<div class="row question_title_score_content">\n' +
        '                                        <div class="col-12 p-0">\n' +
        '                                            <div class="modal-body justify-content-center">\n' +
        '                                                <div class="row mt-3 m-0 ml-8 input-content justify-content-center">\n' +
        '                                                    <div class="col-md-10">\n' +
        '                                                        <div class="form-group">\n' +
        '                                                            <div class="input-group input-group-transparent"><input type="hidden" class="question_ids" name="question_ids[]" value="'+ question_id +'" >\n' +
        '                                                                <label class="control-label col-md-2 text-right p-0 pr-3">题目标题</label>\n' +
        '                                                                <input type="text" name="" readonly id=""\n' +
        '                                                                       class="form-control col-lg-8 pl-3" value="'+ question_name +'">\n' +
        '                                                            </div>\n' +
        '                                                        </div>\n' +
        '                                                    </div>\n' +
        '                                                </div>\n' +
        '                                            </div>\n' +
        '                                        </div>\n' +
        '                                        <div class="col-12 p-0">\n' +
        '                                            <div class="modal-body justify-content-center">\n' +
        '                                                <div class="row mt-3 m-0 ml-8 input-content justify-content-center">\n' +
        '                                                    <div class="col-md-10">\n' +
        '                                                        <div class="form-group">\n' +
        '                                                            <div class="input-group input-group-transparent">\n' +
        '                                                                <label class="control-label col-md-2 text-right p-0 pr-3">分值</label>\n' +
        '                                                                <input type="text" name="question_score_'+ question_id +'" id=""\n' +
        '                                                                       class="form-control col-lg-6 pl-3" placeholder="题目分值">\n' +
        '                                                                <button class="btn btn-primary remove-question" type="button" data-id="'+ id +'">删除</button>\n' +
        '                                                            </div>\n' +
        '                                                        </div>\n' +
        '                                                    </div>\n' +
        '                                                </div>\n' +
        '                                            </div>\n' +
        '                                        </div>\n' +
        '                                    </div>';

    $('#show-selected-question').append(str);

    // 计算选中题目数量
    var tr_length = $('#show-selected-question').children('.question_title_score_content').length;
    $('.question_length_content').html(tr_length);
    $('#questions_count_id').val(tr_length);
    $('.question_count').show();

    // $(str).insertAfter($('.question_count'));
});

// 删除已选题目
$(document).on('click', '.remove-question', function() {
    // 删除分值以及题目标题
    $(this).parents('.question_title_score_content').remove();
    // 重新计算题目数量
    var question_length = $('.question_title_score_content').length;
    $('.question_length_content').html(question_length);
    // 如果数量为0隐藏题目数量提示
    if (question_length == 0) {
        $('.question_count').hide();
    }

    var id = $(this).data('id');
    $('#add_question_'+id).addClass('add_question');
    $('#add_question_'+id).parent().parent().removeClass('active');
});