<link rel="stylesheet" href="{{ mix('/css/teacher/exam/navBar.css') }}">
<div class="navbar_content col-xl-3 p-0 col-md-12 col-12">
    <div class="card navbar-card">

        <div class="list-group list-group-sm list-group-flush">
            <a href="{{ route('manage.question.index') }}"
               class="list-group-item list-group-item-action d-flex justify-content-between {{ active_class(if_route('manage.question.show', 'active')) }} {{ active_class(if_route('manage.question.index', 'active')) }}">
                <div>
                    <span>题目列表</span>
                </div>
            </a>
            <a href="{{ route('manage.question.create') }}"
               class="list-group-item list-group-item-action d-flex justify-content-between {{ active_class(if_route('manage.question.create', 'active')) }}">
                <div>
                    <span>添加题目</span>
                </div>
            </a>
            <a href="{{ route('manage.paper.index') }}"
               class="list-group-item list-group-item-action d-flex justify-content-between {{ active_class(if_route('manage.paper.index', 'active')) }} {{ active_class(if_route('manage.paper.show', 'active')) }}">
                <div>
                    <span>试卷列表</span>
                </div>
            </a>
            <a href="{{ route('manage.paper.create') }}"
               class="list-group-item list-group-item-action d-flex justify-content-between {{ active_class(if_route('manage.paper.create', 'active')) }}">
                <div>
                    <span>添加试卷</span>
                </div>
            </a>
            <a href="{{ route('manage.paper.result.index') }}"
               class="list-group-item list-group-item-action d-flex justify-content-between {{ active_class(if_route('manage.paper.result.index', 'active')) }}">
                <div>
                    <span>试卷批阅</span>
                </div>
            </a>
        </div>
    </div>
</div>