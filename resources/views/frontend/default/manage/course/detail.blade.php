@extends('frontend.default.manage.course.index')
@section('title', '课程 详细信息')

@section('partStyle')
    <link href="{{ asset('dist/course_manage_detail/css/index.css') }}" rel="stylesheet">
@endsection

@section('rightBody')
    <div class="col-xl-9 profile">
        <div class="card">
            <form action="" id="signupForm">
                <div class="card-body">
                    <h6 class="card-title">详细信息</h6>
                    <hr class="pb-3">
                    <!-- Horizontal material form -->
                    <form>
                        <div class="form-group row col-xl-12 pl-0 mb-4">
                            <label for="editor" class="col-sm-2 col-form-label pr-0">课程简介</label>
                            <div class="col-sm-10">
                                <script id="editor" name="content" type="text/plain">这里写你的初始化内容</script>
                            </div>
                        </div>

                        <div class="form-group row col-xl-12 pl-0 mb-4">
                            <label for="goal" class="col-sm-2 col-form-label pr-0">课程目标</label>
                            <div class="col-sm-10">
                                <div class="chips chips-placeholder" id="goal"></div>
                            </div>
                        </div>

                        <div class="form-group row col-xl-12 pl-0 mb-4">
                            <label for="people" class="col-sm-2 col-form-label pr-0">适应人群</label>
                            <div class="col-sm-10">
                                <div class="chips chips-placeholder" id="people"></div>
                            </div>
                        </div>
                    </form>
                    <!-- Horizontal material form -->
                </div>
            </form>
        </div>
    </div>
@endsection

@section('partScript')
    <script type="text/javascript" src="{{ asset('dist/course_manage_detail/js/index.js') }}"></script>
@endsection