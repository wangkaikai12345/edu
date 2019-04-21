@extends('frontend.review.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/notice/index.css') }}">
@endsection

@section('content')
    <div class="zh_notice">
        <div class="container">
            <h2 class="page_title">
                私信
            </h2>
            <div class="notice_wrap">
                <div class="notice_lists message active">
                    <div class="line" style="height: 2px;background: #EEEEEE;"></div>
                    {{--  遍历无数据保留一个line  --}}

                @if ($conversations->count())
                    @foreach($conversations as $conversation)
                        <a href="{{ route('users.message.show', $conversation) }}">
                            <div class="notice_item">
                                <img src="{{ render_cover($conversation->another->avatar, 'avatar') }}" alt="" data-toggle="popover" data-content='<div class="popover_card">
                                                <div class="teacher_header">
                                                    <img src="{{ render_cover($conversation->another->avatar, 'avatar') }}" alt="" class="teacher_avatar">
                                                    <div class="teacher_info">
                                                        <span class="teacher_name">{{ $conversation->another->username }}</span>
                                                        <span class="teacher_job">特约教师</span>
                                                    </div>
                                                </div>
                                                <div class="teacher_fans">
                                                    <div class="fans_item">
                                                        <span>0</span>
                                                        <span class="fans_title">
                                                            在学
                                                        </span>
                                                    </div>
                                                    <div class="fans_item">
                                                        <span>2</span>
                                                        <span class="fans_title">
                                                            关注
                                                        </span>
                                                    </div>
                                                    <div class="fans_item mr-0">
                                                        <span>120</span>
                                                        <span class="fans_title">
                                                            粉丝
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="teacher_controls">
                                                    <a href="" class="btn btn-primary btn-sm btn-circle float-left">关注</a>
                                                    <a href="" class="btn btn-primary btn-sm btn-circle float-right" data-toggle="modal" data-target="#modal_6">私信</a>
                                                </div>
                                            </div>' data-html="true" data-trigger="hover click" data-placement="auto">
                                <div class="notice_content">
                                    <div class="notice_name">
                                        @if(auth('web')->id() == $conversation->last_message->sender_id)
                                            <span class="float-left">发给{{ $conversation->last_message->recipient->username }}：</span>
                                        @else
                                            <span class="float-left">{{ $conversation->last_message->sender->username }}：</span>
                                        @endif

                                        <span class="float-left message_content">{{ $conversation->last_message->body }}</span>
                                    </div>
                                    <div class="notice_p">
                                       {{ $conversation->last_message->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="message_num">
                                    共{{ $conversation->messages->count() }}条
                                </div>
                                {{--<a href="" class="message_del">删除</a>--}}
                            </div>
                            <div class="line"></div>
                        </a>
                    @endforeach
                @else
                    <div class="no_data">
                        ~&nbsp;空空如也&nbsp;~
                    </div>
                @endif
                </div>
            </div>
            <nav class="pageNumber" aria-label="Page navigation example">
                {!! $conversations->render() !!}
            </nav>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{  mix('/js/front/notice/index.js')  }}"></script>
@endsection