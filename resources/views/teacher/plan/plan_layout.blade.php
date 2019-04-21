@extends('teacher.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('/css/teacher/plan/index.css') }}">
    @yield('plan_style')
@endsection

@section('header')
    @include('teacher.header', ['nav_name' => 'teach_course'])
@endsection

@section('content')
    <div class="wrapper">
        <div class="plan_content">
            @include('teacher.plan.header')
            <div class="container">
                <div class="row padding-content">
                    @include('teacher.plan.navBar')
                    @yield('plan_content')
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
{{--    <script>--}}
{{--        /**--}}
{{--         * 发布版本--}}
{{--         */--}}
{{--        $('.plan-publish').click(function () {--}}
{{--            var url = $(this).data('url');--}}

{{--            if (!url) { edu.alert('danger', '请选择版本'); return false;}--}}
{{--            var status = $(this).data('status');--}}

{{--            if (!status) { edu.alert('danger', '请选择版本'); return false;}--}}

{{--            $.ajax({--}}
{{--                'url': url,--}}
{{--                'type': 'patch',--}}
{{--                'data': {status: status},--}}
{{--                'success': function(res) {--}}
{{--                    if (res.status == '200') {--}}
{{--                        edu.alert('success', '操作成功');--}}
{{--                        window.location.reload();--}}
{{--                    } else {--}}
{{--                        edu.alert('danger', res.message);--}}
{{--                    }--}}
{{--                }--}}
{{--            });--}}

{{--        });--}}
{{--    </script>--}}
    @yield('plan_script')
@endsection