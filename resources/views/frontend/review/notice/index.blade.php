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

                    {{--  遍历无数据保留一个line  --}}
            @if ($notify->count())
                @foreach($notify as $notice)
                    <div class="notice_item">
                        <i class="iconfont float-left" style="line-height: 20px;font-size: 20px;margin-right: 10px;">
                            &#xe646;
                        </i>
                        <div class="notice_content">
                            <div class="notice_name">
                                系统通知：{{ $notice['data']['title'] }}
                            </div>
                            <div class="notice_p">
                                {{ $notice['created_at'] }}
                            </div>
                        </div>
                        <span class="is_read">{{ $notice['read_at'] ? '已读' :'未读' }}</span>
                        <a data-toggle="modal" href="javascript:;" class="notice_info"
                                data-target="#modal" data-url="{{ route('users.notification.show', $notice) }}">查看</a>
                    </div>
                    <div class="line"></div>
                @endforeach
            @else
                    <div class="no_data">
                        ~&nbsp;空空如也&nbsp;~
                    </div>
            @endif
                </div>
            </div>
            <nav class="pageNumber" aria-label="Page navigation example">
                {!! $notify->render() !!}
            </nav>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{  mix('/js/front/notice/index.js')  }}"></script>
    <script>

        $('.notice_info').click(function(){
            $(this).parents('.notice_item').find('.is_read').html('已读');
        })

    </script>
@endsection