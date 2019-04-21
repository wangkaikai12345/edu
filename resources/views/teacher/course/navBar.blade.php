<link rel="stylesheet" href="{{ mix('/css/front/course/navbar/index.css') }}">
<div class="navbar_content col-xl-3 pl-0 p-0 col-md-12 col-12">
    <div class="card navbar-card teacher_style">

        <div class="list-group list-group-sm list-group-flush">
            <a href="{{ route('manage.plans.index', $course) }}"
               class="list-group-item list-group-item-action d-flex justify-content-between {{active_class(if_route('manage.plans.index', 'active'))}}">
                <div>
                    <span>版本列表</span>
                </div>
            </a>
            <a href="{{ route('manage.courses.edit', $course) }}"
               class="list-group-item list-group-item-action d-flex justify-content-between {{active_class(if_route('manage.courses.edit', 'active'))}}">
                <div>
                    <span>课程信息</span>
                </div>
            </a>
        </div>
    </div>
</div>