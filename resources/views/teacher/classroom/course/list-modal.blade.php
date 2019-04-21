<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">选择课程</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form method="post" action="{{ route('manage.classroom.course.store', $classroom) }}" id="plan-add-form">
        {{ csrf_field() }}
        <div class="container">
            <div class="row input-content justify-content-center pl-3">
                <div class="col-xl-12 pt-3 pb-2">
                    <div class="create_course_message">
                        加入班级的课程与原课程同步更新。如需解除同步，请对该课程进行编辑，解除同步后，课程可独立编辑。
                    </div>
                    <div class="create_course_message" style="margin-top:10px;">
                        只能添加学习模式为 [解锁式学习] 的任务。如过没有该类型的任务，请进行 课程管理-版本管理-创建版本。
                    </div>
                </div>
                <div class="col-xl-12 pb-3">
                    <div class="form-group">
                        <div class="input-group input-group-transparent">
                            <input type="text" id="search-course-title" name=""
                                   class="form-control col-xl-3 mt-0" placeholder="课程名称" value="{{ request()->title }}">
                            <button type="button" id="search-course-btn" class="btn btn-primary mr-2 item_btn search_course">搜索</button>
                            <button type="button" id="all-course-btn" class="btn btn-primary item_btn all_course">全部课程</button>
                        </div>
                        <div class="tab-content" id="myTabContent">
                            {{-- 正式学员 --}}
                            <div class="tab-pane fade show active" id="formal-student" role="tabpanel"
                                 aria-labelledby="home-tab">
                                <div class="table_content">
                                    <table class="table table-hover course_table">
                                        <thead>
                                        <tr>
                                            <th scope="col" width="10"></th>
                                            <th scope="col" width="300">课程名称</th>
                                            <th scope="col" width="250">教学版本名称</th>
                                            <th scope="col">教师名称</th>
                                            <th scope="col">价格</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($planTeachers as $planTeacher)
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox mt-4">
                                                    <input type="checkbox" name="plans[]" value="{{ $planTeacher->plan_id }}" class="custom-control-input" id="course-list-{{ $planTeacher->id }}"/>
                                                    <label class="custom-control-label" for="course-list-{{$planTeacher->id}}"></label>
                                                </div>
                                            </td>
                                            <td class="course_img_name">
                                                <img src="/imgs/classroom.png" alt="">
                                                <div class="course_name">
                                                    {{ $planTeacher->course->title }}
                                                </div>
                                            </td>
                                            <td>
                                                {{ $planTeacher->plan->title }}
                                            </td>
                                            <td class="teacher_name">
                                                {{ $planTeacher->user->username }}
                                            </td>
                                            <td>
                                                {{ $planTeacher->plan->price ? $planTeacher->plan->price : '免费' }}
                                            </td>
                                        </tr>
                                        @endforeach
                                        {{-- tr暂无数据 --}}
                                        {{--<tr class="empty">--}}
                                        {{--<td colspan="20">暂无课程记录...</td>--}}
                                        {{--</tr>--}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                    </div>
                </div>
                <nav aria-label="Page navigation example" style="margin-left: 35%;">
                    {{ $planTeachers->appends(request()->all())->links('vendor.pagination.bootstrap-4') }}
                </nav>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                <button type="submit" class="btn btn-primary primary-btn" id="plan-add-btn">创建</button>
            </div>
        </div>
    </form>
</div>