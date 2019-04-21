@extends('frontend.default.plan.show')

@section('title', '版本学习-评价')

@section('leftBody')
    <div class="tab-pane"  role="tabpanel">
        <div class="row">
            <div class="col-md-12">
                @if(!$control)
                    <form action="{{ route('plans.store.review', [$course, $plan]) }}" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="star mb-4" style="overflow: hidden;">
                            <span>请评分：</span>
                            <div id="star" data-score="{{ old('rating', $current ? $current['rating'] : 0) }}"></div>
                        </div>
                        <div class="form-group shadow-textarea green-border-focus">
                        <textarea class="form-control z-depth-1"
                                  rows="3"
                                  name="content"
                                  placeholder="请输入您的评价...">{{ old('content', $current ? $current['content'] : '' ) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-rounded mb-5 btn-sm">{{ $current ? '继续评价' : '我要评价'}}</button>
                    </form>
                @endif

                <div class="mdb-feed">

                    @foreach($reviews as $review)
                        <!-- Fourth news -->
                            <div class="news">
                                <!-- Label -->
                                <div class="label">
                                    <img src="{{ render_cover($review['user']['avatar'], 'avatar') }}"
                                         class="rounded-circle z-depth-1-half">
                                </div>
                                <!-- Excerpt -->
                                <div class="excerpt">
                                    <!-- Brief -->
                                    <div class="brief">
                                        <a class="name float-left mr-2">{{ $review['user']['username'] }}</a>&nbsp;
                                        <ul class="rating float-left">
                                            @for ($i = 0; $i < intval($review['rating']); $i++)
                                                <li>
                                                    <i class="fa fa-star"></i>
                                                </li>
                                            @endfor
                                        </ul>
                                        <div class="date">{{ $review['created_at']->diffForHumans() }}</div>
                                    </div>
                                    <!-- Added text -->
                                    <div class="added-text">
                                        {{ $review['content'] }}
                                    </div>
                                </div>
                                <!-- Excerpt -->
                            </div>
                            <!-- Fourth news -->
                    @endforeach

                    <nav aria-label="Page navigation example">
                        {!! $reviews->render() !!}
                    </nav>
                </div>
                <!-- Newsfeed -->
            </div>
        </div>
    </div>
@endsection