@extends('teacher.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/course/notice/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/exam/topic_list.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/exam/paper_mark.css') }}">
    <style>
        .mark_over {
            background-color: #e1e1e1;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row padding_content">
            <div class="col-xl-9 col-md-12 col-12 form_content p-0">
                <!-- Attach a new card -->
                <div class="card">
                    <div class="card-body row_content" style="min-height:500px">
                        <div class="row_div">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h6>阅卷 [{{ $paper->title }}] ({{ auth('web')->user()->username }})</h6>
                                </div>
                                <div class="col-lg-4 text-lg-right">
                                    <a href="{{ route('manage.paper.result.index') }}" id="back-list" class="btn reback_list">
                                        <i class="iconfont">
                                            &#xe644;
                                        </i>
                                        返回批阅列表
                                    </a>
                                </div>
                            </div>
                            <hr class="course_hr">
                        </div>
                        <div class="bd-example">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="question_num">
                                            @foreach($questionResults as $questionResult)
                                                <div data-id="{{ $questionResult->question_id }}" id="question_list_{{ $questionResult->question_id }}"
                                                     class="question_list num_item {{ $loop->first ? 'active' : '' }}">
                                                    {{ $loop->iteration }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr width="95%" style="margin:0 auto;">
                            {{--<div class="row">--}}
                            {{--<div class="col-12">--}}
                            {{--<div class="form-group">--}}
                            {{--<div class="answer_student" style="width:90%;margin:0 auto;">--}}
                            {{--<label for="">答题学员</label>--}}
                            {{--</div>--}}
                            {{--<div class="question_num answer_student_content">--}}
                            {{--<div class="num_item active student_item">--}}
                            {{--某某某--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="answer_student" style="width:90%;margin:0 auto;">
                                            <label for="">题目</label>
                                        </div>
                                        <div class="question_content" id="question_title">
                                            加载中...
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="answer_student" style="width:90%;margin:0 auto;">
                                            <label for="">学员答案</label>
                                        </div>
                                        <div class="question_content" id="user_answer">
                                            加载中...
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="answer_student" style="width:90%;margin:0 auto;">
                                            <label for="">答案解析</label>
                                        </div>
                                        <div class="question_content" id="question_explain">
                                            加载中...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-12 col-12">
                <div class="card">
                    <div class="card-body row_content" style="min-height:500px">
                        <div class="row_div">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h6>评分</h6>
                                </div>
                                <div class="col-lg-5 text-lg-right mt-4">
                                    <div class="over_mark">
                                        已批阅
                                        <span id="mark_over_num">0</span>/<span id="mark_all_num">0</span>题
                                    </div>
                                </div>
                            </div>
                            <hr class="course_hr" style="margin-top:21px;">
                        </div>
                        <div class="bd-example">
                            <div class="row">
                                <div class="col-10" style="margin:0 auto;">
                                    <div class="form-group">
                                        <div class="answer_student">
                                            <label for="">当前题目分值：<span id="question_score">-</span>
                                                <span id="is_mark" style="color: #ed4014; display: none">&nbsp;&nbsp;已批阅</span></label>
                                        </div>
                                        <input type="number" name="mark-one-show" id="mark-one-show"
                                               class="form-control col-lg-12"
                                               placeholder="评定分值：" data-max="10">
                                        <div class="calculator_content col-lg-12 mt-3 mb-3 p-0 float-left">
                                            <div class="calculator_number_item">
                                                <div class="calculator_number">
                                                    1
                                                </div>
                                            </div>
                                            <div class="calculator_number_item">
                                                <div class="calculator_number">
                                                    2
                                                </div>
                                            </div>
                                            <div class="calculator_number_item">
                                                <div class="calculator_number">
                                                    3
                                                </div>
                                            </div>
                                            <div class="calculator_number_item">
                                                <div class="calculator_number">
                                                    4
                                                </div>
                                            </div>
                                            <div class="calculator_number_item">
                                                <div class="calculator_number">
                                                    5
                                                </div>
                                            </div>
                                            <div class="calculator_number_item">
                                                <div class="calculator_number">
                                                    6
                                                </div>
                                            </div>
                                            <div class="calculator_number_item">
                                                <div class="calculator_number">
                                                    7
                                                </div>
                                            </div>
                                            <div class="calculator_number_item">
                                                <div class="calculator_number">
                                                    8
                                                </div>
                                            </div>
                                            <div class="calculator_number_item">
                                                <div class="calculator_number">
                                                    9
                                                </div>
                                            </div>
                                            <div class="calculator_number_item" data-type="clear">
                                                <div class="calculator_number">
                                                    清除
                                                </div>
                                            </div>
                                            <div class="calculator_number_item">
                                                <div class="calculator_number">
                                                    0
                                                </div>
                                            </div>
                                            <div class="calculator_number_item" data-type="max">
                                                <div class="calculator_number">
                                                    满分
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 p-0 float-left">
                                            <button id="confirm-grade" class="btn btn-primary score_btn float-right">
                                                确认
                                            </button>
                                        </div>
                                        <div class="col-lg-12 p-0 mb-3 float-left">
                                            <input type="text" name="remark" id="mark-remark-show"
                                                   class="form-control col-lg-12"
                                                   placeholder="评语：">
                                        </div>
                                        <div class="col-lg-12 p-0">
                                            <button id="end-paper-mark" data-url="{{ route('manage.paper.result.store', [$paper, $paperResult]) }}" class="btn btn-primary end-mark float-right">
                                                结束阅卷
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="question_answer_id" value="">
        <input type="hidden" id="question_id" value="">
        @endsection
        @section('script')
            <script src="{{ mix('/js/teacher/exam/paper_mark.js') }}"></script>
            <script>
                $(function () {
                    /**
                     * 获取必要的数据
                     */
                    var questions = @json($questions); // 所有题目
                    var questionResults = @json($questionResults); // 所有回答
                    var paperQuestions = @json($paperQuestions); // 所有分值
                    var storage, read_answer_ids, read_answer_data = [];
                    var all_num = Object.keys(questionResults).length;

                    /**
                     * 缓存数据
                     */
                    function storgeData(answer_id, mark_data) {
                        // 是否存储id
                        if ($.inArray(answer_id, read_answer_ids) < 0) {
                            read_answer_ids.push(answer_id);
                            storage.setItem('read_answer_ids', JSON.stringify(read_answer_ids));
                        }

                        var key = 'mark_' + answer_id;
                        // 添加或者替换数据
                        storage.setItem(key, JSON.stringify(mark_data));
                    }

                    /**
                     * 获取数据
                     */
                    function getData() {
                        storage = window.localStorage;
                        // 获取已批阅的answer id
                        read_answer_ids = storage.getItem('read_answer_ids') ? JSON.parse(storage.getItem('read_answer_ids')) : [];

                        // 如果有批阅记录, 才会获取批阅数据
                        if (read_answer_ids.length > 0) {
                            for (var o in read_answer_ids) {
                                var key = 'mark_' + read_answer_ids[o];
                                read_answer_data.push(JSON.parse(storage.getItem(key)));
                            }
                        }

                        return {
                            read_answer_ids: read_answer_ids,
                            read_answer_data: read_answer_data
                        };
                    }
                    // 自动执行一次获取缓存数据
                    getData();

                    /**
                     * 进入该页面的时候, 显示第一条数据
                     */
                    var firstQuestion = @json($firstQuestion);
                    changeQuestion(firstQuestion.id);

                    /**
                     * 根据题目的id不同, 渲染不同的数据
                     */
                    function changeQuestion(qid) {
                        var question = questions[qid];
                        var answer = questionResults[qid];
                        var paperQuestion = paperQuestions[qid];

                        // 题目标题
                        $('#question_title').html(question.title);
                        // 答案解析
                        $('#question_explain').html(question.explain);
                        // 学员如果没有答第一个题目
                        if (answer == undefined) {
                            $('#user_answer').html('此题目未答');
                        } else {
                            $('#user_answer').html(answer.subjective_answer);
                        }
                        // 显示当前分值的分数
                        $('#question_score').html(paperQuestion.score);

                        // 设置当前回答的id
                        $('#question_answer_id').val(answer.id);

                        // 设置当前的题目id
                        $('#question_id').val(question.id);

                        // 如果题目已经阅卷, 显示相关信息 is_mark
                        if ($.inArray((answer.id).toString(), read_answer_ids) >= 0 ) {
                            var key = 'mark_' + answer.id;
                            var mark_info = JSON.parse(storage.getItem(key));
                            showScore(mark_info.score, mark_info.remark);
                            $('#is_mark').show();
                        } else {
                            clearScore();
                            $('#is_mark').hide();
                        }

                        // 渲染数字
                        $('#mark_over_num').text(read_answer_ids.length);
                        $('#mark_all_num').text(all_num);
                    }

                    /**
                     * 改变已经阅卷的状态
                     */
                    function changeColor() {
                        for (var o in questionResults) {
                            // 如果已经批阅过, 那么变绿色 #19be6b
                            if ($.inArray((questionResults[o].id).toString(), read_answer_ids) >= 0 ) {
                                $('#question_list_' + questionResults[o].question_id).addClass('mark_over');
                            }
                        }

                    }
                    // 页面一加载就检查批阅情况
                    changeColor();

                    /**
                     * 点击题目编号切换下一题
                     */
                    $('.question_list').click(function () {
                        changeQuestion($(this).data('id'));
                        // 移除所有的选中, 给当前选中的题目显示选中样式
                        $('.question_list').removeClass('active');
                        $(this).addClass('active');
                    });

                    /**
                     * 清空打分和备注
                     */
                    function clearScore() {
                        // 清空已打的分数
                        $('#mark-one-show').val('');
                        // 清空备注
                        $('#mark-remark-show').val('');
                    }

                    /**
                     * 显示打分和备注
                     */
                    function showScore(s, r) {
                        // 清空已打的分数
                        $('#mark-one-show').val(s);
                        // 清空备注
                        $('#mark-remark-show').val(r);
                    }

                    /**
                     * 分数变化事件
                     */
                    $('.calculator_number_item').on({
                        click: markChange
                    });
                    $('#mark-one-show').change(markChange);
                    function markChange() {
                        var val = $('#mark-one-show').val()
                            , max = $('#question_score').html();
                        // 点击清除的显示
                        if ($(this).data('type') === 'clear') {
                            $('#mark-one-show').val('');
                            return;
                        }
                        // 点击满分的显示
                        if ($(this).data('type') === 'max') {
                            $('#mark-one-show').val(max);
                            return;
                        }
                        val = new Number((val + $(this).text()).replace(/\s/g, ''));
                        // 如果分数大于指定分数 等于最大分数
                        val = val > max ? max : val;

                        $('#mark-one-show').val(val);
                    }

                    /**
                     * 确定打分事件
                     */
                    $('#confirm-grade').click(function () {
                        // 如果没有分数, 直接返回
                        var score = $('#mark-one-show').val();
                        if (!score) {
                            edu.alert('danger', '分数不能为空');
                            return false;
                        }
                        // 获取当前评分的题目id
                        var answerId = $('#question_answer_id').val();

                        var data = {
                            answer_id: answerId,
                            score: $('#mark-one-show').val(),
                            remark: $('#mark-remark-show').val(),
                            question_id: $('#question_id').val()
                        };

                        // 缓存数据
                        storgeData(answerId.toString(), data);

                        // 将当前题目标记为已批阅 mark_over
                        $('#question_list_' + $('#question_id').val()).addClass('mark_over');

                        // 切换到下一题
                        var nextDiv = $('#question_list_' + $('#question_id').val()).next();
                        nextDiv.click();

                        if (nextDiv.length == 0) {
                            $('#mark_over_num').text(read_answer_ids.length);
                            $('#mark_all_num').text(all_num);
                            $('#is_mark').show();
                        }

                    });

                    /**
                     * 结束阅卷的事件
                     */
                    $('#end-paper-mark').click(function () {
                        var that = this;
                        var msg = '<div style="line-height: 40px; font-size: 18px; text-align: center; padding:20px"><p>您还有 <span style="color: #ed4014; ">'+ (all_num - read_answer_ids.length) +'</span> 题目未批阅!</p> <p>确定结束阅卷吗? 未批阅的题目将自动为0分!</p></div>';
                        edu.confirm({
                            type: 'danger',
                            dataType: 'html',
                            message: msg,
                            title: '确定要结束阅卷吗?',
                            callback: function (props) {
                                if (props.type === 'success') {
                                    var data = getData();
                                    var url = $(that).data('url');
                                    $.ajax({
                                        url: url,
                                        type: 'post',
                                        data: data,
                                        success: function(res) {
                                            if (res.status == 200) {
                                                storage.clear();
                                                edu.alert('success', '阅卷成功');
                                                window.location.href = $('#back-list').attr('href');
                                            } else {
                                                edu.alert('danger', res.message);
                                            }
                                        }
                                    });

                                }
                            }
                        });
                    })
                })
            </script>
@endsection
