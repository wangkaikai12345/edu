{{--{{ dd($task) }}--}}
@extends('frontend.review.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/article/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/plan/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/front/classroom/index.css') }}">
@endsection

@section('content')
    <div class="zh_course">
        <div class="container">
            {{--面包屑--}}
            <div class="row">
                <div class="col-10">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 pl-0">
                            <li class="breadcrumb-item"><a href="/">首页</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('classrooms.index') }}">班级列表</a></li>
                            <li class="breadcrumb-item active">{{ $classroom->title }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            {{--头部导航--}}
            <div class="course_card student_style">
                <h2 class="course_title">
                    {{ $classroom->title }}
                </h2>
                <div class="row">
                    <div class="col-xl-3 pr-0">
                        <div class="course-progress m-auto">
                            {{--{{  }}--}}
                            <div class="cricle-progress" id="freeprogress"
                                 data-percent="{{  $already ? (ytof($already/$total)) : 0 }}">
                                <span class="percent"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 pl-xl-5 p-0">
                        <div class="p_item p_first_item">
                            <div class="p_mb">
                                {{ $already }} / {{ $total }}
                                <span>个</span>
                            </div>
                            <div class="p_title">
                                <i class="iconfont">
                                    
                                </i>
                                <span>
                                    已完成关
                                </span>
                            </div>
                        </div>
                        <div class="p_item">
                            <div class="p_mb">
                                {{ $same }}
                                <span>
                                    人
                                </span>
                            </div>
                            <div class="p_title">
                                <i class="iconfont">
                                    &#xe60a;
                                </i>
                                <span>
                                    相同进度人数
                                </span>
                            </div>
                        </div>
                        <div class="p_item mr-0 border-right-0">
                            <div class="p_mb">
                                {{ $pre ? $pre->score : '0' }}
                                <span>分</span>
                                <i class="iconfont" style="color: #2F8C0D;font-size: 22px;">
                                    &#xe603;
                                </i>
                            </div>
                            <div class="p_title">
                                <i class="iconfont">
                                    &#xe60b;
                                </i>
                                <span>
                                    成长
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 btn-group btn-group-justified p-0 shopping">
                        <a class="btn btn-primary btn-sm btn-circle m-xl-auto"
                           href="{{ $current ? route('tasks.show', [$current, 'cid' => $classroom->id]) : 'javascript:;' }}">继续学习</a>
                    </div>
                </div>
            </div>
            <div class="course-tabs">
                <div class="row">
                    <div class="col-xl-9 mb-xl-30">
                        @if ($chapters->count())

                            @foreach($chapters as $chapter)
                                <div class="card card_shadow student_style active">
                                    <div class="card-body card-body-cascade">
                                        <div id="collapseOne{{ $loop->iteration }}" class="sub_title collapse show"
                                             role="tabpanel"
                                             aria-labelledby="headingOne{{ $loop->iteration }}"
                                             data-parent="#accordionEx" style="">
                                            <div class="card-body section-wrap p-0 phase">
                                                <a data-toggle="collapse"
                                                   data-parent="#collapseOne{{ $loop->iteration }}"
                                                   href="#dieOne{{ $loop->iteration }}"
                                                   aria-expanded="false" aria-controls="dieOne{{ $loop->iteration }}"
                                                   class="phase_item collapsed">
                                            <span class="phase_num">
                                                阶段{{ $loop->iteration }}
                                            </span>
                                                    <span class="phase_description">
                                                {{ $chapter->title }} {{ $chapter->goals }}
                                            </span>
                                                    <i class="iconfont float-right phase_arrow">
                                                        &#xe604;
                                                    </i>
                                                    @if ($chapter->lock)
                                                        <i class="iconfont float-right">
                                                            &#xe645;
                                                        </i>

                                                    @else
                                                        <i class="iconfont float-right">
                                                            &#xe602;
                                                        </i>
                                                    @endif

                                                </a>
                                                <div id="{{ $chapter->lock ? '' : 'dieOne'.$loop->iteration }}" class="sub_title collapse row {{ $chapter->lock ? '' : 'show' }}" role="tabpanel"

                                                     aria-labelledby="headingOne1" data-parent="#accordionEx" style="">
                                                    <div class="line"></div>

                                                    {{--   王凯看这里！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！  --}}
                                                    {{--   王凯看这里！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！  --}}
                                                    {{--   王凯看这里！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！  --}}

                                                    {{--   以下遍历时，若此日满分 date_item 下 添加 <img src="/imgs/classroom_item_perfect.png" alt="" class="classroom_perfect">  --}}
                                                    {{--   以下遍历时，若此日锁定
                                                    date_item 添加 lock 类，图片路径为 /imgs/classroom/black，
                                                    否则为 /imgs/classroom/white  --}}
                                                    {{--   以下遍历时，若此日未学习 date_item 添加 no_study 类  --}}
                                                    {{--   以下遍历时，实心五角星 &#xe601; 空心五角星 &#xe60d; --}}
                                                    {{--   以下遍历时，作业待提交 wait_submit， 作业待审批 wait_shenpi， 待学习 wait_study， 分 mark， 0-9 eng (zero - nine) --}}
                                                    {{--
                                                    周报成绩 折线图 数据 /js/front/classroom/index.js option.series[0].data
                                                    --}}

                                                    {{--   王凯看这里！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！  --}}
                                                    {{--   王凯看这里！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！  --}}
                                                    {{--   王凯看这里！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！  --}}

                                                    {{--   用完可删  --}}

                                                    @if (!$chapter->lock && $chapter->children->count())
                                                        @foreach($chapter->children as $child)

                                                            <div class="date_item col-xl-3 col-md-4 {{ $child->lock ? 'lock' : ($child->result ? '' :'no_study') }}">
                                                                <a href="{{ $child->lock ? 'javascript:;' : route('tasks.show', [$child, 'cid' => $classroom->id]) }}"
                                                                   style="display: block;width:100%;height: 100%;">
                                                            <span class="date_seq">
                                                                @foreach(str_split($loop->iteration) as $num)
                                                                    <img src="/imgs/classroom/{{ $child->lock ? 'black' : 'white' }}/{{ $num }}@3x.png"
                                                                         alt="" class="one">
                                                                @endforeach
                                                                <img src="/imgs/classroom/{{ $child->lock ? 'black' : 'white' }}/{{ renderSerial($loop->iteration) }}@3x.png"
                                                                     alt="" class="st">
                                                            </span>

                                                                @if($child->lock)
                                                                    <span class="date_time">
                                                                    {{--关的目标：{{ $child->goals }}--}}
                                                                </span>
                                                                        <span class="date_score">
                                                                        <i class="iconfont">
                                                                            &#xe645;
                                                                        </i>
                                                                    </span>

                                                                    @else
                                                                        @if ($child->result)
                                                                            @if ($child->result->status == 'pass')
                                                                                <span class="date_time">
                                                                                {{--通关时长：{{ $child->result->created_at }} - {{ $child->result->updated_at }}--}}
                                                                                通关时长：{{ $child->result->updated_at->diffInMinutes($child->result->created_at, true) }} 分钟

                                                                            </span>
                                                                                <span class="date_score">
                                                                                @foreach(str_split($child->result->score) as $num)
                                                                                        <img src="/imgs/classroom/white/{{ $num }}@3x.png"
                                                                                             alt="" class="one">
                                                                                    @endforeach
                                                                                    <img src="/imgs/classroom/white/mark@3x.png"
                                                                                         alt="" class="mark">
                                                                            </span>


                                                                                <span class="date_star">
                                                                                    @if($child->result->score < 60)
                                                                                        <i class="iconfont">
                                                                                            &#xe601;
                                                                                        </i>
                                                                                    @elseif($child->result->score >= 60 && $child->result->score < 80)
                                                                                        <i class="iconfont">
                                                                                            &#xe601;
                                                                                        </i>
                                                                                        <i class="iconfont">
                                                                                            &#xe601;
                                                                                        </i>
                                                                                    @elseif($child->result->score >= 80 && $child->result->score <= 100)
                                                                                        <i class="iconfont">
                                                                                        &#xe601;
                                                                                        </i>
                                                                                        <i class="iconfont">
                                                                                            &#xe601;
                                                                                        </i>
                                                                                        <i class="iconfont">
                                                                                            &#xe601;
                                                                                        </i>
                                                                                    @endif
                                                                                </span>

                                                                            @if($child->result->score == 100)
                                                                                <img src="/imgs/classroom_item_perfect.png" alt=""
                                                                                     class="classroom_perfect">
                                                                            @endif
                                                                            @elseif($child->result->status == 'approval')
                                                                                <span class="date_time">
                                                                               {{ $child->title }}
                                                                                </span>
                                                                                <span class="date_score">
                                                                                    作业待审批
                                                                                </span>

                                                                            @else
                                                                                <span class="date_time">
                                                                            {{ $child->title }}
                                                                            </span>
                                                                                <span class="date_score">
                                                                                学习中
                                                                            </span>
                                                                            @endif

                                                                    @else
                                                                        <span class="date_time">
                                                                        {{--关的目标：{{ $child->goals }}--}}
                                                                        {{ $child->title }}
                                                                        </span>
                                                                            <span class="date_score">
                                                                            <img src="/imgs/classroom/white/wait_study@3x.png"
                                                                                 alt=""
                                                                                 class="wait_study">
                                                                        </span>
                                                                        @endif

                                                                @endif
                                                                @if($child->lock)
                                                                    <img src="/imgs/classroom/black/goals@3x.png" alt="" class="date_goals">
                                                                    @else
                                                                    <img src="/imgs/classroom/white/goals@3x.png" alt="" class="date_goals">
                                                                    @endif
                                                                <div class="goals_wrap">
                                                                    <ul class="goals_lists">
                                                                        <li>
                                                                            <i class="iconfont">&#xe649;</i>
                                                                            <p>
                                                                                {{ $child->goals }}
                                                                            </p>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </a>
                                                        </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="col-xl-3">
                        <div class="card card_shadow student_style">
                            <div class="card-header">
                                周报
                            </div>
                            <div class="card-body">
                                <div class="card_item_title">
                                    近七关成绩
                                </div>
                                <div class="grade" id="grade" data-score="{{ json_encode($score) }}"></div>
                                <div class="card_item_title">
                                    本周进度排名
                                </div>
                                <div class="ranking_item">
                                    <div class="ranking_title">前三</div>
                                    <div class="ranking_header">

                                        @foreach($ranking as $rank)
                                            @break($loop->iteration > 3)
                                            <div class="header_item">
                                                <img src="{{ render_cover($rank->user->avatar, 'avatar') }}" alt=""
                                                     data-toggle="popover"
                                                     data-html="true"
                                                     data-trigger="click"
                                                     data-placement="auto"
                                                     data-content='
                                                     <div class="popover_card">
                                                        <div class="teacher_header">
                                                            <img src="{{ render_cover($rank->user->avatar, 'avatar') }}" alt="" class="teacher_avatar">
                                                            <div class="teacher_info">
                                                                <span class="teacher_name">{{ $rank->user->username }}</span>
                                                                <span class="teacher_job">{{ $rank->user->signature }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="teacher_fans">
                                                            <div class="fans_item">
                                                                <span>{{ $rank->user->plans()->count() }}</span>
                                                                <span class="fans_title">
                                                                    在学
                                                                </span>
                                                            </div>
                                                            <div class="fans_item">
                                                                <span>{{ $rank->user->followers()->count() }}</span>
                                                                <span class="fans_title">
                                                                    关注
                                                                </span>
                                                            </div>
                                                            <div class="fans_item mr-0">
                                                                <span>{{ $rank->user->fans()->count() }}</span>
                                                                <span class="fans_title">
                                                                    粉丝
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @auth('web')
                                                            @if (auth('web')->id() != $rank->user->id)
                                                                 <div class="teacher_controls">
                                                                    <a href="#" class="btn btn-primary btn-sm btn-circle float-left follow" data-id="{{ $rank->user->id }}">
                                                                            {{ $rank->user->isFollow() ? '取消关注' : '关注' }}
                                                                    </a>
                                                                    <a href="#" class="btn btn-primary btn-sm btn-circle float-right message"
                                                                     data-id="{{ $rank->user->id }}" data-username="{{ $rank->user->username }}"
                                                                        >私信</a>
                                                                 </div>
                                                            @endif
                                                        @else
                                                             <div class="teacher_controls">
                                                                 <a href="{{ route('login') }}" class="btn btn-primary btn-sm btn-circle float-left">关注</a>
                                                                 <a href="{{ route('login') }}" class="btn btn-primary btn-sm btn-circle float-right">私信</a>
                                                             </div>
                                                        @endAuth
                                                             </div>'>
                                                <img src="/imgs/ranking_{{ $loop->iteration }}.png" alt=""
                                                     class="ranking_num">
                                            </div>

                                        @endforeach
                                    </div>
                                </div>
                                <div class="ranking_item mb_40">
                                    <div class="ranking_title">你的排名</div>
                                    <div class="ranking_header">
                                        <div class="header_item me_ranking">
                                            <img src="{{ render_cover(auth('web')->user()->avatar, 'avatar') }}"
                                                 class="float-left" alt=""
                                                 data-toggle="popover"
                                                 data-html="true"
                                                 data-trigger="click"
                                                 data-placement="auto">
                                            <div class="me_info">
                                                <span class="ranking_n">
                                                第{{ $myRank }}名
                                                </span>
                                                <span class="ranking_name">
                                                   {{ auth('web')->user()->username }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card_item_title">
                                    本周平均成绩排名
                                </div>
                                <div class="ranking_item">
                                    <div class="ranking_title">前三</div>
                                    <div class="ranking_header">
                                        @foreach($mark as $rank)
                                            @break($loop->iteration > 3)
                                            <div class="header_item">
                                                <img src="{{ render_cover($rank->user->avatar, 'avatar') }}" alt=""
                                                     data-toggle="popover"
                                                     data-html="true"
                                                     data-trigger="click"
                                                     data-placement="auto"
                                                     data-content='
                                                     <div class="popover_card">
                                                        <div class="teacher_header">
                                                            <img src="{{ render_cover($rank->user->avatar, 'avatar') }}" alt="" class="teacher_avatar">
                                                            <div class="teacher_info">
                                                                <span class="teacher_name">{{ $rank->user->username }}</span>
                                                                <span class="teacher_job">{{ $rank->user->signature }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="teacher_fans">
                                                            <div class="fans_item">
                                                                <span>{{ $rank->user->plans()->count() }}</span>
                                                                <span class="fans_title">
                                                                    在学
                                                                </span>
                                                            </div>
                                                            <div class="fans_item">
                                                                <span>{{ $rank->user->followers()->count() }}</span>
                                                                <span class="fans_title">
                                                                    关注
                                                                </span>
                                                            </div>
                                                            <div class="fans_item mr-0">
                                                                <span>{{ $rank->user->fans()->count() }}</span>
                                                                <span class="fans_title">
                                                                    粉丝
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @auth('web')
                                                            @if (auth('web')->id() != $rank->user->id)
                                                                 <div class="teacher_controls">
                                                                    <a href="#" class="btn btn-primary btn-sm btn-circle float-left follow" data-id="{{ $rank->user->id }}">
                                                                        {{ $rank->user->isFollow() ? '取消关注' : '关注' }}
                                                                    </a>
                                                                    <a href="#" class="btn btn-primary btn-sm btn-circle float-right message"
                                                                        data-id="{{ $rank->user->id }}" data-username="{{ $rank->user->username }}"
                                                                    >私信</a>
                                                                 </div>
                                                            @endif
                                                        @else
                                                                <div class="teacher_controls">
                                                                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm btn-circle float-left">关注</a>
                                                                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm btn-circle float-right">私信</a>
                                                                </div>
                                                        @endAuth
                                                             </div>'>
                                                <img src="/imgs/ranking_{{ $loop->iteration }}.png" alt=""
                                                     class="ranking_num">
                                            </div>

                                        @endforeach
                                    </div>
                                </div>
                                <div class="ranking_item">
                                    <div class="ranking_title">你的排名</div>
                                    <div class="ranking_header">
                                        <div class="header_item me_ranking">
                                            <img src="{{ render_cover(auth('web')->user()->avatar, 'avatar') }}"
                                                 class="float-left" alt=""
                                                 data-toggle="popover"
                                                 data-html="true"
                                                 data-trigger="click"
                                                 data-placement="auto">
                                            <div class="me_info">
                                                <span class="ranking_n">
                                                第{{ $myMark }}名
                                                </span>
                                                <span class="ranking_name">
                                                      {{ auth('web')->user()->username }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- 发送私信的模态框 --}}
    <div class="modal modal-danger fade send_modal" id="modal_6" tabindex="-1" role="dialog" aria-labelledby="modal_6" aria-hidden="true">
        <div class="modal-dialog" role="document" style="background: #FFFFFF;border-radius: 8px;box-shadow: 0px 5px 6px 0px rgba(0,0,0,0.10);">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title float-left text-center w-100" id="modal_title_6">发送私信</h5>
                </div>
                <div class="modal-body">
                                        <span class="to_sender">
                                            TO：<span id="username"></span>
                                            <input type="hidden" id="user_id">
                                        </span>
                    <div class="py-3" style="background: #FAFAFA;font-size: 14px; color: #666;height: 226px;position: relative;overflow-y: auto;">
                        <textarea name="" id="message" cols="30" rows="10"></textarea>
                    </div>
                    <div class="notice_time">
                        {{ now() }}
                    </div>
                </div>
                <div class="modal-footer justify-content-center" style="border-top: 0;">
                    <button type="button" class="btn btn-sm btn-primary btn-circle" style="padding: 5px 26px;margin-right: 30px;" data-dismiss="modal" id="sendMessage">发送</button>
                    <button type="button" class="btn btn-sm btn-primary btn-circle" style="padding: 5px 26px;" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ '/vendor/easy-pie-chart-master/dist/jquery.easypiechart.min.js' }}"></script>
    <script src="{{  mix('/js/front/plan.js')  }}"></script>
    <script src="{{  mix('/js/front/classroom/index.js')  }}"></script>
    <script>
        // 关注
        $(document).on('click', '.follow', function () {

            var follow = $(this);
            var id = $(this).data('id');

            follow.removeClass('follow');

            $.ajax({
                url: '/users',
                method:'post',
                data:{follow_id: id},
                success:function(res){

                    if (res.status == '200') {

                        if(res.data.length == 0){

                            follow.html('关注');
                            edu.alert('success', '取消关注成功');

                        } else {

                            follow.html('取消关注');
                            edu.alert('success', '关注成功');
                        }

                        follow.addClass('follow');
                    }
                }
            })
            return false;
        })

        // 发送私信的模态框
        $(document).on('click', '.message', function () {
            var id = $(this).data('id');

            var username = $(this).data('username');

            $('#username').html(username);

            $('#user_id').val(id);

            $('#modal_6').modal('show');

            return false;
        })

        // 发送私信
        $('#sendMessage').click(function(){

            // 数据验证
            var id = $('#user_id').val();

            var message = $('#message').val();

            if (!id || !message) {
                edu.alert('danger', '请完善私信信息');
                return false;
            }

            $.ajax({
                url: '/my/message',
                method:'post',
                data:{user_id: id, message:message},
                success:function(res){

                    if (res.status == '200') {
                        // 清空信息
                        $('#username').html('');

                        $('#user_id').val('');

                        $('#message').val('');

                        edu.alert('success','发送成功');

                        $('#modal_6').modal('hide');
                    }
                }
            })
        })
    </script>
    @yield('partScript')
@endsection
