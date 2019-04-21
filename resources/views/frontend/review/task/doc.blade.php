<link rel="stylesheet" href="{{ mix('/css/front/task/ppt/index.css') }}">

<div id="dplayer" class="ppt_wrap full_page" data-route="{{ renderTaskResultRoute('tasks.result.store',[$task], $member) }}">
    <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{ render_cover($task->target['media_uri'], '') }}"
            width='100%' height='100%' frameBorder='1'>
    </iframe>
</div>

<script>
//    window.onload = function () {
$(function(){
    $.ajax({
        url: $('#dplayer').data('route'),
        method: 'PUT',
        data: {
            time: 999
        },
        success: (res) => {
            var pro = $('#zh_directory .progress_now');

            pro && pro.css('width', res.data.status+'%');

            if(res && res.data && res.data.status == 100) {
                const aElm = $('#zh_directory .directory_footer a');
                aElm && aElm.removeClass('disabled').attr('disabled', false).attr('href', aElm.attr('data-href')).text('任务完成，开始作业');
            }
        }
    })
})

//    }
</script>