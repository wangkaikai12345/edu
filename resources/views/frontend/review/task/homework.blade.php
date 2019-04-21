
<link rel="stylesheet" href="{{ mix('/css/front/classroom/homework/index.css') }}">
<link rel="stylesheet" href="{{ mix('/css/front/classroom/homework/modal.css') }}">
<link rel="stylesheet" href="{{ mix('/css/front/classroom/homework/submit-modal.css') }}">

<div class="xh">
    <div class="container">
        <div class="xh_content">
            <div class="xh_first_content">
                {{--<div class="number_content">1</div>--}}
                <div class="homework_title" id="homework" data-route="{{ renderTaskResultRoute('tasks.result.store',[$task], $member) }}">{{ $task->target->title }}</div>
            </div>
            <div class="xh_second_content">
                <div class="homework_item">
                    <div class="homework_item_dian"></div>
                    <div class="homework_item_title">
                        作业要求：
                    </div>
                    <div class="homework_item_desc">
                        {!! $task->target->about !!}
                    </div>
                </div>
                <div class="homework_item">
                    <div class="homework_item_dian"></div>
                    <div class="homework_item_title">
                        解题提示：
                    </div>
                    <div class="homework_item_desc">
                        {!! $task->target->hint !!}
                    </div>
                </div>
                <div class="homework_item">
                    <div class="homework_item_dian"></div>
                    <div class="homework_item_title">
                        预计时间：
                    </div>
                    <div class="homework_item_desc">
                        {{ $task->length/60 }} 分钟
                    </div>
                </div>
                @if ($task->target->package)
                    <div class="homework_item">
                        <div class="homework_item_dian"></div>
                        <div class="homework_item_title data_pack">
                            资料包：
                        </div>
                        <div class="homework_item_desc">
                            <a href="javascript:;" class="pack">
                                {{ render_cover($task->target->package,'') }}
                                <a href="{{ render_cover($task->target->package,'') }}"><i class="iconfont">&#xe626;</i></a>
                                {{-- 如果下载了资料图使用这个图片 --}}
                                {{--<i class="iconfont">&#xe61d;</i>--}}
                            </a>
                        </div>
                    </div>
                @endif

                @if ($task->target->video)
                    <div class="homework_item">
                        <div class="homework_item_dian"></div>
                        <div class="homework_item_title data_pack">
                            讲解视频：
                        </div>
                        <div class="homework_item_desc">
                            <video src="{{ render_cover($task->target->video, '') }}"
                                   controls="controls"
                            ></video>
                        </div>
                    </div>
                @endif

                <div class="homework_item">
                    <div class="homework_item_dian"></div>
                    <div class="homework_item_title">
                        批改标准：
                    </div>
                    @foreach($task->target->grades_content as $value)
                        <div class="homework_item_desc">
                            <span class="pack">{{ $loop->iteration }}. </span> {{ $value }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
//    window.onload = function () {
$(function(){
    $.ajax({
        url: $('#homework').data('route'),
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