@extends('frontend.default.plan.show')
@section('title', '版本学习-话题')
@section('leftBody')
    <div class="tab-pane" role="tabpanel">
        <div class="row">
            <!-- Grid column -->
            <div class="col-md-12">
                <!-- Newsfeed -->
                <div class="mdb-feed">
                    <!-- Fourth news -->
                    @if(count($topics))
                    @foreach($topics as $topic)
                        <div class="news">
                            <div class="label">
                                <img src="{{ render_cover($topic['user']['avatar'], 'avatar') }}"
                                     class="rounded-circle z-depth-1-half">
                            </div>
                            <!-- Excerpt -->
                            <div class="excerpt">
                                <!-- Brief -->
                                <div class="brief">
                                    <a class="name">{{ $topic['user']['username'] }}</a>&nbsp;发起了{{ $topic['type']== 'discussion' ? '话题' :'问答' }}
                                    <div class="date">{{ $topic['created_at']->diffForHumans() }}</div>
                                </div>
                                <!-- Added text -->
                                <div class="added-text">
                                    {{ $topic['content'] }}
                                </div>
                                <!-- Feed footer -->
                                <div class="feed-footer">
                                    <a class="">
                                        <i class="fas fa-comment"></i>
                                        <span>{{ $topic['replies_count'] }} 回复</span>
                                    </a>  &nbsp;&nbsp;
                                    <a class="">
                                        <i class="far fa-eye"></i>
                                        <span>{{ $topic['hit_count'] }} 查看</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                        @else
                        暂无话题
                    @endif

                    </div>
                <!-- Newsfeed -->

                <nav aria-label="Page navigation example">
                    {!! $topics->render() !!}
                </nav>
            </div>
            <!-- Grid column -->
        </div>
    </div>
@endsection