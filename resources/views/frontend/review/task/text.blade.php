<link rel="stylesheet" href="{{ mix('/css/front/task/text/index.css') }}">

<div id="text" class="full_page" data-route="{{ renderTaskResultRoute('tasks.result.store',[$task], $member) }}">
    <div class="text_img">
    {!! $task->target->body !!}
    </div>
</div>

<script>
//    window.onload = function () {

$(function(){
    $.ajax({
        url: $('#text').data('route'),
        method: 'PUT',
        data: {
            time: 999
        },
        success: (res) => {
            var pro = $('#zh_directory .progress_now');

            pro && pro.css('width', res.data.status+'%');

            if(res && res.data && res.data.status ==100) {
                const aElm = $('#zh_directory .directory_footer a');
                aElm && aElm.removeClass('disabled').attr('disabled', false).attr('href', aElm.attr('data-href')).text('任务完成，开始作业');
            }
        }
    })
})

//    }
</script>