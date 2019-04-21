@extends('frontend.review.plan.layout')

@section('leftBody')
    <div class="tab-pane fade huati evaluation active show" id="awesome-classic" role="tabpanel"
         aria-labelledby="awesome-tab-classic">
        <div class="col-md-12">
            <!-- Newsfeed -->
            <div class="huati_wrap">
                @if(!$control)
                    <form action="{{ route('plans.store.review', [$course, $plan]) }}" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="score">
                            <span>请评分:</span><br>
                            <div id="star" data-score="{{ old('rating', $current ? $current['rating'] : 0) }}"></div>
                            <br>
                        </div>
                        <textarea class="form-control z-depth-1"
                                  rows="3"
                                  name="content"
                                  placeholder="请输入您的评价...">{{ old('content', $current ? $current['content'] : '' ) }}</textarea>
                        <br>

                        <button type="submit" class="btn btn-primary btn-circle btn-sm float-right">{{ $current ? '重新评价' : '我要评价'}}</button>



                        {{--<div class="star mb-4" style="overflow: hidden;">--}}
                            {{--<span>请评分：</span>--}}
                            {{--<div id="star" data-score="{{ old('rating', $current ? $current['rating'] : 0) }}"></div>--}}
                        {{--</div>--}}
                        {{--<div class="form-group shadow-textarea green-border-focus">--}}
                        {{--<textarea class="form-control z-depth-1"--}}
                                  {{--rows="3"--}}
                                  {{--name="content"--}}
                                  {{--placeholder="请输入您的评价...">{{ old('content', $current ? $current['content'] : '' ) }}</textarea>--}}
                        {{--</div>--}}
                        {{--<button type="submit" class="btn btn-primary btn-rounded mb-5 btn-sm">{{ $current ? '继续评价' : '我要评价'}}</button>--}}
                    </form>
                @endif


                <div class="huati_list">
                    @if ($reviews->count())
                        @foreach($reviews as $review)
                            <div class="huati_item">
                                <img src="{{ render_cover($review['user']['avatar'], 'avatar') }}"
                                     alt="" class="huati_avatar">
                                <div class="huati_info ml-0 ml-sm-4">
                                    <a href="" class="huati_username">
                                        {{ $review['user']['username'] }}
                                    </a>
                                    {{--<span class="plan_name">--}}
                                    {{--教学版本--}}
                                    {{--</span>--}}
                                    {{--<span class="plan_progress">--}}
                                    {{--完成进度:0/4--}}
                                    {{--</span>--}}
                                    <div class="huati_star">
                                        <div class="star">
                                            @for ($i = 0; $i < 5; $i++)

                                                @if ($i < intval($review['rating']))
                                                    <i class="iconfont">
                                                        &#xe601;
                                                    </i>
                                                @else
                                                    <i class="iconfont">
                                                        &#xe60d;
                                                    </i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="star_time">
                                            {{ $review['created_at']->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="huati_content">
                                        {{ $review['content'] }}
                                    </p>
                                </div>

                            </div>
                        @endforeach
                        <nav aria-label="Page navigation example">
                            {!! $reviews->render() !!}
                        </nav>
                        @else
                            <span class="no_data">
                                还没有人评价...
                            </span>
                    @endif
                </div>

            </div>

        </div>
    </div>
@endsection

@section('partScript')
    <script src="{{ '/vendor/raty/jquery.raty.min.js' }}"></script>
    <script>

        $('#star').raty({
            starOff: '/imgs/star-off.svg',
            starOn: '/imgs/star-on.svg',
            size: 30,
            score: function() {
                return $(this).attr('data-score');
            },
            click: function(score, evt) {
                console.log('ID: ' + $(this).attr('id') + "\nscore: " + score + "\nevent: " + evt.type);
            }
        });

    </script>
@endsection