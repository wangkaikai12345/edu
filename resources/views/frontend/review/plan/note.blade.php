@extends('frontend.review.plan.layout')
@section('leftBody')
    <div class="tab-pane note hide_note" role="tabpanel"
         aria-labelledby="contact-tab-classic">
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
                                <div class="excerpt pl-0 pl-sm-4" style="margin-top: 0;padding-top: 0;">
                                    <!-- Brief -->
                                    <div class="brief">
                                        <a class="name">{{ $note['user']['username'] }}</a>
                                    </div>
                                    <!-- Added text -->
                                    <div class="added-text">
                                        {!! $note['content'] !!}
                                    </div>
                                    <!-- Feed footer -->
                                    <div class="feed-footer">
                                        <span>{{ $note['created_at']->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <span class="no_data">
                            还没有笔记...
                        </span>
                    @endif

                        <nav aria-label="Page navigation example">
                            {!! $notes->render() !!}
                        </nav>
                </div>
            </div>
        </div>
    </div>
@endsection







