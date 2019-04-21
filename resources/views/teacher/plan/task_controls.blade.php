<div class="controls content-control">
    <div class="control_item task-type" data-type="text">
        <i class="iconfont">
            &#xe60c;
        </i>
        <span class="item_title">图文</span>
    </div>
    <div class="control_item task-type" data-type="video">
        <i class="iconfont">
            &#xe623;
        </i>
        <span class="item_title">视频</span>
    </div>
    <div class="control_item task-type" data-type="audio">
        <i class="iconfont">
            &#xe66a;
        </i>
        <span class="item_title">音频</span>
    </div>
    <div class="control_item task-type" data-type="doc">
        <i class="iconfont">
            &#xe63b;
        </i>
        <span class="item_title">文档</span>
    </div>
    <div class="control_item task-type" data-type="ppt">
        <i class="iconfont">
            &#xe721;
        </i>
        <span class="item_title">PPT</span>
    </div>

    <div class="control_item task-type" data-type="paper">
        <i class="iconfont">
            &#xe8b3;
        </i>
        <span class="item_title">试卷</span>
    </div>
    <div class="control_item task-type" data-type="homework">
        <i class="iconfont">
            &#xe60e;
        </i>
        <span class="item_title">作业</span>
    </div>
    <div class="control_item task-type" data-type="practice">
        <i class="iconfont">
            &#xe622;
        </i>
        <span class="item_title">练习</span>
    </div>
    <div class="control_item task-type" data-type="zip">
        <i class="iconfont">
            &#xe622;
        </i>
        <span class="item_title">下载</span>
    </div>
</div>
<div class="teach_methods content-control">
    <span>温馨提示：（学员的学习页面会依次按照下方教学类型，循序渐进，不同的教学类型请选择合适的教学形式）</span>
    <p>例子展示：（试卷 的教学形式 对应教学类型 测试和考试）</p>
</div>
<br>
<div class="teach_methods content-control">

    <span class="float-left">
        教学类型
    </span>
    @foreach(\App\Enums\TaskType::toSelectArray() as $typeValue => $typeName)
        <div class="custom-control custom-radio mb-3 float-left">
            <input type="radio" name="task-type" value="{{ $typeValue }}" class="custom-control-input task-type-sort" id="customRadio-{{ $typeValue }}">
            <label class="custom-control-label" for="customRadio-{{ $typeValue }}">{{ $typeName }}</label>
        </div>
    @endforeach

</div>

<script>
    var mode = $('#learn_mode').data('mode');

    var typeObj = {
        'text': [
            'b-introduce',
            'c-task',
            'f-extra'
        ],
        'video': [
            'b-introduce',
            'c-task',
            'f-extra'
        ],
        'audio': [
            'b-introduce',
            'c-task',
            'f-extra'
        ],
        'doc': [
            'b-introduce',
            'c-task',
            'f-extra'
        ],
        'ppt': [
            'b-introduce',
            'c-task',
            'f-extra'
        ],
        'paper': [
            'a-test',
            'e-exam',
        ],
        'homework': [
           'd-homework'
        ],
        'practice': [
            'c-task',
        ],
        'zip': [
            'c-task',
        ]
    }

    // 如果是解锁式学习
//    if (mode == 'lock') {
        // 任务资源的选择
        $('.task-type').click(function(){

            var type = $(this).data('type');

            $('.task-type-sort').attr('disabled', false).prop("checked", false);;

            $('.task-type-sort').each(function(index, item){

                if(typeObj[type].indexOf($(item).val()) < 0) {
                    $(item).attr('disabled', true);
                }
            })

        })
//    }

</script>
