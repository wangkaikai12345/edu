<link rel="stylesheet" href="{{ mix('/css/front/task/test/index.css') }}">
<link rel="stylesheet" href="{{ mix('/css/front/task/test/modal.css') }}">

<div class="row czh_content" style="padding-bottom: 0;" data-taskId="{{ $task->id }}">
    <script>
        var obj = {};
    </script>
    @if($task->target->paperResult()->task($task)->first())

        @if (request('paper') == 'result')
            @include('frontend.review.task.test.analysis', ['paperResult' => $task->target->paperResult()->task($task)->first()])
        @else
            @include('frontend.review.task.test.result', ['paperResult' =>$task->target->paperResult()->task($task)->first()])
        @endif
    @else
        <div class="col-md-9 problemDetails">
            <div class="numAndTime">
                <div class="quesNum">
                    <p>题数：<span id="current"
                                data-sort="1"
                                data-current="{{ $task->target->questions->first()->id }}"> 1 </span>/<span>{{ $task->target->questions_count }}</span>
                    </p>
                </div>
                <div class="queTime">
                    <p>
                        <span><i class="iconfont">&#xe619;</i>  剩余总时间：</span>
                        <span class="secondNumBody"><span id="secondNum">{{ $task->target->expect_time }}</span>秒</span>
                        <span>{{ $task->target->title }}</span>

                    </p>
                </div>
            </div>
            <div class="queData row">
                @foreach($task->target->questions as $question)
                    <div class="queData_content col-md-10" style="display:{{ $loop->first ? 'block': 'none' }}"
                         id="{{$question->id}}">
                        <div class="que_title">
                            <p>第 {{ $loop->iteration }} 题<span> ({{ $question->pivot->score.'分' }}) ({{ \App\Enums\QuestionType::getDescription($question->type) }})</span>
                                <span class="q_t">{!! substr($question->title,3,-4) !!}</span>
                            </p>
                        </div>
                        <div class="option_content">
                            @if ($question->type == 'answer')
                                <script data-id="{{ $question->id }}" id="question_editor_{{ $question->id }}"
                                        name="question_editor" type="text/plain"></script>
                                <script>

                                    obj['ue_{{ $question->id }}'] = UE.getEditor('question_editor_{{ $question->id }}', {
                                        UEDITOR_HOME_URL: '/vendor/ueditor/',
                                        serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php',
                                        autoWidth: true
                                    });

                                </script>
                            @else
                                @foreach($question->options as $option)
                                    <div class="eachOption"
                                         data-value="{{ $loop->index }}"
                                         data-type="{{ $question->type }}"
                                         data-qid="{{ $question->id }}">
                                        <div class="optionSeq">
                                            <p>{{ ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'M', 'N'][$loop->index] }}</p>
                                        </div>
                                        <div class="optionTile">
                                            <p>{!! $option !!}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif


                        </div>
                    </div>
                @endforeach

                <div class="queBtns">
                    <button class="queBtn lastQuestion falseQueBtn" id="pre">上一题</button>
                    <button class="queBtn nextQuestion trueQueBtn" id="next">下一题</button>
                </div>
            </div>
        </div>
        <div class="col-md-3 problemNum">
            <div class="row queDataNum">
                <div class="col-md-4 totalQue">
                    <p>总题数：<span>{{ $task->target->questions_count }}</span></p>
                </div>
                <div class="col-md-4 alreadyQue">
                    <p>已答：<span></span></p>
                </div>
                <div class="col-md-4 unanswered">
                    <p>未答：<span></span></p>
                </div>
            </div>
            <div class="questionNumBtn">

                @foreach($task->target->questions as $question)
                    <div class="col-zdlg-2-5">
                        <div class="sectionQue {{ $loop->first ? 'nowQueStyle' : 'alreadyStyle' }} alreadyStyle question"
                             id="qb{{ $loop->iteration }}"
                             data-first="{{ $loop->first ? '1' : '0'}}"
                             data-end="{{ $loop->last ? '1' : '0' }}"
                             data-id="{{ $question->id }}">{{ $loop->iteration }}</div>
                    </div>
                @endforeach
            </div>
            <div class="postQue">
                <button type="button" data-toggle="modal" data-target="#modal_5" id="submit"
                        data-num="{{ $task->target->questions_count }}">提交
                </button>
            </div>
        </div>
    @endif
</div>

<div class="modal modal-danger fade" id="modal_5" tabindex="-1" role="dialog" aria-labelledby="modal_5"
     aria-hidden="true">
    <div class="modal-dialog" role="document" style="height:100%">
        <div class="modal-content" style="position:absolute;left:0;top:0;bottom: 200px;right:0;margin:auto;">
            <div class="modal-body">
                <div class="modal_con">
                    <p id="noq" style="display:none;">您还有题目没有完成</p>
                    <p>是否确认提交并查看测试结果？</p>
                    <div class="postAndAnswer">
                        <button id="goon">取消</button>
                        {{--<button id="confirm" data-route="{{ route('tasks.result.paper', $task) }}">确认提交</button>--}}
                        <button id="confirm" data-route="{{ renderTaskResultRoute('tasks.result.paper',[$task], $member) }}">确认提交</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var answerEnd = false;
    // 获取已答和未答的元素
    var alreadyQue = $('.alreadyQue').children('p').children('span');
    var unanswered = $('.unanswered').children('p').children('span');

    const taskId = $('.czh_content').attr('data-taskId');

    // 页面加载，用户的答题记录展示
    $(function () {
        var answer = JSON.parse(window.localStorage.getItem(`answer-${taskId}`));
        var alread_num = answer ? Object.keys(answer).length : 0; // 获取缓存数据的条数 如果没有 value = 0

        // 如果答案不为空
        if (answer && alread_num !== 0) {
            for (var i in answer) {
                if (answer[i] instanceof Array) {
                    for (var x = 0; x < answer[i].length; x++) {
                        var elm = $('#' + i + ' .option_content .eachOption');
                        elm.each(function (index, item) {
                            if ($(item).attr('data-value') == answer[i][x]) {
                                $(item).addClass('active');
                            }
                        })
                    }
                } else {
                    obj['ue_' + i] && obj['ue_' + i].setContent(answer[i]);
                }
            }
        }

        // 填充答题进度
        alreadyQue.html(alread_num);
        unanswered.html({{ $task->target->questions_count }} - alread_num);
    });
    // 考试时间定时器
    var queTime = setInterval(function () {
        // 获取时间
        var $demo = $('#secondNum');

        var $num = $demo.html();

        if ($num * 1 <= 0) {
            edu.alert('考试时间到！');

            clearTimeout(queTime);
            $('#confirm').trigger('click');
        } else {
            $demo.html($num * 1 - 1);
        }
    }, 1000);

    // 右侧栏点选题目
    $('.question').click(function () {
        // 获取点选的id
        var _id = $(this).data('id');

        // 展示要展示的题目
        $('.queData_content').css('display', 'none');
        $('#' + _id).css('display', 'block');

        // 设置选中状态
        $('.question').removeClass('nowQueStyle');
        $(this).addClass('nowQueStyle');

        // 设置序号和当前id
        $('#current').html($(this).html());
        $('#current').attr('data-current', _id);
        $('#current').attr('data-sort', $(this).html());

        // 上一题，下一题，不可点状态
        var first = $(this).data('first');
        var end = $(this).data('end');

        $('#pre').removeClass('falseQueBtn').addClass('trueQueBtn');
        $('#next').removeClass('falseQueBtn').addClass('trueQueBtn');

        if (first) {
            $('#pre').removeClass('trueQueBtn').addClass('falseQueBtn');
        }

        if (end) {
            $('#next').removeClass('trueQueBtn').addClass('falseQueBtn');
        }

    })

    // 上一题
    $('#pre').on('click', function () {
        if ($(this).hasClass('falseQueBtn')) {
            return false;
        }
        var sort = parseInt($('#current').attr('data-sort')) - 1;

        if (sort < 1) {
            return false;
        }

        $('#qb' + sort).trigger('click');
    })

    // 下一题
    $('#next').on('click', function () {
        if ($(this).hasClass('falseQueBtn')) {
            return false;
        }

        var sort = parseInt($('#current').attr('data-sort')) + 1;

        if (sort < 1) {
            return false;
        }

        $('#qb' + sort).trigger('click');
    })

    // 选项
    $('.eachOption').on('click', function () {
        var answer = JSON.parse(window.localStorage.getItem(`answer-${taskId}`));
        var value = $(this).attr('data-value');
        var type = $(this).attr('data-type');
        var qid = $(this).attr('data-qid');

        if (!value || !type || !qid) {
            return false;
        }

        if ($.inArray(qid, answer ? Object.keys(answer) : []) < 0) {

            alreadyQue.html(alreadyQue.html() * 1 + 1);
            unanswered.html(unanswered.html() * 1 - 1);
        }

        switch (type) {
            // 单选
            case 'single':
                $(this).parent().find('.eachOption').removeClass('active');
                $(this).addClass('active');

                // 选择单选答案
                var answer = JSON.parse(window.localStorage.getItem(`answer-${taskId}`));

                // 如果已经开始答题
                if (answer) {
                    // 找到并更新
                    answer[qid] = [parseInt(value)];
                }

                window.localStorage.setItem(`answer-${taskId}`, JSON.stringify(answer || {[qid]: [parseInt(value)]}));

                break;
            // 多选
            case 'multiple':

                var answer = JSON.parse(window.localStorage.getItem(`answer-${taskId}`));

                if ($(this).hasClass('active')) {

                    // 不选中了
                    if (answer && answer[qid]) {
                        // 找到并更新
                        var index = answer[qid].indexOf(parseInt(value));
                        answer[qid].splice(index, 1);
                        if (!answer[qid].length) delete answer[qid];
                    }

                    window.localStorage.setItem(`answer-${taskId}`, JSON.stringify(answer));

                    $(this).removeClass('active');
                } else {
                    // 选中

                    if (answer) {
                        if (answer[qid]) {
                            if (answer[qid].indexOf(value) < 0) {
                                answer[qid].push(parseInt(value));
                            }
                        } else {
                            answer[qid] = [parseInt(value)];
                        }
                    }
                    window.localStorage.setItem(`answer-${taskId}`, JSON.stringify(answer || {[qid]: [parseInt(value)]}));

                    $(this).addClass('active');
                }

                break;
            default:
                return false;
        }

    })

    // storage, 监听
    var orignalSetItem = localStorage.setItem;
    localStorage.setItem = function (key, newValue) {
        var setItemEvent = new Event("setItemEvent");
        setItemEvent.newValue = newValue;
        window.dispatchEvent(setItemEvent);
        orignalSetItem.apply(this, arguments);
    }
    window.addEventListener("setItemEvent", function (e) {
        if (answerEnd) return;

        setTimeout(function () {
            var ueditor_preference = JSON.parse(window.localStorage.getItem('ueditor_preference'))
                , urlName = window.location.href.replace(/\?.+/, '').replace(/:\//, '').replace(/[\/\.:]/g, '_');
            urlName += 'question_editor_';

            for (var i in ueditor_preference) {
                if (i.indexOf(urlName) > -1) {

                    var qid = new Number(i.replace(urlName, '').split('-')[0])
                        , value = ueditor_preference[i];
                    var answer = JSON.parse(window.localStorage.getItem(`answer-${taskId}`));

                    // 如果已经开始答题
                    if (answer) {
                        if (answer[qid] === value) {
                            continue;
                        }
                        // 找到并更新
                        answer[qid] = value;
                    }

                    window.localStorage.setItem(`answer-${taskId}`, JSON.stringify(answer || {[qid]: value}));
                }
            }
        }, 300)
    });

    // 获取对象的个数的函数
    function getJsonLength(jsonData) {
        var length = 0;
        for (var ever in jsonData) {
            length++;
        }
        return length;
    }

    // 提交答案确认模态框
    $('#submit').click(function () {
        var num = $(this).attr('data-num');

        var answer = JSON.parse(window.localStorage.getItem(`answer-${taskId}`));
        if (parseInt(num) > getJsonLength(answer)) {
            $('#noq').show();
        }else {
            $('#noq').hide();
        }

    })

    // 继续答题
    $('#goon').click(function () {
        $('#modal_5').modal('toggle');
    })

    // 后台提交答案
    $('#confirm').click(function () {

        // 答案
        var answer = JSON.parse(window.localStorage.getItem(`answer-${taskId}`));

        if (!answer) {
            answer = [];
        }

        var time = $('#secondNum').html();

        // 考试结果批阅
        $.ajax({
            url: $(this).data('route'),
            type: 'post',
            data: {
                answer: answer, time: time,
            },
            success: function (res) {

                if (res.status == '200') {
                    clearTimeout(queTime);

                    edu.alert('success', '考试完成');

                    answerEnd = true;

                    window.localStorage.removeItem('answer');

                    window.location.reload();
                }
            }
        })
    })


</script>

