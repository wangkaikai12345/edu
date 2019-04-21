@extends('frontend.default.task.index')

@section('style')

@endsection

@section('iframe')
    <div class="text-center pt-5">
        {!! $task->target['body'] !!}
    </div>
@endsection

@section('script')

@endsection