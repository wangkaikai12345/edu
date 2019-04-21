@extends('frontend.default.notice.index')
@section('title', '我的通知')

@section('partStyle')
    <link href="{{ asset('dist/notice/css/index.css') }}" rel="stylesheet">
@endsection

@section('rightBody')
    <div class="col-xl-9">
        <div class="card">
            <div class="card-body">
                <!--Title-->
                <h6 class="card-title">我的通知
                    <form action="{{ route('users.notification.read_all') }}" method="post" style="float: right;margin-top: -15px;">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-sm btn-primary float-right font-small" style="padding: 0.5rem 1rem;">全部标为已读</button>
                    </form>

                </h6>
                <hr>
                @foreach($notify as $no)
                    <div class="row mb-3 mt-3">
                        <div class="col-xl-10 col-md-10 pl-4">
                            <img src="/dist/notice/images/notice.svg" alt="" class="notice-icon float-left mt-1">
                            <span class="is_read">{{ $no['read_at'] ? '已读' :'未读' }}</span>
                            <h6 class="float-left ml-3 notice-truncate">
                                <a href="javascript:;" class="font-small notice-info" data-route="{{ route('users.notification.show', $no['id']) }}">
                                {{ $no['data']['title'] }}
                                </a>
                                <span class="time font-small pt-2">
                                    {{ $no['created_at'] }}
                                </span>
                            </h6>
                        </div>
                        <div class="col-xl-2 col-md-2">
                            <form action="{{ route('users.notification.destroy', $no) }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button href="" type="submit" class="btn btn-sm btn-outline-danger btn-rounded waves-effect text-danger mt-0 delete">删除</button>
                            </form>

                        </div>
                    </div>
                @endforeach

                <nav aria-label="Page navigation example">
                    {!! $notify->render() !!}
                </nav>

            </div>
        </div>
    </div>

    <!-- Central Modal Small -->
    <div class="modal fade" id="noticeInfoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <!-- Change class .modal-sm to change the size of the modal -->
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="notice-body">
            </div>
        </div>
    </div>
@endsection

@section('partScript')
    <script type="text/javascript" src="{{ asset('dist/notice/js/index.js') }}"></script>
    <script>
        // 通知详情
        $('.notice-info').click(function(){

            var that = $(this);

            edu.ajax({
                url: $(this).data('route'),
                method:'get',
                callback:function(res){
                    if (res.status == 'html') {
                        $('#notice-body').empty();
                        $('#notice-body').append(res.data);
                        $('#noticeInfoModal').modal('show');

                        that.parents('.row').find('.is_read').html('已读');
                    }
                },
                elm: '.notice-info'
            })
            return false;
        })

        // 删除通知
        $('.delete').click(function(){
            var that = $(this);
            add_modal({
                title: '删除通知',
                content: '您确定要删除这个通知',
                callback: function (res) {
                    if (res.status) {
                        that.parents('form').submit();
                    };
                }
            })
            return false;
        })

    </script>
@endsection