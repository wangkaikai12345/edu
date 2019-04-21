@extends('frontend.default.study.index')
@section('title', '我的课程')

@section('partStyle')
    <link href="{{ asset('dist/my_course/css/index.css') }}" rel="stylesheet">
@endsection

@section('rightBody')
    <div class="col-xl-9">
        <div class="card">
            <div class="card-body">
                <!--Title-->
                <h6 class="card-title">我的课程</h6>
                <hr>
                <!--Text-->
                <div class="classic-tabs">
                    <ul class="nav" id="myClassicTab" role="tablist">
                        <li class="nav-item ml-0">
                            <a class="nav-link  waves-light {{ active_class(!if_query('is_finished', '1') && !if_query('favourite', '1')) }}" href="{{ route('users.courses') }}"
                              >学习中</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link waves-light {{ active_class(if_query('is_finished', '1')) }}" href="{{ route('users.courses', ['is_finished'=> true]) }}">已学完</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link waves-light {{ active_class(if_query('favourite', '1')) }}" href="{{ route('users.courses', ['favourite'=> true]) }}">收藏</a>
                        </li>
                    </ul>
                    <div class="tab-content rounded-bottom pt-0" id="myClassicTabContent">
                        <div class="show" id="profile-classic" role="tabpanel"
                             aria-labelledby="profile-tab-classic">
                            @if (count($courses))
                                @foreach($courses as $course)
                                    @if ($course['model'])
                                        <div class="row mb-3 mt-3">
                                            <div class="col-xl-12 p-0">
                                                <div class="card">
                                                    <div class="card-body row text-center">
                                                        <div class="col-xl-4 col-md-4">
                                                            <img class="card-img-top" src="{{ render_cover($course['model']['cover'], 'course') }}"
                                                                 alt="Card image cap">
                                                        </div>
                                                        <div class="col-xl-6 text-left col-md-6">
                                                            <h6 class="font-weight-bold mb-3 text-truncate mt-5">{{ $course['model']['title'] }}</h6>
                                                            <p class="text-uppercase font-small">价  格</p>
                                                            <div class="row pl-3">
                                                                <span class="font-small text-danger">
                                                                    {{ ftoy($course['model']['min_course_price']) }}元 +
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-2 text-center col-md-2"
                                                             style="flex-direction: column-reverse;display: flex;">
                                                            <a type="button"
                                                               class="btn align-items-center btn-primary btn-rounded btn-sm waves-effect waves-light mb-5"
                                                               href="{{ route('courses.show', $course['model']['id']) }}"
                                                            >查看课程
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <div class="row mb-3 mt-3">
                                            <div class="col-xl-12 p-0">
                                                <div class="card">
                                                    <div class="card-body row text-center">
                                                        <div class="col-xl-4 col-md-4">
                                                            <img class="card-img-top" src="{{ render_cover($course['course']['cover'], 'course') }}"
                                                                 alt="Card image cap">
                                                        </div>
                                                        <div class="col-xl-6 text-left col-md-6">
                                                            <h6 class="font-weight-bold mb-3 text-truncate mt-5">{{ $course['course']['title'].'-'.$course['plan']['title'] }}</h6>
                                                            <p class="text-uppercase font-small">学习进度</p>
                                                            <div class="row pl-3">
                                                                <div class="progress md-progress col-xl-10 pl-0 mt-1 col-md-10">
                                                                    <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="{{ $course['plan']['tasks_count'] ? ytof($course['learned_count']/$course['plan']['tasks_count']): 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                                <span class="font-small col-xl-2 col-md-2">
                                                            {{ $course['plan']['tasks_count'] ? ytof($course['learned_count']/$course['plan']['tasks_count']): 0 }}%
                                                        </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-2 text-center col-md-2"
                                                             style="flex-direction: column-reverse;display: flex;">
                                                            <a type="button"
                                                               class="btn align-items-center btn-primary btn-rounded btn-sm waves-effect waves-light mb-5"
                                                               href="{{ route('plans.intro', [$course['course'], $course['plan']]) }}"
                                                            >继续学习
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                @endforeach
                            @else
                                <div class="alert alert-warning mt-3" role="alert">
                                    没有数据...
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <nav aria-label="Page navigation example">
                                {!! $courses->render() !!}
                            </nav>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('partScript')
    <script type="text/javascript" src="{{ asset('dist/my_course/js/index.js') }}"></script>
@endsection