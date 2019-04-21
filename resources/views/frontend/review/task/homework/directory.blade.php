{{--  在slideNav 中绑定 data-toggle-slide-nav="directory" 使用 展示隐藏  --}}
<link rel="stylesheet" href="{{ mix('/css/front/classroom/directory/index.css') }}">

<div class="zh_directory" id="zh_directory">
    <div class="directory_wrap">
        <div class="directory_header">
            <div class="course_title">
                作业个数
                <span class="homework_num">{{ count($tasks[$task->type]) }}个</span>
            </div>
            <div class="course_progress">
                <div class="progress_now" style="width:{{ typeResult($chapter->id, $task->type, auth('web')->id()).'%' }}"></div>
                <div class="progress_now_yuan" data-toggle="tooltip" data-placement="top" title=""></div>
            </div>
        </div>
        <hr width="95%" class="division_xian">
        <div class="directory_content">
            <ul class="directory_list">
                @foreach($tasks[$task->type] as $value)
                    @if ($value->type == 'homework')
                        <li class="directory_item {{ $value['id'] == $task['id'] ? 'active' : '' }}">
                            <a href="{{ renderTaskRoute([$task->chapter, 'task' => $value->id], $member) }}">
                                <span class="homework_name">
                                    <span class="homework_sort">{{ $loop->iteration }}</span>
                                    {{ $value->title }}
                                </span>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
    <div class="phone_controls" id="phone_controls">
        <i class="iconfont _right">&#xe632;</i>
        <i class="iconfont _left">&#xe9d2;</i>
    </div>
</div>

<script src="{{ mix('/js/front/task/directory/index.js') }}"></script>
<script>
    $(document).ready(function () {
        let width = $('.progress_now').data('width');
        $('.progress_now_yuan').css({
            'left': width - 0.5+'%'
        });
    });
    $('.progress_now_yuan').on('mouseover', function() {
       let width = $(this).siblings('.progress_now').data('width');
        $(this).attr('data-original-title', width+'%');
    });
</script>