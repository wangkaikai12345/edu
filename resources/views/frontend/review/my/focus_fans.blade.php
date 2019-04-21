
{{-- 关注 --}}
@if (if_query('rel', 'follows'))

    <div class="tab-pane active note" id="focus-fans" role="tabpanel"
         aria-labelledby="awesome-tab-classic">

        <div class="accordion md-accordion" id="accordionEx" role="tablist"
             aria-multiselectable="true">
            <div class="container">
                <div class="item_content row">

                    @forelse($user['rel'] ?? [] as $value)
                        <div class="col-xs-1-5 col-sm-1-5 col-md-1-5 col-lg-1-5">
                        <div class="item second">
                            <div class="cover second">
                                <img src="{{ render_cover($value['follow']['avatar'], 'avatar') }}" alt="">
                            </div>
                            <h2 class="teacher_name">
                                {{ $value['follow']['username'] }}
                            </h2>
                            <div class="price second">
                                关注&nbsp;{{ $value['follow']['followers_count'] }}
                                <span>粉丝&nbsp;{{ $value['follow']['fans_count'] }}</span>
                            </div>
                            <hr style="margin-top:10px;margin-bottom:22px;">
                            <div class="teacher_autograph">
                                {{ $value['follow']['signature'] }}
                            </div>
                            <!-- 移入要显示的按钮 -->
                            <div class="operation_btn">

                                @if (auth('web')->id())
                                    @if (auth('web')->id() != $value['follow']['id'])
                                        <button class="btn btn-outline-primary btn-sm follow" data-auth="{{ auth('web')->id() }}" data-follow="{{ $value['follow']['id'] }}">
                                            @if (in_array($value['follow']['id'], $user['follows']))
                                                取消关注
                                            @else
                                                关注
                                            @endif
                                        </button>
                                    @endif
                                @else
                                    <a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}">关注</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                        {{-- 暂无关注 --}}
                        <div class="no_course">暂无关注...</div>
                    @endforelse
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <nav aria-label="Page navigation example">
                        {!! $user['rel']->render() !!}
                    </nav>
                </div>
            </div>

        </div>
    </div>

@endif

{{-- 粉丝 --}}
@if (if_query('rel', 'fans'))

    <div class="tab-pane active note" id="focus-fans" role="tabpanel"
         aria-labelledby="awesome-tab-classic">

        <div class="accordion md-accordion" id="accordionEx" role="tablist"
             aria-multiselectable="true">
            <div class="container">
                <div class="item_content row">
                    @forelse($user['rel'] ?? [] as $value)
                        <div class="col-xs-1-5 col-sm-1-5 col-md-1-5 col-lg-1-5">
                            <div class="item second">
                                <div class="cover second">
                                    <img src="{{ render_cover($value['user']['avatar'], 'avatar') }}" alt="">
                                </div>
                                <h2 class="teacher_name">
                                    {{ $value['user']['username'] }}
                                </h2>
                                <div class="price second">
                                    关注&nbsp;{{ $value['user']['followers_count'] }}
                                    <span>粉丝&nbsp;{{ $value['user']['fans_count'] }}</span>
                                </div>
                                <hr style="margin-top:10px;margin-bottom:22px;">
                                <div class="teacher_autograph">
                                    {{ $value['user']['signature'] }}
                                </div>
                                <!-- 移入要显示的按钮 -->
                                <div class="operation_btn">

                                    @if (auth('web')->id())
                                        @if (auth('web')->id() != $value['user']['id'])
                                            <button class="btn btn-outline-primary btn-sm follow" data-auth="{{ auth('web')->id() }}" data-follow="{{ $value['user']['id'] }}">
                                                @if (in_array($value['user']['id'], $user['follows']))
                                                    取消关注
                                                @else
                                                    关注
                                                @endif
                                            </button>
                                        @endif
                                    @else
                                        <a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}">关注</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        {{-- 暂无粉丝 --}}
                        <div class="no_course">暂无粉丝...</div>
                    @endforelse
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <nav aria-label="Page navigation example">
                        {!! $user['rel']->render() !!}
                    </nav>
                </div>
            </div>

        </div>
    </div>

@endif





