@extends('frontend.review.plan.layout')
@section('leftBody')

    <div class="tab-pane folder" role="tabpanel"
         aria-labelledby="follow-tab-classic">
        <div class="accordion md-accordion" role="tablist"
             aria-multiselectable="true" id="accordionEx">
            @if ($chapters->count())
                @foreach($chapters as $key => $chapter)

                    <div class="card">

                        <div class="card-header" role="tab" id="headingOne1">
                            <a data-toggle="collapse" data-parent="#accordionEx"
                               href="#co{{ $chapter['id'] }}" aria-expanded="true"
                               aria-controls="co{{ $chapter['id'] }}">
                                <h6 class="mb-0 float-left">
                                    <i class="iconfont">
                                        &#xe63a;
                                    </i></i>第{{ $key+1 }}章：{{ $chapter['title'] }} <i
                                            class="fas fa-angle-down rotate-icon"></i>
                                </h6>
                                <i class="iconfont float-right">
                                    &#xe624;
                                </i>
                            </a>
                        </div>

                        @foreach($chapter['children'] as $k => $child )
                            <div id="co{{ $chapter['id'] }}" class="collapse show sub_title" role="tabpanel"
                                 aria-labelledby="headingOne1"
                                 data-parent="#accordionEx">
                                <div class="card-body section-wrap p-0">
                                    <h6 class="mb-0 task section">
                                        <i class="iconfont">
                                            &#xe63e;

                                        </i>
                                        <span>
                                            第{{ $k+1 }}节：{{ $child['title'] }}
                                        </span>
                                        <a href="{{ $child->tasks->count() ? route('tasks.show', $child) :'javascript:;' }}" class="iconfont float-right" style="font-size: 14px;color: #666666;font-weight: 400;">开始学习</a>
                                    </h6>
                                    @foreach($child['tasks'] as $tas)

                                        <h6 class="task">
                                            <a href="{{ $plan->learn_mode == 'free' ? route('tasks.show', [$tas->chapter, 'task' => $tas->id]) :'javascript:;' }}">
                                                <i class="iconfont">{{ render_task_class($tas['target_type']) }}</i>
                                                <span>
                                                    {{ render_task_type($tas['type']) }}:{{ $tas['title'] }}
                                                </span>
                                                <i class="iconfont float-right" style="font-size: 22px;" data-status="{{ $tas->currentResult(auth('web')->id()) }}">
                                                    {{ $tas->currentResult(auth('web')->id()) ? ($tas->currentResult(auth('web')->id()) == 'finish' ? '&#xe609;' : '&#xe602;' ): '&#xe645;' }}
                                                </i>
                                            </a>
                                        </h6>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                    </div>
                @endforeach
            @else
                <span class="no_data">
                    还没有任务目录...
                </span>
            @endif
        </div>
    </div>
@endsection







