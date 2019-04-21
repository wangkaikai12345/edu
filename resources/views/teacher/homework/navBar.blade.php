<link rel="stylesheet" href="{{ mix('/css/teacher/homework/navBar.css') }}">
<link rel="stylesheet" href="{{ mix('/css/front/course/navbar/index.css') }}">
<link rel="stylesheet" href="{{ mix('/css/front/personal/navBar.css') }}">
<div class="navbar_content col-xl-3 m-0 p-0 pl-0 col-md-12 col-12">
    <div class="card navbar-card teacher_style">
        <div class="list_group_item_content">
            <div class="czh_nav_group list-group list-group-sm list-group-flush" style="padding-top: 20px !important;">
                <a href="{{ route('manage.homework.index') }}" style="padding-left: 20px !important;"
                   class="nav_item_a list-group-item list-group-item-action d-flex justify-content-between {{active_class(if_route('manage.homework.index', 'active'))}}">
                    <div>
                        <span class="nav_item">作业列表</span>
                    </div>
                </a>
                <a href="{{ route('manage.homework.create') }}" style="padding-left: 20px !important;"
                   class="nav_item_a list-group-item list-group-item-action d-flex justify-content-between {{active_class(if_route('manage.homework.create', 'active'))}}">
                    <div>
                        <span class="nav_item">添加作业</span>
                    </div>
                </a>
                <a href="{{ route('manage.homework.post.index') }}" style="padding-left: 20px !important;"
                   class="nav_item_a list-group-item list-group-item-action d-flex justify-content-between {{active_class(if_route('manage.homework.post.show', 'active'))}} {{active_class(if_route('manage.homework.post.index', 'active'))}}">
                    <div>
                        <span class="nav_item">作业批阅</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
