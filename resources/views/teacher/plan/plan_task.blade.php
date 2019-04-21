@extends('teacher.plan.plan_layout')
@section('plan_style')
    <link rel="stylesheet" href="{{ mix('/css/front/article/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/plan/planTask.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/course/notice/index.css') }}">
    <link rel="stylesheet" href="/vendor/select2/dist/css/select2.min.css">
@endsection
@section('plan_content')
    <div class="col-xl-9 col-md-12 col-12 form_content p-0">
        <!-- Attach a new card -->
        <form class="form-default zh_course" onsubmit="return false">
            <div class="card teacher_style">
                <div class="card-body row_content" style="padding-top:10px;">
                    <div class="row_div" style="margin-bottom: 20px;">
                        <div class="row pr-4">
                            <div class="col-lg-8">
                                <h6 id="learn_mode" data-mode="{{ $plan->learn_mode }}">版本任务</h6>
                            </div>
                            <div class="col-lg-4 text-lg-right pt-2 pr-4">
                                <button type="button" class="btn btn-primary add-plan add-notice btn-task append_cut"
                                        data-chapter="{{ $plan->chap()->id }}"
                                        data-toggle="modal"
                                        data-target="#modal_5">+ 添加视频
                                </button>
                                {{--<button data-chapter="{{ $plan->chap()->id }}" type="button"--}}
                                        {{--class="btn btn-primary float-right btn-task append_cut "--}}
                                        {{--data-toggle="modal" data-target="#modal_5">--}}
                                    {{--任务--}}
                                {{--</button>--}}
                            </div>
                        </div>
                        <hr class="course_hr">
                    </div>
                    <div class="plan_wrap">
                        <span class="plan_num">
                            发布任务数量：{{ $plan->tasks_count }}
                        </span>
                        <div class="plan_lists" data-sort-url="{{ route('manage.chapters.sort', ['type' => 'chapter']) }}">
                            {{------------------------  循环展示章节  --------------------}}
                            @foreach($chapters->sortBy('seq') as $chapter)
                                <div class="jieduan" data-id="{{ $chapter->id }}">
                                    {{--<div class="jieduan_item active">--}}
                                        {{--第<span class="seq-num">{{ $chapter->seq }}</span>阶段：{{ $chapter->title }}--}}
                                        {{--<i class="iconfont float-right">--}}
                                            {{----}}
                                        {{--</i>--}}
                                        {{--<button type="button"--}}
                                                {{--class="btn btn-primary float-right btn-delete append-item chapter-delete"--}}
                                                {{--data-route="{{ route('manage.chapters.delete', [$plan, $chapter->id]) }}"--}}
                                        {{-->--}}
                                            {{--<i class="iconfont">&#xe62e;</i>--}}
                                            {{--删除--}}
                                        {{--</button>--}}
                                        {{--<button class="btn btn-primary float-right btn-edit"--}}
                                                {{--data-target="#modal"--}}
                                                {{--data-url="{{ route('manage.chapters.edit', [$plan, $chapter]) }}"--}}
                                        {{-->--}}
                                            {{--<i class="iconfont">&#xe60f;</i>--}}
                                            {{--编辑--}}
                                        {{--</button>--}}
                                        {{--<button type="button"--}}
                                                {{--class="btn btn-primary float-right btn-task chapter-open-btn-p show-add-task"--}}
                                                {{--data-toggle="modal"--}}{{----}}{{--  data-target="#modal_c2"--}}
                                                {{--data-pid="{{ $chapter->id }}">--}}
                                            {{--<i class="iconfont">&#xe6af;</i>--}}
                                            {{--添加关--}}
                                        {{--</button>--}}
                                    {{--</div>--}}
                                    {{--<div class="jieduan_wrap" data-sort-url="{{ route('manage.chapters.sort', ['type' => 'chapter']) }}">--}}
                                        {{------------------------  循环展示关  --------------------}}
                                        @foreach($chapter->children->sortBy('seq') as $pass)
                                            <div class="guan" data-id="{{ $pass->id }}">
                                                {{--<div class="guan_item active">--}}
                                                    {{--第<span class="seq-num">{{ $pass->seq }}</span>关：{{ $pass->title }}--}}
                                                    {{--<i class="iconfont float-right">--}}
                                                        {{----}}
                                                    {{--</i>--}}
                                                    {{--<button--}}
                                                            {{--class="btn btn-primary float-right btn-delete append-item chapter-delete"--}}
                                                            {{--data-route="{{ route('manage.chapters.delete', [$plan, $pass->id]) }}"--}}
                                                    {{-->--}}
                                                        {{--<i class="iconfont">&#xe62e;</i>--}}
                                                        {{--删除--}}
                                                    {{--</button>--}}
                                                    {{--<button class="btn btn-primary float-right btn-edit"--}}
                                                            {{--data-toggle="modal" data-target="#modal"--}}
                                                            {{--data-url="{{ route('manage.chapters.edit', [$plan, $pass]) }}"--}}
                                                    {{-->--}}
                                                        {{--<i class="iconfont">&#xe60f;</i>--}}
                                                        {{--编辑--}}
                                                    {{--</button>--}}
                                                    {{--<button data-chapter="{{ $pass->id }}" type="button"--}}
                                                            {{--class="btn btn-primary float-right btn-task append_cut "--}}
                                                            {{--data-toggle="modal" data-target="#modal_5">--}}
                                                        {{--<i class="iconfont">&#xe6af;</i>--}}
                                                        {{--任务--}}
                                                    {{--</button>--}}
                                                {{--</div>--}}
                                                <div class="guan_wrap">
                                                    <div class="jie" data-sort-url="{{ route('manage.chapters.sort', ['type' => 'task']) }}">
                                                    @foreach($pass->tasks->sortBy('seq') as $tas)
                                                        <div data-id="{{ $tas->id }}" class="jie_item">
                                                            <span class="seq-num">{{ $tas->seq }}</span>-{{ \App\Enums\TaskType::getDescription($tas->type) }}：{{ $tas->title }}
                                                            <span class="jie_status {{ $tas->status == 'published' ? 'text-success' : 'text-warning' }}">{{ \App\Enums\Status::getDescription($tas->status) }}</span>
                                                            <button type="button" class="btn btn-primary float-right btn-task append-item task-plush"
                                                                    data-toggle="modal"
                                                                    data-target="#modal_1"
                                                                    data-status="{{ $tas->status == 'published' ? 'closed' : 'published' }}"
                                                                    data-plush="{{ route('manage.tasks.publish', [$pass, $tas]) }}">
                                                                <i class="iconfont">&#xe643;</i>
                                                                {{ $tas->status == 'published' ? '取消发布':'发布' }}
                                                            </button>
                                                            @if ($tas->target_type == 'video')
                                                            <button type="button" class="btn btn-primary float-right">
                                                                <a href="{{ route('manage.task.video.question.create', [$course, $tas]) }}">
                                                                   ({{ $tas->notes()->count() }})
                                                                    查看评论
                                                                </a>
                                                            </button>
                                                            @endif
                                                            @if ($tas->status == 'published')
                                                                <button type="button" class="btn btn-primary float-right">
                                                                    <a href="{{ route('tasks.show', [$tas->chapter, 'task_id' =>$tas->id ]) }}" target="_blank">
                                                                        <i class="iconfont" style="font-weight: 800;">&#xe65b;</i>
                                                                        预览
                                                                    </a>
                                                                </button>
                                                            @endif

                                                            <button type="button"
                                                                    class="btn btn-primary float-right btn-delete append-item task-delete"
                                                                    data-route="{{ route('manage.tasks.delete', [$pass, $tas->id]) }}"
                                                            >
                                                                <i class="iconfont">&#xe62e;</i>
                                                                删除
                                                            </button>
                                                            {{--<button type="button" class="btn btn-primary float-right"--}}
                                                                    {{--data-toggle="modal" data-target="#modal" data-developer="zh_modal"--}}
                                                                    {{--data-url="{{ route('manage.tasks.edit', [$pass, $tas]) }}"--}}
                                                            {{-->--}}
                                                                {{--<i class="iconfont">&#xe60f;</i>--}}
                                                                {{--编辑--}}
                                                            {{--</button>--}}
                                                        </div>
                                                    @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{------------------------    添加阶段   ------------------------}}
    {{--<div class="modal fade bd-example-modal-lg" id="modal_c1" tabindex="-1" role="dialog" aria-hidden="true">--}}
        {{--<div class="modal-dialog modal-sm" role="document">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<h5 class="modal-title" id="exampleModalLabel">添加阶段</h5>--}}
                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                        {{--<span aria-hidden="true">&times;</span>--}}
                    {{--</button>--}}
                {{--</div>--}}
                {{--<div class="modal-body">--}}
                    {{--<div class="row mt-4 m-0 ml-8 input-content justify-content-center">--}}
                        {{--<div class="col-md-10 col-12  p-0 mb-4">--}}
                            {{--<div class="form-group">--}}
                                {{--<div class="input-group input-group-transparent">--}}
                                    {{--<label class="control-label col-md-2 col-lg-2 col-xl-2 text-center p-0 m-0">阶段标题</label>--}}
                                    {{--<input id="chapter-title" type="text" placeholder="请输入阶段的标题"--}}
                                           {{--class="form-control col-md-9 col-lg-9 col-xl-9 ml-2">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row m-0 ml-8 input-content justify-content-center">--}}
                        {{--<div class="col-md-10 col-12  p-0 mb-4">--}}
                            {{--<div class="form-group">--}}
                                {{--<div class="input-group input-group-transparent">--}}
                                    {{--<label class="control-label col-md-2 col-lg-2 col-xl-2 text-right p-0 m-0 mr-2">阶段目标</label>--}}
                                    {{--<textarea id="chapter-goals"--}}
                                              {{--style="margin-left: 8px;width: 75%;height: 100px;resize: none;"></textarea>--}}
                                    {{--<textarea type="text" id="chapter-goals" class="form-control col-md-12 col-lg-9 col-xl-9"></textarea>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="modal-footer">--}}
                    {{--<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>--}}
                    {{--<button type="button" class="btn btn-primary primary-btn" id="chapter-add-btn"--}}
                            {{--data-url="{{ route('manage.chapters.store', $plan) }}">确定--}}
                    {{--</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{------------------------    添加关   ------------------------}}
    {{--<div class="modal fade bd-example-modal-lg" id="modal_c2" tabindex="-1" role="dialog" aria-hidden="true">--}}
        {{--<div class="modal-dialog modal-sm" role="document">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<h5 class="modal-title" id="exampleModalLabel2">添加关</h5>--}}
                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                        {{--<span aria-hidden="true">&times;</span>--}}
                    {{--</button>--}}
                {{--</div>--}}
                {{--<div class="modal-body">--}}
                    {{--<div class="row mt-4 m-0 ml-8 input-content justify-content-center">--}}
                        {{--<div class="col-md-10 col-12  p-0 mb-4">--}}
                            {{--<div class="form-group">--}}
                                {{--<div class="input-group input-group-transparent">--}}
                                    {{--<label class="control-label col-md-2 col-lg-2 col-xl-2 text-center p-0 m-0">关的标题</label>--}}
                                    {{--<input id="chapter-title-p" type="text" placeholder="请输入关的标题"--}}
                                           {{--class="form-control col-md-9 col-lg-9 col-xl-9 ml-2">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row mt-4 m-0 ml-8 input-content justify-content-center">--}}
                        {{--<div class="col-md-10 col-12  p-0 mb-4">--}}
                            {{--<div class="form-group">--}}
                                {{--<div class="input-group input-group-transparent">--}}
                                    {{--<label class="control-label col-md-2 col-lg-2 col-xl-2 text-center p-0 m-0 mr-2">关的目标</label>--}}
                                    {{--<textarea type="text" id="chapter-goals-p" class="form-control col-md-12 col-lg-9 col-xl-9"></textarea>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="modal-footer">--}}
                    {{--<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>--}}
                    {{--<button type="button" class="btn btn-primary primary-btn" id="chapter-add-btn-p"--}}
                            {{--data-url="{{ route('manage.chapters.store', $plan) }}" data-pid="0">确定--}}
                    {{--</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--添加任务--}}
    <div class="modal modal-danger zh_modal active" id="modal_5" tabindex="-1" role="dialog" aria-labelledby="modal_5"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_title_6">添加任务</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="stepper">
                        <div class="stepper_item active" id="step-one">

                            <div class="item_circle">
                                <span>

                                </span>
                                <i class="iconfont">
                                    &#xe617;
                                </i>
                            </div>
                            <div class="item_title">

                            </div>
                        </div>
                        <div class="stepper_item active" id="step-two">
                            <div class="item_circle">
                                <span>

                                </span>
                                <i class="iconfont">
                                    &#xe617;
                                </i>
                            </div>
                            <div class="item_title">
                                设置内容
                            </div>
                        </div>
                        <div class="stepper_item" id="step-three">
                            <div class="item_circle">
                                <span>

                                </span>
                                <i class="iconfont">
                                    &#xe617;
                                </i>
                            </div>
                            <div class="item_title">

                            </div>
                        </div>
                    </div>
                    <div id="content-body" data-driver="{{ data_get(\Facades\App\Models\Setting::namespace('qiniu'), 'driver', 'local') }}">
                        {{--选择类型页面--}}
                        {{--@include('teacher.plan.task_controls')--}}

                        {{--图文--}}
                        {{--@include('teacher.plan.upload_text', ['editStatus' => ''])--}}
                        {{--视频--}}
                        @include('teacher.plan.upload_video', ['editStatus' => ''])
                        {{--音频--}}
                        {{--@include('teacher.plan.upload_audio', ['editStatus' => ''])--}}
                        {{--资料--}}
                        {{--@include('teacher.plan.upload_doc', ['editStatus' => ''])--}}
                        {{--ppt--}}
                        {{--@include('teacher.plan.upload_ppt', ['editStatus' => ''])--}}
                        {{--作业--}}
                        {{--@include('teacher.plan.upload_homework', ['editStatus' => ''])--}}
                        {{--考试--}}
                        {{--@include('teacher.plan.upload_exam', ['editStatus' => ''])--}}
                        {{--练习--}}
                        {{--@include('teacher.plan.upload_practice', ['editStatus' => ''])--}}
                        {{--下载--}}
                        {{--@include('teacher.plan.upload_zip', ['editStatus' => ''])--}}

                        {{--最后页面--}}
                        {{--@include('teacher.plan.last_step')--}}
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="now-chapter-id">
                    {{--<button type="button" class="btn btn-sm btn-outline-primary m-0" style="display:none" id="pre-step">--}}
                        {{--上一步--}}
                    {{--</button>--}}
                    {{--<button type="button" class="btn btn-sm btn-outline-primary m-0" style="display: inline" id="next-step" data-type="def"--}}
                            {{--data-step="1">下一步--}}
                    {{--</button>--}}
                    <button type="button" class="btn btn-sm btn-primary m-0" id="task-add-btn">
                        保存
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- 编辑模态框 --}}
    <div class="modal fade edit-modal" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" id="modal-content">
            </div>
        </div>
    </div>
@endsection
@section('plan_script')
    <script src="{{  '/vendor/jquery-ui/jquery-ui.min.js' }}"></script>
    <script src="/tools/qiniu/qiniu2.min.js"></script>
    <script src="/tools/sha1.js"></script>
    <script src="/tools/qetag.js"></script>
    <script src="/tools/qiniu/qiniu-luwnto.js"></script>
    <script src="/vendor/select2/dist/js/select2.min.js"></script>

    <script src="{{ mix('/js/upload/image-aetherupload.js') }}"></script>
    <script src="/js/front/manage/chapter.js"></script>
    <script>
        window.onload = function () {

            // 章节的删除
            // $('.chapter-delete').click(function(){
            //
            //     if (!confirm('您确定要执行删除操作')) {
            //         return false;
            //     }
            //
            //     // 执行删除
            //     $.ajax({
            //         url: $(this).data('route'),
            //         type: 'delete',
            //         success: function (res) {
            //
            //             if (res.status == 200) {
            //                 edu.alert('success', '删除成功!');
            //                 window.location.reload();
            //             } else {
            //                 edu.alert('danger', res.message);
            //             }
            //         }
            //     })
            //
            // })

            // 任务的删除
            $('.task-delete').click(function(){
                if (!confirm('您确定要执行删除操作')) {
                    return false;
                }

                // 执行删除
                $.ajax({
                    url: $(this).data('route'),
                    type: 'delete',
                    success: function (res) {

                        if (res.status == 200) {
                            edu.alert('success', '任务删除成功!');
                            window.location.reload();
                        } else {
                            edu.alert('danger', res.message);
                        }
                    }
                })
            })


            var upload_stepper = 0
                ,modalFlag = false;

            stepperChange(upload_stepper);

            function stepperChange(stepper) {
                var prevElm = $('#upload_prev')
                    , nextElm = $('#upload_next')
                    , saveElm = $('#upload_save')
                    , stepperElm = $('#stepper');
                switch (stepper) {
                    case 0:
                        prevElm.hide();
                        saveElm.hide();
                        nextElm.show();
                        break;
                    case 1:
                        prevElm.show();
                        nextElm.show();
                        saveElm.show();
                        break;
                    case 2:
                        nextElm.hide();
                        prevElm.show();
                        saveElm.show();
                        break;
                }
                stepperElm.find('.stepper_item').each(function (index, item) {
                    $(item).removeClass('pass active');
                    if (index < stepper) {
                        $(item).addClass('pass');
                    }
                    if (stepper === index) {
                        $(item).addClass('active');
                    }
                })
            }

            $(document).on('click', '#upload_prev', function () {
                upload_stepper--;
                if (upload_stepper < 0) {
                    upload_stepper = 0;
                }
                stepperChange(upload_stepper);
            });

            $(document).on('click', '#upload_next', function () {
                upload_stepper++;
                if (upload_stepper > 2) {
                    upload_stepper = 2;
                }
                stepperChange(upload_stepper);
            });

            $('.append-item').on({
                click: function (e) {
                    e.stopPropagation();
                }
            });

            // 编辑按钮事件
            $('.btn-edit').on({
                click: function (e) {
                    e.stopPropagation();
                    $('#edit_modal .modal-content').load($(this).data("url"));
                    $('#edit_modal').modal('toggle');
                }
            });

            /**
             * 弹出添加关的模态框
             */
            // $('.show-add-task').on({
            //     click: function (e) {
            //         $('#modal_c2').modal('toggle');
            //         return false;
            //     }
            // });

            /**
             * 弹出添加任务的模态框
             */
//             $('.append_cut').on({
//                 click: function (e) {
//                     var chapterId = $(this).data('chapter');
//
//                     // 为模态框设置任务和章节的id
//                     $('#now-chapter-id').val(chapterId);
//
//                     $('#next-step').data('type', 'video');
//                     $('#next-step').data('step', 2);
//
//                     showTwoPage('video');
//
//                     // 清空内容
// //                    $('.form-con').val('');
// //                    ue.setContent('');
//
//                     $('#modal_5').modal('toggle');
//                     return false;
//                 }
//             });
            $('.controls .control_item').on({
                click: function () {
                    $('.controls .control_item').removeClass('active');
                    $(this).addClass('active');
                }
            });
            $('[data-toggle="popover"]').popover();

            $('.jieduan_item').on({
                click: function () {
                    $(this).toggleClass('active').parents('.jieduan').find('.jieduan_wrap').slideToggle('slow');
                }
            });

            $('.guan_item').on({
                click: function (e) {
                    e.stopPropagation();
                    $(this).toggleClass('active').parents('.guan').find('.guan_wrap').slideToggle('slow');
                }
            });
            // https://www.cnblogs.com/neil120/p/6094618.html 所有事件参阅

            // 阶段的拖动排序
            $('.plan_lists').sortable({
                update: function (event, ui) {
                    var ids = [];
                    $(event.target).children().each(function (k, v) {
                        $(v).find('.seq-num').html(k+1);
                        ids.push($(this).data('id'))
                    });
                    var url = $(event.target).data('sortUrl');
                    sort(url, ids);
                }
            });

            // 关的拖动排序
            $('.jieduan_wrap').sortable({
                update: function (event, ui) {
                    var ids = [];
                    $(event.target).children().each(function (k, v) {
                        $(v).find('.seq-num').html(k+1);
                        ids.push($(this).data('id'))
                    });
                    var url = $(event.target).data('sortUrl');
                    sort(url, ids);
                }
            });

            // 任务的拖动排序
            $('.jie').sortable({
                update: function (event, ui) {
                    var ids = [];
                    $(event.target).children().each(function (k, v) {
                        $(v).find('.seq-num').html(k+1);
                        ids.push($(this).data('id'))
                    });
                    var url = $(event.target).data('sortUrl');
                    sort(url, ids);
                }
            });

            /**
             * 对阶段, 关, 任务进行排序
             */
            function sort(url, ids) {
                $.ajax({
                    url: url,
                    data: {ids: ids},
                    type: 'patch',
                    success: function (res) {
                        if (res.status == 200) {
                            edu.alert('success', '排序成功!');
//                            window.location.reload();
                        } else {
                            edu.alert('danger', res.message);
                        }
                    }
                });
            }

        }
    </script>
@endsection
