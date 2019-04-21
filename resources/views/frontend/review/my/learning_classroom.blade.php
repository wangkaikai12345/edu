<div class="tab-pane active folder" id="learning-course" role="tabpanel" aria-labelledby="follow-tab-classic">
    <!--Accordion wrapper-->
    <div class="accordion md-accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
        <div class="container">
            <div class="item_content row">

                @if (if_query('rel', 'classroom'))
                    @forelse($user['rel'] ?? [] as $member )
                        <div class="col-xl-3 col-md-6 col-sm-6">
                            <a href="{{ route('classrooms.plans', $member->classroom) }}">
                                <div class="item">
                                    <div class="cover">
                                        <img src="{{ render_cover($member->classroom->cover, 'classroom') }}" alt="">
                                        <div class="right_tips">
                                            <div class="update">
                                                更新中
                                            </div>
                                        </div>
                                    </div>
                                    <h2 class="course_title">
                                        {{ $member->classroom->title }}
                                    </h2>
                                    <div class="peoples">
                                        <i class="iconfont ml-0" style="font-size:15px;">
                                            &#xe661;
                                        </i>
                                        <span>{{ $member->classroom->courses_count }} </span>
                                        <i class="iconfont" style="font-size:17px;">
                                            &#xe64d;
                                        </i>
                                        <span> {{ $member->classroom->members_count }}</span>
                                    </div>
                                    <div class="price">
                                        价格：
                                        <span class="text-danger">
                                        {{ $member->classroom->coin_price ? $member->classroom->coin_price.'虚拟币': ftoy($member->classroom->price).'元' }}
                                    </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        {{-- 暂无班级 --}}
                        <div class="no_course">暂无班级...</div>
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