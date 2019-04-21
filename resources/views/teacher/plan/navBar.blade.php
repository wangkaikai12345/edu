<link rel="stylesheet" href="{{ mix('/css/teacher/plan/navbar/index.css') }}">
<div class="navbar_content col-xl-3 p-0 pl-0 col-md-12 col-12">
    <div class="card navbar-card teacher_style">

        <div class="list-group list-group-sm list-group-flush">
            <a href="{{ route('manage.plans.show', [$course, $plan]) }}"
               class="{{active_class(if_route('manage.plans.show', 'active'))}} {{active_class(if_route('manage.task.video.question.create', 'active'))}}  list-group-item list-group-item-action d-flex justify-content-between">
                <div>
                    <span>版本任务</span>
                </div>
            </a>
            <a href="{{ route('manage.plans.edit', [$course, $plan]) }}"
               class="{{active_class(if_route('manage.plans.edit', 'active'))}} list-group-item list-group-item-action d-flex justify-content-between">
                <div>
                    <span>基础设置</span>
                </div>
            </a>
            {{--<a href="{{ route('manage.plans.teachers', [$course, $plan]) }}"--}}
               {{--class="{{ active_class(if_route('manage.plans.teachers'))}} list-group-item list-group-item-action d-flex justify-content-between">--}}
                {{--<div>--}}
                    {{--<span>教师设置</span>--}}
                {{--</div>--}}
            {{--</a>--}}
            {{--<a href="{{ route('manage.plans.member.index', [$course, $plan]) }}"--}}
               {{--class="{{ active_class(if_route('manage.plans.member.index'))}} list-group-item list-group-item-action d-flex justify-content-between">--}}
                {{--<div>--}}
                    {{--<span>学员管理</span>--}}
                {{--</div>--}}
            {{--</a>--}}
            {{--<a href="{{ route('manage.plans.orders', [$course, $plan]) }}"--}}
               {{--class="{{ active_class(if_route('manage.plans.orders'))}} list-group-item list-group-item-action d-flex justify-content-between">--}}
                {{--<div>--}}
                    {{--<span>订单查询</span>--}}
                {{--</div>--}}
            {{--</a>--}}
            {{--<a href="javascript:;"--}}
               {{--class="list-group-item list-group-item-action d-flex justify-content-between">--}}
                {{--<div>--}}
                    {{--<span>数据概览</span>--}}
                {{--</div>--}}
            {{--</a>--}}
            {{--<a href="javascript:;"--}}
               {{--class="list-group-item list-group-item-action d-flex justify-content-between">--}}
                {{--<div>--}}
                    {{--<span>笔记统计</span>--}}
                {{--</div>--}}
            {{--</a>--}}
            {{--<a href="javascript:;"--}}
               {{--class="list-group-item list-group-item-action d-flex justify-content-between">--}}
                {{--<div>--}}
                    {{--<span>问答管理</span>--}}
                {{--</div>--}}
            {{--</a>--}}
            {{--<a href="{{ route('manage.notices.index', [$course, $plan]) }}"--}}
               {{--class="{{active_class(if_route('manage.notices.index', 'active'))}} list-group-item list-group-item-action d-flex justify-content-between">--}}
                {{--<div>--}}
                    {{--<span>公告管理</span>--}}
                {{--</div>--}}
            {{--</a>--}}

            {{--<a href="{{ route('manage.task.video.question.count', [$course, $plan]) }}"--}}
               {{--class="{{active_class(if_route('manage.task.video.question.count', 'active'))}} list-group-item list-group-item-action d-flex justify-content-between">--}}
                {{--<div>--}}
                    {{--<span>弹题统计</span>--}}
                {{--</div>--}}
            {{--</a>--}}
            {{--<a href="{{ route('teacher.plan.question.manage', [$course, $plan]) }}"--}}
               {{--class="{{active_class(if_route('teacher.plan.question.manage', 'active'))}} list-group-item list-group-item-action d-flex justify-content-between">--}}
                {{--<div>--}}
                    {{--<span>test</span>--}}
                {{--</div>--}}
            {{--</a>--}}
        </div>
    </div>
</div>