<link rel="stylesheet" href="{{ mix('/css/teacher/classroom/header.css') }}">
<div class="row header" style="margin-bottom: 30px;">
    <div class="col-md-12">
        <div class="card bg-primary">
            <div class="card-body py-4">
                <div class="d-flex align-items-start">
                    <div class="icon icon-lg">
                        <img src="{{ render_cover($classroom->cover, 'classroom') }}" alt="">
                    </div>
                    <div class="icon-text">

                        <p class="text-white">{{ $classroom->title }}</p>

                        @if ($classroom->status == \App\Enums\Status::DRAFT)
                            <span class="courseStatus c_release badge badge-pill badge-warning">{{ \App\Enums\Status::getDescription($classroom->status) }}</span>
                            <input type="hidden" id="classroom-status" value="0">
                        @elseif($classroom->status == \App\Enums\Status::PUBLISHED)
                            <span class="courseStatus c_unpublished badge badge-pill badge-success">{{ \App\Enums\Status::getDescription($classroom->status) }}</span>
                            <input type="hidden" id="classroom-status" value="1">
                        @else
                            <span class="courseStatus badge badge-pill badge-warning">{{ \App\Enums\Status::getDescription($classroom->status) }}</span>
                        @endif

                        <form action="{{ route('manage.classroom.publish', $classroom) }}" id="publish-form" method="post" style="float:right;">
                            {{ csrf_field() }}
                            {{ method_field('patch') }}
                            <input type="hidden" name="status" value="{{ $classroom->status }}">
                            <button type="submit" class="btn btn-secondary btn-icon">
                                {{ \App\Enums\Status::getDescription($classroom->status) }}
                            </button>
                        </form>
                        <a href="{{ route('classrooms.show', $classroom) }}" target="_blank">
                        <button class="btn-inner--text btn btn-primary btn-icon">
                        预览
                        </button>
                        </a>

                        {{--<div class="dropdown float-right">--}}
                            {{--<button class="btn btn-sm btn-primary dropdown-toggle btn-icon pl-1" type="button"--}}
                                    {{--id="dropdown_small" data-toggle="dropdown" aria-haspopup="true"--}}
                                    {{--aria-expanded="false">--}}
                                {{--预览--}}
                            {{--</button>--}}
                            {{--<div class="dropdown-menu dropdown-menu-sm" aria-labelledby="dropdown_small">--}}
                                {{--<a class="dropdown-item text_color_666" href="#">未购买用户</a>--}}
                                {{--<a class="dropdown-item text_color_666" href="#">正式学员</a>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('#publish-form').submit(function () {
            var msg = $('#classroom-status').val() == 1 ? '取消发布' : '发布';
            if (confirm('是否要' + msg + '该班级?')) {
                return true;
            }
            return false;
        })
    })
</script>