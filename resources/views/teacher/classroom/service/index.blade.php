@extends('teacher.classroom.classroom_layout')
@section('classroom_style')
    <link rel="stylesheet" href="{{ mix('/css/front/course/notice/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/student/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/classroom/service/index.css') }}">
@endsection
@section('classroom_content')
    <div class="col-xl-9 col-md-12 col-12 form_content p-0">
        <!-- Attach a new card -->
        <form class="form-default" method="post">
            {{ csrf_field() }}
            <div class="card teacher_style">
                <div class="card-body row_content">
                    <div class="row_div">
                        <div class="row">
                            <div class="col-lg-12 row">
                                <div class="col-lg-8 col-md-5 col-sm-4">
                                    <h6>服务设置</h6>
                                </div>
                                {{--<div class="col-lg-4 p-0">--}}
                                {{--<button type="button" class="btn btn-primary add-plan add-item" data-toggle="modal"--}}
                                {{--data-target=".add-student-modal-lg"--}}
                                {{--style="margin-left:210px;width:96px !important;">+ 添加学员--}}
                                {{--</button>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                    </div>
                    <hr class="course_hr">
                    <div class="bd-example">
                        <div class="form_item" id="set_server">
                            <div class="row mt-3 m-0 ml-8 input-content justify-content-center">
                                <div class="col-md-12 col-11" style="padding-left:70px;">
                                    <div class="form-group">
                                        <div class="input-group input-group-transparent">
                                            <label class="control-label col-md-2 col-lg-2 col-xl-2 text-right modal-label modal-last-label">
                                                <span>承诺提供服务</span>
                                            </label>
                                            <div class="promise_service_content" id="select-service">
                                                @foreach($services as $service)
                                                    <div class="service_item">
                                                        <div class="custom-control custom-radio plan_service p-0 {{ in_array($service, $classroom->services) ? 'ser_active' : '' }}">
                                                            {{ $service }}
                                                        </div>
                                                    </div>
                                                    @if(in_array($service, $classroom->services))
                                                        <input type="hidden" name="services[]" value="{{ $service }}">
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form_item">
                            <div class="row mt-3 m-0 ml-8 input-content justify-content-center">
                                <div class="col-md-12 col-11" style="padding-left:70px;">
                                    <div class="form-group">
                                        <div class="input-group input-group-transparent">
                                            <button id="save_data_btn" type="submit" class="btn btn-sm btn-info">保存</button>
                                        </div>
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
    <script>
        // 承诺服务选中
        $('.plan_service').click(function () {
            const html = $(this).html().replace(/\s/g, '');


            if ($(this).hasClass('ser_active')) {

                $(this).removeClass('ser_active');

                $('input[name="services[]"][value="' + html + '"]').remove();

                return;

            } else {

                $(this).addClass('ser_active');

            }

            $(this).parent().parent().append(`<input type="hidden" name="services[]" value="${html}">`)

        });
    </script>
@endsection