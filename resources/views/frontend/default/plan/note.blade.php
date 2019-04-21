@extends('frontend.default.plan.show')
@section('title', '版本学习-笔记')
@section('leftBody')
    <div class="tab-pane"  role="tabpanel">
        <div class="row">

            <!-- Grid column -->
            <div class="col-md-12">

                <!-- Newsfeed -->
                <div class="mdb-feed">
                    @if (count($notes))
                    @foreach($notes as $note)
                        <div class="news">
                            <!-- Label -->
                            <div class="label">
                                <img src="{{ render_cover($note['user']['avatar'], 'avatar') }}"
                                     class="rounded-circle z-depth-1-half">
                            </div>
                            <!-- Excerpt -->
                            <div class="excerpt">
                                <!-- Brief -->
                                <div class="brief">
                                    <a class="name">{{ $note['user']['username'] }}</a>&nbsp; 添加了笔记
                                    <div class="date">{{ $note['created_at']->diffForHumans() }}</div>
                                </div>
                                <!-- Added text -->
                                <div class="added-text">
                                    {{ $note['content'] }}
                                </div>
                            </div>
                            <!-- Excerpt -->
                        </div>
                    @endforeach
                        @else
                        暂无笔记
                    @endif
                </div>

                <nav aria-label="Page navigation example">
                    {!! $notes->render() !!}
                </nav>

            </div>
            <!-- Grid column -->
        </div>
    </div>
@endsection







