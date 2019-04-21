<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>任务列表</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link href="{{ asset('dist/vendor/css/0.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/task/css/index.css') }}" rel="stylesheet">
    @yield('style')
</head>
<body>
<div class="container_">

    <div class="content">
        <div class="dashboard_header">
            <a href="{{ route('plans.intro', [ $task->plan->course, $task->plan ]) }}">
                <i class="fas fa-chevron-left mr-3"></i>
                返回课程
            </a>
            {{ $task['title'] }}
            <div class="float-right">
                <div class="state_opration font-small">
                    <span class="material-tooltip-main mr-2" data-toggle="tooltip" data-placement="bottom"
                          title="满足以下条件，学习到最后">
                        <i class="fas fa-info-circle mr-2"></i>
                        任务完成条件
                    </span>

                    <button class="btn btn-sm blue-gradient">正在学习</button>
                    {{--<button class="btn btn-sm aqua-gradient"><i class="far fa-check-circle mr-2"></i>已学完</button>--}}
                </div>
            </div>
        </div>
        <div class="dashboard_body">
            @yield('iframe')
            {{--<iframe src="{{ route('a') }}" frameborder="0"></iframe>--}}
            {{--<iframe src="/test.html" frameborder="0"></iframe>--}}
        </div>
        <div class="dashboard_footer">
            <div class="row">
                <div class="col-xs-12 col-md-0">
                    <button type="button" class="btn btn-primary" disabled="">
                        <span>上一任务</span>
                    </button>
                </div>
                <div class="col-xs-12 col-md-0">
                    <button type="button" class="btn btn-primary">
                        <span>下一任务</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
    <div class="dashboard_sidebar active">
        <div class="switch">
            <a href="javascript:;" class="toolbar">
                <i class="fas fa-bars"></i>
                <br>
                <span class="sidebar_open">收起</span>
                <span class="sidebar_close">展开</span>
            </a>
        </div>
        <div class="classic-tabs">
            <ul class="nav" id="myClassicTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link waves-light active show"
                       id="profile-tab-classic" data-toggle="tab" href="#profile-classic"
                       role="tab" aria-controls="profile-classic"
                       aria-selected="true">目录</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link waves-light" id="follow-tab-classic"
                       data-toggle="tab" href="#follow-classic" role="tab"
                       aria-controls="follow-classic" aria-selected="false">笔记</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link waves-light" id="contact-tab-classic"
                       data-toggle="tab" href="#contact-classic" role="tab"
                       aria-controls="contact-classic" aria-selected="false">问答</a>
                </li>
            </ul>
            <div class="tab-content rounded-bottom pl-0 pr-0 pt-3" id="myClassicTabContent">
                <div class="tab-pane fade active show" id="profile-classic" role="tabpanel"
                     aria-labelledby="profile-tab-classic">
                    <div class="video-list-wrap">
                        <ul class="list-group list-group-flush" style="width: 100%;">
                            @foreach($tasks as $k => $value)
                            <li class="list-group-item font-small pl-0 border-top-0 {{ $value['id'] == $task['id'] ? 'video-active' : '' }}">
                                <i class="fas {{ render_task_class($value['target_type']) }} text-primary mr-3"></i>
                                <a href="{{ route('tasks.show', $value) }}">
                                    {{ $k+1 }}-{{ render_task_type($value['type']) }}: {{ $value['title'] }}
                                </a>

                                <span class="time">{{ ($value['target_type'] == 'video' || $value['target_type'] == 'audio') ?  gmdate('H:i:s', $value['length']): '' }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="tab-pane fade" id="follow-classic" role="tabpanel"
                     aria-labelledby="follow-tab-classic">
                    <div class="pl-2 pr-2">
                        <div class="form-group shadow-textarea">
                            <textarea class="form-control z-depth-1 font-small" id="note"
                                      rows="10">{{ $note['content'] }}</textarea>
                        </div>
                        @if ($note['content'])
                            <h6 class="font-small text-right">
                                最后更新于：{{ $note['created_at'] }}
                            </h6>
                        @endif

                        <button class="btn btn-primary btn-sm float-right" id="save_note" data-route="{{ route('tasks.notes.store', $task) }}">保存一下</button>
                    </div>
                </div>
                <div class="tab-pane fade" id="contact-classic" role="tabpanel"
                     aria-labelledby="contact-tab-classic">
                    <div class="question-list-wrap active">
                        <div class="pl-2 pr-2 clearfix">
                            <input type="text" id="title" class="form-control question-title font-small"
                                   placeholder="我要提问">
                            <div class="form-group shadow-textarea mt-3">
                                <textarea class="form-control z-depth-1 font-small" id="content" rows="8"
                                          placeholder="我想问..."></textarea>
                            </div>
                            <button class="btn btn-primary btn-sm float-left ml-0"
                                    id="to_question"
                                    data-route="{{ route('tasks.topics.store', $task) }}"
                            >发起提问</button>
                        </div>
                        <div class="question-list mt-3">
                            <ul class="list-group list-group-flush pl-3 pr-3" id="question_list">
                                @if (count($topics))
                                    @foreach($topics as $topic)
                                        <li class="list-group-item pl-0">
                                            <div class="medio_box font-small">
                                                <div class="medix_item">
                                                    <div class="mbma">
                                                        <a href="javascript:;">
                                                            <i class="fas fa-question-circle mr-2"></i>{{ $topic['content'] }}</a>
                                                    </div>
                                                    <div class="metas mt-2">
                                                        <a href="javascript:;" class="link mr-2">{{ $topic['user']['id'] == auth('web')->id() ? '我' : $topic['user']['username'] }}</a>
                                                        发表于{{ $topic['created_at']->diffForHumans() }}
                                                        <span class="bullect ml-2 mr-2"> • </span>
                                                        <a class="huida" href="javascript:;" data-route="{{ route('tasks.reply.show', [$topic->task, $topic]) }}"> 我要回答</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>

                    <div class="answer pl-2 pr-2">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('dist/vendor/chunk/index.js') }}"></script>
<script type="text/javascript" src="{{ asset('dist/task/js/index.js') }}"></script>
<script>
    // 保存笔记
    $('#save_note').click(function(){

        var content = $('#note').val();

        if (!content) { edu.toastr.error('请添加笔记后保存'); return false;}

        edu.ajax({
            url: $(this).data('route'),
            method: 'post',
            data: {
                'content':content,
            },
            callback:function(res){

                if (res.status == 'success') {
                    $('#note').val(res.data.content);
                }
            },
            elm: '#save_note'
        })
    })

    // 问答
    $('#to_question').click(function(){
        var title = $('#title').val();
        var content = $('#content').val();

        // 数据验证
        if (!title || !content) { edu.toastr.error('请完善您的问答'); return false;}

        edu.ajax({
            url: $(this).data('route'),
            method: 'post',
            data: {
                'title':title,
                'content':content,
                'type':'question',
            },
            callback:function(res){

                if (res.status == 'success') {

                    // 清空表单
                    $('#title').val(''); $('#content').val('');

                    // 添加元素
                    $('#question_list').prepend(
                        ` <li class="list-group-item pl-0">
                                            <div class="medio_box font-small">
                                                <div class="medix_item">
                                                    <div class="mbma">
                                                        <a href="javascript:;">
                                                            <i class="fas fa-question-circle mr-2"></i>${res.data.content}</a>
                                                    </div>
                                                    <div class="metas mt-2">
                                                        <a href="javascript:;" class="link mr-2">我</a>
                                                        发表于${res.data.created_at}
                            <span class="bullect ml-2 mr-2"> • </span>
                            <a class="huida" href="javascript:;"> 我要回答</a>
                        </div>
                    </div>
                </div>
            </li>`
                    );
                }
            },
            elm: '#to_question'
        })
    })

    // 回答问题
    $(document).on('click', '.huida', function () {

        edu.ajax({
            url: $(this).data('route'),
            method: 'get',
            data: {},
            callback:function(res){
                console.log(res);
                if (res.status == 'html') {
                    $('.answer').empty();
                    $('.answer').append(res.data);

                    $('.answer').addClass('active');
                    $('.question-list-wrap').removeClass('active');

                    $('.answer-list').css({
                        'height': $('.answer').height() - $('.answer-top').height()
                    });
                }
            },
            elm: '.huida'
        })



    });



</script>
    @yield('script')
</body>
</html>
