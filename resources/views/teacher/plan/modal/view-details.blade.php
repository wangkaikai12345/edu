<link rel="stylesheet" href="{{ mix('/css/teacher/plan/view-details.css') }}">

<div class="modal-header">
    <p>查看详情</p>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body" style="padding: 30px !important;">
    <div class="view_details_content">
        <div class="row option_item">
            <div class="col-md-3 p-0">
                <p class="item_p">
                    试卷名称
                </p>
            </div>
            <div class="col-md-9 p-0">
                <input class="form-control" type="text" value="{{ $paper->title }}" readonly>
            </div>
        </div>
        @php
            $ops = ['A', 'B', 'C', 'D', 'E', 'F'];
        @endphp
        @foreach($paper->paperQuestions as $pq)
        <div class="row option_item">
            <div class="col-md-3 p-0">
                <div class="item_t_v item_p">
                    题目标题
                </div>
            </div>
            <div class="col-md-8 p-0">
                <span class="question_title">{!! $pq->question->title !!}</span>
            </div>
            <div class="col-md-1 p-0">
                {{--<button class="del_question btn btn-primary">删除</button>--}}
            </div>
            <div class="col-md-3 p-0 mt-2">
                <p class="item_p">
                    分值
                </p>
            </div>
            <div class="col-md-9 p-0 mt-2">
                <input class="form-control" type="text" value="{{ $pq->score }}" readonly>
            </div>
            <div class="col-md-3 p-0 mt-2">
                <p class="item_p">
                    选项
                </p>
            </div>
            <div class="col-md-9 p-0 mt-2">
                <ul class="item_ul">
                    @foreach($pq->question->options as $key => $option)
                        <li>
                            {!! $ops[$key] . '.' .$option !!}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endforeach

    </div>
</div>
<div class="modal-footer">
    <div class="cancel_btn">
        <button data-dismiss="modal">取消</button>
    </div>
    <div class="determine_btn">
        <button>确定</button>
    </div>
</div>

<script type="text/javascript">
    $(document).on('click', '.del_question', function () {
        $(this).parent().parent().remove();
    });
</script>