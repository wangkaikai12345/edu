@extends('frontend.default.task.index')

@section('style')

@endsection

@section('iframe')

    <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{ render_task_source($task->target['media_uri']) }}"
            width='100%' height='100%' frameBorder='1'>
    </iframe>

@endsection

@section('script')

@endsection