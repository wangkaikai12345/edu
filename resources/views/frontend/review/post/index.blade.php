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
                    @forelse($posts as $post)
                        <div class="notice_item">
                            <i class="iconfont float-left"
                               style="line-height: 20px;font-size: 20px;margin-right: 10px;">
                            </i>
                            <div class="notice_content">
                                <div class="notice_ava">
                                    <img src=" {{ render_cover($post->user->avatar, 'avatar') ?? '/imgs/avatar.png' }}">
                                </div>
                                <div class="notice_name" style="display: contents">
                                    {{ $post->title }}
                                </div>
                                <div class="notice_p" style="float: none;width: 50%">
                                    {{ $post->category->name }}&nbsp;|&nbsp;发布于{{ $post->created_at->diffForHumans() }}
                                </div>
                                <div style="float: right;margin-top: -37px;">
                                    {{$post->view_count}}&nbsp;|&nbsp;{{$post->vote_count}}
                                </div>
                            </div>
                        </div>
                        <div class="line"></div>
                    @empty
                        <div class="no_data">
                            ~&nbsp;空空如也&nbsp;~
                        </div>
                    @endforelse
                </div>
            </div>
            <nav class="pageNumber" aria-label="Page navigation example">
                {!! $posts->render() !!}
            </nav>
        </div>
    </div>
@endsection

@section('script')

@endsection