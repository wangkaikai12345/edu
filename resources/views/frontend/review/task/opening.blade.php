
    <link rel="stylesheet" href="{{ mix('/css/front/task/opening/index.css') }}">

    <div class="xh">
        {{--<div class="row" id="opening" data-route="{{ route('tasks.result.store', ) }}">--}}
        <div class="row" id="opening" data-route="{{ renderTaskResultRoute('tasks.result.store',[$task], $member) }}">
            <div class="col-sm-12 col-md-12 col-xl-9 desc-content pr-0">
                <div class="col-sm-11 opening-left-item active">
                    {!! $task->target->body !!}
                </div>

            </div>
            <div class="col-sm-12 col-md-12 col-xl-3 right-content p-0">
                {{--<div class="dividing_line"></div>--}}
                <div class="col-sm-10 opening-right-item">
                    <div class="left-tips">
                        <div class="tips-font">建议学习时间</div>
                        <div class="tips-time">{{ floor($chapter->tasks->sum('length')/60) }} 分钟</div>
                    </div>
                    <div class="left-tips">
                        <div class="tips-font">建议作业时间</div>
                        <div class="tips-time">{{ $chapter->tasks->where('type', 'd-homework')->sum('length')/60 }} 分钟</div>
                    </div>
                    <div class="left-tips">
                        <div class="tips-font">任务个数</div>
                        <div class="tips-time">{{ $chapter->tasks->where('type', 'c-task')->count() }}个</div>
                    </div>
                    {{--<div class="left-tips">--}}
                        {{--<div class="tips-font">下一步</div>--}}
                        {{--<div class="tips-time">--}}
                            {{--<a href="{{ route('tasks.show', [$chapter, 'type' => 'c-task']) }}">学习任务</a>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                </div>
                <a href="{{ renderTaskRoute([$chapter, 'type' => 'c-task'], $member) }}" class="btn btn-primary next">下一步</a>
            </div>
        </div>
    </div>

<script src="{{ mix('/js/front/task/opening/index.js') }}"></script>
    <script>
//        window.onload = function () {
$(function(){
    $.ajax({
        url: $('#opening').data('route'),
        method: 'PUT',
        data: {
            time: 999
        },
        success: (res) => {
            if(res && res.data && res.data.status) {
                const aElm = $('#zh_directory .directory_footer a');
                aElm && aElm.removeClass('disabled').attr('disabled', false).attr('href', aElm.attr('data-href')).text('任务完成，开始作业');
            }
        }
    })
})

//        }
    </script>
