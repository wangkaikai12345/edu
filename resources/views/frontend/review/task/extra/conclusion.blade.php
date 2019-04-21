<link rel="stylesheet" href="{{ mix('/css/front/task/conclusion/index.css') }}">
<div class="zh_conclusion">
    <div class="conclusion_wrap">
        <div class="conclusion_card">
            <div class="card_title">
                综合成绩
            </div>
            <div class="card_body">
            <span class="card_grade">
                 @foreach(str_split($summary['mine']->score) as $num)
                    <img src="/imgs/extra/{{ $num }}@3x.png" alt="" class="points_num points_{{ $num }}">
                @endforeach
                <img src="/imgs/extra/mark@3x.png" alt="" class="points">
            </span>
                <span class="grade_rank">
                超越本关{{ $summary['ratio'] }}%的小伙伴
            </span>
                <a href="javascript:;" class="btn btn-sm btn-circle btn-outline-primary join_draw">参与抽奖(敬请期待)</a>
            </div>
        </div>
        <div class="conclusion_card" style="width: 538px;">
            <div class="card_title">
                本关排名
            </div>
            <div class="card_body">
                <div class="ranking_wrap">
                    <div class="conclusion_ranking">
                        <span class="title">
                            前三
                        </span>
                        @if ($summary['mark']->count())
                            @foreach($summary['mark'] as $rank)
                                @break($loop->iteration > 3)

                                <div class="header_item">
                                    <img src="{{ render_cover($rank->user->avatar, 'avatar') }}" alt="" data-toggle="popover" data-html="true"
                                         data-trigger="click" data-placement="auto" data-content="
                                                         <div class=&quot;popover_card&quot;>
                                                            <div class=&quot;teacher_header&quot;>
                                                                <img src=&quot;{{ render_cover($rank->user->avatar, 'avatar') }}&quot; alt=&quot;&quot; class=&quot;teacher_avatar&quot;>
                                                                <div class=&quot;teacher_info&quot;>
                                                                    <span class=&quot;teacher_name&quot;>{{ $rank->user->username }}</span>
                                                                    <span class=&quot;teacher_job&quot;>{{ $rank->user->signature ?? '让学习成为一种习惯！' }}</span>
                                                                </div>
                                                            </div>
                                                            <div class=&quot;teacher_fans&quot;>
                                                                <div class=&quot;fans_item&quot;>
                                                                    <span>{{ $rank->user->managePlans()->count() }}</span>
                                                                    <span class=&quot;fans_title&quot;>
                                                                        在学
                                                                    </span>
                                                                </div>
                                                                <div class=&quot;fans_item&quot;>
                                                                    <span>{{ $rank->user->followers()->count() }}</span>
                                                                    <span class=&quot;fans_title&quot;>
                                                                        关注
                                                                    </span>
                                                                </div>
                                                                <div class=&quot;fans_item mr-0&quot;>
                                                                    <span>{{ $rank->user->fans()->count() }}</span>
                                                                    <span class=&quot;fans_title&quot;>
                                                                        粉丝
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            @auth('web')
                                                                @if (auth('web')->id() != $rank->user->id)
                                                                    <div class=&quot;teacher_controls&quot;>
                                                                        <a href=&quot;#&quot; class=&quot;btn btn-primary btn-sm btn-circle float-left follow&quot; data-id=&quot;{{ $rank->user->id }}&quot;>
                                                                                {{ $rank->user->isFollow() ? '取消关注' : '关注' }}
                                                                        </a>
                                                                        <a href=&quot;#&quot; class=&quot;btn btn-primary btn-sm btn-circle float-right message&quot;
                                                                         data-id=&quot;{{ $rank->user->id }}&quot; data-username=&quot;{{ $rank->user->username }}&quot;
                                                                            >私信</a>
                                                                    </div>
                                                                @endif
                                                            @else
                                                                    <div class=&quot;teacher_controls&quot;>
                                                                        <a href=&quot;{{ route('login') }}&quot; class=&quot;btn btn-primary btn-sm btn-circle float-left&quot;>
                                                                                关注
                                                                        </a>
                                                                        <a href=&quot;{{ route('login') }}&quot; class=&quot;btn btn-primary btn-sm btn-circle float-right&quot;>私信</a>
                                                                    </div>
                                                            @endAuth
                                            </div>">
                                    <img src="/imgs/ranking_{{ $loop->iteration }}.png" alt="" class="ranking_num">
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="conclusion_ranking user_ranking">
                        <span class="title">
                            你的排名
                        </span>
                        <div class="header_item">
                            <img src="{{ render_cover(\Auth::user()->avatar, 'avatar') }}" alt="" data-toggle="popover" data-html="true"
                                 data-trigger="click" data-placement="auto" data-content="
                                                     <div class=&quot;popover_card&quot;>
                                                        <div class=&quot;teacher_header&quot;>
                                                            <img src=&quot;{{ render_cover(\Auth::user()->avatar, 'avatar') }}&quot; alt=&quot;&quot; class=&quot;teacher_avatar&quot;>
                                                            <div class=&quot;teacher_info&quot;>
                                                                <span class=&quot;teacher_name&quot;>{{ \Auth::user()->username }}</span>
                                                                <span class=&quot;teacher_job&quot;>{{ \Auth::user()->signature ?? '让学习成为一种习惯！' }}</span>
                                                            </div>
                                                        </div>
                                                        <div class=&quot;teacher_fans&quot;>
                                                            <div class=&quot;fans_item&quot;>
                                                                <span>{{ $rank->user->managePlans()->count() }}</span>
                                                                <span class=&quot;fans_title&quot;>
                                                                    在学
                                                                </span>
                                                            </div>
                                                            <div class=&quot;fans_item&quot;>
                                                                <span>{{ $rank->user->followers()->count() }}</span>
                                                                <span class=&quot;fans_title&quot;>
                                                                    关注
                                                                </span>
                                                            </div>
                                                            <div class=&quot;fans_item mr-0&quot;>
                                                                <span>{{ $rank->user->fans()->count() }}</span>
                                                                <span class=&quot;fans_title&quot;>
                                                                    粉丝
                                                                </span>
                                                            </div>
                                                        </div>
                                                     </div>">
                            <div class="user_info" style="width: 85px;">
                                <span class="user_rank_num">
                                    第{{ $summary['myMark'] }}名
                                </span>
                                <span class="username">
                                    {{ auth('web')->user()->username }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="conclusion_card" style="width: 641px;margin-right: 0;">
            <div class="card_title">
                近七关成绩
            </div>
            <div class="card_body">
                <div class="grade" id="grade" data-score="{{ json_encode($summary['seven']) }}"></div>
            </div>
        </div>
        <div class="float-left">
            <div class="conclusion_card">
                <div class="card_title">
                    学习时长
                </div>
                <div class="card_body">
                    <ul class="card_lists">
                        <li>
                            <i class="iconfont">
                                &#xe649;
                            </i>
                            本关学习时长： <span>{{ $summary['mine']->updated_at->diffInMinutes($summary['mine']->created_at, true) }} 分钟</span>
                        </li>
                        <li>
                            <i class="iconfont">
                                &#xe649;
                            </i>
                            学习总时长： <span><a href="javascript:;">
                                    {{ $member->finish_at ? timeFormat($member->finish_at->diffInSeconds($member->created_at, true)) : timeFormat(now()->diffInSeconds($member->created_at, true)) }}
                                </a></span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="conclusion_card" style="clear: both;">
                <div class="card_title">
                    笔记问答
                </div>
                <div class="card_body">
                    <ul class="card_lists">
                        <li>
                            <i class="iconfont">
                                &#xe649;
                            </i>
                            我的笔记： <span>{{ $summary['notes'] }}</span>
                        </li>
                        <li>
                            <i class="iconfont">
                                &#xe649;
                            </i>
                            我的问答： <span><a href="">{{ $summary['topics'] }}</a></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="conclusion_card" style="width: 1220px;height: 480px;margin-right: 0;">
            <div class="card_title">
                作业成绩
            </div>
            <div class="card_body">
                <div class="card_results">
                    @if(!empty($summary['homeworks']))
                        @foreach($summary['homeworks'] as $task)
                            <div class="results_item">
                                <div class="results_header">
                                    {{ $task->title }}
                                    <div class="header_seq">
                                        {{ $loop->iteration }}
                                    </div>
                                </div>
                                <div class="results_body">
                                    <ul class="card_lists">
                                        <li>
                                            <i class="iconfont">
                                                &#xe649;
                                            </i>
                                            用时： <span>{{ $task->length/60 }}分钟</span>
                                        </li>
                                        <li>
                                            <i class="iconfont">
                                                &#xe649;
                                            </i>
                                            成绩： <span><a href="javascript:;">{{ $task->target->homeworkPosts[0]->result }}分</a></span>
                                        </li>
                                    </ul>
                                    <a href="{{ renderTaskRoute([$task->chapter, 'task' => $task->id], $member) }}"
                                       class="btn btn-sm btn-circle btn-outline-primary" style="display: block;width: 100px;padding: 5px 22px;margin: 50px auto 0;">查看详情</a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 发送私信的模态框 --}}
<div class="modal modal-danger fade send_modal" id="modal_6" tabindex="-1" role="dialog"
     aria-labelledby="modal_6" aria-hidden="true">
    <div class="modal-dialog" role="document"
         style="background: #FFFFFF;border-radius: 8px;box-shadow: 0px 5px 6px 0px rgba(0,0,0,0.10);">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title float-left text-center w-100" id="modal_title_6">发送私信</h5>
            </div>
            <div class="modal-body">
                        <span class="to_sender">
                            TO：<span id="username"></span>
                            <input type="hidden" id="user_id">
                        </span>
                <div class="py-3"
                     style="background: #FAFAFA;font-size: 14px; color: #666;height: auto;position: relative;overflow-y: auto;">
                    <textarea name="" id="message" cols="30" rows="10"></textarea>
                </div>
                <div class="notice_time">
                    {{ now() }}
                </div>
            </div>
            <div class="modal-footer justify-content-center" style="border-top: 0;">
                <button type="button" class="btn btn-sm btn-primary btn-circle"
                        style="padding: 5px 26px;margin-right: 30px;" data-dismiss="modal" id="sendMessage">发送
                </button>
                <button type="button" class="btn btn-sm btn-primary btn-circle" style="padding: 5px 26px;"
                        data-dismiss="modal">取消
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{ mix('/js/front/classroom/conclusion/index.js') }}"></script>