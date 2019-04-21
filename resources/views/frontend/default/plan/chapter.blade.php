@extends('frontend.default.plan.show')
@section('title', '版本学习-目录')
@section('leftBody')
    <div class="tab-pane" role="tabpanel">
        <!--Accordion wrapper-->
        <div class="accordion md-accordion" role="tablist" id="accordionEx">
            @foreach($chapters as $key => $chapter)
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header" role="tab">
                        <a data-toggle="collapse" data-parent="#accordionEx"
                           href="#ch{{ $chapter['id'] }}" aria-expanded="false"
                           aria-controls="ch{{ $chapter['id'] }}">
                            <h6 class="mb-0">
                                <i class="fas fa-bars mr-3"></i>第{{ $key+1 }}章：{{ $chapter['title'] }} <i
                                        class="fas fa-angle-down rotate-icon"></i>
                            </h6>
                        </a>
                    </div>

                @foreach($chapter['children'] as $k => $child )
                    <!-- Card body -->
                        <div id="ch{{ $chapter['id'] }}" class="collapse" role="tabpanel"
                             data-parent="#accordionEx">
                            <div class="card-body section-wrap">
                                <h6 class="mb-0 task section">
                                    第{{ $k+1 }}节：{{ $child['title'] }}
                                </h6>

                                @foreach($child['tasks'] as $task)
                                    <h6 class="task">
                                        <i class="fas {{ render_task_class($task['target_type']) }} mr-3"></i>{{ render_task_type($task['type']) }}：{{ $task['title'] }}
                                    </h6>
                                @endforeach

                            </div>
                        </div>
                    @endforeach

                </div>
            @endforeach
        </div>
        <!-- Accordion wrapper -->
    </div>
@endsection







