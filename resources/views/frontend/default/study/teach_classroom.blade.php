@extends('frontend.default.study.index')
@section('title', '在教班级')

@section('partStyle')
    <link href="{{ asset('dist/my_course/css/index.css') }}" rel="stylesheet">
@endsection

@section('rightBody')
    <div class="col-xl-9 profile">
        <div class="card">
            <form action="" id="signupForm">
                <div class="card-body">
                    <h6 class="card-title">在教班级 <a href="{{ config('app.manage_url').'/create_class' }}" class="float-right font-small pt-2">创建班级</a></h6>
                    <hr class="pb-3">
                    <div class="alert alert-warning mt-3" role="alert">
                        没有数据...
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('partScript')
    <script type="text/javascript" src="{{ asset('dist/my_course/js/index.js') }}"></script>
@endsection