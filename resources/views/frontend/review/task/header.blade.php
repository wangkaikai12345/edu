@inject('index', 'App\Handlers\IndexHandler')
<link rel="stylesheet" href="{{ mix('/css/front/task/header/index.css') }}">

<div class="zh_nav">
    <div class="nav">
        <a href="{{!empty($member) ?  (isset($member->classroom_id) ? route('classrooms.plans', $member->classroom ) : route('plans.chapter', [ $chapter->plan->course, $chapter->plan ])) : route('courses.show', $chapter->course) }}"><button class="back"></button></a>
        <div class="stepper">
            @foreach($types as $type)
                <div class="stepper-item {{ active_class($task->type == $type) }}">
                    <span>
                        <a href="{{ array_key_exists($type, is_array($tasks) ? $tasks :$tasks->toArray()) ? renderTaskRoute([$chapter, 'type' => $type], $member):'javascript:;' }}" style="color:black">
                            {{ render_task_type($type) }}
                        </a>
                    </span>
                </div>
            @endforeach
        </div>
        <div class="collapse navbar-collapse show" id="navbar_example_2">
            <ul class="navbar-nav ml-auto right_controls">
                @auth('web')
                    <li class="nav-item dropdown">
                        <div class="user_avatar" data-toggle="dropdown" role="button">
                            <img src="{{ render_cover(auth('web')->user()['avatar'], 'avatar') ?? '/imgs/avatar.png' }}" alt="">
                        </div>
                        <a class="nav-link nav-link-icon" href="#" id="navbar_2_dropdown_3" aria-haspopup="true" aria-expanded="true"><i class="fas fa-user"></i></a>
                        <div class="dropdown-menu dropdown-menu-right user_info">
                            <h6 class="dropdown-header">{{ auth('web')->user()['username'] }}</h6>
                            <ul>
                                @if(auth('web')->user()->isSuperAdmin() || auth('web')->user()->isAdmin())
                                    <li>
                                        <a href="{{ route('backstage.index') }}">后台管理</a>
                                    </li>
                                @endif
                                {{--<li>--}}
                                    {{--<a href="{{ route('users.order') }}">账户中心</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="{{ route('users.edit', auth('web')->user()) }}">个人设置</a>--}}
                                {{--</li>--}}
                                @if(!auth('web')->user()->isStudent())
                                    <li>
                                        <a href="{{ route('manage.users.teach_course') }}">我的教学</a>
                                    </li>
                                @endif
                                {{--<li>--}}
                                    {{--<a href="{{ route('users.courses') }}">我的学习</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="{{ route('users.show', auth('web')->user()) }}">我的主页</a>--}}
                                {{--</li>--}}
                                <li>
                                    <a href="{{ route('logout') }}">退出登陆</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="btn btn-white btn-sm btn-outline-dark btn-circle font-weight-400 mr-3">登录</a>
                    </li>
                    <li class="nav-item mr-3">
                        <a href="{{ route('register') }}" class="btn btn-white btn-sm btn-outline-dark btn-circle font-weight-400">注册</a>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</div>