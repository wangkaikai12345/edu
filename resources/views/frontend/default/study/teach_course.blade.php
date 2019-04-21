@extends('frontend.default.study.index')
@section('title', '在教课程')

@section('partStyle')
    <link href="{{ asset('dist/my_course/css/index.css') }}" rel="stylesheet">
@endsection

@section('rightBody')
    <div class="col-xl-9 profile">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">在教课程 <a href="{{ config('app.manage_url').'/create' }}" target="_blank" class="float-right font-small pt-2">创建课程</a></h6>
                <hr class="pb-3">
                @if (count($plans))
                    @foreach($plans as $plan)
                        <div class="mb-3 mt-3">
                            <div class="col-xl-12 p-0">
                                <div class="card">
                                    <div class="card-body row text-center">
                                        <div class="col-xl-4 col-md-4">
                                            <img class="card-img-top" src="{{ render_cover($plan['course']['cover'], 'course') }}"
                                                 alt="Card image cap">
                                        </div>
                                        <div class="col-xl-6 text-left col-md-6">
                                            <h6 class="font-weight-bold mb-3 text-truncate mt-4">{{ $plan['course']['title'] }} -- {{ $plan['plan']['title'] }}</h6>
                                            <p class="text-uppercase font-small pt-3">学员数：{{ $plan['plan']['students_count'] }}</p>
                                            <p class="text-uppercase font-small">状态：<span class="text-warning">{{ render_status($plan['plan']['status']) }}</span></p>
                                            <p class="text-uppercase font-small">价格：<span class="text-danger">{{ $plan['plan']['coin_price'] ? $plan['plan']['coin_price'].'虚拟币' : ftoy($plan['plan']['price']).'元' }}</span></p>
                                        </div>
                                        <div class="col-xl-2 text-center col-md-2"
                                             style="flex-direction: column-reverse;display: flex;">
                                            @if ($plan['course']['user_id'] == auth('web')->id())
                                                <a type="button"
                                                   class="btn align-items-center btn-primary btn-rounded btn-sm waves-effect waves-light mb-5"
                                                   href="{{ config('app.manage_url').'/home/manage/information/'.$plan->course_id }}"
                                                   target="_blank"
                                                >
                                                    课程管理
                                                </a>
                                            @else
                                                <a  type="button"
                                                    class="btn align-items-center btn-primary btn-rounded btn-sm waves-effect waves-light mb-5"
                                                    href="{{ config('app.manage_url').'/home/manage/information/'.$plan->course_id.'/'.$plan->id }}"
                                                    target="_blank"
                                                >
                                                    版本管理
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                @else
                <div class="alert alert-warning mt-3" role="alert">
                    没有数据...
                </div>
                @endif
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <nav aria-label="Page navigation example">
                        {!! $plans->render() !!}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('partScript')
    <script type="text/javascript" src="{{ asset('dist/my_course/js/index.js') }}"></script>
@endsection