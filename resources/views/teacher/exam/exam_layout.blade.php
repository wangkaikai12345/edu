@extends('teacher.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('/css/teacher/exam/index.css') }}">
    @yield('exam_style')
@endsection

@section('header')
    @include('teacher.header', ['nav_name' => 'question'])
@endsection

@section('content')
    <div class="xh_exam_content">
        <div class="container">
            <div class="row padding-content">
                @include('teacher.exam.navBar')
                @yield('exam_content')
            </div>
        </div>
    </div>
@endsection
@section('script')
    @yield('exam_script')
@endsection