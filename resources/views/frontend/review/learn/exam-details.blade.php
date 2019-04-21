@extends('frontend.review.layouts.app')
@section('title')
    我的考试-PHP课前测试
@endsection
@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/learn/exam-details.css') }}">
@endsection
@section('content')
    @php
        $ops = ['A', 'B', 'C', 'D', 'E', 'F', 'H'];

        $singleQue = $result->questionResult->where('type','single');

        $multipleQue = $result->questionResult->where('type','multiple');

        $answerQue = $result->questionResult->where('type','answer');
    @endphp
    <div class="container">
        <div class="row czh_examDetails">
            <div class="left_con col-md-9">
                <div class="left_con_top col-md-12 row">
                    <p class="exam_title col-md-10">{{ $result->paper_title }}</p>
                    <p class="exam_status col-md-2">批阅已完成</p>
                    <div class="details_data col-md-12 row">
                        <p class="answer_user">
                            答题人：{{ $result->user->username}}
                        </p>
                        <p class="post_time">
                            交卷时间：{{ $result->end_at }}
                        </p>
                        <p class="use_time">
                            用时：{{ round($result->time / 60) }}分钟
                        </p>
                    </div>
                    <div class="exam_score_data col-md-12 row">
                        <div class="exam_score_num col-md-3 p-0">
                            <p class="e_s_n">
                                <span>{{ $result->answer_score }}</span> 分
                            </p>
                            <p class="total_score">
                                总分：{{ $result->score }}分
                            </p>
                            <div class="exam_que_desc">
                                <p>
                                    本考试共{{ count($result->paper->questions) }}题，总分{{ $result->score }}
                                    ，及格为{{ $result->pass_score }}
                                </p>
                            </div>
                        </div>
                        <div class="exam_score_table col-md-9">
                            <table class="e_s_t">
                                <tr>
                                    <th class="shortTable"></th>
                                    <th>单选题</th>
                                    <th>多选题</th>
                                    <th>问答题</th>
                                </tr>
                                <tr class="answer_yes">
                                    <td class="shortTable">答对</td>
                                    <td>{{ $singleQue->where('status','right')->count() }}</td>
                                    <td>{{ $multipleQue->where('status','right')->count() }}</td>
                                    <td>{{ $answerQue->where('status','right')->count() }}</td>
                                </tr>
                                <tr class="answer_no">
                                    <td class="shortTable">答错</td>
                                    <td>{{ $singleQue->where('status','error')->count() }}</td>
                                    <td>{{ $multipleQue->where('status','error')->count() }}</td>
                                    <td>{{ $answerQue->where('status','error')->count() }}</td>
                                </tr>
                                <tr class="answer_not">
                                    <td class="shortTable">未答</td>
                                    <td>{{ $singleQue->where('status','noanswer')->count() }}</td>
                                    <td>{{ $multipleQue->where('status','noanswer')->count() }}</td>
                                    <td>{{ $answerQue->where('status','noanswer')->count() }}</td>
                                </tr>
                                <tr class="answer_score">
                                    <td class="shortTable">得分</td>
                                    <td>{{ $singleQue->sum('score') }}</td>
                                    <td>{{ $multipleQue->sum('score') }}</td>
                                    <td>{{ $answerQue->sum('score') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="exam_tips col-md-12">
                        <p>恭喜您已通过本次考试</p>
                    </div>
                </div>
                <div class="left_con_bottom col-md-12 row pl-0 pr-0">
                    {{--单选题--}}
                    @if(count($singleQue) > 0)
                        <div class="single_question">
                            <div class="que_type_title">
                                <p>单选题</p>
                                {{--                                <span>共{{ count($singleQue) }}题 共 分</span>--}}
                            </div>
                            <hr class="title_hr">
                            @foreach($singleQue as $single)
                                <div class="question_item {{ $single->status == 'right' ? 'rightQ' : '' }}">
                                    <p class="question_title">{{ $loop->iteration}}.
                                        题目：{{ substr($single->question->title,3,-4) }}</p>
                                    <p class="question_score">
                                        {{ questionScore($single->paper_id,$single->question_id) }}分
                                    </p>
                                    <div class="que_options">
                                        @foreach($single->question->options as $option)
                                            <div class="que_option {{ in_array($loop->index, $single->question->answers) ? 'active' : '' }}">
                                                <span>{{ $ops[$loop->index] }}.</span> {{ substr($option,3,-4) }}
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="true_data">
                                        <p class="answerNum">正确答案: C</p>
                                        <p class="answerStatus {{ $single->status == 'right' ? 'true_g' : 'false_r' }} ">{{ $single->status == 'right' ? '回答正确' : '回答错误' }}</p>
                                        {{--                                    <button class="btn btn-link collection">收藏</button>--}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    {{--多选题--}}
                    @if(count($multipleQue) > 0)
                        <div class="single_question">
                            <div class="que_type_title">
                                <p>多选题</p>
                                {{--                                <span>共2题 共4分</span>--}}
                            </div>
                            <hr class="title_hr">
                            @foreach($multipleQue as $multiple)
                                <div class="question_item {{ $multiple->status == 'right' ? 'rightQ' : '' }}">
                                    <p class="question_title">{{ $loop->iteration}}.
                                        题目：{{ substr($multiple->question->title,3,-4) }}</p>
                                    <p class="question_score">
                                        {{ questionScore($multiple->paper_id,$multiple->question_id) }}分
                                    </p>
                                    <div class="que_options">
                                        @foreach($multiple->question->options as $option_m)
                                            <div class="que_option {{ in_array($loop->index, $multiple->question->answers) ? 'active' : '' }}">
                                                <span>{{ $ops[$loop->index] }}.</span> {{ substr($option_m,3,-4) }}
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="true_data">
                                        <p class="answerNum">正确答案: A D</p>
                                        <p class="answerStatus {{ $multiple->status == 'right' ? 'true_g' : 'false_r' }} ">{{ $multiple->status == 'right' ? '回答正确' : '回答错误' }}</p>
                                        {{--                                        <button class="btn btn-link collection">收藏</button>--}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    {{--问答题--}}
                    @if(count($answerQue) > 0)
                        <div class="single_question">
                            <div class="que_type_title">
                                <p>问答题</p>
                                {{--                                <span>共2题 共4分</span>--}}
                            </div>
                            <hr class="title_hr">
                            @foreach($answerQue as $answer)
                                <div class="question_item {{ $answer->status == 'right' ? 'rightQ' : '' }}">
                                    <p class="question_title">{{ $loop->iteration}}.
                                        题目：{{ substr($multiple->question->title,3,-4) }}</p>
                                    <p class="question_score">
                                        {{ questionScore($answer->paper_id,$answer->question_id) }}分
                                    </p>
                                    <div class="que_options">
                                        <div class="answer">
                                            <p class="answer_title">我的答案:</p>
                                            <p class="answer_con">
                                                {{ substr($answer->subjective_answer,3,-4) }}
                                            </p>
                                        </div>
                                        <div class="answer">
                                            <p class="answer_title">正确答案:</p>
                                            <p class="answer_con">
                                                {{ $answer->question->explain }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="true_data">
                                        <p class="answerStatus {{ $answer->status == 'right' ? 'true_g' : 'false_r' }} ">{{ $multiple->status == 'right' ? '回答正确' : '回答错误' }}</p>
                                        {{--                                        <button class="btn btn-link collection">收藏</button>--}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div class="right_con col-md-3">
                <div class="answer_card_title">
                    <p class="">
                        答题卡
                    </p>
                    {{--                    <a href="/" class="btn btn-default">再考一次</a>--}}
                </div>
                @if(count($singleQue) > 0)
                    <div class="que_card">
                        <p class="que_card_title">单选题</p>
                        <div class="queNum">
                            @foreach($singleQue as $singleNum)
                                @if($singleNum->status == 'right')
                                    <div class="queNumItem trueQue">
                                        {{ $loop->iteration }}
                                    </div>
                                @elseif($singleNum->status == 'error')
                                    <div class="queNumItem falseQue">
                                        {{ $loop->iteration }}
                                    </div>
                                @else
                                    <div class="queNumItem waitQue">
                                        {{ $loop->iteration }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
                @if(count($multipleQue) > 0)
                    <div class="que_card">
                        <p class="que_card_title">多选题</p>
                        <div class="queNum">
                            @foreach($multipleQue as $multipleNum)
                                @if($multipleNum->status == 'right')
                                    <div class="queNumItem trueQue">
                                        {{ $loop->iteration }}
                                    </div>
                                @elseif($multipleNum->status == 'error')
                                    <div class="queNumItem falseQue">
                                        {{ $loop->iteration }}
                                    </div>
                                @else
                                    <div class="queNumItem waitQue">
                                        {{ $loop->iteration }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
                @if(count($answerQue) > 0)
                    <div class="que_card">
                        <p class="que_card_title">问答题</p>
                        <div class="queNum">
                            @foreach($answerQue as $answerNum)
                                @if($answerNum->status == 'right')
                                    <div class="queNumItem trueQue">
                                        {{ $loop->iteration }}
                                    </div>
                                @elseif($answerNum->status == 'error')
                                    <div class="queNumItem falseQue">
                                        {{ $loop->iteration }}
                                    </div>
                                @else
                                    <div class="queNumItem waitQue">
                                        {{ $loop->iteration }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="que_status_tips">
                    <div class="status_item">
                        <div class="status_color" style="background-color:#7cb035"></div>
                        <p>正确</p>
                    </div>
                    <div class="status_item">
                        <div class="status_color" style="background-color:#e65046"></div>
                        <p>错误</p>
                    </div>
                    <div class="status_item">
                        <div class="status_color" style="background-color:#F0A818"></div>
                        <p>未做</p>
                    </div>
                    {{--                    <div class="status_item">--}}
                    {{--                        <div class="status_color" style="border:1px solid #999"></div>--}}
                    {{--                        <p>未做</p>--}}
                    {{--                    </div>--}}
                </div>
                <div class="just_look">
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="onlyError">
                        <label class="custom-control-label" for="onlyError">只看错题</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        // $(document).on('click', '.collection', function () {
        //     // 获取当前状态
        //     var btn_val = $(this).html();
        //
        //     if (btn_val == '收藏') {
        //         $(this).html('取消收藏');
        //         edu.alert('success', '收藏成功！');
        //     } else {
        //         $(this).html('收藏');
        //         edu.alert('success', '取消收藏成功！');
        //     }
        // });

        // $(function () {
        //     $(window).scroll(function () {
        //         console.log($(window).scrollTop());
        //         if ($(window).scrollTop() > 90) {
        //             $('.right_con').css({'position': 'fixed', 'top': '0', 'right': '312.5px'});
        //         } else if ($(window).scrollTop() < 90) {
        //             $('.right_con').removeAttr("style");
        //         }
        //
        //     });
        // });

        $('#onlyError').click(function () {
            if ($(this).is(':checked')) {
                $('.rightQ').hide();
            } else {
                $('.rightQ').show();
            }
        });
    </script>
@endsection