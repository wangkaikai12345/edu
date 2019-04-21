@extends('frontend.default.task.index')
@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/task/homework/index.css') }}">
@endsection
@section('content')
    @include('frontend.default.task.slideNav.index')
    <div class="xh">
        <div class="container">
            <div class="xh_content">
                <div class="xh_first_content">
                    <div class="number_content">1</div>
                    <div class="homework_title">输出乘法口诀</div>
                </div>
                <div class="xh_second_content">
                    <div class="homework_item">
                        <div class="homework_item_dian"></div>
                        <div class="homework_item_title">
                            作业要求：
                        </div>
                        <div class="homework_item_desc">
                            根据课程中打印10行10列的星星的例子，编写9*9乘法口诀表根据课程中打印10行10列的星星的例子，编写9*9乘法口诀表
                        </div>
                    </div>
                    <div class="homework_item">
                        <div class="homework_item_dian"></div>
                        <div class="homework_item_title">
                            解题提示：
                        </div>
                        <div class="homework_item_desc">
                            分行与列考虑，共9行9列，i控制行，j控制列
                        </div>
                    </div>
                    <div class="homework_item">
                        <div class="homework_item_dian"></div>
                        <div class="homework_item_title">
                            预计时间：
                        </div>
                        <div class="homework_item_desc">
                            1小时
                        </div>
                    </div>
                    <div class="homework_item">
                        <div class="homework_item_dian"></div>
                        <div class="homework_item_title data_pack">
                            资料包：
                        </div>
                        <div class="homework_item_desc">
                            <a href="javascript:;" class="pack">
                                2.png
                                <i class="iconfont">&#xe626;</i>
                            </a>
                        </div>
                    </div>
                    <div class="homework_item">
                        <div class="homework_item_dian"></div>
                        <div class="homework_item_title">
                            批改标准：
                        </div>
                        <div class="homework_item_desc">
                            <span class="pack">1. </span>编码规范  分值: <span>30</span>
                        </div>
                        <div class="homework_item_desc">
                            <span class="pack">2. </span>运行结果  分值: <span>70</span>
                        </div>
                    </div>
                    <div class="homework_item">
                        <div class="homework_item_dian"></div>
                        <div class="homework_item_title">
                            成绩排名：
                        </div>
                        <div class="homework_item_desc ranking">
                            <div class="ranking_item">
                                <div class="student_img">
                                    <img src="/imgs/user/front/task/homework/first-student.png" alt="">
                                </div>
                                <div class="ranking_right_content">
                                    <div class="ranking_student_name">
                                        马慧婷
                                    </div>
                                    <div class="ranking_student_score">98</div>
                                    <div class="ranking_student_date">3-17 13:49</div>
                                </div>
                            </div>
                            <div class="ranking_item">
                                <div class="student_img">
                                    <img src="/imgs/user/front/task/homework/second-student.png" alt="">
                                </div>
                                <div class="ranking_right_content">
                                    <div class="ranking_student_name">
                                        顿增利
                                    </div>
                                    <div class="ranking_student_score">97</div>
                                    <div class="ranking_student_date">6-04 18:59</div>
                                </div>
                            </div>
                            <div class="ranking_item">
                                <div class="student_img">
                                    <img src="/imgs/user/front/task/homework/third-student.png" alt="">
                                </div>
                                <div class="ranking_right_content">
                                    <div class="ranking_student_name">
                                        董策
                                    </div>
                                    <div class="ranking_student_score">97</div>
                                    <div class="ranking_student_date">3-29 22:12</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right_content">
                <button type="button" class="submit_button">提交作业</button>
            </div>
        </div>
    </div>
@endsection