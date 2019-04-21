<form action="{{ route('manage.question.list.video') }}" id="search-question-form" onsubmit="return false;">

<div class="search_form">
        <div class="search_left">
            <p>标题关键字</p>
            <input class="form-control" type="text" name="title" id="title" value="{{ request()->title }}" placeholder="标题">
        </div>
        <div class="search_right">
            <p>出题人</p>
            <input class="form-control" type="text" name="username" id="username" value="{{ request()->username }}" placeholder="出题人">
            <input class="form-control" type="text" name="tag" id="tag" value="{{ request()->tag }}" placeholder="标签">
            <button type="submit" class="submit_btn btn btn-primary" id="search-question-btn">搜索</button>
        </div>
    </div>
    <div class="choice-content">
        <table class="table table-hover table-cards align-items-center">
            <thead>
            <tr>
                <th scope="col">题目名称</th>
                <th scope="col">题目类型</th>
                <th scope="col" width="140">难度</th>
                <th scope="col">出题人</th>
                <th scope="col" width="120">操作</th>
            </tr>
            </thead>
            <tbody>
            @php
                $ops = ['A', 'B', 'C', 'D', 'E', 'F'];
            @endphp
            @foreach($questions as $question)
            <tr class="bg-white">
                <td>{!! $question->title !!}</td>
                <td>{{ \App\Enums\QuestionType::getDescription($question->type) }}</td>
                <td>
                    <div class="star">
                        @for($i = 0; $i < 5; $i++)
                            @if($i < $question->rate)
                                <i class="iconfont">
                                    &#xe601;
                                </i>
                            @else
                                <i class="iconfont">
                                    &#xe60d;
                                </i>
                            @endif
                        @endfor
                    </div>
                </td>
                <td>{{ $question->user->username }}</td>
                <td class="operation">
                    <a class="yu_lan" href="javascript:;">
                        <button class="btn btn-link show-question-info" data-toggle="modal" data-id="{{ $question->id }}" data-target="#preview-question">
                            预览
                        </button>
                    </a>
                    <a class="add_q" href="javascript:;">
                        <button class="btn btn-link add_question" data-id="{{ $question->id }}" data-title="{{ $question->title }}">添加</button>
                    </a>
                </td>
            </tr>

            {{--  这里来放置问题详情  --}}
            <div style="display:none">
                <div id="show-question-info-{{ $question->id }}" class="row">
                    <div class="question_type_and_title">
                        <p class="question_type">
                            {{ \App\Enums\QuestionType::getDescription($question->type) }}
                        </p>
                        <p class="question_title">
                            {!! $question->title !!}
                        </p>
                    </div>
                    <div class="question_title_and_options">
                        <p class="question_title">
                            题目选项
                        </p>
                        <ul class="question_options" id="options-info-{{ $question->id }}">
                            @foreach($question->options as $key => $option)
                                <li>
                                    {!! $ops[$key] . '.' .$option !!}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="question_answer_and_text">
                        <p class="question_answer">
                            答案解析
                        </p>
                        <p class="question_text">
                            {!! $question->explain !!}
                        </p>
                    </div>

                </div>
            </div>
            @endforeach
            </tbody>
        </table>
    </div>
</form>
{{--<script type="text/javascript">--}}
{{--$(document).on('click', '.add_q button', function () {--}}

{{--var str = '';--}}
{{--str += '<tr class="bg-white">';--}}
{{--str += '<td>命名命名</td>';--}}
{{--str += '<td>';--}}
{{--str += '<button class="btn btn-link b_t_t">展开</button>';--}}
{{--str += '<i class="iconfont b_t_t_i">&#xe616;</i>';--}}
{{--str += '</td>';--}}
{{--str += '<td>';--}}
{{--str += '<input class="form-control b_t_n" type="number">';--}}
{{--str += '</td>';--}}
{{--str += '<td>';--}}
{{--str += '<a href="javascript:;">';--}}
{{--str += '<button class="btn btn-link b_t_b">删除</button>';--}}
{{--str += '</a>';--}}
{{--str += '</td>';--}}
{{--str += '</tr>';--}}
{{--str += '<tr>';--}}
{{--str += '<td class="q_options" colspan="4">';--}}
{{--str += '<ul>';--}}
{{--str += '<li>A.我是答案A</li>';--}}
{{--str += '<li>B.我是答案B</li>';--}}
{{--str += '<li>C.我是答案C</li>';--}}
{{--str += '<li>D.我是答案D</li>';--}}
{{--str += '</ul>';--}}
{{--str += '</td>';--}}
{{--str += '</tr>';--}}

{{--$('#questions_body').append(str);--}}

{{--$(this).attr('disabled',"true");--}}
{{--$(this).parent().css("cursor", "not-allowed");--}}
{{--});--}}
{{--</script>--}}