<link rel="stylesheet" href="">
<div class="add-student-message-modal-lg">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">作业详情</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="formal-student" role="tabpanel"
                 aria-labelledby="home-tab">
                <div class="table_content">
                    <table class="table">
                        <tr>
                            <th width="130">作业标题</th>
                            <td class="theme_color">
                                {{ $homeworkPost->title }}
                            </td>
                        </tr>
                        <tr>
                            <th width="130">所属课程</th>
                            <td class="theme_color">
                                {{ $homeworkPost->course->title }}
                            </td>
                        </tr>
                        <tr>
                            <th width="130">作业包</th>
                            <td class="theme_color">
                                {{ $homeworkPost->package }}
                            </td>
                        </tr>
                        <tr>
                            <th width="130">作业代码</th>
                            <td class="theme_color">
                                @if($homeworkPost->code)
                                    {{ $homeworkPost->code }}
                                @else
                                    无
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th width="130">作业图片</th>
                            <td class="theme_color">
                                @if($homeworkPost->post_img)
                                    @foreach($homeworkPost->post_img as $img)
                                        <img src="{{ render_cover($img, '') }}" alt="">
                                    @endforeach
                                @else
                                    无
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th width="130">作业简介</th>
                            <td class="theme_color">
                                @if($homeworkPost->description)
                                    {{ $homeworkPost->description }}
                                @else
                                    无
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th width="130">老师回复</th>
                            <td class="theme_color">
                                @if($homeworkPost->teacher_review)
                                    {{ $homeworkPost->teacher_review }}
                                @else
                                    无
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
    </div>

</div>
