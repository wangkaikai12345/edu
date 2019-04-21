<link rel="stylesheet" href="{{ mix('/css/teacher/homework/preview-modal.css') }}">

<div class="modal-body">
    <div class="modal_head">
        <p>{{ $homework->title }}</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal_con">
        <div class="que_desc">
            <p>问题描述</p>
            {!! $homework->about !!}
        </div>
        <div class="que_desc">
            <p>解答提示</p>
            {!! $homework->hint !!}
        </div>
        <div class="que_desc">
            <p>批改标准</p>
            <ol class="que_desc_ol">
                @foreach($homework->grades_content as $i)
                    <li>{{ $i }}</li>
                @endforeach
            </ol>
        </div>

        <div class="que_desc">
            <p>讲解视频</p>
            <div class="que_desc_div">
                <video src="{{ $homework->video }}" controls="controls">
                    您的浏览器不支持 video 标签。
                </video>
            </div>
        </div>

        <div class="que_desc">
            <p>资料包</p>
            <div class="que_desc_div">
                <a href="{{ $homework->package }}" target="_blank" download="">下载资料包</a>
            </div>
        </div>

    </div>
</div>
