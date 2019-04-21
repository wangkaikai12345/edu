import edu_proto from '../../../../edu/edu';

const edu = new edu_proto();

$('.answer_num').on({
   click: function () {
       $(this).parents('.question_item').find('.reply_wrap').stop(true).slideToggle();
   }
});

$('#open_question').on({
   click: function () {
       $('.search_wrap').css({
           'transform': 'translateX(-100%)'
       });
       $('.form_wrap').toggleClass('active');
       $('.form').css({
           'position': 'relative',
           'opacity': 1,
           'transform': 'translateX(-100%)',
           'left': 0
       });
       $('.question_title#title').val($('#search_input').val());
       return false;
   }
});

function questionBack() {
    $('.search_wrap').css({
        'transform': 'translateX(0)'
    });
    $('.form_wrap').toggleClass('active');
    $('.form').css({
        'position': 'absolute',
        'opacity': 0,
        'transform': 'translateX(0)',
        'left': '100%'
    });
}

$('#question_back').on({
   click: function () {
       questionBack();
       return false;
   }
});

$('#search_input').on('input propertychange', edu.throttle(function () {
    let str = $(this).val()
        ,type = null
        ,tabActiveId = $('#zh_question #myTab .nav-item a.active').prop('id');

    if(tabActiveId === 'questions-tab') {
        type = 'question';
    }

    if(tabActiveId === 'contact-tab') {
        type = 'reply';
    }

    getQuestion({
        key: str,
        type,
    }, `/tasks/${$('#zh_question').data('task-id')}/topics/search`);
}));

// 获取问答的函数
function getQuestion(data, url, more) {
    $.ajax({
        url: url || $('#row').data('route'),
        type: 'get',
        data: data,
        success: function (res) {
            // url 存在 即搜索
            const result = !url ? res.data.data : res.data;

            if (res.status == '200') {

                url && $('#question_wrap').html('');

                if(result.length === 0 && !more) {
                    $('#question_wrap').addClass('no_data').html('暂无数据...');
                }

                // 判断是否展示加载更多
                if (result.length < 5) {
                    $('#more_my_question').attr('style', 'display:none');
                } else {
                    $('#more_my_question').attr('style', 'display:block');
                    $('#more_my_question').attr('data-page', res.data.current_page+1);
                    if (data && data.type) {
                        $('#more_my_question').attr('data-type', data.type);
                    }
                }

                if (data && data.type && data.type == 'reply') {

                    // 填充数据
                    result.map(function (item) {
                        let topic = null
                            ,html = '';
                        if(url) {
                            topic = item;
                            html += `
                            <div class="question_item">
                            <h3 class="question_title">
                                ${topic.title}
                            </h3>
                            <div class="question_content">
                            <span class="question_status">

                            </span>
                                <p class="question_p">
                                   ${topic.content}
                                </p>
                            </div>

                            <div class="question_other">
                            <span class="answer_num important_primary" data-route="/tasks/${item.task_id}/topics/${topic.id}/reply">
                                ${topic.replies_count}回答
                            </span>
                                <span class="watch_num">
                                ${topic.hit_count}浏览
                            </span>
                                <span class="float-right">
                                ${item.created_at}
                            </span>
                            </div>
                            <div class="reply_wrap">
                                <input type="text" class="content form-control" placeholder="请输入回答内容">
                                <button data-route="/tasks/${item.task_id}/topics/${topic.id}/reply"
                                        class="btn btn-primary btn-sm btn-circle send_reply float-right" style="margin-top: 15px;">回答
                                </button>
                                <div class="reply_list">

                                </div>
                            </div>
                        </div>
                        `
                        }else {
                            topic = item.topic;
                            html += `
                            <div class="question_item">
                            <h3 class="question_title">
                                ${topic.title}
                            </h3>
                            <div class="question_content">
                            <span class="question_status">

                            </span>
                                <p class="question_p">
                                   ${topic.content}
                                </p>
                            </div>

                            <h3 class="question_title">
                                我的回答:
                            </h3>
                            <div class="question_content">
                            <span class="question_status">

                            </span>
                                <p class="question_p">
                                   ${item.content}
                                </p>
                            </div>

                            <div class="question_other">
                            <span class="answer_num important_primary" data-route="/tasks/${item.task_id}/topics/${topic.id}/reply">
                                ${topic.replies_count}回答
                            </span>
                                <span class="watch_num">
                                ${topic.hit_count}浏览
                            </span>
                                <span class="float-right">
                                ${item.created_at}
                            </span>
                            </div>
                            <div class="reply_wrap">
                                <input type="text" class="content form-control" placeholder="请输入回答内容">
                                <button data-route="/tasks/${item.task_id}/topics/${topic.id}/reply"
                                        class="btn btn-primary btn-sm btn-circle send_reply float-right" style="margin-top: 15px;">回答
                                </button>
                                <div class="reply_list">

                                </div>
                            </div>
                        </div>
                        `
                        }
                        $('#question_wrap').append(html).removeClass('no_data');
                    })
                } else {
                    // 填充数据
                    result.map(function (item) {
                        $('#question_wrap').append(`
                            <div class="question_item">
                            <h3 class="question_title">
                                ${item.title}
                            </h3>
                            <div class="question_content">
                            <span class="question_status">

                            </span>
                                <p class="question_p">
                                   ${item.content}
                                </p>
                            </div>

                            <div class="question_other">
                            <span class="answer_num important_primary" data-route="/tasks/${item.task_id}/topics/${item.id}/reply">
                                ${item.replies_count}回答
                            </span>
                                <span class="watch_num">
                                ${item.hit_count}浏览
                            </span>
                                <span class="float-right">
                                ${item.created_at}
                            </span>
                            </div>
                            <div class="reply_wrap">
                                <input type="text" class="content form-control" placeholder="请输入回答内容">
                                <button data-route="/tasks/${item.task_id}/topics/${item.id}/reply"
                                        class="btn btn-primary btn-sm btn-circle send_reply float-right" style="margin-top: 15px;">回答
                                </button>
                                <div class="reply_list">

                                </div>
                            </div>
                        </div>
                        `);
                    })
                }


            } else {
                edu.alert('danger', res.message);
            }
        },
    })
}

getQuestion({});

// 发起问答
$('#question').click(function () {
    // type, title, content
    var title = $('#title').val();
    if (!title) {
        edu.alert('danger', '请输入您的问题的标题');
        return false;
    }
    var content = ue.getContent();
    if (!content) {
        edu.alert('danger', '请输入您的问题');
        return false;
    }

    // 问答
    $.ajax({
        url: $(this).data('route'),
        method: 'post',
        data: {
            'content': content,
            'title': title,
            'type': 'question',
        },
        success: function (res) {

            if (res.status == '200') {
                edu.alert('success', '提问成功');
                ue.setContent('');
                $('#title').val('');
                questionBack();
            } else {
                edu.alert('danger', res.message);
            }
        },
    })
});

if($('#question_editor').length) {
    var ue = UE.getEditor('question_editor', {
        UEDITOR_HOME_URL: '/vendor/ueditor/',
        serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php',
        autoWidth: true
    });
}

// 热门
$('#hot-tab').click(function () {

    $('.nav-link').removeClass('active');
    $(this).addClass('active');
    $('#question_wrap').empty();
    $('#more_my_question').attr('data-page', 1);
    $('#more_my_question').attr('data-type', '');

    const str = $('#search_input').val();

    if(str.replace(/\s/g, '')) {
        getQuestion({
            key: str
        }, `/tasks/${$('#zh_question').data('task-id')}/topics/search`);
    }else {
        getQuestion({});
    }
});

// 我的提问
$('#questions-tab').click(function () {
    $('.nav-link').removeClass('active');
    $(this).addClass('active');
    $('#question_wrap').empty();
    $('#more_my_question').attr('data-page', 1);
    $('#more_my_question').attr('data-type', 'question');

    const str = $('#search_input').val();

    if(str.replace(/\s/g, '')) {
        getQuestion({
            key: str,
            type: 'question'
        }, `/tasks/${$('#zh_question').data('task-id')}/topics/search`);
    }else {
        getQuestion({type: 'question'});
    }

});

// 我的回答
$('#contact-tab').click(function () {
    $('.nav-link').removeClass('active');
    $(this).addClass('active');
    $('#question_wrap').empty();
    $('#more_my_question').attr('data-page', 1);
    $('#more_my_question').attr('data-type', 'reply');


    const str = $('#search_input').val();

    if(str.replace(/\s/g, '')) {
        getQuestion({
            key: str,
            type: 'reply'
        }, `/tasks/${$('#zh_question').data('task-id')}/topics/search`);
    }else {
        getQuestion({type: 'reply'});
    }

});

// 查看更多
$('#more_my_question').click(function () {

    var page = $(this).attr('data-page');
    var type = $(this).attr('data-type');

    var data = {};
    if (type) {
        data.type = type;
    }
    data.page = page;

    getQuestion(data, '', true);
});

// 回答下的回复
$(document).on('click', '.answer_num', function () {

    var _that = $(this);

    if ($(this).parents('.question_item').find('.reply_wrap').is(':hidden')) {
        $.ajax({
            url: $(this).data('route'),
            type: 'get',
            data: {},
            success: function (res) {

                if (res.status == '200') {
                    _that.parents('.question_item').find('.reply_list').html('');
                    // 填充数据
                    res.data.map(function (item) {
                        _that.parents('.question_item').find('.reply_list').append(`
                               <div class="reply_item">
                                    <img src="${item.user.avatar}" alt="" class="reply_avatar">
                                    <div class="reply_info">
                                        <a href="javascript:;" class="reply_username">
                                            ${item.user.username}
                                        </a>
                                        <p class="reply_content">
                                            ${item.content}
                                        </p>
                                        <span class="reply_time">${item.created_at}</span>
                                    </div>
                                </div>
                        `);
                    })

                } else {
                    edu.alert('danger', res.message);
                }
            },
        })
    }

    $(this).parents('.question_item').find('.reply_wrap').stop(true).slideToggle();
});

// 回答下创建回复
$(document).on('click', '.send_reply', function () {

    var _that = $(this);

    var content = $(this).parents('.reply_wrap').find('.content').val();

    if (!content) {
        edu.alert('danger', '请输入您的答案');
        return false;
    }

    $.ajax({
        url: $(this).data('route'),
        type: 'post',
        data: {'content': content},
        success: function (res) {

            if (res.status == '200') {
                _that.parents('.reply_wrap').find('.content').val('');
                edu.alert('success', '回答成功');

                _that.parents('.reply_wrap').find('.reply_list').append(`
                     <div class="reply_item">
                                    <img src="${res.data.user.avatar}" alt="" class="reply_avatar">
                                    <div class="reply_info">
                                        <a href="javascript:;" class="reply_username">
                                            ${res.data.user.username}
                                        </a>
                                        <p class="reply_content">
                                           ${res.data.content}
                                        </p>
                                        <span class="reply_time">${res.data.created_at}</span>
                                    </div>
                                </div>
                    `)
            } else {
                edu.alert('danger', res.message);
            }
        },
    })
});