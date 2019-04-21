{{--@extends('frontend.review.classroom.layout')--}}
{{--@extends('frontend.review.task.layout')--}}
{{--@section('style')--}}
<link rel="stylesheet" href="{{ mix('/css/front/classroom/homework/index.css') }}">
<link rel="stylesheet" href="{{ mix('/css/front/classroom/homework/modal.css') }}">
<link rel="stylesheet" href="{{ mix('/css/front/classroom/homework/submit-modal.css') }}">
{{--@endsection--}}
{{--@section('content')--}}
<div class="xh">
    <div class="container">
        <div class="xh_content">
            <div class="xh_first_content">
                {{--<div class="number_content">1</div>--}}
                <div class="homework_title">{{ $task->title }}</div>
            </div>
            <div class="xh_second_content">
                <div class="homework_item">
                    <div class="homework_item_dian"></div>
                    <div class="homework_item_title">
                        学习资料包
                    </div>
                    <div class="homework_item_desc">
                        下载资料，帮助学习
                    </div>
                </div>

                    <div class="homework_item">
                        <div class="homework_item_dian"></div>
                        <div class="homework_item_title data_pack">
                            资料包：
                        </div>
                        <div class="homework_item_desc">
                            <a href="javascript:;" class="pack">
                                {{ render_cover($task->target->media_uri,'') }}
                                <i class="iconfont">&#xe626;</i>
                                {{-- 如果下载了资料图使用这个图片 --}}
                                {{--<i class="iconfont">&#xe61d;</i>--}}
                            </a>
                        </div>
                    </div>
            </div>
            <div class="right_operation">

                <div class="submit_btn_content">
                    <a class="submit_button btn btn-primary"
                       href="{{ render_cover($task->target->media_uri,'') }}"
                       id="zip" data-route="{{ renderTaskResultRoute('tasks.result.store',[$task], $member) }}">下载</a>
                </div>


            </div>
        </div>
    </div>
</div>
{{--@endsection--}}

<script>
//    window.onload = function () {
$(function(){
    $.ajax({
        url: $('#zip').data('route'),
        method: 'PUT',
        data: {
            time: 999
        },
        success: (res) => {

            var pro = $('#zh_directory .progress_now');

            pro && pro.css('width', res.data.status+'%');

            if(res && res.data && res.data.status == 100 ) {
                const aElm = $('#zh_directory .directory_footer a');
                aElm && aElm.removeClass('disabled').attr('disabled', false).attr('href', aElm.attr('data-href')).text('任务完成，开始作业');
            }
        }
    })
})

//    }
</script>