@extends('teacher.layouts.app')
@section('style')
    @yield('homework_style')
@endsection

@section('header')
    @include('teacher.header', ['nav_name' => 'homework'])
@endsection

@section('content')
    <div class="czh_homework_content">
        <div class="container">
            <div class="row padding-content">
                @include('teacher.homework.navBar')
                @yield('homework_content')
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('vendor/jquery.validate.min.js') }}"></script>
    <script src="/tools/qiniu/qiniu2.min.js"></script>
    <script src="/tools/sha1.js"></script>
    <script src="/tools/qetag.js"></script>
    <script src="/tools/qiniu/qiniu-luwnto.js"></script>
    @yield('homework_script')
@endsection