@extends('teacher.homework.homework_layout')

@section('title', '作业批阅列表')

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/teacher/homework/rating.css') }}">
    <link rel="stylesheet" href="/vendor/select2/dist/css/select2.min.css">
@endsection

@section('content')
    <div class="czh_homework_content">
        <div class="container">
            <div class="row padding-content">
                @include('teacher.homework.navBar')
                <div class="czh_list_con col-md-9 p-0">
                    <div class="list_head">
                        <p>作业批阅列表</p>
                    </div>
                    <div class="list_content">
                        <div class="con_form">
                            <form action="">
                                {{--<div class="form-group search_class">--}}
                                {{--<span class="inp_de">班级</span>--}}
                                {{--<select class="search_select form-control" name="" id="">--}}
                                {{--<option value="">请选择班级</option>--}}
                                {{--<option value="">PHP实训班</option>--}}
                                {{--<option value="">Java实训班</option>--}}
                                {{--<option value="">Python实训班</option>--}}
                                {{--</select>--}}
                                {{--</div>--}}

                                <div class="form-group search_tag">
                                    <input type="text" class="form-control" name="username" placeholder="学员姓名" id=""
                                           value="{{ request()->username }}">
                                </div>
                                <div class="form-group search_status">
                                    <select class="form-control col-md-12 col-lg-9 col-xl-9" id="teacher_name"
                                            name="status"
                                            data-toggle="select" title="Simple select" data-live-search="true"
                                            data-live-search-placeholder="Search ...">
                                        <option value="0">批阅状态</option>
                                        <option {{ request('status') == \App\Enums\HomeworkPostStatus::READING ? 'selected' : '' }} value="{{ \App\Enums\HomeworkPostStatus::READING }}">
                                            未批阅
                                        </option>
                                        <option {{ request('status') == \App\Enums\HomeworkPostStatus::READED ? 'selected' : '' }} value="{{ \App\Enums\HomeworkPostStatus::READED }}">
                                            已批阅
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group search_btn">
                                    <button class="btn btn-primary">搜索</button>
                                </div>
                            </form>
                        </div>
                        <div class="list_item">
                            <table class="table table-hover table-cards align-items-center">
                                <thead>
                                <tr>
                                    <th scope="col" style="width: 360px;">作业名称</th>
                                    <th scope="col" style="min-width: 150px;">学员</th>
                                    <th scope="col">状态</th>
                                    <th scope="col">批改人</th>
                                    <th scope="col" style="min-width: 64px;">分数</th>
                                    <th scope="col">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($homeworkPosts as $homeworkPost)
                                    <tr class="bg-white">
                                        <td>
                                            {{ $homeworkPost->title }}
                                            {{--<p class="vice_con">班期：测试班级</p>--}}
                                            <p class="vice_con">{{ $homeworkPost->course->title }}
                                                -{{ $homeworkPost->plan->title }}</p>
                                        </td>
                                        <td>
                                            {{ $homeworkPost->user->username }}
                                            <p class="vice_con">{{ $homeworkPost->created_at }}</p>
                                        </td>
                                        <td>
                                            <span class="{{ $homeworkPost->status == 'reading' ? 'rating_wait' : 'rating_yes' }}">{{ \App\Enums\HomeworkPostStatus::getDescription($homeworkPost->status) }}</span>
                                        </td>
                                        <td>{{ $homeworkPost->teacher ? $homeworkPost->teacher->username : '暂无' }}</td>
                                        <td>{{ $homeworkPost->result }}</td>
                                        <td class="list_item_action">
                                            <a href="{{ route('manage.homework.post.show', $homeworkPost) }}"
                                               class="yl">查看</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <nav class="course_list" aria-label="Page navigation example">
                            {{ $homeworkPosts->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
                        </nav>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="/vendor/select2/dist/js/select2.min.js"></script>
    <script>
        // var unSelected = "#bbb";
        // var selected = "#333";
        // $(function () {
        //     $(".search_select").css("color", unSelected);
        //     $(".search_select").children().css("color", selected);
        //     $(".search_select").change(function () {
        //         var selItem = $(this).val();
        //         if (selItem == $(this).find('option:first').val()) {
        //             $(this).css("color", unSelected);
        //         } else {
        //             $(this).css("color", selected);
        //         }
        //     });
        // })


    </script>
@endsection