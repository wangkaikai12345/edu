<link rel="stylesheet" href="{{ mix('/css/teacher/homework/scoreNormal-modal.css') }}">
<div class="modal modal-fluid fade" id="scoreNormal_modal" tabindex="-1" role="dialog" aria-labelledby="modal_1"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal_head">
                    <p>添加批改标准</p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="grade-create-form" action="{{ route('manage.homework.grade.add') }}" method="post">
                    <div class="modal_con">
                        <div class="work_czh row">
                            <div class="col-md-2 view_left">标题</div>
                            <div class="col-md-10 view_right">
                                <input class="view_r_input form-control" name="title" type="text">
                            </div>
                        </div>
                        <div class="work_czh row">
                            <div class="col-md-2 view_left">差评评语</div>
                            <div class="col-md-10 view_right">
                                <textarea class="form-control view_r_text" name="comment_bad" id="" cols="30" rows="10"
                                          placeholder="多条评语请以回车分开，评语前面不要有编号，以便智能生成综合评语"></textarea>
                            </div>
                        </div>
                        <div class="work_czh row">
                            <div class="col-md-2 view_left">中评评语</div>
                            <div class="col-md-10 view_right">
                                <textarea class="form-control view_r_text" name="comment_middle" id="" cols="30" rows="10"
                                          placeholder="多条评语请以回车分开，评语前面不要有编号，以便智能生成综合评语"></textarea>
                            </div>
                        </div>
                        <div class="work_czh row">
                            <div class="col-md-2 view_left">好评评语</div>
                            <div class="col-md-10 view_right">
                                <textarea class="form-control view_r_text" name="comment_good" id="" cols="30" rows="10"
                                          placeholder="多条评语请以回车分开，评语前面不要有编号，以便智能生成综合评语"></textarea>
                            </div>
                        </div>

                        <div class="work_czh row">
                            <div class="col-md-2 view_left">备注</div>
                            <div class="col-md-10 view_right">
                                <textarea class="form-control view_r_text_remark" name="remarks" id="" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="model_foot">
                        <button class="btn btn-primary post_btn" type="submit">提交</button>
                        <button class="btn btn-default cancel_btn" type="button" data-dismiss="modal">取消</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
