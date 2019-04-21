<div class="tab-pane active folder" id="online-course" role="tabpanel" aria-labelledby="follow-tab-classic">
    <!--Accordion wrapper-->
    <div class="accordion md-accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
        <div class="container">
            <div class="item_content row">
                @if (if_query('rel', 'teaching'))
                    @forelse($user['rel'] ?? [] as $course )
                        <div class="col-xl-3 col-md-6 col-sm-6">
                            <a href="{{ route('plans.intro', [$course->course, $course->plan]) }}">
                                <div class="item">
                                    <div class="cover">
                                        <img src="{{ render_cover($course['course']['cover'], 'course') }}" alt="">
                                        <div class="right_tips">
                                            <div class="update">
                                                @if($course->plan->status == 'published')
                                                    已完结
                                                @else
                                                    更新中
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <h2 class="course_title">
                                        {{ $course['course']['title'].'-'.$course['plan']['title'] }}
                                    </h2>
                                    <div class="peoples">
                                        <i class="iconfont ml-0">
                                            &#xe64a;
                                        </i>
                                        <span>{{ $course['plan']['topics_count']}} </span>
                                        <i class="iconfont">
                                            &#xe64d;
                                        </i>
                                        <span> {{ $course['plan']['students_count'] }}</span>
                                    </div>
                                    <div class="price">
                                        价格：
                                        <span class="text-danger">
                                        {{ $course['plan']['coin_price'] ? $course['plan']['coin_price'].'虚拟币': ftoy($course['plan']['price']).'元' }}
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