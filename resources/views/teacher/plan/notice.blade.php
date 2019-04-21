<!----------------------- 公告管理页 ------------------------>
<link rel="stylesheet" href="{{ mix('/css/front/course/notice/index.css') }}">
<style>
    #notices-list img {
        width: 150px;
    }
</style>
<div class="col-xl-9 col-md-12 col-12 form_content p-0">
    <!-- Attach a new card -->

    <form class="form-default">
        <div class="card teacher_style">
            <div class="card-body row_content" style="padding-top:10px;">
                <div class="row_div">
                    <div class="row pr-4">
                        <div class="col-lg-8">
                            <h6>历史公告</h6>
                        </div>
                        <div class="col-lg-4 text-lg-right pt-2 pr-4">
                            <button type="button" class="btn btn-primary add-notice" data-toggle="modal"
                                    data-target="#modal" data-url="{{ route('manage.notices.create', $plan) }}">+ 添加公告
                            </button>
                        </div>
                    </div>
                    <hr class="course_hr">
                </div>
                <div class="bd-example">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col" width="20%">公告内容</th>
                            <th scope="col">发布时间</th>
                            <th scope="col">结束时间</th>
                            <th scope="col">状态</th>
                            <th scope="col">发布人</th>
                            <th scope="col">操作</th>
                        </tr>
                        </thead>
                        <tbody id="notices-list">
                        @forelse($notices as $notice)
                            <tr>
                                <td scope="row">{!! $notice->content !!}</td>
                                <td>{{ $notice->started_at }}</td>
                                <td>{{ $notice->ended_at }}</td>
                                <td>{{ \App\Enums\NoticeType::getDescription($notice->type) }}</td>
                                <td>{{ $plan->user->username }}</td>
                                <td>
                                    <a href="javascript:;" data-toggle="modal" data-target="#modal"
                                       data-url="{{ route('manage.notices.edit', $notice) }}">编辑</a>
                                    <a href="javascript:;" class="notice-del-btn"
                                       data-url="{{ route('manage.notices.destroy', $notice) }}">删除</a>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty">
                                <td colspan="20">暂无数据</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="{{ mix('/js/front/course/notice/index.js') }}"></script>
{{--<script src="{{ '/vendor/jquery/dist/jquery.min.js' }}"></script>--}}
<script type="text/javascript">
    window.onload = function () {
        $('.form_datetime').datepicker({
            autoclose: true,
            clearBtn: true, //清除按钮
            todayBtn: false, //今日按钮
            format: "yyyy-mm-dd"
        });
    };
</script>
