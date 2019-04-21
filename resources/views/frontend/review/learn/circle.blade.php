@extends('frontend.review.layouts.app')
@section('title')
    我的学习-我的学习圈
@endsection
@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/learn/circle.css') }}">
@endsection
@section('content')
    <div class="container">
        <div class="row padding-content">
            @include('frontend.review.learn.navBar')
            <div class="czh_learn_circle col-xl-9">
                <div class="course_head">
                    <p>我的学习圈</p>
                </div>
                <div class="course_content">
                    <div class="courseCollect row">
                        <div class="courseItem col-md-4">
                            <div class="courseImg">
                                <img src="/imgs/course.png" alt="">
                            </div>
                            <div class="courseTitle">
                                <p>我的学习圈</p>
                            </div>
                            <div class="courseLesson">
                                已学：<span> 1天</span>
                            </div>
                            <div class="operationBtn">
                                <button type="button">继续学习</button>
                            </div>
                        </div>
                        <div class="courseItem col-md-4">
                            <div class="courseImg">
                                <img src="/imgs/course.png" alt="">
                            </div>
                            <div class="courseTitle">
                                <p>我的学习圈</p>
                            </div>
                            <div class="courseLesson">
                                已学：<span> 1天</span>
                            </div>
                            <div class="operationBtn">
                                <button type="button">继续学习</button>
                            </div>
                        </div>
                        <div class="courseItem col-md-4">
                            <div class="courseImg">
                                <img src="/imgs/course.png" alt="">
                            </div>
                            <div class="courseTitle">
                                <p>我的学习圈</p>
                            </div>
                            <div class="courseLesson">
                                已学：<span> 1天</span>
                            </div>
                            <div class="operationBtn">
                                <button type="button">继续学习</button>
                            </div>
                        </div>
                        <hr class="col-md-12">
                        <div class="courseItem col-md-4">
                            <div class="courseImg">
                                <img src="/imgs/course.png" alt="">
                            </div>
                            <div class="courseTitle">
                                <p>我的学习圈</p>
                            </div>
                            <div class="courseLesson">
                                已学：<span> 1天</span>
                            </div>
                            <div class="operationBtn">
                                <button type="button">继续学习</button>
                            </div>
                        </div>
                        <div class="courseItem col-md-4">
                            <div class="courseImg">
                                <img src="/imgs/course.png" alt="">
                            </div>
                            <div class="courseTitle">
                                <p>我的学习圈</p>
                            </div>
                            <div class="courseLesson">
                                已学：<span> 1天</span>
                            </div>
                            <div class="operationBtn">
                                <button type="button">继续学习</button>
                            </div>
                        </div>
                        <div class="courseItem col-md-4">
                            <div class="courseImg">
                                <img src="/imgs/course.png" alt="">
                            </div>
                            <div class="courseTitle">
                                <p>我的学习圈</p>
                            </div>
                            <div class="courseLesson">
                                已学：<span> 1天</span>
                            </div>
                            <div class="operationBtn">
                                <button type="button">继续学习</button>
                            </div>
                        </div>
                        <hr class="col-md-12">
                        <div class="courseItem col-md-4">
                            <div class="courseImg">
                                <img src="/imgs/course.png" alt="">
                            </div>
                            <div class="courseTitle">
                                <p>我的学习圈</p>
                            </div>
                            <div class="courseLesson">
                                已学：<span> 1天</span>
                            </div>
                            <div class="operationBtn">
                                <button type="button">继续学习</button>
                            </div>
                        </div>
                        <div class="courseItem col-md-4">
                            <div class="courseImg">
                                <img src="/imgs/course.png" alt="">
                            </div>
                            <div class="courseTitle">
                                <p>我的学习圈</p>
                            </div>
                            <div class="courseLesson">
                                已学：<span> 1天</span>
                            </div>
                            <div class="operationBtn">
                                <button type="button">继续学习</button>
                            </div>
                        </div>
                        <div class="courseItem col-md-4">
                            <div class="courseImg">
                                <img src="/imgs/course.png" alt="">
                            </div>
                            <div class="courseTitle">
                                <p>我的学习圈</p>
                            </div>
                            <div class="courseLesson">
                                已学：<span> 1天</span>
                            </div>
                            <div class="operationBtn">
                                <button type="button">继续学习</button>
                            </div>
                        </div>
                        <hr class="col-md-12">
                    </div>

                    <nav class="course_list" aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item"><a class="page-link" href="#"><i class="iconfont">&#xe9d2;</i></a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item active"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">4</a></li>
                            <li class="page-item"><a class="page-link" href="#">5</a></li>
                            <li class="page-item"><a class="page-link" href="#">6</a></li>
                            <li class="page-item"><a class="page-link" href="#"><i class="iconfont">&#xe632;</i></a>
                            </li>
                        </ul>
                    </nav>
                    {{--<p class="notDataP">暂无学习圈</p>--}}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection