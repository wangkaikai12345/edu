<link rel="stylesheet" href="{{ mix('/css/teacher/classroom/navBar.css') }}">
<div class="navbar_content col-xl-3 pl-0 p-0 col-md-12 col-12">
    <div class="card navbar-card">

        <div class="list-group list-group-sm list-group-flush">
            <a href="{{ route('manage.classroom.show', $classroom) }}"
               class="list-group-item list-group-item-action d-flex justify-content-between {{active_class(if_route('manage.classroom.show', 'active'))}}">
                <div>
                    <span>管理首页</span>
                </div>
            </a>
            <a href="javascript:;"
               class="list-group-item list-group-item-action d-flex justify-content-between display-btn text_color_999">
                <div>
                    <span>班级设置</span>
                </div>
            </a>
            <a href="{{ route('manage.classroom.base', $classroom) }}"
               class="list-group-item list-group-item-action d-flex justify-content-between {{active_class(if_route('manage.classroom.base', 'active'))}}">
                <div>
                    <span>基本信息
                        @if(empty($classroom->description))
                        <span class="courseStatus c_unpublished badge badge-pill badge-warning">未完善</span>
                        @endif
                    </span>
                </div>
            </a>
            <a href="{{ route('manage.classroom.price', $classroom) }}"
               class="list-group-item list-group-item-action d-flex justify-content-between {{active_class(if_route('manage.classroom.price', 'active'))}}">
                <div>
                    <span>价格设置
                        @if(empty($classroom->price))
                            <span class="courseStatus c_unpublished badge badge-pill badge-warning">未完善</span>
                        @endif
                    </span>
                </div>
            </a>
            <a href="{{ route('manage.classroom.service', $classroom) }}"
               class="list-group-item list-group-item-action d-flex justify-content-between {{active_class(if_route('manage.classroom.service', 'active'))}}">
                <div>
                    <span>服务设置
                        @if(empty($classroom->services))
                            <span class="courseStatus c_unpublished badge badge-pill badge-warning">未完善</span>
                        @endif
                    </span>
                </div>
            </a>
            <a href="{{ route('manage.classroom.teacher', $classroom) }}"
               class="list-group-item list-group-item-action d-flex justify-content-between {{active_class(if_route('manage.classroom.teacher', 'active'))}}">
                <div>
                    <span>教师设置
                        @if(empty($classroom->teachers->count()))
                            <span class="courseStatus c_unpublished badge badge-pill badge-warning">未完善</span>
                        @endif
                    </span>
                </div>
            </a>
            <a href="javascript:;"
               class="list-group-item list-group-item-action d-flex justify-content-between display-btn text_color_999">
                <div>
                    <span>班级管理</span>
                </div>
            </a>
            <a href="{{ route('manage.classroom.course.list', $classroom) }}"
               class="list-group-item list-group-item-action d-flex justify-content-between {{active_class(if_route('manage.classroom.course.list', 'active'))}}">
                <div>
                    <span>课程管理
                        @if(empty($classroom->plans->count()))
                            <span class="courseStatus c_unpublished badge badge-pill badge-warning">未完善</span>
                        @endif
                    </span>
                </div>
            </a>
            <a href="{{ route('manage.classroom.member.list', $classroom) }}"
               class="list-group-item list-group-item-action d-flex justify-content-between {{active_class(if_route('manage.classroom.member.list', 'active'))}}">
                <div>
                    <span>学员管理</span>
                </div>
            </a>
        </div>
    </div>
</div>