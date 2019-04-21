{{--@extends('frontend.review.task.layout')--}}
{{--@section('title','测试')--}}
{{--@section('style')--}}
<link rel="stylesheet" href="{{ mix('/css/front/task/test/analysis.css') }}">
{{--@endsection--}}

{{--@section('content')--}}
<div class="row czh_analysis_content" style="padding-bottom: 0;">
    <div class="col-md-9 analysis_left">
        <div class="analysis_header">
            <p>试卷 [ {{ $paperResult->paper_title }} ] 详解</p>

        </div>
        <span id="current"
              data-sort="1"
              data-current="{{ $task->target->questions->first()->id }}" style="display:none"></span>
        <div class="testQueData row">
            @foreach($paperResult->questionResult as $questionResult)
                <div class="queData_content col-md-10" style="display:{{ $loop->first ? 'block': 'none' }}"
                     id="{{$questionResult->id}}">
                    <div class="que_title">
                        <p>第 {{ $loop->iteration }}
                            题<span>(得分：{{ $questionResult->status == 'noread' ? ' 批阅中 ' :$questionResult->score }}
                                ) ({{ \App\Enums\QuestionType::getDescription($questionResult->question->type) }}
                                )</span>
                            <span class="q_t">{!! substr($questionResult->question->title,3,-4) !!}</span>
                        </p>
                    </div>
                    <div class="option_content">
                        @if ($questionResult->type == 'answer')
                            {!! $questionResult->subjective_answer !!}
                        @else
                            @foreach($questionResult->question->options as $option)

                                <div class="eachOption

                @if ($questionResult->objective_answer)
                                @foreach (json_decode($questionResult->objective_answer) as $answer)
                                @if ($answer == $loop->parent->index)
                                        falseOpStyle
                                        @else
                                        normalStyle
                                        @endif
                                @endforeach
                @endif

                                {{--@if ($questionResult->status == 'right')--}}
                                @foreach ($questionResult->question->answers as $answer)
                                @if ($answer == $loop->parent->index)
                                        trueOpStyle
@else
                                        normalStyle
@endif
                                @endforeach
                                {{--@else--}}


                                {{--@endif--}}
                                        ">
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
        </div>
        <div class="queAnswer col-md-10">
            <div class="queAnswer_desc">
                <p>
                    详细解答：
                </p>
            </div>
            <div class="answer_que">
                <p>{!! $questionResult->question->explain !!}</p>
            </div>
        </div>
        <div class="prevAndNextBtn">
            <button class="queBtn lastQuestion falseQueBtn" id="pre">上一题</button>
            <button class="queBtn nextQuestion trueQueBtn" id="next">下一题</button>
        </div>
    </div>
    <div class="col-md-3 analysis_right">
        <div class="row queDataNum">
            <div class="col-md-4 totalQue">
                <p>总题数：<span>{{ $paperResult->paper->questions_count }}</span></p>
            </div>
            <div class="col-md-4 alreadyQue_an">
                <p>正确：<span>{{ $paperResult->right_count }}</span></p>
            </div>
            <div class="col-md-4 unanswered_an">
                <p>错误：<span>{{ $paperResult->false_count }}</span></p>
            </div>
        </div>
        <div class="questionNumBtn">
            @foreach($paperResult->questionResult as $question)
                <div class="col-zdlg-2-5">
                    <div class="sectionQue {{ $loop->first ? 'nowTrueStyle' : '' }} {{ $question->status == 'right' ? 'trueQueStyle' : 'falseQueStyle'}} question"
                         id="qb{{ $loop->iteration }}"
                         data-first="{{ $loop->first ? '1' : '0'}}"
                         data-end="{{ $loop->last ? '1' : '0' }}"
                         data-id="{{ $question->id }}">{{ $loop->iteration }}</div>
                </div>
            @endforeach
        </div>
        @if($chapter->plan->learn_mode == 'lock')
            <div class="postQue row" style="margin: 0 auto;">

                {{--<button>重新测试</button>--}}
                <div class="col-md-6">
                    @if (count($types)>1)
                        <a href="{{ renderTaskRoute([$chapter, 'type' => $types[1]], $member) }}">
                            <button>下一步</button>
                        </a>
                    @else
                        <a href="javascript:;">
                            <button>下一步</button>
                        </a>
                    @endif
                </div>
                <div class="col-md-6">
                    <form action="" method="get">
                        <button>查看成绩</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
{{--@endsection--}}

{{--@section('script')--}}
{{--<script src="{{ mix('/js/front/task/test/index.js') }}"></script>--}}
{{--@endsection--}}

<script>

    // 右侧栏点选题目
    $('.question').click(function () {
        // 获取点选的id
        var _id = $(this).data('id');

        // 展示要展示的题目
        $('.queData_content').css('display', 'none');
        $('#' + _id).css('display', 'block');

        // 设置选中状态
        $('.question').removeClass('nowTrueStyle');
        $(this).addClass('nowTrueStyle');

        // 设置序号和当前id
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
        var sort = parseInt($('#current').attr('data-sort'));

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

        var sort = parseInt($('#current').attr('data-sort'));

        if (sort < 1) {
            return false;
        }

        $('#qb' + sort).trigger('click');
    })

</script>
