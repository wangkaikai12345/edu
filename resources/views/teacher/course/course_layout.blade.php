@extends('teacher.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('/css/front/course/index.css') }}">
    @yield('course_style')
@endsection

@section('header')
    @include('teacher.header', ['nav_name' => 'teach_course'])
@endsection

@section('content')
        <div class="wrapper">
            <div class="xh_content_wrap">
                <div class="container">
                    @include('teacher.course.header')
                    <div class="row padding_content">
                        @include('teacher.course.navBar')
                        @yield('course_content')
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('script')
    <script>
        window.onload = () => {
            $(function() {
                @if (Session::has('error'))
                edu.alert('danger', "{{ Session::get('error') }}");
                @endif

                @if (Session::has('success'))
                edu.alert('success', "{{ Session::get('success') }}");
                @endif

                @if (Session::has('danger'))
                edu.alert('danger', "{{ Session::get('danger') }}");
                @endif

                @if (count($errors) > 0)
                edu.alert('danger', "{{ $errors->all()[0] }}");
                @endif
            })
        };
    </script>
    @yield('course_script')
@endsection

