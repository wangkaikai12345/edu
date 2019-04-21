<link rel="stylesheet" href="{{ mix('/css/front/course/navbar/index.css') }}">
<link rel="stylesheet" href="{{ mix('/css/front/learn/navBar.css') }}">
<div class="navbar_content col-xl-3 m-0 p-0 pl-0 col-md-12 col-12">
    <div class="card navbar-card student_style">
        <div class="list_group_item_content">
            <div class="list_group_item_title">
                我的学习
            </div>
            <hr style="margin:0 auto;" width="85%">

            <div class="list-group list-group-sm list-group-flush">
                <a href="{{ route('users.courses') }}"
                   class="list-group-item list-group-item-action d-flex justify-content-between {{ user_active('courses') }}">
                    <div>
                        <span>我的课程</span>
                    </div>
                </a>

                <?php if(config('app.model') == 'classroom'): ?>
                    <a href="{{ route('users.classrooms') }}"
                       class="list-group-item list-group-item-action d-flex justify-content-between {{ user_active('classrooms') }}">
                        <div>
                            <span>我的班级</span>
                        </div>
                    </a>
                <?php endif; ?>

                {{--<a href="#"--}}
                   {{--class="list-group-item list-group-item-action d-flex justify-content-between">--}}
                    {{--<div>--}}
                        {{--<span>我的学习圈</span>--}}
                    {{--</div>--}}
                {{--</a>--}}
                <a href="{{ route('users.questions') }}"
                   class="list-group-item list-group-item-action d-flex justify-content-between {{ user_active('questions') }}">
                    <div>
                        <span>我的问答</span>
                    </div>
                </a>
                <a href="{{ route('users.topics') }}"
                   class="list-group-item list-group-item-action d-flex justify-content-between {{ user_active('topics') }}">
                    <div>
                        <span>我的话题</span>
                    </div>
                </a>
                <a href="{{ route('users.notes') }}"
                   class="list-group-item list-group-item-action d-flex justify-content-between {{ user_active('notes') }}">
                    <div>
                        <span>我的笔记</span>
                    </div>
                </a>
                <a href="{{ route('users.jobs', ['keyword' => 'readed']) }}"
                   class="list-group-item list-group-item-action d-flex justify-content-between {{ user_active('jobs') }}">
                    <div>
                        <span>我的作业</span>
                    </div>
                </a>
                <a href="{{ route('users.exams') }}"
                   class="list-group-item list-group-item-action d-flex justify-content-between  {{ user_active('exams') }}">
                    <div>
                        <span>我的考试</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
