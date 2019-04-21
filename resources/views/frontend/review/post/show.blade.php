@extends('frontend.review.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/notice/index.css') }}">
@endsection

@section('content')
    <div class="zh_notice">
        <div class="container">
            <h2 class="page_title">
                通知
            </h2>
            <div class="notice_wrap">
                <div class="notice_lists active">
                    <div class="line" style="height: 2px;background: #EEEEEE;"></div>
                    <div class="no_data">
                        <div class="title" style="margin-bottom: 30px;">
                            <h1>{{ $post->title }}</h1>
                        </div>
                        {!! $post->body !!}
                    </div>
                </div>
                <button type="button" onclick="vote()">点赞</button>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function vote() {
            $.ajax({
                url: '{{ route('posts.vote', ['post' => $post->id]) }}',
                type: 'POST',
                dataType: 'JSON',
                data: {_token: "{{csrf_token()}}"},
                success:function (response) {
                    console.log(response)
                }
            });
        }
    </script>
@endsection