<link rel="stylesheet" href="{{ mix('/css/teacher/homework/scoreNormal-modal.css') }}">

<div class="modal-body">
    <div class="modal_head">
        <p>查看评语</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <form action="">
        <div class="modal_con">
            <div class="work_czh row">
                <div class="col-md-2 view_left">差评评语</div>
                <div class="col-md-10 view_right">
                    <div class="form-control view_r_text" style="background: #d5d5d5">
                        @foreach($grade->comment_bad as $c)
                            <p>{{ $c }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="work_czh row">
                <div class="col-md-2 view_left">中评评语</div>
                <div class="col-md-10 view_right">
                    <div class="form-control view_r_text" style="background: #d5d5d5">
                        @foreach($grade->comment_middle as $c)
                            <p>{{ $c }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="work_czh row">
                <div class="col-md-2 view_left">好评评语</div>
                <div class="col-md-10 view_right">
                    <div class="form-control view_r_text" style="background: #d5d5d5">
                        @foreach($grade->comment_good as $c)
                            <p>{{ $c }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
