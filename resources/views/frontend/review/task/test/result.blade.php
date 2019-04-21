{{--@extends('frontend.review.task.layout')--}}
{{--@section('title','测试')--}}
{{--@section('style')--}}
<link rel="stylesheet" href="{{ mix('/css/front/task/test/result.css') }}">
{{--@endsection--}}

{{--@section('content')--}}
<div class="czh_result_content">
    <div class="result_content">
        @if ($paperResult->is_mark)

            @switch($paperResult->answer_score)
                @case(0 < $paperResult->answer_score && $paperResult->answer_score <= 60)

                <div class="result_view pass">
                    <div class="result_people"></div>
                    <p class="result_score">{{ $paperResult->answer_score}}</p>
                </div>
                @break

                @case(60 < $paperResult->answer_score && $paperResult->answer_score < 80)
                <div class="result_view good">
                    <div class="result_people"></div>
                    <p class="result_score">{{ $paperResult->answer_score}}</p>
                </div>
                @break

                @default
                <div class="result_view excellent">
                    <div class="result_people"></div>
                    <p class="result_score">{{ $paperResult->answer_score}}</p>
                </div>

            @endswitch

        @else
            <div class="result_view pass">
                <div class="result_people"></div>
                <p class="marking_paper">阅卷中</p>
            </div>
        @endif


        <div class="resultAnswer row">
            @if (!$paperResult->is_mark)
                <div class="col-md-12 row">
                    <p class="col-md-6 text-right">阅卷中</p>
                    <p class="col-md-6 text-left">
                        {{ $paperResult->finished_count - $paperResult->right_count - $paperResult->false_count }}题
                    </p>
                </div>
            @endif
            <div class="col-md-12 row">
                <p class="col-md-6 text-right">回答正确</p>
                <p class="col-md-6 text-left">{{ $paperResult->right_count }}题</p>
            </div>
            <div class="col-md-12 row">
                <p class="col-md-6 text-right">回答错误</p>
                <p class="col-md-6 text-left"><span class="font_custom">{{ $paperResult->false_count }}</span>题</p>
            </div>
            <div class="col-md-12 form_cat_answer">

                    <a href="{{ renderTaskRoute([$chapter, 'task' => $task->id, 'paper' => 'result'], $member) }}">
                    <button class="btn btn-primary cat_answer_btn" type="button">查看测试结果</button>
                    </a>
            </div>
        </div>
    </div>
</div>
{{--@endsection--}}

{{--@section('script')--}}
{{--<script src="{{ mix('/js/front/task/test/index.js') }}"></script>--}}
{{--@endsection--}}
