<div class="answer-top" style="overflow: hidden;">
    <a href="javascript:;" id="question-back"><i class="fas fa-chevron-left mr-2 ml-3"></i>返回</a>
    <h6 class="mt-4 ml-2">
        <span class="cd_tag">问</span>
        {{ $topic['title'] }}
    </h6>
    <div class="ml-2 mt-3 font-small">
        由 <a href="javscript:;" class="ml-2 mr-2">{{ $topic->user['username'] }}</a> •&nbsp; 提问
    </div>
    <div class="font-small mt-2 ml-2 answer-content">
        {{ $topic['content'] }}
    </div>
    <div class="form-group shadow-textarea mt-2">
                                <textarea class="form-control z-depth-1 font-small" id="answer" rows="8"
                                          placeholder="我有更好的答案"></textarea>
    </div>
    <button class="btn btn-primary btn-sm float-right ml-0" id="add_reply" data-route="{{ route('tasks.reply.store', [$topic->task, $topic]) }}">我要回答</button>
</div>
<div class="answer-list">
    <h6 class="mt-4 ml-2">
        <span class="cd_tag" style="background: rgb(67, 188, 96);">问</span>
        全部的回答 {{ count($topic->replies) }}
    </h6>
    <div class="question-list mt-3">
        <ul class="list-group list-group-flush pl-2 pr-1" id="reply_list">
           @if (count($topic->replies))

               @foreach($topic->replies as $reply)
                    <li class="list-group-item pl-0 pt-0">
                        <div class="medio_box font-small">
                            <div class="medix_item">
                                <div class="mbma">
                                    由
                                    <a href="javascript:;" class="ml-2">
                                        {{ $reply->user['username'] }}</a>回答
                                </div>
                                <div class="metas mt-2">
                                    {{ $reply['content'] }}
                                </div>
                            </div>
                        </div>
                    </li>
                   @endforeach
                   @foreach($topic->replies as $reply)
                       <li class="list-group-item pl-0 pt-0">
                           <div class="medio_box font-small">
                               <div class="medix_item">
                                   <div class="mbma">
                                       由
                                       <a href="javascript:;" class="ml-2">
                                           {{ $reply->user['username'] }}</a>回答
                                   </div>
                                   <div class="metas mt-2">
                                       {{ $reply['content'] }}
                                   </div>
                               </div>
                           </div>
                       </li>
                   @endforeach
               @endif

        </ul>
    </div>
</div>

<script>
    // 返回
    $('#question-back').on({
        click: function () {
            $('.answer').removeClass('active');
            $('.question-list-wrap').addClass('active');
        }
    });

    // 添加答案
    $('#add_reply').click(function(){

        var answer = $('#answer').val();
        if (!answer) { edu.toastr.error('请完善您的答案'); return false;}

        edu.ajax({
            url: $(this).data('route'),
            method: 'post',
            data: {
                'content':answer,
            },
            callback:function(res){

                if (res.status == 'success') {

                    // 清空表单
                    $('#answer').val('');

                    // 添加元素
                    $('#reply_list').prepend(
                        `<li class="list-group-item pl-0 pt-0">
                        <div class="medio_box font-small">
                            <div class="medix_item">
                                <div class="mbma">
                                    由
                                    <a href="javascript:;" class="ml-2">
                                        我</a>回答
                                </div>
                                <div class="metas mt-2">
                                    ${res.data.content}
                            </div>
                        </div>
                    </div>
                </li>`
                    );
                }
            },
            elm: '#add_reply'
        })
    })

</script>