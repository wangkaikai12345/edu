<link rel="stylesheet" href="{{ mix('/css/teacher/plan/header.css') }}">
<div class="wrapper">
    <div class="xh_content_wrap">
        <div class="container" style="background:#fff;">
            <div class="row header">
                <div class="col-10 p-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a
                                        href="{{ route('manage.courses.edit', $course) }}">{{ $course->title }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $plan->title  }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="card bg-primary teacher_style">
                        <div class="card-body py-4">
                            <div class="d-flex align-items-start">
                                <div class="icon-text">
                                    <div class="text-white">
                                        {{ $course->title }} - {{ $plan->title  }}
                                    </div>
                                    @if($plan->status == 'draft')
                                        <span class="courseStatus c_release badge-lg badge badge-pill badge-warning text-uppercase">未发布</span>
                                    @elseif($plan->status == 'published')
                                        <span class="courseStatus c_unpublished badge badge-pill badge-warning text-uppercase success">已发布</span>
                                    @else
                                        <span class="courseStatus badge badge-pill badge-warning text-uppercase text-danger">已关闭</span>
                                    @endif
                                    <div class="teacher-information">
                                        教师 : {{ $plan->user->username }}
                                        <a href="{{ route('manage.courses.edit', $course) }}"
                                           class="btn-inner--text btn-icon">
                                            <span class="btn-inner--icon"><i class="iconfont">&#xe644;</i></span>
                                            返回课程编辑</a>
                                        @if ($plan->status == 'published')
                                            <a href="{{ route("plans.intro", [$course, $plan]) }}"
                                               class="btn-inner--text btn-icon">
                                                {{--<span class="btn-inner--icon"><i class="iconfont">&#xe644;</i></span>--}}
                                                预览版本</a>

                                            {{--<form action="{{ route('manage.plans.publish', [$course, $plan]) }}" method="post" style="float:right;">--}}
                                            {{--{{ csrf_field() }}--}}
                                            {{--{{ method_field('patch') }}--}}
                                            {{--<input type="hidden" name="status" value="closed">--}}
                                            {{--<button type="submit" class="btn btn-secondary btn-icon">--}}
                                            {{--关闭版本--}}
                                            {{--</button>--}}
                                            {{--</form>--}}
                                            {{--<span class="btn-inner--icon"><i class="iconfont">&#xe644;</i></span>--}}
                                            <a href="javascript:;" class="btn-inner--text plan-publish btn-icon"
                                               data-status="{{ $plan->status == 'published' ? 'closed' : 'published' }}"
                                               data-url="{{ route('manage.plans.publish', [$course, $plan]) }} "
                                            >关闭版本</a>
                                        @else
                                            {{--<span class="btn-inner--icon"><i class="iconfont">&#xe644;</i></span>--}}
                                            <a href="javascript:;" class="btn-inner--text plan-publish btn-icon"
                                               data-status="{{ $plan->status == 'published' ? 'closed' : 'published' }}"
                                               data-url="{{ route('manage.plans.publish', [$course, $plan]) }} "
                                            >发布版本</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    /**
     * 发布版本
     */
    $('.plan-publish').click(function () {
        var url = $(this).data('url');

        if (!url) { edu.alert('danger', '请选择版本'); return false;}
        var status = $(this).data('status');

        if (!status) { edu.alert('danger', '请选择版本'); return false;}

        $.ajax({
            'url': url,
            'type': 'patch',
            'data': {status: status},
            'success': function(res) {
                if (res.status == '200') {
                    edu.alert('success', '操作成功');
                    window.location.reload();
                } else {
                    edu.alert('danger', res.message);
                }
            }
        });

    });
</script>