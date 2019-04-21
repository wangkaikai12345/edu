<link rel="stylesheet" href="{{ mix('/css/front/task/video/index.css') }}">

<div id="dplayer" data-length="{{ $task->length }}"
     data-tanti="{{ videoQuestion($task->target->id) }}"
     data-url="{{ render_task_source($task->target['media_uri']) }}"
     {{--data-route="{{ route('tasks.result.store', $task['id']) }}" --}}
     data-route="{{ renderTaskResultRoute('tasks.result.store',[$task], $member) }}"
     {{--data-taskRoute="{{ route('tasks.result.video.paper', $task['id']) }}" --}}
     data-taskRoute="{{ renderTaskResultRoute('tasks.result.video.paper',[$task], $member) }}"
     data-taskId="{{ $task->id }}"  class="full_page"></div>

<script src=" {{ mix('/js/front/task/video/index.js') }} "></script>
