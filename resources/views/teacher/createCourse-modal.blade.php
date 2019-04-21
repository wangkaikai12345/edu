<div class="modal modal-danger fade" id="createCourse" tabindex="-1" role="dialog" aria-labelledby="modal_5"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="color:#666;font-weight: 400;" id="modal_title_6">创建课程</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="create_modal_con">
                        <input type="hidden" id="default-url" value="{{ route('manage.courses.store') }}">
                        {{--<div class="form-group row">--}}
                            {{--<label for="inputEmail3" class="col-md-2 col-12 col-form-label text-center text-md-right">选择类型</label>--}}
                            {{--<div class="courseType col-md-10 col-12 row">--}}
                                {{--<input type="hidden" id="default-url" value="{{ route('manage.courses.store') }}">--}}
                                {{--<div class="col-4 p-0">--}}
                                    {{--<div class="typeItem active" data-url="{{ route('manage.courses.store') }}">--}}
                                        {{--<div class="typeIcon">--}}
                                            {{--<i class="iconfont">&#xe667;</i>--}}
                                        {{--</div>--}}
                                        {{--<p class="typeName">课程</p>--}}
                                        {{--<p class="typeDesc">支持视频、图文、PPT等各种形式，支持作业、笔记等多个学习工具的课程</p>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-4 p-0">--}}
                                    {{--<div class="typeItem unavailable" data-url="/">--}}
                                        {{--<div class="typeIcon">--}}
                                            {{--<i class="iconfont">&#xe6e6;</i>--}}
                                        {{--</div>--}}
                                        {{--<p class="typeName">班级</p>--}}
                                        {{--<p class="typeDesc">班级是一系列课程的组合，将一系列课程打包成为一个产品。</p>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-4 p-0">--}}
                                    {{--<div class="typeItem unavailable">--}}
                                        {{--<div class="typeIcon">--}}
                                            {{--<i class="iconfont">&#xe778;</i>--}}
                                        {{--</div>--}}
                                        {{--<p class="typeName">直播</p>--}}
                                        {{--<p class="typeDesc">以直播为教学形式的，支持多个学习工具的课程</p>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-md-2 col-12 col-form-label text-center text-md-right">项目标题</label>
                            <div class="col-md-10 col-12">
                                <input type="text" class="form-control" id="title" style="width: 98%;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" style="border-color:#d5d5d5;" class="btn btn-sm btn-default"
                            data-dismiss="modal">取消
                    </button>
                    <button type="button" class="btn btn-sm" id="create-btn">创建</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    window.onload = function () {
        /**
         * 点击创建类型, 设置不同的请求url
         */
        var createUrl = $('#default-url').val();
        $('.typeItem').click(function () {
            createUrl = $(this).data('url');
        });

        /**
         * 执行创建课程或班级
         */
        $('#create-btn').click(function () {

            var title = $('#title').val();

            if (!title) { edu.alert('danger', '请输入课程的标题'); return false;}

            $.ajax({
                'url': createUrl,
                'type': 'post',
                'data': {'title': title},
                'success': function (res) {
                    if (res.status == 200) {
                        edu.alert('success', res.message);
                        window.location.reload();
                    } else {
                        edu.alert('danger',res.message);
                    }
                }
            });
        });


    }
</script>