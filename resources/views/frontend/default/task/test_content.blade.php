<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>考试</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <link href="/dist/vendor/css/0.css" rel="stylesheet">
    <link href="/dist/test/css/index.css" rel="stylesheet">
</head>
<body>
<div class="main">

    <div class="center">
        <h6 style="padding: 20px 0px;font-size: 30px;font-weight: 400;color: #666">{{ $test['title'] }}</h6>
    </div>

    <!--nr start-->
    <div class="test_main">
        <div class="nr_left">
            <div class="test">
                <form action="" method="post">
                    <div class="test_title">
                        <p class="test_time">
                            <img src="/dist/test/images/djs.svg" alt="" style="float: left;padding: 12px 0px 0 0px;">
                            {{--<b class="alt-1" style="float: left;">01:40</b>--}}
                        </p>
                        <font><input type="button" name="test_jiaojuan" value="交卷"></font>
                    </div>

                    @if ($test['single_count'])
                        <div class="test_content">
                            <div class="test_content_title">
                                <h2>单选题</h2>
                                <p>
                                    <span>共</span><i class="content_lit">{{ $test['single_count'] }}</i><span>题</span>
                                </p>
                            </div>
                        </div>
                        <div class="test_content_nr">
                            <ul>
                                @foreach($questions as $key => $question)
                                    @if ($question['type'] == 'single')
                                        @php
                                            $num = 0;
                                            $num++;
                                        @endphp
                                        <li id="{{ $key.'_'.$num }}">
                                            <div class="test_content_nr_tt">
                                                <i class="num">{{ $num }}</i><span>({{ $question->pivot['score'] }}分)</span>
                                                <div>
                                                   {!! $question['title'] !!}
                                                </div>
                                            </div>

                                            <div class="test_content_nr_main">
                                                <ul>

                                                    @foreach($question['options'] as $k => $option)
                                                        <li class="option">

                                                            <input type="radio" class="radioOrCheck" name="answer1"
                                                                   id="0_answer_1_option_1"
                                                            />


                                                            <label for="0_answer_1_option_1">
                                                                <p class="ue" style="display: inline;">{{ $option['content'] }}</p>
                                                            </label>
                                                        </li>
                                                        @endforeach

                                                </ul>
                                            </div>
                                        </li>
                                        @endif

                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($test['multiple_count'])
                        <div class="test_content">
                            <div class="test_content_title">
                                <h2>多选题</h2>
                                <p>
                                    <span>共</span><i class="content_lit">{{ $test['multiple_count'] }}</i><span>题</span>
                                </p>
                            </div>
                        </div>
                        <div class="test_content_nr">
                            <ul>
                                @foreach($questions as $key => $question)
                                    @if ($question['type'] == 'multiple')
                                        @php
                                            $num = 0;
                                            $num++;
                                        @endphp
                                        <li id="{{ $key.'_'.$num }}">
                                            <div class="test_content_nr_tt">
                                                <i class="num">{{ $num }}</i><span>({{ $question->pivot['score'] }}分)</span>
                                                <div>
                                                    {!! $question['title'] !!}
                                                </div>
                                            </div>

                                            <div class="test_content_nr_main">
                                                <ul>

                                                    @foreach($question['options'] as $k => $option)
                                                        <li class="option">


                                                            <input type="checkbox" class="radioOrCheck" name="answer1"
                                                                   id="1_answer_1_option_1"
                                                            />

                                                            <label for="1_answer_1_option_1">
                                                                <p class="ue" style="display: inline;">{{ $option['content'] }}</p>
                                                            </label>
                                                        </li>
                                                    @endforeach

                                                </ul>
                                            </div>
                                        </li>
                                    @endif

                                @endforeach

                            </ul>
                        </div>
                    @endif

                    @if ($test['judge_count'])
                        <div class="test_content">
                            <div class="test_content_title">
                                <h2>判断题</h2>
                                <p>
                                    <span>共</span><i class="content_lit">{{ $test['judge_count'] }}</i><span>题</span>
                                </p>
                            </div>
                        </div>
                        <div class="test_content_nr">
                            <ul>
                                <li id="qu_0_0">
                                    <div class="test_content_nr_tt">
                                        <i class="num">1</i><span>(1分)</span>
                                        <font>
                                            <img src="/dist/course/images/img1.jpg" alt="" class="question-img">
                                            这是个什么图？
                                        </font>
                                    </div>

                                    <div class="test_content_nr_main">
                                        <ul>

                                            <li class="option">

                                                <input type="radio" class="radioOrCheck" name="answer1"
                                                       id="0_answer_1_option_1"
                                                />


                                                <label for="0_answer_1_option_1">
                                                    A.
                                                    <p class="ue" style="display: inline;">在工具栏中点击“workflow”标签</p>
                                                </label>
                                            </li>

                                            <li class="option">

                                                <input type="radio" class="radioOrCheck" name="answer1"
                                                       id="0_answer_1_option_2"
                                                />


                                                <label for="0_answer_1_option_2">
                                                    B.
                                                    <p class="ue" style="display: inline;">在缺陷单界面中点击“推进流程”按钮</p>
                                                </label>
                                            </li>

                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    @endif

                </form>
            </div>

        </div>
        <div class="nr_right active">
            <div class="switch">
                <a href="javascript:;" class="toolbar">
                    <i class="fas fa-bars"></i>
                    <br>
                    <span class="sidebar_open">收起</span>
                    <span class="sidebar_close">展开</span>
                </a>
            </div>
            <div class="rt_nr1_title">
                <h1>
                    <img src="/dist/test/images/datika.svg" alt="" style="float: left;padding: 12px 5px 0 32px;">
                    <span style="float: left;">答题卡</span>
                </h1>
                <p class="test_time">
                    <img src="/dist/test/images/djs.svg" alt="" style="float: left;padding: 12px 0px 0 28px;">
                    {{--<b class="alt-1" style="float: left;">01:40</b>--}}
                </p>
            </div>
            <div class="nr_rt_main">
                <div class="rt_nr1">
                    @if ($test['single_count'])
                        <div class="rt_content">
                        <div class="rt_content_tt">
                            <h2>单选题</h2>
                            <p>
                                <span>共</span><i class="content_lit">{{ $test['single_count'] }}</i><span>题</span>
                            </p>
                        </div>
                        <div class="rt_content_nr answerSheet">
                            <ul>
                                @foreach($questions as $key => $question)
                                    @if ($question['type'] == 'single')
                                        @php
                                            $num = 0;
                                            $num++;
                                        @endphp
                                        <li><a href="#{{ $key.'_'.$num }}">1</a></li>
                                    @endif

                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif

                    @if ($test['multiple_count'])
                        <div class="rt_content">
                            <div class="rt_content_tt">
                                <h2>多选题</h2>
                                <p>
                                    <span>共</span><i class="content_lit">{{ $test['multiple_count'] }}</i><span>题</span>
                                </p>
                            </div>
                            <div class="rt_content_nr answerSheet">
                                <ul>
                                    @foreach($questions as $key => $question)
                                        @if ($question['type'] == 'multiple')
                                            @php
                                                $num = 0;
                                                $num++;
                                            @endphp
                                            <li><a href="#{{ $key.'_'.$num }}">1</a></li>
                                        @endif

                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    @if ($test['judge_count'])
                        <div class="rt_content">
                            <div class="rt_content_tt">
                                <h2>判断题</h2>
                                <p>
                                    <span>共</span><i class="content_lit">{{ $test['judge_count'] }}</i><span>题</span>
                                </p>
                            </div>
                            <div class="rt_content_nr answerSheet">
                                <ul>
                                    @foreach($questions as $key => $question)
                                        @if ($question['type'] == 'judge')
                                            @php
                                                $num = 0;
                                                $num++;
                                            @endphp
                                            <li><a href="#{{ $key.'_'.$num }}">1</a></li>
                                        @endif

                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
    <!--nr end-->
    <div class="foot"></div>
</div>
<script type="text/javascript" src="/dist/vendor/chunk/index.js"></script>
<script type="text/javascript" src="/dist/test/js/index.js"></script>
</body>
</html>