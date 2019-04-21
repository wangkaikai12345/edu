@extends('teacher.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/course/index.css') }}">
    @yield('classroom_style')
@endsection

@section('header')
    @include('teacher.header', ['nav_name' => 'classroom'])
@endsection

@section('content')
    <div class="wrapper">
        <div class="xh_content_wrap">
            <div class="container">
                @include('teacher.classroom.header')
                <div class="row padding_content">
                    @include('teacher.classroom.navBar')
                    @yield('classroom_content')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    @yield('classroom_script')
@endsection