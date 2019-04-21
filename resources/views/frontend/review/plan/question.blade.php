@extends('frontend.review.plan.layout')

@section('leftBody')
    <div class="tab-pane fade huati evaluation active show" id="huati-classic"
         role="tabpanel"
         aria-labelledby="huati-tab-classic">
        <div class="col-md-12">
            <div class="huati_wrap">
                <a href=""
                   class="btn btn-primary btn-circle btn-sm float-right"
                   id="create_huati">
                    提问题
                </a>
                <div class="huati_list">
                    @if($topics->count())
                        @foreach($topics as $topic)
                            <div class="huati_item">
                                <img src="{{ render_cover($topic['user']['avatar'], 'avatar') }}"
                                     alt="" class="huati_avatar" >
                                <div class="huati_info ml-0 ml-sm-4">
                                    <div class="huati_title">
                                        <i class="iconfont">
                                            &#xe659;
                                        </i>
                                        {!! $topic['content'] !!}
                                    </div>

                                    <div class="huati_user">
                              <span class="user_name">
                                  {{ $topic['user']['username'] }}
                              </span>
                                        <span class="huati_time">
                                  提问与：{{ $topic['created_at']->diffForHumans() }}
                              </span>
                                    </div>
                                </div>
                                <div class="watch_num">
                                    <div class="watch_item">
                                        <i class="iconfont">
                                            &#xe62a;
                                        </i>
                                        <span>
                                 {{ $topic['hit_count'] }}
                              </span>
                                    </div>
                                    <div class="watch_item reply_control" data-route="{{ route('plans.topics.reply', [$plan, $topic]) }}" >
                                        <i class="iconfont reply">
                                            &#xe64a;
                                        </i>
                                        <span>
                                  {{ $topic['replies_count'] }}
                              </span>
                                    </div>
                                </div>
                                <div class="reply_wrap">

                                    <input type="text" class="content create_reply" placeholder="请输入回答内容">
                                    <button data-route="{{ route('plans.store.reply', [$plan, $topic]) }}"
                                            class="btn btn-primary btn-sm btn-circle send_reply float-right">回答</button>
                                    <br><br><br><br>
                                    <div class="reply_list">
                                        {{--<div class="reply_item">--}}
                                        {{--<img src="http://cdn.ydma.cn/image/2018-12-17-a0oTbKZP" alt=""--}}
                                        {{--class="reply_avatar">--}}
                                        {{--<div class="reply_info">--}}
                                        {{--<a href="" class="reply_username">--}}
                                        {{--阿萨德吧--}}
                                        {{--</a>--}}
                                        {{--<p class="reply_content">--}}
                                        {{--阿湿波哈街吧市场不能玻尿酸大V 时代cvahbvhja dns dvhbdvjk dj kjSAD VAdsva超大v安居客吧本命年爱好不清楚凤凰网被告一千万个--}}
                                        {{--</p>--}}
                                        {{--<span class="reply_time">--}}
                                        {{--2018-08-16 14:53:17--}}
                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--<div class="reply_controls">--}}
                                        {{--<i class="iconfont">--}}
                                        {{--&#xe62b;--}}
                                        {{--</i>--}}
                                        {{--<ul>--}}
                                        {{--<li>--}}
                                        {{--<a href="" class="reply_del">删除</a>--}}
                                        {{--</li>--}}
                                        {{--</ul>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                    </div>
                                </div>
                            </div>

                        @endforeach
                        <nav aria-label="Page navigation example">
                            {!! $topics->render() !!}
                        </nav>
                    @else
                        <span class="no_data">
                            还没有问题...
                        </span>
                    @endif
                </div>
                <div class="create_huati">
                    <form action="{{ route('plans.store.topic', [$course, $plan]) }}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="type" value="question">
                        <input type="text" class="huati_title" name="title"
                               placeholder="请输入问题标题">
                        <div style="height: 320px;overflow: hidden;">
                            <script id="editor" name="content" type="text/plain"></script>
                        </div>
                        {{--<button type="button" class="btn btn-primary btn-sm btn-circle upload_files" data-toggle="modal" data-target="#modal_7">上传附件</button>--}}
                        <br>
                        <input type="submit" value="提问" class="btn btn-sm btn-primary btn-circle">
                        <a href="javascript:;" class="close_create">取消</a>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('partScript')
    <script src="{{ '/vendor/ueditor/ueditor.config.js' }}"></script>
    <script src="{{ '/vendor/ueditor/ueditor.all.js' }}"></script>
    <script>
        $('#create_huati').on({
            click: function () {
                $('.create_huati,.huati_wrap').toggleClass('active');
                setTimeout(() => {
                    $('.huati_list').css('opacity', 0);
            }, 200);
                return false;
            }
        });

        $('.close_create').on({
            click: function () {
                $('.create_huati,.huati_wrap').toggleClass('active');
                $('.huati_list').css('opacity', 1);
                return false;
            }
        });
        $('[data-toggle="popover"]').popover({}).on('shown.bs.popover', function (event) {
            var that = this;
            $('body').find('div.popover').on('mouseenter', function () {
                $(that).attr('in', true);
            }).on('mouseleave', function () {
                $(that).removeAttr('in');
                $(that).popover('hide');
            });
        }).on('hide.bs.popover', function (event) {
            if ($(this).attr('in')) {
                event.preventDefault();
            }
        });
        var ue = UE.getEditor('editor', {
            UEDITOR_HOME_URL: '/vendor/ueditor/',
            serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
        });

        // 回复话题下拉按钮
        $('.reply_control').on({
            click: function () {
                var _this = $(this);
                // 如果不是隐藏的
                if ($(this).parents('.huati_item').find('.reply_wrap').is(":hidden")) {
                    $.ajax({
                        url: $(this).data('route'),
                        method:'get',
                        data:{},
                        success:function(res){
                            if (res.status == '200') {
                                if (res.data.length) {
                                    _this.parents('.huati_item').find('.reply_wrap').find('.reply_list').empty();
                                    res.data.map(function(item){

                                        _this.parents('.huati_item').find('.reply_list').append(
                                            `<div class="reply_item">
                                     <img src=${item.user.avatar ? item.user.avatar : '/imgs/avatar.png'} alt=""
                                          class="reply_avatar">
                                     <div class="reply_info">
                                         <a href="javascript:;" class="reply_username">
                                             ${item.user.username}
                                         </a>
                                         <p class="reply_content">
                                             ${item.content}
                                         </p>
                                         <span class="reply_time">${item.created_at}</span>
                                     </div>

                                 </div>`
                                        );
                                    })

                                }
                                // 隐藏展开
                                _this.parents('.huati_item').find('.reply_wrap').stop(true).slideToggle(300);
                            }
                        }
                    })
                } else {
                    // 隐藏展开
                    $(this).parents('.huati_item').find('.reply_wrap').stop(true).slideToggle(300);
                }

            }
        });

        // 话题下发表回复
        $('.send_reply').click(function(){

            var content = $(this).parents('.reply_wrap').find('.content').val();
            var _this = $(this);

            $.ajax({
                url: $(this).data('route'),
                type:'post',
                data:{
                    content:content,
                },
                success:function(res){

                    if (res.status == '200' && res.data) {
                        edu.alert('success','回复成功');
                        _this.parents('.reply_wrap').find('.reply_list').prepend(
                            `<div class="reply_item">
                                     <img src=${res.data.user.avatar ? res.data.user.avatar : '/imgs/avatar.png'} alt=""
                                          class="reply_avatar">
                                     <div class="reply_info">
                                         <a href="javascript:;" class="reply_username">
                                             ${res.data.user.username}
                                         </a>
                                         <p class="reply_content">
                                             ${res.data.content}
                                         </p>
                                         <span class="reply_time">${res.data.created_at}</span>
                                     </div>

                                 </div>`
                        )
                    }

                }
            })
        })

    </script>
@endsection







