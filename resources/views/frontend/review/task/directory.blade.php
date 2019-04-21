{{--  在slideNav 中绑定 data-toggle-slide-nav="directory" 使用 展示隐藏  --}}
<link rel="stylesheet" href="{{ mix('/css/front/task/directory/index.css') }}">

<div class="zh_directory" id="zh_directory">
    <div class="directory_wrap">
        <div class="directory_header">
            <div class="course_title">
               {{ $chapter->course->title }} / {{ $chapter->plan->title }}
            </div>
            <div class="course_progress">
                <div class="progress_now" style="width:{{ typeResult($chapter->id, $task->type, auth('web')->id()).'%' }}"></div>
            </div>
            <div class="down_controls">
                <i class="iconfont">&#xe604;</i>
            </div>
        </div>
        <div class="directory_content">
            <ul class="directory_list">

                @if($chapter->plan->learn_mode == 'free')
                    @if (!empty($tasks['c-task']))
                        @foreach($tasks['c-task'] as $k => $value)
                            {{--<li class="list-group-item font-small pl-0 border-top-0 {{ $value['id'] == $task['id'] ? 'video-active' : '' }}">--}}
                            {{--<i class="fas  text-primary mr-3"></i>--}}
                            {{--<a href="{{ route('tasks.show', $value) }}">--}}
                            {{--{{ $k+1 }}-{{ render_task_type($value['type']) }}: {{ $value['title'] }}--}}
                            {{--</a>--}}
                            {{--<span class="time">{{ ($value['target_type'] == 'video' || $value['target_type'] == 'audio') ?  gmdate('H:i:s', $value['length']): '' }}</span>--}}
                            {{--</li>--}}
                            <li class="directory_item {{ $value['id'] == $task['id'] ? 'active' : '' }} {{ (!mobile_view($value->target_type) && is_show_mobile_page()) ?  'mobile_warning' : ''}}">
                                <a
                                        href="javascript:;"
                                        data-route="{{ !mobile_view($value->target_type) && is_show_mobile_page() ? '' : renderTaskRoute([$task->chapter, 'task' => $value->id], $member) }}"
                                        class="target_type"
                                        data-type="{{ $value->target_type }}"
                                >
                                    @switch($value->target_type)
                                        @case('video')
                                        <i class="iconfont">&#xe61c;</i>
                                        @break
                                        @case('audio')
                                        <i class="iconfont">&#xe66a;</i>
                                        @break
                                        @case('text')
                                        <i class="iconfont">&#xe60c;</i>
                                        @break
                                        @case('doc')
                                        <i class="iconfont">&#xe63b;</i>
                                        @break
                                        @case('ppt')
                                        <i class="iconfont">&#xe65a;</i>
                                        @break

                                        @case('homework')
                                        <i class="iconfont">&#xe9d0;</i>
                                        @break

                                        @case('paper')
                                        <i class="iconfont">&#xe61e;</i>
                                        @break

                                        @case('zip')
                                        <i class="iconfont">&#xe65a;</i>
                                        @break
                                        @default

                                    @endswitch
                                    <span class="course_name">课时{{ $k+1 }}：{{ $value['title'] }}</span>
                                    <div class="course_icon {{ $value->currentResult(auth('web')->id()) == 'finish' ? 'active' :''}}">
                                        <div class="active_content"></div>
                                    </div>
                                    {{ (!mobile_view($value->target_type) && is_show_mobile_page()) ?  '移动端无法观看' : ''}}

                                </a>
                            </li>
                        @endforeach
                    @endif
                @else
                    @if (!empty($tasks[$task->type]))
                        @foreach($tasks[$task->type] as $k => $value)
                            {{--<li class="list-group-item font-small pl-0 border-top-0 {{ $value['id'] == $task['id'] ? 'video-active' : '' }}">--}}
                            {{--<i class="fas  text-primary mr-3"></i>--}}
                            {{--<a href="{{ route('tasks.show', $value) }}">--}}
                            {{--{{ $k+1 }}-{{ render_task_type($value['type']) }}: {{ $value['title'] }}--}}
                            {{--</a>--}}

                            {{--<span class="time">{{ ($value['target_type'] == 'video' || $value['target_type'] == 'audio') ?  gmdate('H:i:s', $value['length']): '' }}</span>--}}
                            {{--</li>--}}
                            <li class="directory_item {{ $value['id'] == $task['id'] ? 'active' : '' }}">
                                <a
                                        href="javascript:;"
                                        data-route="{{ !mobile_view($value->target_type) && is_show_mobile_page() ? '' : renderTaskRoute([$task->chapter, 'task' => $value->id], $member) }}"
                                        class="target_type"
                                        data-type="{{ $value->target_type }}"
                                >
                                    @switch($value->target_type)
                                        @case('video')
                                        <i class="iconfont">&#xe61c;</i>
                                        @break
                                        @case('audio')
                                        <i class="iconfont">&#xe66a;</i>
                                        @break
                                        @case('text')
                                        <i class="iconfont">&#xe60c;</i>
                                        @break
                                        @case('doc')
                                        <i class="iconfont">&#xe63b;</i>
                                        @break
                                        @case('ppt')
                                        <i class="iconfont">&#xe65a;</i>
                                        @break

                                        @case('homework')
                                        <i class="iconfont">&#xe9d0;</i>
                                        @break

                                        @case('paper')
                                        <i class="iconfont">&#xe61e;</i>
                                        @break

                                        @case('zip')
                                        <i class="iconfont">&#xe65a;</i>
                                        @break
                                        @default

                                    @endswitch
                                    <span class="course_name">课时{{ $k+1 }}：{{ $value['title'] }}</span>
                                    <div class="course_icon {{ $value->currentResult(auth('web')->id()) == 'finish' ? 'active' :''}}">
                                        <div class="active_content"></div>
                                    </div>
                                    {{ (!mobile_view($value->target_type) && is_show_mobile_page()) ?  '移动端无法观看' : ''}}

                                </a>
                            </li>
                        @endforeach
                    @endif

                @endif

            </ul>
        </div>

        @if($chapter->plan->learn_mode == 'lock' && $task->type == 'c-task')
        <div class="directory_footer">
            <i class="iconfont test">
                &#xe61e;
            </i>
            <span>
            今日作业
        </span>
            <a href="{{  typeResult($chapter->id, \App\Enums\TaskType::TASK, auth('web')->id()) == 100  ? renderTaskRoute([$chapter, 'type' => 'd-homework'], $member): 'javascript:;' }}"

               {{ typeResult($chapter->id, \App\Enums\TaskType::TASK, auth('web')->id()) == 100 ? '' : 'disabled' }}
               data-href="{{ renderTaskRoute([$chapter, 'type' => 'd-homework'], $member) }}" class="btn btn-sm btn-circle float-right {{ typeResult($chapter->id, \App\Enums\TaskType::TASK, auth('web')->id()) == 100 ? '' : 'disabled' }}">

                {{ typeResult($chapter->id, \App\Enums\TaskType::TASK, auth('web')->id()) == 100 ? '任务完成，开始作业' : '完成任务，开启作业' }}
            </a>
            <div class="up_controls">
                <i class="iconfont">&#xe627;</i>
            </div>
        </div>
        @endif
    </div>
    <div class="phone_controls" id="phone_controls">
        <i class="iconfont _right">&#xe632;</i>
        <i class="iconfont _left">&#xe9d2;</i>
    </div>
</div>

<script src="{{ mix('/js/front/task/directory/index.js') }}"></script>