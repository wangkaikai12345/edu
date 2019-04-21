<link rel="stylesheet" href="{{ mix('/css/front/course/header/index.css') }}">
<div class="row header" style="margin-bottom: 30px;">
    <div class="col-md-12">
        <div class="card bg-primary teacher_style">
            <div class="card-body py-4">
                <div class="d-flex align-items-start">
                    <div class="icon icon-lg">
                        <img src="{{ render_cover($course->cover, 'course') }}" alt="">
                    </div>
                    <div class="icon-text">

                        <p class="text-white">{{ $course->title }}</p>

                        @if($course->status == 'draft')
                            <span class="courseStatus c_release badge-lg badge badge-pill badge-warning text-uppercase">未发布</span>
                        @elseif($course->status == 'published')
                            <span class="courseStatus c_unpublished badge badge-pill badge-warning text-uppercase success">已发布</span>
                        @else
                            <span class="courseStatus badge badge-pill badge-warning text-uppercase text-danger">已关闭</span>
                        @endif

                        @if ($course->status == 'published')
                            <a href="{{ route("courses.show", $course) }}" target="_blank">
                                <button class="btn-inner--text btn btn-primary btn-icon">
                                    返回课程主页
                                </button>
                            </a>
                            <form action="{{ route('manage.courses.publish', $course) }}" method="post" style="float:right;">
                                {{ csrf_field() }}
                                {{ method_field('patch') }}
                                <input type="hidden" name="status" value="closed">
                                <button class="btn-inner--text btn btn-primary btn-icon" style="right: 150px;">
                                    关闭课程
                                </button>
                            </form>
                        @else
                            <form action="{{ route('manage.courses.publish', $course) }}" method="post" style="float:right;">
                                {{ csrf_field() }}
                                {{ method_field('patch') }}
                                <input type="hidden" name="status" value="published">
                                <button type="submit" class="btn btn-secondary btn-icon" style="right: 150px;">
                                    发布课程
                                </button>
                            </form>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>