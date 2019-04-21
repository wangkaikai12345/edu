<!----------------------- 公告管理页 ------------------------>
<link rel="stylesheet" href="{{ mix('/css/front/course/notice/index.css') }}">
<link rel="stylesheet" href="{{ mix('/css/front/course/plan/index.css') }}">
<div class="col-xl-9 col-md-12 col-12 form_content p-0">
    <!-- Attach a new card -->
    <form class="form-default">
        <div class="card teacher_style">
            <div class="card-body row_content" style="min-height:500px">
                <div class="row_div">
                    <div class="row">
                        <div class="col-lg-8">
                            <h6>版本列表</h6>
                        </div>
                        <div class="col-lg-4 text-lg-right">
                            <button type="button" class="btn btn-primary add-plan mr-4 mt-3" data-toggle="modal"
                                    data-target=".bd-example-modal-lg">+ 创建教学版本
                            </button>
                        </div>
                    </div>
                    <hr class="course_hr m-0">
                </div>
                <div class="bd-example">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col">名称</th>
                            <th scope="col">任务数</th>
                            <th scope="col">总评论数</th>
                            <th scope="col">状态</th>
                            <th scope="col">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($course->plans as $plan)
                            <tr>
                                <th scope="colgroup" width="200">
                                    {{ $plan->title }}
                                </th>
                                <td>{{ $plan->tasks_count }}</td>
                                <td>{{ $plan->notes()->count() }}</td>

                                <td>
                                    <span class="{{ $plan->status == 'closed' ? 'text-danger' : 'text-success' }}">{{ \App\Enums\Status::getDescription($plan->status) }}</span>
                                </td>
                                <td>
                                    <a class="float-left" href="{{ route('manage.plans.show', [$course, $plan]) }}">管理</a>
                                    <a class="float-left plan-publish" href="javascript:;"
                                       data-status="{{ $plan->status == 'published' ? 'closed' : 'published' }}"
                                       data-url="{{ route('manage.plans.publish', [$course, $plan]) }} ">{{$plan->status == 'published' ? '关闭' : '发布'}}</a>

                                    {{--<ul class="float-left nav user-nav">--}}
                                        {{--<li class="user-avatar-li nav-hover float-left">--}}
                                            {{--<div class="dropdown">--}}
                                                {{--<a class="more item_more" data-toggle="dropdown"--}}
                                                   {{--style="border:0;" aria-expanded="false">--}}
                                                    {{--更多--}}
                                                {{--</a>--}}
                                                {{--<div class="dropdown-menu">--}}
                                                    {{--<a class="dropdown-item" href="{{ route('plans.intro', [$course, $plan]) }}" target="_blank">--}}
                                                        {{--<i class="iconfont">&#xe6d8;</i>--}}
                                                        {{--<span>预览</span>--}}
                                                    {{--</a>--}}
                                                    {{--<a class="dropdown-item copy" data-toggle="modal" data-title="{{ $plan->title }}"--}}
                                                       {{--data-target=".bd-copy-modal-lg">--}}
                                                        {{--<i class="iconfont">&#xe677;</i>--}}
                                                        {{--<span>复制</span>--}}
                                                    {{--</a>--}}
                                                    {{--<a class="dropdown-item" href="javascript:;">--}}
                                                        {{--<i class="iconfont">&#xe62e;</i>--}}
                                                        {{--<span>删除</span>--}}
                                                    {{--</a>--}}
                                                    {{--<a class="dropdown-item plan-publish" href="javascript:;"--}}
                                                       {{--data-status="{{ $plan->status == 'published' ? 'closed' : 'published' }}"--}}
                                                       {{--data-url="{{ route('manage.plans.publish', [$course, $plan]) }} ">--}}
                                                        {{--<i class="iconfont">&#xe643;</i>--}}
                                                        {{--<span>{{$plan->status == 'published' ? '关闭' : '发布'}}</span>--}}
                                                    {{--</a>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</li>--}}
                                    {{--</ul>--}}
                                </td>
                            </tr>
                        @empty
                            <tr class="empty">
                                <td colspan="20">
                                    暂无数据
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>

{{----------------------------- 创建教学版本 ----------------------------------}}
<div class="modal fade bd-example-modal-lg xh_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">创建教学版本</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('manage.plans.store', $course) }}" id="plan-add-form">
                <div class="modal-body">

                        <div class="row mt-4 m-0 ml-8 input-content justify-content-center">
                            <div class="col-md-10 col-12">
                                <div class="form-group">
                                    <div class="input-group input-group-transparent">
                                        <label class="control-label col-md-2 col-lg-2 col-xl-2 text-center"><span
                                                    class="text-label">*</span>新版本名称</label>
                                        <input type="text" id="title" name="title" class="form-control col-md-9 col-lg-9 col-xl-9 ml-2">
                                        <input type="hidden" name="learn_mode" value="free">
                                        <input type="hidden" name="expiry_mode" value="forever">
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--<div class="row mt-3 m-0 ml-8 input-content justify-content-center">--}}
                            {{--<div class="col-md-10 col-12">--}}
                                {{--<div class="form-group">--}}
                                    {{--<div class="input-group input-group-transparent">--}}
                                        {{--<label class="control-label col-md-2 col-lg-2 col-xl-2 text-right modal-label">--}}
                                            {{--<i class="iconfont">--}}
                                                {{--&#xe640;--}}
                                            {{--</i>--}}
                                            {{--<span>学习模式</span>--}}
                                        {{--</label>--}}
                                        {{--<div class="col-md-12 col-xl-9 col-lg-9 row ml-2 pl-0">--}}
                                            {{--<div class="col-md-6 col-xl-3 col-sm-6 col-lg-6 col-6 p-0">--}}
                                                {{--<div class="custom-control custom-radio">--}}
                                                    {{--<input type="radio" name="learn_mode"--}}
                                                           {{--class="custom-control-input" checked--}}
                                                           {{--id="customRadio1" value="free">--}}
                                                    {{--<label class="custom-control-label" for="customRadio1">自由式学习</label>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-md-6 col-xl-3 col-sm-6 col-6 col-lg-3 p-0">--}}
                                                {{--<div class="custom-control custom-radio">--}}
                                                    {{--<input type="radio" name="learn_mode"--}}
                                                           {{--class="custom-control-input"--}}
                                                           {{--id="customRadio2" value="lock">--}}
                                                    {{--<label class="custom-control-label" for="customRadio2">解锁式学习</label>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<label class="col-12 pl-0 message-label">教学版本创建后“学习模式”将不能修改。</label>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--<div class="row mt-3 m-0 ml-8 input-content justify-content-center">--}}
                        {{--<div class="col-md-10 col-12">--}}
                            {{--<div class="form-group">--}}
                                {{--<div class="input-group input-group-transparent">--}}
                                    {{--<label class="control-label col-md-2 col-lg-2 col-xl-2 text-left modal-label modal-last-label">--}}
                                        {{--<i class="iconfont">--}}
                                            {{--&#xe640;--}}
                                        {{--</i>--}}
                                        {{--<span>学习有效期</span>--}}
                                    {{--</label>--}}
                                    {{--<div class="col-md-12 col-xl-9 col-lg-9 row ml-2 pl-0">--}}
                                        {{--<div class="col-md-4 col-xl-3 col-sm-4 col-lg-4 col-4 p-0">--}}
                                            {{--<div class="custom-control custom-radio">--}}
                                                {{--<input type="radio" name="expiry_mode"--}}
                                                       {{--class="custom-control-input expiry_mode"--}}
                                                       {{--id="customRadio3" value="forever" checked>--}}
                                                {{--<label class="custom-control-label" for="customRadio3">永久有效</label>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-4 col-xl-3 col-sm-4 col-4 col-lg-3 p-0">--}}
                                            {{--<div class="custom-control custom-radio">--}}
                                                {{--<input type="radio" name="expiry_mode"--}}
                                                       {{--class="custom-control-input expiry_mode"--}}
                                                       {{--id="customRadio4" value="valid">--}}
                                                {{--<label class="custom-control-label" for="customRadio4">有效时间</label>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-4 col-xl-3 col-sm-4 col-4 col-lg-3 p-0">--}}
                                            {{--<div class="custom-control custom-radio">--}}
                                                {{--<input type="radio" name="expiry_mode"--}}
                                                       {{--class="custom-control-input expiry_mode"--}}
                                                       {{--id="customRadio5" value="period">--}}
                                                {{--<label class="custom-control-label" for="customRadio5">固定周期</label>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
{{--<<<<<<< HEAD--}}

                                        {{-- 时间范围--}}
                                        {{--<div class="col-12 row mt-3" id="period" style="display:none">--}}
                                            {{--<div class="col-6 p-0">--}}
                                                {{--<div class="form-group">--}}
                                                    {{--<div class="input-group input-group-transparent text-center">--}}
                                                        {{--开始日期--}}
                                                        {{--<input name="expiry_started_at" type="date"--}}
                                                               {{--class="form-control">--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-6 p-0">--}}
                                                {{--<div class="form-group">--}}
                                                    {{--<div class="input-group input-group-transparent text-center">--}}
                                                        {{--结束日期--}}
                                                        {{--<input name="expiry_ended_at" type="date"--}}
                                                               {{--class="form-control">--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}

                                        {{-- 有效天数--}}
                                        {{--<div class="col-12 row mt-3" id="valid" style="display:none">--}}
                                            {{--<div class="col-12 p-0">--}}
                                                {{--<div class="form-group">--}}
                                                    {{--<div class="input-group input-group-transparent text-center">--}}
                                                        {{--<input type="number" name="expiry_days" value="0"--}}
                                                        {{--class="form-control col-md-3 col-lg-3 col-xl-3 ml-2">--}}
                                                         {{--天之内，学员可进行学习。--}}
                                                        {{--</div>--}}
{{--=======--}}
                                    {{--</div>--}}
                                    {{--<div class="col-12 row mt-3 time_select">--}}
                                        {{--<div class="col-6 p-0">--}}
                                            {{--<div class="form-group">--}}
                                                {{--<div class="input-group input-group-transparent text-center">--}}
                                                    {{--开始日期--}}
                                                    {{--<input class="form-control col-md-7 col-lg-7 col-xl-7 ml-2 form_datetime">--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-6 p-0">--}}
                                            {{--<div class="form-group">--}}
                                                {{--<div class="input-group input-group-transparent text-center">--}}
                                                    {{--结束日期--}}
                                                    {{--<input class="form-control col-md-7 col-lg-7 col-xl-7 ml-2 form_datetime">--}}
{{-->>>>>>> origin/zhonghua--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}

                                        {{--<label class="col-12 pl-0 message-label" style="font-size:12px; color:#F18C29;"></label>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary primary-btn">创建</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 复制教学版本 --}}
<div class="modal fade bd-copy-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="copy_title">复制教学版本</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('manage.plans.copy', [$course, $plan]) }}" id="plan-copy-form">
                <div class="modal-body">

                    <div class="row mt-4 m-0 ml-8 input-content justify-content-center">
                        <div class="col-md-10 col-12">
                            <div class="form-group">
                                <div class="input-group input-group-transparent">
                                    <label class="control-label col-md-2 col-lg-2 col-xl-2 text-center"><span
                                                class="text-label">*</span>新版本名称</label>
                                    <input type="text" name="title" class="form-control col-md-9 col-lg-9 col-xl-9 ml-2">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
{{--<<<<<<< HEAD--}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary primary-btn">复制</button>
{{--=======--}}
            {{--</div>--}}
            {{--<div class="modal-body last-modal-body">--}}
                {{--<div class="row mt-3 m-0 ml-8 input-content justify-content-center">--}}
                    {{--<div class="col-md-10 col-12">--}}
                        {{--<div class="form-group">--}}
                            {{--<div class="input-group input-group-transparent">--}}
                                {{--<label class="control-label col-md-2 col-lg-2 col-xl-2 text-left modal-label modal-last-label">--}}
                                    {{--<i class="iconfont">--}}
                                        {{--&#xe640;--}}
                                    {{--</i>--}}
                                    {{--<span>学习有效期</span>--}}
                                {{--</label>--}}
                                {{--<div class="col-md-12 col-xl-9 col-lg-9 row">--}}
                                    {{--<div class="col-md-4 col-xl-3 col-sm-4 col-lg-4 col-4 p-0">--}}
                                        {{--<div class="custom-control custom-radio">--}}
                                            {{--<input type="radio" name="custom-radio-1"--}}
                                                   {{--class="custom-control-input"--}}
                                                   {{--id="customRadio3">--}}
                                            {{--<label class="custom-control-label" for="customRadio3">永久有效</label>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="col-md-4 col-xl-3 col-sm-4 col-4 col-lg-4 p-0">--}}
                                        {{--<div class="custom-control custom-radio">--}}
                                            {{--<input type="radio" name="custom-radio-1"--}}
                                                   {{--class="custom-control-input"--}}
                                                   {{--id="customRadio4">--}}
                                            {{--<label class="custom-control-label" for="customRadio4">随到随学</label>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="col-md-4 col-xl-3 col-sm-4 col-4 col-lg-4 p-0">--}}
                                        {{--<div class="custom-control custom-radio">--}}
                                            {{--<input type="radio" name="custom-radio-1"--}}
                                                   {{--class="custom-control-input"--}}
                                                   {{--id="customRadio5">--}}
                                            {{--<label class="custom-control-label" for="customRadio5">固定周期</label>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="col-12 row mt-2">--}}
                                        {{--<div class="col-md-4 col-xl-3 col-sm-4 col-4 col-lg-4 p-0">--}}
                                            {{--<div class="custom-control custom-radio">--}}
                                                {{--<input type="radio" name="custom-radio-1"--}}
                                                       {{--class="custom-control-input"--}}
                                                       {{--id="customRadio6">--}}
                                                {{--<label class="custom-control-label" for="customRadio6">按截止日期</label>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-4 col-xl-3 col-sm-4 col-4 col-lg-4 p-0">--}}
                                            {{--<div class="custom-control custom-radio">--}}
                                                {{--<input type="radio" name="custom-radio-1"--}}
                                                       {{--class="custom-control-input"--}}
                                                       {{--id="customRadio7">--}}
                                                {{--<label class="custom-control-label" for="customRadio7">按有效天数</label>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<!------------------- 按有效天数 ------------------->--}}
                                {{--<div class="col-12 row mt-3">--}}
                                {{--<div class="col-12 p-0">--}}
                                {{--<div class="form-group">--}}
                                {{--<div class="input-group input-group-transparent text-center">--}}
                                {{--在--}}
                                {{--<input type="text"--}}
                                {{--class="form-control col-md-3 col-lg-3 col-xl-3 ml-2">--}}
                                {{--前，学员可进行学习。--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<!------------------- 按截止日期 ------------------->--}}
                                    {{--<div class="col-12 row mt-3">--}}
                                        {{--<div class="col-6 p-0">--}}
                                            {{--<div class="form-group">--}}
                                                {{--<div class="input-group input-group-transparent text-center">--}}
                                                    {{--开始日期--}}
                                                    {{--<input class="form-control col-md-7 col-lg-7 col-xl-7 ml-2 form_datetime">--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-6 p-0">--}}
                                            {{--<div class="form-group">--}}
                                                {{--<div class="input-group input-group-transparent text-center">--}}
                                                    {{--结束日期--}}
                                                    {{--<input class="form-control col-md-7 col-lg-7 col-xl-7 ml-2 form_datetime">--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<label class="col-12 pl-0 mt-3 message-label"--}}
                                           {{--style="font-size:12px; color:#F18C29;">教学版本首次发布后“学习有效期”不能再更改模式。</label>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
{{-->>>>>>> origin/zhonghua--}}
                </div>
            </form>
        </div>
    </div>
</div>


<script src="{{ mix('/js/front/course/notice/index.js') }}"></script>
{{--<script src="{{ '/vendor/jquery/dist/jquery.min.js' }}"></script>--}}

<script>
    window.onload = () => {
        // 鼠标移入头像触发下拉
        $('.dropdown').on({
            mouseenter: function () {
                $(this).find('.dropdown-menu').addClass('show');
            }
        });
        // 鼠标移除头像隐藏下拉
        $('.dropdown').on({
            mouseleave: function () {
                $(this).find('.dropdown-menu').removeClass('show');
            }
        })
    }
    ;
</script>
