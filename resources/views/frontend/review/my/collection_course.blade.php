<div class="tab-pane active folder" id="collection-course" role="tabpanel" aria-labelledby="follow-tab-classic">
    <!--Accordion wrapper-->
    <div class="accordion md-accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
        <div class="container">
            <div class="item_content row">
                @if (if_query('rel', 'collect'))
                    @forelse($user['rel'] ?? [] as $course )

                        <div class="col-xl-3 col-md-6 col-sm-6">
                            <a href="{{ route('courses.show', $course->model) }}">
                                <div class="item">
                                    <div class="cover">
                                        <img src="{{ render_cover($course['model']['cover'], 'course') }}" alt="">
                                        <div class="right_tips">
                                            <div class="update">
                                                更新中
                                            </div>
                                        </div>
                                    </div>
                                    <h2 class="course_title">
                                        {{ $course['model']['title'] }}
                                    </h2>
                                    <div class="peoples">
                                        <i class="iconfont ml-0">
                                            &#xe64a;
                                        </i>
                                        <span>{{ $course['model']['hit_count']}} </span>
                                        <i class="iconfont">
                                            &#xe64d;
                                        </i>
                                        <span> {{ $course['model']['students_count'] }}</span>
                                    </div>
                                    <div class="price">
                                        价格：
                                        <span class="text-danger">
                                       {{ ftoy($course['model']['min_course_price']).'元+' }}
                                    </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        {{-- 暂无课程 --}}
                        <div class="no_course">暂无课程...</div>
                    @endforelse
                @endif
            </div>
            <nav class="pageNumber" aria-label="Page navigation example">
                {!! $user['rel']->render() !!}
            </nav>
        </div>

    </div>
    <!-- Accordion wrapper -->
</div>
