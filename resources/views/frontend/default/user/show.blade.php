@extends('frontend.default.layouts.app')

@section('style')
    <link href="{{ asset('dist/homepage/css/index.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="bg_other">
        <div class="user_info">
            <div class="user_pic">
                <div class="user_pic_bg"><img alt="" src="{{ render_cover($user['avatar'], 'avatar') }}"></div>
            </div>
            <div class="user_info_right"><h3 class="user_name"><span>{{ $user['username'] }}</span></h3>
                <p class="about_info"><span>{{ render_user_sex($user->profile['gender']) }}</span></p></div>
            <div class="user_sign"><p class="user_desc">{{ $user['signature'] }}</p></div>
            <div class="study_info">
                <div class="item">
                    <div class="sc">21</div>
                    <span class="sc_desc">学习时长</span></div>
                <div class="item">
                    <div class="sc">1</div>
                    <span class="sc_desc">经验</span></div>
                <div class="item">
                    <div class="sc">81</div>
                    <span class="sc_desc">积分</span></div>
                <div class="item">
                    <div class="sc">{{ $user->followers()->count() }}</div>
                    <span class="sc_desc">关注</span></div>
                <div class="item">
                    <div class="sc">{{ $user->fans()->count() }}</div>
                    <span class="sc_desc">粉丝</span></div>
                <div class="item">
                    @if (auth('web')->id())
                        @if (auth('web')->id() != $user['id'])
                            @if (in_array($user['id'], $user['follows']))
                                <button class="follow btn btn-sm btn-primary m-0" data-auth="{{ auth('web')->id() }}" data-follow="{{ $user['id'] }}">取消关注</button>
                            @else
                                <button class="follow btn btn-sm btn-primary m-0" data-auth="{{ auth('web')->id() }}" data-follow="{{ $user['id'] }}">关注</button>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sm btn-primary m-0">关注</a>
                    @endif
                </div>
            </div>


        </div>
    </div>
    <div class="wrap">
        <div class="slider">
            <ul>
                <li>
                    <a href="{{ route('users.show', $user) }}" class="{{ active_class(if_query('rel', '')) }}"><i><i class="anticon anticon-user"></i></i><span>个人介绍</span></a>
                </li>
                @if (!$user->isStudent())
                    <li>
                        <a href="{{ route('users.show', [$user, 'rel' => 'teaching']) }}" class="{{ active_class(if_query('rel', 'teaching')) }}"><i>
                                <i class="anticon anticon-book"></i></i><span>在教课程</span></a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('users.show', [$user, 'rel' => 'learning']) }}" class="{{ active_class(if_query('rel', 'learning')) }}"><i><i
                                    class="anticon anticon-code-o"></i></i><span>在学课程</span></a>
                </li>
                <li>
                    <a href="{{ route('users.show', [$user, 'rel' => 'collect']) }}" class="{{ active_class(if_query('rel', 'collect')) }}"><i><i
                                    class="anticon anticon-exception"></i></i><span>收藏课程</span></a>
                </li>
                <li>
                    <a href="{{ route('users.show', [$user, 'rel' => 'follows']) }}" class="{{ active_class(if_query('rel', 'fans') || if_query('rel', 'follows')) }}"><i><i
                                    class="anticon anticon-check-square-o"></i></i><span>关注/粉丝</span></a>
                </li>
            </ul>
        </div>
        {{--个人介绍--}}
        <div class="u_container mt-4 {{ active_class(if_query('rel', ''), 'd-block', '') }}">
            <div class="classic-tabs">
                <ul class="nav" id="myClassicTab" role="tablist">
                    <li class="nav-item ml-0">
                        <a class="nav-link  waves-light active show"
                           id="profile-tab-classic" data-toggle="tab" href="#profile-classic"
                           role="tab" aria-controls="profile-classic"
                           aria-selected="true">个人介绍</a>
                    </li>
                </ul>
                <div class="tab-content rounded-bottom pl-0" id="myClassicTabContent">
                    <div class="tab-pane fade active show" id="profile-classic" role="tabpanel"
                         aria-labelledby="profile-tab-classic">
                        <div>
                           {{ $user['about'] ?? '暂无更多介绍'  }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--在教课程--}}
        <div class="u_container mt-4 content pt-0 {{ active_class(if_query('rel', 'teaching') && !$user->isStudent(), 'd-block', '') }}">
            <div class="classic-tabs">
                <ul class="nav" id="myClassicTabStudy" role="tablist">
                    <li class="nav-item ml-0">
                        <a class="nav-link  waves-light active show"
                           id="study-tab-classic" data-toggle="tab" href="#profile-classic"
                           role="tab" aria-controls="study-classic"
                           aria-selected="true">最近教学</a>
                    </li>
                </ul>
                @if (if_query('rel', 'teaching'))
                    <div class="tab-content rounded-bottom pl-0" id="myClassicTabStudyLiveContent">
                        <div class="tab-pane fade active show" id="study-classic-live" role="tabpanel"
                             aria-labelledby="study-tab-classic-live">
                            <div class="wrapper">
                                <div class="main">
                                    <h1 class="title">在教课程</h1>
                                    @foreach($user['rel'] as $k => $course)
                                        @if ($k == 0)
                                            <div class="year">
                                                <h2><a href="#">{{ $course['created_at']->year }}年<i></i></a></h2>
                                                <div class="list">
                                                    <ul>
                                                        @foreach($user['rel'] as $key => $month)
                                                            @if ($month['created_at']->year == $course['created_at']->year)
                                                                <li class="cls highlight">
                                                                    <p class="date">{{ $month['created_at']->month }}月 {{ $month['created_at']->day }}日</p>
                                                                    <div class="row time-right">
                                                                        <div class="col-xl-12 p-0">
                                                                            <div class="card border-0 bg-transparent">
                                                                                <div class="card-body row text-center pt-0 pl-0">
                                                                                    <div class="col-xl-4 col-md-4">
                                                                                        <img class="card-img-top" src="{{ render_cover($month['course']['cover'], 'course') }}"
                                                                                             alt="Card image cap">
                                                                                    </div>
                                                                                    <div class="col-xl-6 text-left col-md-5">
                                                                                        <h6 class="font-weight-bold mb-3 text-truncate mt-2">{{ $month['course']['title'] }}</h6>
                                                                                        <p class="text-uppercase font-small">教学版本：{{ $month['plan']['title'] }}</p>
                                                                                        <p class="text-uppercase font-small">学员数量：{{ $month['course']['students_count'] }}</p>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>

                                            </div>
                                        @else
                                            @if ($user['rel'][$k-1]['created_at']->year != $course['created_at']->year)
                                                <div class="year">
                                                    <h2><a href="#">{{ $course['created_at']->year }}年<i></i></a></h2>
                                                    <div class="list">
                                                        <ul>
                                                            @foreach($user['rel'] as $key => $month)
                                                                @if ($month['created_at']->year == $course['created_at']->year)
                                                                    <li class="cls highlight">
                                                                        <p class="date">{{ $month['created_at']->month }}月 {{ $month['created_at']->day }}日</p>
                                                                        <div class="row time-right">
                                                                            <div class="col-xl-12 p-0">
                                                                                <div class="card border-0 bg-transparent">
                                                                                    <div class="card-body row text-center pt-0 pl-0">
                                                                                        <div class="col-xl-4 col-md-4">
                                                                                            <img class="card-img-top" src="{{ render_cover($month['course']['cover'], 'course') }}"
                                                                                                 alt="Card image cap">
                                                                                        </div>
                                                                                        <div class="col-xl-6 text-left col-md-5">
                                                                                            <h6 class="font-weight-bold mb-3 text-truncate mt-2">{{ $month['course']['title'] }}</h6>
                                                                                            <p class="text-uppercase font-small">教学版本：{{ $month['plan']['title'] }}</p>
                                                                                            <p class="text-uppercase font-small">学员数量：{{ $month['course']['students_count'] }}</p>

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </div>

                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <nav aria-label="Page navigation example">
                                            {!! $user['rel']->render() !!}
                                        </nav>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        {{--在学课程--}}
        <div class="u_container mt-4 content pt-0 {{ active_class(if_query('rel', 'learning'), 'd-block', '') }}">
            <div class="classic-tabs">
                <ul class="nav" id="myClassicTabStudyLive" role="tablist">
                    <li class="nav-item ml-0">
                        <a class="nav-link  waves-light active show"
                           id="study-tab-classic-live" data-toggle="tab" href="#profile-classic"
                           role="tab" aria-controls="study-classic-live"
                           aria-selected="true">在学课程</a>
                    </li>
                </ul>
                @if (if_query('rel', 'learning'))
                    <div class="tab-content rounded-bottom pl-0" id="myClassicTabStudyLiveContent">
                        <div class="tab-pane fade active show" id="study-classic-live" role="tabpanel"
                             aria-labelledby="study-tab-classic-live">
                            <div class="wrapper">
                                <div class="main">
                                    <h1 class="title">学习日志</h1>
                                    @foreach($user['rel'] as $k => $course)
                                        @if ($k == 0)
                                            <div class="year">
                                                <h2><a href="#">{{ $course['created_at']->year }}年<i></i></a></h2>
                                                <div class="list">
                                                    <ul>
                                                        @foreach($user['rel'] as $key => $month)
                                                            @if ($month['created_at']->year == $course['created_at']->year)
                                                                <li class="cls highlight">
                                                            <p class="date">{{ $month['created_at']->month }}月 {{ $month['created_at']->day }}日</p>
                                                            <div class="row time-right">
                                                                <div class="col-xl-12 p-0">
                                                                    <div class="card border-0 bg-transparent">
                                                                        <div class="card-body row text-center pt-0 pl-0">
                                                                            <div class="col-xl-4 col-md-4">
                                                                                <img class="card-img-top" src="{{ render_cover($month['course']['cover'], 'course') }}"
                                                                                     alt="Card image cap">
                                                                            </div>
                                                                            <div class="col-xl-6 text-left col-md-5">
                                                                                <h6 class="font-weight-bold mb-3 text-truncate mt-2">{{ $month['course']['title'] }}</h6>
                                                                                <p class="text-uppercase font-small">教学版本：{{ $month['plan']['title'] }}</p>
                                                                                <p class="text-uppercase font-small">学习进度</p>
                                                                                <div class="row pl-3">
                                                                                    <div class="progress md-progress col-xl-10 pl-0 mt-1 col-md-10">
                                                                                        <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $month['plan']['tasks_count'] ? ytof($month['learned_count']/$month['plan']['tasks_count']): 0 }}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                                                    </div>
                                                                                    <span class="font-small col-xl-2 col-md-2">
                                                        {{ $month['plan']['tasks_count'] ? ytof($month['learned_count']/$month['plan']['tasks_count']): 0 }}%
                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>

                                            </div>
                                        @else
                                            @if ($user['rel'][$k-1]['created_at']->year != $course['created_at']->year)
                                            <div class="year">
                                                <h2><a href="#">{{ $course['created_at']->year }}年<i></i></a></h2>
                                                <div class="list">
                                                    <ul>
                                                        @foreach($user['rel'] as $key => $month)
                                                            @if ($month['created_at']->year == $course['created_at']->year)
                                                                <li class="cls highlight">
                                                                    <p class="date">{{ $month['created_at']->month }}月 {{ $month['created_at']->day }}日</p>
                                                                    <div class="row time-right">
                                                                        <div class="col-xl-12 p-0">
                                                                            <div class="card border-0 bg-transparent">
                                                                                <div class="card-body row text-center pt-0 pl-0">
                                                                                    <div class="col-xl-4 col-md-4">
                                                                                        <img class="card-img-top" src="{{ render_cover($month['course']['cover'], 'course') }}"
                                                                                             alt="Card image cap">
                                                                                    </div>
                                                                                    <div class="col-xl-6 text-left col-md-5">
                                                                                        <h6 class="font-weight-bold mb-3 text-truncate mt-2">{{ $month['course']['title'] }}</h6>
                                                                                        <p class="text-uppercase font-small">教学版本：{{ $month['plan']['title'] }}</p>
                                                                                        <p class="text-uppercase font-small">学习进度</p>
                                                                                        <div class="row pl-3">
                                                                                            <div class="progress md-progress col-xl-10 pl-0 mt-1 col-md-10">
                                                                                                <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $month['plan']['tasks_count'] ? ytof($month['learned_count']/$month['plan']['tasks_count']): 0 }}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                                                            </div>
                                                                                            <span class="font-small col-xl-2 col-md-2">
                                                        {{ $month['plan']['tasks_count'] ? ytof($month['learned_count']/$month['plan']['tasks_count']): 0 }}%
                                                    </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>

                                            </div>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <nav aria-label="Page navigation example">
                                            {!! $user['rel']->render() !!}
                                        </nav>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        {{--收藏课程--}}
        <div class="u_container mt-4 content pt-0 {{ active_class(if_query('rel', 'collect'), 'd-block', '') }}">
            <div class="classic-tabs">
                <ul class="nav" id="myClassicTabStudyStar" role="tablist">
                    <li class="nav-item ml-0">
                        <a class="nav-link  waves-light active show"
                           id="study-tab-classic-star" data-toggle="tab" href="#profile-classic"
                           role="tab" aria-controls="study-classic-star"
                           aria-selected="true">最近收藏</a>
                    </li>
                </ul>
                @if (if_query('rel', 'collect'))
                    <div class="tab-content rounded-bottom pl-0" id="myClassicTabStudyLiveContent">
                        <div class="tab-pane fade active show" id="study-classic-live" role="tabpanel"
                             aria-labelledby="study-tab-classic-live">
                            <div class="wrapper">
                                <div class="main">
                                    <h1 class="title">收藏课程</h1>
                                    @foreach($user['rel'] as $k => $course)

                                        @if ($k == 0)
                                            <div class="year">
                                                <h2><a href="#">{{ $course['model']['created_at']->year }}年<i></i></a></h2>
                                                <div class="list">
                                                    <ul>
                                                        @foreach($user['rel'] as $key => $month)
                                                            @if ($month['model']['created_at']->year == $course['model']['created_at']->year)
                                                                <li class="cls highlight">
                                                                    <p class="date">{{ $month['model']['created_at']->month }}月 {{ $month['model']['created_at']->day }}日</p>
                                                                    <div class="row time-right">
                                                                        <div class="col-xl-12 p-0">
                                                                            <div class="card border-0 bg-transparent">
                                                                                <div class="card-body row text-center pt-0 pl-0">
                                                                                    <div class="col-xl-4 col-md-4">
                                                                                        <img class="card-img-top" src="{{ render_cover($month['model']['cover'], 'course') }}"
                                                                                             alt="Card image cap">
                                                                                    </div>
                                                                                    <div class="col-xl-6 text-left col-md-5">
                                                                                        <h6 class="font-weight-bold mb-3 text-truncate mt-2">{{ $month['model']['title'] }}</h6>
                                                                                        <p class="text-uppercase font-small">教学版本：{{ $month['model']->plans->count() }}</p>
                                                                                        <p class="text-uppercase font-small">浏览数：{{ $month['model']['hit_count'] }}</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>

                                            </div>
                                        @else
                                            @if ($user['rel'][$k-1]['model']['created_at']->year != $course['model']['created_at']->year)
                                                <div class="year">
                                                    <h2><a href="#">{{ $course['model']['created_at']->year }}年<i></i></a></h2>
                                                    <div class="list">
                                                        <ul>
                                                            @foreach($user['rel'] as $key => $month)
                                                                @if ($month['model']['created_at']->year == $course['model']['created_at']->year)
                                                                    <li class="cls highlight">
                                                                        <p class="date">{{ $month['model']['created_at']->month }}月 {{ $month['model']['created_at']->day }}日</p>
                                                                        <div class="row time-right">
                                                                            <div class="col-xl-12 p-0">
                                                                                <div class="card border-0 bg-transparent">
                                                                                    <div class="card-body row text-center pt-0 pl-0">
                                                                                        <div class="col-xl-4 col-md-4">
                                                                                            <img class="card-img-top" src="{{ render_cover($month['model']['cover'], 'course') }}"
                                                                                                 alt="Card image cap">
                                                                                        </div>
                                                                                        <div class="col-xl-6 text-left col-md-5">
                                                                                            <h6 class="font-weight-bold mb-3 text-truncate mt-2">{{ $month['model']['title'] }}</h6>
                                                                                            <p class="text-uppercase font-small">教学版本：{{ $month['model']->plans->count() }}</p>
                                                                                            <p class="text-uppercase font-small">浏览数：{{ $month['model']['hit_count'] }}</p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </div>

                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <nav aria-label="Page navigation example">
                                            {!! $user['rel']->render() !!}
                                        </nav>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        {{--关注/粉丝--}}
        <div class="u_container mt-4 content pt-0 {{ active_class( (if_query('rel', 'fans') || if_query('rel', 'follows')), 'd-block', '') }}">
            <div class="classic-tabs">
                <ul class="nav" id="myClassicTabStar" role="tablist">
                    <li class="nav-item ml-0">
                        <a class="nav-link  waves-light {{ active_class(if_query('rel', 'follows'))}}"
                           {{--data-toggle="tab"--}}
                           {{--href="#profile-classic-star"--}}
                           href="{{ route('users.show', [$user, 'rel' => 'follows']) }}"
                           {{--role="tab" --}}
                           {{--aria-controls="profile-classic-star"--}}
                           {{--aria-selected="true"--}}
                        >我的关注</a>
                    </li>
                    <li class="nav-item ml-0">
                        <a class="nav-link  waves-light {{ active_class(if_query('rel', 'fans'))}}"
                           {{--id="profile-tab-classic-fensi" --}}
                           {{--data-toggle="tab" --}}
                           {{--href="#profile-classic-fensi"--}}
                           {{--role="tab" --}}
                           {{--aria-controls="profile-classic-fensi"--}}
                           {{--aria-selected="true"--}}
                           href="{{ route('users.show', [$user, 'rel' => 'fans']) }}"
                        >我的粉丝</a>
                    </li>
                </ul>
                <div class="tab-content rounded-bottom pl-0" id="myClassicTabStarContent">
                    {{-- 关注 --}}
                    @if (if_query('rel', 'follows'))
                        <div class="tab-pane fade active show" id="profile-classic-star" role="tabpanel"
                             aria-labelledby="profile-tab-classic-star">
                            <div class="row">
                                @foreach($user['rel'] as $value)
                                    <div class="col-xl-4 col-md-6 mb-4">
                                        <div class="card">
                                            <div class="row mt-3">
                                                <div class="col-md-5 col-5 text-left pl-4">
                                                    <a type="button" class="btn-floating btn-lg primary-color ml-4 waves-effect waves-light" href="{{ route('users.show', $value->follow) }}">
                                                        <img src="{{ render_cover($value['follow']['avatar'], 'avatar') }}" alt="" class="user-avatar">
                                                    </a>
                                                </div>
                                                <div class="col-md-7 col-7 text-right pr-5">
                                                    <h6 class="ml-4 mt-4 mb-2">{{ $value['follow']['username'] }}</h6>
                                                    <p class="font-small grey-text">{{ $value['follow']['signature'] }}</p>
                                                </div>
                                            </div>
                                            <div class="row mb-3 mt-2">
                                                <div class="col-md-6 col-5 text-left pl-4">
                                                    <p class="font-small grey-text ml-4 mt-2">
                                                        <span>关注&nbsp;{{ $value['follow']['followers_count'] }}&nbsp;</span>
                                                        <span class="ml-3">粉丝&nbsp;{{ $value['follow']['fans_count'] }}&nbsp;</span>
                                                    </p>
                                                </div>
                                                <div class="col-md-5 col-7 text-right">
                                                    @if (auth('web')->id())
                                                        @if (auth('web')->id() != $value['follow']['id'])
                                                            <button class="btn btn-outline-primary btn-sm follow" data-auth="{{ auth('web')->id() }}" data-follow="{{ $value['follow']['id'] }}">
                                                                @if (in_array($value['follow']['id'], $user['follows']))
                                                                    取消关注
                                                                    @else
                                                                    关注
                                                                @endif
                                                            </button>
                                                        @endif
                                                    @else
                                                        <a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}">关注</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <nav aria-label="Page navigation example">
                                        {!! $user['rel']->render() !!}
                                    </nav>

                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- 粉丝 --}}
                    @if (if_query('rel', 'fans'))
                        <div class="" role="tabpanel"
                         aria-labelledby="profile-tab-classic-fensi">
                        <div class="row">
                            @foreach($user['rel'] as $value)
                                <div class="col-xl-4 col-md-6 mb-4">
                                    <div class="card">
                                        <div class="row mt-3">
                                            <div class="col-md-5 col-5 text-left pl-4">
                                                <a type="button" class="btn-floating btn-lg primary-color ml-4 waves-effect waves-light" href="{{ route('users.show', $value->user) }}">
                                                    <img src="{{ render_cover($value['user']['avatar'], 'avatar') }}" alt="" class="user-avatar">
                                                </a>
                                            </div>
                                            <div class="col-md-7 col-7 text-right pr-5">
                                                <h6 class="ml-4 mt-4 mb-2">{{ $value['user']['username'] }}</h6>
                                                <p class="font-small grey-text">{{ $value['user']['signature'] }}</p>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-2">
                                            <div class="col-md-6 col-5 text-left pl-4">
                                                <p class="font-small grey-text ml-4 mt-2">
                                                    <span>关注&nbsp;{{ $value['user']['followers_count'] }}&nbsp;</span>
                                                    <span class="ml-3">粉丝&nbsp;{{ $value['user']['fans_count'] }}&nbsp;</span>
                                                </p>
                                            </div>
                                            <div class="col-md-5 col-7 text-right">
                                                @if (auth('web')->id())
                                                    @if (auth('web')->id() != $value['user']['id'])
                                                        <button class="btn btn-outline-primary btn-sm follow" data-auth="{{ auth('web')->id() }}" data-follow="{{ $value['user']['id'] }}">
                                                            @if (in_array($value['user']['id'], $user['follows']))
                                                                取消关注
                                                            @else
                                                                关注
                                                            @endif
                                                        </button>
                                                    @endif
                                                @else
                                                    <a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}">关注</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <nav aria-label="Page navigation example">
                                    {!! $user['rel']->render() !!}
                                </nav>

                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('dist/homepage/js/index.js') }}"></script>
    <script>
        $('.follow').click(function(){

            var that = $(this);

            var auth = $(this).data('auth');

            if (!auth) {
                edu.toastr.error('请登陆！');
                location.href= '/login';
                return false;
            }
            var follow = $(this).data('follow');
            if (!follow) {
                edu.toastr.error('请选择');
                return false;
            }

            edu.ajax({
                url: '/users',
                method: 'post',
                data: {
                    'follow_id':follow,
                },
                callback:function(res){
                    console.log(res);
                    if (res.status == 'success') {
                        if (res.data.length) {
                            that.html('取消关注')
                        } else {
                            that.html('关注')
                        }
                    }
                },
                elm: '.follow'
            })
        })


    </script>
@endsection