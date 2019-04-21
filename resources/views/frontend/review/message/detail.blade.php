@extends('frontend.review.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/notice/index.css') }}">
@endsection

@section('content')
    <div class="zh_notice">
        <div class="container">
            {{--{{ dd($conversation) }}--}}
            <h2 class="page_title">
                与 {{ $conversation->another->username }} 的对话 <a style="float:right" href="{{ route('users.message') }}">返回私信列表</a>
            </h2>
            <div class="line" style="height: 2px;background: #EEEEEE;"></div>
            <div class="message_details col-xl-8 col-md-10">
                <div class="reply_message">
                    <form action="{{ route('users.message.store') }}" class="" method="post">
                        {{ csrf_field() }}
                        <textarea name="message" cols="30" rows="10" placeholder="请输入私信内容"></textarea>
                        <input type="hidden" name="user_id" value="{{ $conversation->another->id }}">
                        <button class="btn btn-sm btn-primary btn-circle float-right" type="submit">发送</button>
                    </form>
                </div>
                <div class="message_lists">
                    @if ($conversation->messages->count())

                        @foreach($conversation->messages->sortByDesc('created_at') as $message)
                            @if ($message->sender_id == auth('web')->id())
                                <div class="side_item me">
                                    <img src="{{ render_cover($message->sender->avatar, 'avatar') }}" alt="" class="user_avatar">
                                    <div class="item_wrap">
                                        <div class="message_info">
                                    <span class="message_sender">
                                        {{ $message->sender->username }}：
                                    </span>
                                            <span class="message_time">
                                        {{ $message->created_at->diffForHumans() }}
                                    </span>
                                        </div>
                                        <div class="message_content">
                                            {{ $message->body }}
                                            {{--<a href="" class="message_del">删除</a>--}}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="side_item">
                                    <img src="{{ render_cover($message->sender->avatar, 'avatar') }}" alt="" class="user_avatar">
                                    <div class="item_wrap">
                                        <div class="message_info">
                                    <span class="message_sender">
                                        {{ $message->sender->username }}：
                                    </span>
                                            <span class="message_time">
                                        {{ $message->created_at->diffForHumans() }}
                                    </span>
                                        </div>
                                        <div class="message_content">
                                            {{ $message->body }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script src="{{  mix('/js/front/notice/index.js')  }}"></script>
@endsection