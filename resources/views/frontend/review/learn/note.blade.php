@extends('frontend.review.layouts.app')
@section('title')
    我的学习-我的笔记
@endsection
@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/learn/note.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/learn/note-modal.css') }}">
@endsection
@section('content')
    @include('frontend.review.learn._note-modal')
    <div class="container">
        <div class="row padding-content">
            @include('frontend.review.learn.navBar')
            <div class="czh_learn_note col-xl-9">
                <div class="course_head">
                    <p>我的笔记</p>
                </div>
                <div class="course_content">
                    <div class="courseCollect row">

                        @if(count($notes))
                            @foreach($notes as $k => $note)
                                <div class="col-md-4">
                                    <div class="courseItem col-md-12">
                                        <div class="courseImg">
                                            <img src="{{ render_cover($note['course']['cover'], 'course') }}" alt="">
                                        </div>
                                        <div class="courseTitle">
                                            <p>{{ $note['course']['title'].'-'.$note['plan']['title'] }}</p>
                                        </div>
                                        <div class="courseNum">
                                            共{{ $note['course']['notes_count'] }}
                                            篇笔记&nbsp;&nbsp;最后更新于<span>{{ $note['updated_at'] }}</span>
                                        </div>
                                        <div class="operationBtn">
                                            <button type="button" data-toggle="modal" data-target="#noteModal-{{ $k }}">
                                                查看笔记
                                            </button>
                                        </div>

                                        {{--查看的模态框--}}
                                        <div class="modal modal-fluid fade" id="noteModal-{{ $k }}" tabindex="-1"
                                             role="dialog" aria-labelledby="modal_1"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="details-modal-body">
                                                        <div class="modal_head_title row">
                                                            <p class="col-md-10">查看笔记</p>
                                                            <div class="col-md-2">
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>

                                                        </div>
                                                        <div class="modal_con_data">
                                                            <div class="note_data">
                                                                <div class="note_img">
                                                                    <img src="{{ render_cover($note['course']['cover'], 'course') }}"
                                                                         alt="">
                                                                </div>
                                                                <div class="note_title_and_del">
                                                                    <p class="note_title">{{ $note['course']['title'].'-'.$note['plan']['title'] }}</p>
                                                                    <p class="note_status">
                                                                        最后更新于{{ $note['updated_at'] }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="task_and_note">
                                                                <div class="taskNum">
                                                                    <b>任务:</b><span> {{ $note['task']['title'] }} </span>
                                                                </div>
                                                                <div class="noteCon">
                                                                    <p class="noteContent">
                                                                        {!! $note['content'] !!}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="s_a_z">
                                                                <button class="btn btn-link s_a_z">展开</button>
                                                            </div>
                                                            <div class="identifyAndCancel">
                                                                <button type="button" data-dismiss="modal">关闭</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        @else
                            <p class="notDataP">暂无数据</p>
                        @endif


                        {{--<div class="courseItem col-md-4">--}}
                        {{--<div class="courseImg">--}}
                        {{--<img src="/imgs/course.png" alt="">--}}
                        {{--</div>--}}
                        {{--<div class="courseTitle">--}}
                        {{--<p>我的笔记</p>--}}
                        {{--</div>--}}
                        {{--<div class="courseNum">--}}
                        {{--共一篇笔记&nbsp;&nbsp;最后更新于<span>09-21</span>--}}
                        {{--</div>--}}
                        {{--<div class="operationBtn">--}}
                        {{--<button type="button" data-toggle="modal" data-target="#noteModal">查看笔记</button>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="courseItem col-md-4">--}}
                        {{--<div class="courseImg">--}}
                        {{--<img src="/imgs/course.png" alt="">--}}
                        {{--</div>--}}
                        {{--<div class="courseTitle">--}}
                        {{--<p>我的笔记</p>--}}
                        {{--</div>--}}
                        {{--<div class="courseNum">--}}
                        {{--共一篇笔记&nbsp;&nbsp;最后更新于<span>09-21</span>--}}
                        {{--</div>--}}
                        {{--<div class="operationBtn">--}}
                        {{--<button type="button" data-toggle="modal" data-target="#noteModal">查看笔记</button>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--<hr class="col-md-12">--}}
                        {{--<div class="courseItem col-md-4">--}}
                        {{--<div class="courseImg">--}}
                        {{--<img src="/imgs/course.png" alt="">--}}
                        {{--</div>--}}
                        {{--<div class="courseTitle">--}}
                        {{--<p>我的笔记</p>--}}
                        {{--</div>--}}
                        {{--<div class="courseNum">--}}
                        {{--共一篇笔记&nbsp;&nbsp;最后更新于<span>09-21</span>--}}
                        {{--</div>--}}
                        {{--<div class="operationBtn">--}}
                        {{--<button type="button" data-toggle="modal" data-target="#noteModal">查看笔记</button>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="courseItem col-md-4">--}}
                        {{--<div class="courseImg">--}}
                        {{--<img src="/imgs/course.png" alt="">--}}
                        {{--</div>--}}
                        {{--<div class="courseTitle">--}}
                        {{--<p>我的笔记</p>--}}
                        {{--</div>--}}
                        {{--<div class="courseNum">--}}
                        {{--共一篇笔记&nbsp;&nbsp;最后更新于<span>09-21</span>--}}
                        {{--</div>--}}
                        {{--<div class="operationBtn">--}}
                        {{--<button type="button" data-toggle="modal" data-target="#noteModal">查看笔记</button>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="courseItem col-md-4">--}}
                        {{--<div class="courseImg">--}}
                        {{--<img src="/imgs/course.png" alt="">--}}
                        {{--</div>--}}
                        {{--<div class="courseTitle">--}}
                        {{--<p>我的笔记</p>--}}
                        {{--</div>--}}
                        {{--<div class="courseNum">--}}
                        {{--共一篇笔记&nbsp;&nbsp;最后更新于<span>09-21</span>--}}
                        {{--</div>--}}
                        {{--<div class="operationBtn">--}}
                        {{--<button type="button" data-toggle="modal" data-target="#noteModal">查看笔记</button>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--<hr class="col-md-12">--}}
                        {{--<div class="courseItem col-md-4">--}}
                        {{--<div class="courseImg">--}}
                        {{--<img src="/imgs/course.png" alt="">--}}
                        {{--</div>--}}
                        {{--<div class="courseTitle">--}}
                        {{--<p>我的笔记</p>--}}
                        {{--</div>--}}
                        {{--<div class="courseNum">--}}
                        {{--共一篇笔记&nbsp;&nbsp;最后更新于<span>09-21</span>--}}
                        {{--</div>--}}
                        {{--<div class="operationBtn">--}}
                        {{--<button type="button" data-toggle="modal" data-target="#noteModal">查看笔记</button>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="courseItem col-md-4">--}}
                        {{--<div class="courseImg">--}}
                        {{--<img src="/imgs/course.png" alt="">--}}
                        {{--</div>--}}
                        {{--<div class="courseTitle">--}}
                        {{--<p>我的笔记</p>--}}
                        {{--</div>--}}
                        {{--<div class="courseNum">--}}
                        {{--共一篇笔记&nbsp;&nbsp;最后更新于<span>09-21</span>--}}
                        {{--</div>--}}
                        {{--<div class="operationBtn">--}}
                        {{--<button type="button" data-toggle="modal" data-target="#noteModal">查看笔记</button>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="courseItem col-md-4">--}}
                        {{--<div class="courseImg">--}}
                        {{--<img src="/imgs/course.png" alt="">--}}
                        {{--</div>--}}
                        {{--<div class="courseTitle">--}}
                        {{--<p>我的笔记</p>--}}
                        {{--</div>--}}
                        {{--<div class="courseNum">--}}
                        {{--共一篇笔记&nbsp;&nbsp;最后更新于<span>09-21</span>--}}
                        {{--</div>--}}
                        {{--<div class="operationBtn">--}}
                        {{--<button type="button" data-toggle="modal" data-target="#noteModal">查看笔记</button>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--<hr class="col-md-12">--}}
                    </div>

                    <nav class="course_list" aria-label="Page navigation example">
                        {!! $notes->render() !!}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).on('click', '.s_a_z', function () {
            var btnVal = $(this).html();
            var noteDom = $(this).parent().prev().children('.noteCon');
            if (btnVal === "展开") {
                noteDom.css('max-height', '1000px');
                $(this).text('收起');
            } else if (btnVal == "收起") {
                noteDom.css('max-height', '36px');
                $(this).text('展开');
            }
        })
    </script>
@endsection