@extends('frontend.default.study.index')
@section('title', '我的笔记')

@section('partStyle')
    <link href="{{ asset('dist/my_note/css/index.css') }}" rel="stylesheet">
@endsection

@section('rightBody')
    <div class="col-xl-9">
        <div class="card">
            <div class="card-body">
                <!--Title-->
                <h6 class="card-title">我的笔记</h6>
                <hr>
                <!--Text-->
                <div class="classic-tabs pl-3 pr-3">
                    @if(count($notes))
                        @foreach($notes as $k => $note)
                            <div class="row mb-3 mt-3">
                                <div class="col-xl-12 p-0">
                                    <!--Accordion wrapper-->
                                    <div class="accordion" id="accordionEx" role="tablist" aria-multiselectable="true">
                                        <div class="card">
                                            <div class="card-body text-center" role="tab" id="headingOne1">
                                                <a data-toggle="collapse" data-parent="#accordionEx" href="#collapseOne{{ $k }}" aria-expanded="true"
                                                   aria-controls="collapseOne{{ $k }}" class="row">
                                                    <div class="col-xl-4 col-md-4">
                                                        <img class="card-img-top" src="{{ render_cover($note['course']['cover'], 'course') }}"
                                                             alt="Card image cap">
                                                    </div>
                                                    <div class="col-xl-6 text-left col-md-6">
                                                        <h6 class="font-weight-bold mb-3 text-truncate mt-5">{{ $note['course']['title'].'-'.$note['plan']['title'] }}</h6>
                                                        <p class="text-uppercase font-small">共有 {{ $note['course']['notes_count'] }} 篇笔记</p>
                                                        <p class="text-uppercase font-small">最后更新 {{ $note['updated_at'] }}</p>
                                                    </div>
                                                    <div class="col-xl-2 text-center col-md-2"
                                                         style="flex-direction: column-reverse;display: flex;">
                                                        <button type="button"
                                                                class="btn align-items-center btn-primary btn-rounded btn-sm waves-effect waves-light mb-5"
                                                                data-toggle="modal" data-target="#fullHeightModalRight">查看笔记
                                                        </button>
                                                    </div>
                                                </a>
                                            </div>
                                            <div id="collapseOne{{ $k }}" class="collapse" role="tabpanel" aria-labelledby="headingOne1" data-parent="#accordionEx">
                                                <div class="card-body">
                                                    <h6 class="font-small">
                                                        <b>任务:</b>{{ $note['task']['title'] }}
                                                    </h6>
                                                    <div class=" font-small">
                                                        <b>我的笔记：</b> <br>
                                                        {{ $note['content'] }}
                                                    </div>
                                                </div>
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
                            {!! $notes->render() !!}
                        </nav>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('partScript')
    <script type="text/javascript" src="{{ asset('dist/my_note/js/index.js') }}"></script>
@endsection