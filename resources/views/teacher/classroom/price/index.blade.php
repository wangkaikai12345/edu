@extends('teacher.classroom.classroom_layout')
@section('classroom_style')
    <link rel="stylesheet" href="{{ mix('/css/front/course/notice/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/student/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/classroom/price/index.css') }}">
@endsection
@section('classroom_content')
    <div class="col-xl-9 col-md-12 col-12 form_content p-0">
        <form class="form-default" action="{{ route('manage.classroom.price', $classroom) }}" method="post">
            {{ csrf_field() }}
            {{ method_field('patch') }}
            <div class="card teacher_style">
                <div class="card-body row_content">
                    <div class="row_div">
                        <div class="row">
                            <div class="col-lg-12 row mt-0">
                                <div class="col-lg-8 col-md-5 col-sm-4">
                                    <h6>价格设置</h6>
                                </div>
                            </div>
                        </div>
                        <hr class="course_hr">
                    </div>
                    <div class="row mt-3 ml-8 input-content" style="margin-top:50px !important;">
                        <div class="col-md-10">
                            <div class="form-group focused">
                                <div class="input-group input-group-transparent">
                                    <label class="control-label col-md-12 col-lg-3 col-xl-3 text-right pt-1">价格(元)</label>
                                    <input required="" type="num" name="price" id="price"
                                           class="form-control col-md-12 col-lg-9 col-xl-9 ml-2" placeholder="0"
                                           value="{{ ftoy($classroom->price) }}">
                                </div>
                                <p>当前共有 {{ $plansCount }} 个课程，原价共计 {{ $plansPrice }} 元。</p>
                            </div>
                        </div>
                    </div>
                    {{--<div class="row mt-3 ml-8 input-content">--}}
                        {{--<div class="col-md-10">--}}
                            {{--<div class="form-group focused">--}}
                                {{--<div class="input-group input-group-transparent">--}}
                                    {{--<label class="control-label col-md-12 col-lg-3 col-xl-3 text-right pt-1">会员规则设置</label>--}}
                                    {{--<select class="form-control col-md-12 col-lg-5 col-xl-5 ml-2"--}}
                                            {{--id="exampleFormControlSelect1 c">--}}
                                        {{--<option>1</option>--}}
                                        {{--<option>2</option>--}}
                                        {{--<option>3</option>--}}
                                        {{--<option>4</option>--}}
                                        {{--<option>5</option>--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                                {{--<p>设置班级课程后，该会员及更高等级的会员，可免费加入。</p>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="row mt-3 ml-8 input-content">
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="input-group input-group-transparent">
                                    <label class="control-label col-md-12 col-lg-3 col-xl-3 text-left"></label>
                                    <div class="col-md-9 col-lg-9 col-xl-9 p-0 pl-2">
                                        <button type="submit" href="javascript:;"
                                                class="btn btn-sm btn-primary btn-submit" id="xxx">提交
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('classroom_script')
@endsection