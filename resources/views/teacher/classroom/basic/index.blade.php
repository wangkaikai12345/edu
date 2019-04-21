@extends('teacher.classroom.classroom_layout')
@section('classroom_style')
    <link rel="stylesheet" href="{{ '/vendor/select2/dist/css/select2.min.css' }}">
    <link rel="stylesheet" href="{{ mix('/css/front/course/notice/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/student/index.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/teacher/classroom/basic/index.css') }}">
@endsection
@section('classroom_content')
    <div class="col-xl-9 col-md-12 col-12 form_content p-0">
        <form class="form-default" action="{{ route('manage.classroom.update', $classroom) }}" method="post">
            {{ csrf_field() }}
            {{ method_field('put') }}
            <div class="card teacher_style">
                <div class="card-body row_content pl-0 pr-0">
                    <div class="row_div">
                        <div class="row">
                            <div class="col-lg-8">
                                <h6>课程信息</h6>
                            </div>
                            <div class="col-lg-4 text-lg-right">
                            </div>
                        </div>
                        <hr style="margin:0">
                    </div>
                    <div class="row m-0">
                        <div class="son_content">
                            基础信息
                        </div>
                    </div>
                    <div class="row mt-3 ml-8 input-content">
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="input-group input-group-transparent">
                                    <label class="control-label col-md-12 col-lg-3 col-xl-3 text-left">标题</label>
                                    <input required
                                           type="text" name="title" id="title"
                                           class="form-control col-md-12 col-lg-9 col-xl-9"
                                           placeholder="请输入标题" value="{{ $classroom->title }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 ml-8 input-content">
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="input-group input-group-transparent">
                                    <label class="control-label col-md-12 col-lg-3 col-xl-3 text-left">简介</label>
                                    <div class="col-md-12 col-lg-9 col-xl-9 p-0">
                                        <script id="editor" name="description"
                                                type="text/plain">{!! $classroom->description !!}</script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 ml-8 input-content">
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="input-group input-group-transparent">
                                    <label class="control-label col-md-12 col-lg-3 col-xl-3 text-center">标签</label>
                                    <div class="col-md-12 col-lg-9 col-xl-9 p-0">
                                        <select style="width: 549px;" id="label" name="tags[]" class="form-control"
                                                data-toggle="select" title="Option groups"
                                                data-live-search="true" data-live-search-placeholder="Search ..."
                                                multiple>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 ml-8 input-content">
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="input-group input-group-transparent">
                                    <label class="control-label col-md-12 col-lg-3 col-xl-3 text-center">分类</label>
                                    <select class="form-control col-md-12 col-lg-4 col-xl-4" name="category_id">
                                        <option value="0">请选择分类</option>
                                        @foreach($category as $c)
                                            <option {{ $classroom->category_id == $c->id ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr style="margin:0;">
                <div class="card-body row_content pl-0">
                    <div class="row m-0">
                        <div class="son_content">
                            详细信息
                        </div>
                    </div>
                    <div class="row mt-3 ml-8 input-content">
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="input-group input-group-transparent">
                                    <label class="control-label col-md-12 col-lg-3 col-xl-3 text-left">封面图片</label>

                                    <div class="col-md-12 col-lg-9 col-xl-9 p-0">
                                        {{--<div class="img-content" data-target="#imageIcoModal" data-toggle="modal"--}}
                                        {{--id="openCropperIco">--}}
                                        {{--<img src="{{ render_cover($classroom->cover, 'classroom') }}"--}}
                                        {{--width="284" height="160"--}}
                                        {{--id="image_ico"--}}
                                        {{--data-domain="{{ config('filesystems.disks.imgs.domains.default') }}"--}}
                                        {{--/>--}}
                                        {{--</div>--}}
                                        {{--<div id="upload-field"></div>--}}
                                        {{--<p class="ml-0 mt-2 mb-2 mr-auto">你可以上传jpg, gif, png格式的文件,--}}
                                        {{--图片建议尺寸至少为480x270,文件大小不能超过2M。</p>--}}
                                        {{--<input type="file" name="cover" id="cover"--}}
                                        {{--class="custom-input-file custom-input-file--2"--}}
                                        {{--data-multiple-caption="{count} files selected" multiple/>--}}
                                        {{--<label for="file-2">--}}
                                        {{--<i class="fa fa-upload"></i>--}}
                                        {{--<span>选择要上传的图片</span>--}}
                                        {{--</label>--}}

                                        <div class="img-content" data-target="#imageIcoModal" data-toggle="modal"
                                             id="openCropperIco">
                                            <img src="{{ render_cover($classroom->cover, 'classroom') }}"
                                                 width="284" height="160"
                                                 id="image_classroom"
                                                 data-domain="{{ fileDomain() }}"
                                            />
                                        </div>

                                        <input type="hidden" name="cover" value="{{ $classroom->cover }}">
                                        <input type="hidden" name="current"
                                               value="{{ render_cover($classroom->cover, 'classroom') }}">

                                        @component('admin.layouts.image_format')
                                            @slot('modalId')
                                                imageIcoModal
                                            @endslot
                                            @slot('imageUrl')
                                                {{ render_cover($classroom->cover, 'classroom') }}
                                            @endslot
                                            @slot('imageHeight')
                                                500
                                            @endslot
                                            @slot('openCropper')
                                                openCropperIco
                                            @endslot
                                            @slot('imageScript')

                                                $('#image_classroom').attr('src',
                                                $('#image_classroom').data('domain')+'/'+imageName);
                                                $('input[name="cover"]').val(imageName);

                                                {{--$('#imageIcoModal').modal('hide')--}}
                                                $('.close').trigger('click');
                                                $('.modal-backdrop').remove();
                                                $('#imageIcoModal .reset').trigger('click');

                                            @endslot
                                            @slot('imageInput')
                                                input[name="current"]
                                            @endslot
                                            @slot('aspectRatio')
                                                16 / 9
                                            @endslot

                                            @slot('localSuccessCallback')
                                                $('#image_classroom').attr('src',
                                                $('#image_classroom').data('domain')+'/'+this.savedPath);
                                                $('input[name="cover"]').val(this.savedPath);

                                                $('.close').trigger('click');
                                                $('#imageIcoModal .reset').trigger('click');
                                            @endslot

                                        @endcomponent

                                        <div id="upload-field"></div>

                                        <p class="ml-0 mt-2 mb-2 mr-auto">你可以上传jpg, gif, png格式的文件,
                                            图片建议尺寸至少为480x270,文件大小不能超过2M。</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 ml-8 input-content">
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="input-group input-group-transparent">
                                    <label class="control-label col-md-12 col-lg-3 col-xl-3 text-left">预览视频</label>
                                    <div class="col-md-12 col-lg-9 col-xl-9 p-0">
                                        {{--<div class="img-content mb-4" data-target="#imageVideoModal" data-toggle="modal"--}}
                                        {{--id="openCropperIco">--}}
                                        {{--<div class="plan_content">--}}
                                        {{--<img src="/imgs/play.png" alt="">--}}
                                        {{--</div>--}}
                                        {{--<img src="/imgs/classroom.png"--}}
                                        {{--width="284" height="160"--}}
                                        {{--id="image_ico"--}}
                                        {{--data-domain="{{ config('filesystems.disks.imgs.domains.default') }}"--}}
                                        {{--/>--}}
                                        {{--<input type="hidden" class="form-con" id="file-video-key" name="preview" value="{{ $classroom->preview }}">--}}
                                        {{--<input type="hidden" class="form-con" id="file-video-hash" value="{{ $classroom->preview }}">--}}
                                        {{--</div>--}}
                                        {{--<input type="file" name="" id="video-up" accept="video/*"--}}
                                        {{--class="custom-input-file custom-input-file--2"--}}
                                        {{--data-token="{{ route('manage.qiniu.token.hash') }}"/>--}}
                                        {{--<label for="video-up">--}}
                                        {{--<i class="fa fa-upload"></i>--}}
                                        {{--<span>选择要上传的视频</span>--}}
                                        {{--</label>--}}
                                        {{--<div class="progress-wrapper" id="video_progress" style="display: none">--}}
                                        {{--<h4 class="progress-tooltip" style="left: 0%;" id="video_progress_show">0%</h4>--}}
                                        {{--<div class="progress" style="height: 3px;">--}}
                                        {{--<div class="progress-bar bg-primary"--}}
                                        {{--id="video_progress_color"--}}
                                        {{--role="progressbar" aria-valuenow="0" aria-valuemin="0"--}}
                                        {{--aria-valuemax="100" style="width:0%;"></div>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}

                                        <div class="desc_input">
                                            <video src="{{ $classroom->preview ? render_cover($classroom->preview, '') : '' }}"
                                                   controls="controls"
                                                   id="show_video"
                                                   data-domain="{{ fileDomain() }}"
                                            ></video>
                                        </div>
                                        <div class="desc_input" style="margin-top:20px;margin-bottom: 10px;">
                                            <input type="file" name="video" id="upload_file" accept="video/*"
                                                   class="custom-input-file custom-input-file--2"
                                                   data-token="{{ route('manage.qiniu.token.hash') }}"/>
                                            <label for="upload_file"
                                                   style="font-size: 16px;display: block;padding: 10px 10px !important;">
                                                <i class="iconfont" style="float:left;font-size:25px;">&#xe658;</i>
                                                <span id="file_name" style="display:block;margin-top: 2px;">请选择文件</span>
                                            </label>
                                            <input type="hidden" class="form-con" name="preview" id="preview" value="{{ $classroom->preview ? $classroom->preview : '' }}">
                                        </div>
                                        <div id="progress_bar" style="display: none" class="desc_input">
                                            @include('vendor.progress.progress', ['editStatus' => '', 'type'=> 'classroomvideo'])

                                            <div class="aetherupload-progressbar"
                                                 style="background:blue;height:6px;width:0;"></div>
                                            <!--需要一个名为aetherupload-progressbar的id，用以标识进度条-->

                                            <span style="font-size:12px;color:#aaa;" class="aetherupload-output"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 ml-8 input-content">
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="input-group input-group-transparent">
                                    <label class="control-label col-md-12 col-lg-3 col-xl-3 text-left modal-label">学习模式</label>
                                    <div class="col-md-12 col-xl-9 col-lg-9 row">
                                        <div class="col-md-6 col-xl-2 col-sm-6 col-lg-6 col-6 p-0 pl-4">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="learn_mode"
                                                       class="custom-control-input"
                                                       {{ $classroom->learn_mode == \App\Enums\ClassroomMode::HAND ? 'checked' : '' }}
                                                       id="learn_mode1" value="{{ \App\Enums\ClassroomMode::HAND }}">
                                                <label class="custom-control-label" for="learn_mode1">手动</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-2 col-sm-6 col-6 col-lg-3 p-0 pl-4">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="learn_mode"
                                                       class="custom-control-input"
                                                       {{ $classroom->learn_mode == \App\Enums\ClassroomMode::PASS ? 'checked' : '' }}
                                                       id="learn_mode2" value="{{ \App\Enums\ClassroomMode::PASS }}">
                                                <label class="custom-control-label" for="learn_mode2">通关</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-2 col-sm-6 col-6 col-lg-3 p-0 pl-4">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="learn_mode"
                                                       class="custom-control-input"
                                                       {{ $classroom->learn_mode == \App\Enums\ClassroomMode::ALL ? 'checked' : '' }}
                                                       id="learn_mode3" value="{{ \App\Enums\ClassroomMode::ALL }}">
                                                <label class="custom-control-label" for="learn_mode3">全部</label>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="input-group-transparent-font">学员通关模式。</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 ml-8 input-content">
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="input-group input-group-transparent">
                                    <label class="control-label col-md-12 col-lg-3 col-xl-3 text-left modal-label">班级购买</label>
                                    <div class="col-md-12 col-xl-9 col-lg-9 row">
                                        <div class="col-md-6 col-xl-2 col-sm-6 col-lg-6 col-6 p-0 pl-4">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="is_buy"
                                                       class="custom-control-input"
                                                       {{ $classroom->is_buy == 1 ? 'checked' : '' }}
                                                       id="is_buy1" value="1">
                                                <label class="custom-control-label" for="is_buy1">开启</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-2 col-sm-6 col-6 col-lg-3 p-0 pl-4">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="is_buy"
                                                       class="custom-control-input"
                                                       {{ $classroom->is_buy == 0 ? 'checked' : '' }}
                                                       id="is_buy2" value="0">
                                                <label class="custom-control-label" for="is_buy2">关闭</label>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="input-group-transparent-font">关闭后班级将无法在线购买加入。</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3 ml-8 input-content">
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="input-group input-group-transparent">
                                    <label class="control-label col-md-12 col-lg-3 col-xl-3 text-left modal-label">班级展示</label>
                                    <div class="col-md-12 col-xl-9 col-lg-9 row">
                                        <div class="col-md-6 col-xl-2 col-sm-6 col-lg-6 col-6 p-0 pl-4">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="is_show"
                                                       class="custom-control-input"
                                                       {{ $classroom->is_show == 1 ? 'checked' : '' }}
                                                       id="is_show1" value="1">
                                                <label class="custom-control-label" for="is_show1">开启</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-2 col-sm-6 col-6 col-lg-3 p-0 pl-4">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="is_show"
                                                       class="custom-control-input"
                                                       {{ $classroom->is_show == 0 ? 'checked' : '' }}
                                                       id="is_show2" value="0">
                                                <label class="custom-control-label" for="is_show2">关闭</label>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="input-group-transparent-font">开启后班级将显示在前台, 内部使用班级推荐关闭。</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3 ml-8 input-content">
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="input-group input-group-transparent">
                                    <label class="control-label col-md-12 col-lg-3 col-xl-3 text-left modal-label">
                                        学习有效期
                                        <i class="iconfont">&#xe640;</i>
                                    </label>
                                    <div class="col-md-12 col-xl-9 col-lg-9 row">
                                        <div class="col-md-6 col-xl-3 col-sm-6 col-lg-6 col-6 p-0 pl-4">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="expiry_mode"
                                                       class="custom-control-input expiry_mode"
                                                       {{ $classroom->expiry_mode == 'forever' ? 'checked' : '' }}
                                                       id="expiry_mode1" value="forever">
                                                <label class="custom-control-label" for="expiry_mode1">永久有效</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-sm-6 col-6 col-lg-3 p-0 pl-1">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="expiry_mode"
                                                       class="custom-control-input expiry_mode"
                                                       {{ $classroom->expiry_mode == 'valid' ? 'checked' : '' }}
                                                       id="expiry_mode2" value="valid">
                                                <label class="custom-control-label" for="expiry_mode2">随到随学</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-sm-6 col-6 col-lg-3 p-0 pl-1">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="expiry_mode"
                                                       class="custom-control-input expiry_mode"
                                                       {{ $classroom->expiry_mode == 'period' ? 'checked' : '' }}
                                                       id="expiry_mode3" value="period">
                                                <label class="custom-control-label" for="expiry_mode3">固定周期</label>
                                            </div>
                                        </div>

                                        {{-- 时间范围--}}
                                        <div class="col-12 row mt-3 pb-3" id="period"
                                             style="display:{{ $classroom->expiry_mode == 'period' ? 'block' : 'none' }}">
                                            <div class="col-10 p-0">
                                                <div class="form-group">
                                                    <div class="input-group input-group-transparent text-center">
                                                        开始日期 {{ !empty($classroom->expiry_started_at) ? '('. $classroom->expiry_started_at .')' : '' }}
                                                        <input name="expiry_started_at" type="date"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-10 p-0">
                                                <div class="form-group">
                                                    <div class="input-group input-group-transparent text-center">
                                                        结束日期 {{ !empty($classroom->expiry_started_at) ? '('. $classroom->expiry_ended_at .')' : '' }}
                                                        <input name="expiry_ended_at" type="date"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- 有效天数--}}
                                        <div class="col-12 row mt-3" id="valid"
                                             style="display:{{ $classroom->expiry_mode == 'valid' ? 'block' : 'none' }}">
                                            <div class="col-12 p-0">
                                                <div class="form-group">
                                                    <div class="input-group input-group-transparent text-center">
                                                        <input type="number" name="expiry_days" value="0"
                                                               class="form-control col-md-3 col-lg-3 col-xl-2 ml-2"
                                                               style="margin-right:10px;padding-left:10px;">
                                                        天之内，学员可进行学习。
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-warning input-group-transparent-font">
                                        班级首次发布后，有效期类型不能再更改，只允许修改有效日期。</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 ml-8 input-content">
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="input-group input-group-transparent">
                                    <label class="control-label col-md-12 col-lg-3 col-xl-3 text-left"></label>
                                    <div class="col-md-9 col-lg-9 col-xl-9 p-0">
                                        <button type="submit" class="btn btn-sm btn-primary btn-submit" id="xxx">提交
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

    {{--<div class="modal fade add-student-modal-lg" id="imageVideoModal" tabindex="-1" role="dialog" aria-hidden="true">--}}
    {{--<div class="modal-dialog modal-lg" role="document">--}}
    {{--<div class="modal-content">--}}
    {{--<div class="modal-header">--}}
    {{--<h5 class="modal-title" id="exampleModalLabel">预览视频</h5>--}}
    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
    {{--<span aria-hidden="true">&times;</span>--}}
    {{--</button>--}}
    {{--</div>--}}
    {{--<div class="modal-body">--}}
    {{--<div class="row mt-4 m-0 ml-8 input-content justify-content-center">--}}
    {{--<div class="col-9 mb-3">--}}
    {{--<div class="form-group">--}}
    {{--@if (!empty($classroom->preview))--}}
    {{--<video src="{{ render_cover($classroom->preview, 'render_cover') }}"--}}
    {{--controls="controls" width="100%" height="100%"--}}
    {{--data-domain=""></video>--}}
    {{--@else--}}
    {{--<div style="width: 100%; height:100px;text-align: center;line-height: 60px;">暂无视频</div>--}}
    {{--@endif--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="modal-footer">--}}
    {{--<button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
@endsection
@section('classroom_script')
    {{--裁剪--}}
    <script src="/backstage/global/vendor/cropper/cropper.min.js"></script>

    <script src="/vendor/ueditor/ueditor.config.js"></script>
    <script src="/vendor/ueditor/ueditor.all.js"></script>
    <script src="/tools/qiniu/qiniu2.min.js"></script>
    <script src="/tools/sha1.js"></script>
    <script src="/tools/qetag.js"></script>
    <script src="/tools/qiniu/qiniu-luwnto.js"></script>
    <script>
        $(function () {
            $('.expiry_mode').change(function () {

                switch ($(this).val()) {
                    // 时间范围
                    case 'period':
                        $('#period').css('display', 'block');
                        $('#valid').css('display', 'none');
                        break;
                    // 有效天数
                    case 'valid':
                        $('#valid').css('display', 'block');
                        $('#period').css('display', 'none');
                        break;
                    // 永久有效
                    case 'forever':
                        $('#period').css('display', 'none');
                        $('#valid').css('display', 'none');
                        break;
                    default:

                }
            })

            var ue = UE.getEditor('editor', {
                UEDITOR_HOME_URL: '/vendor/ueditor/',
                serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
            });

            /**
             * 标签相关的select2搜索+多选
             */
            var labels = @json($labels);

            var check =  @json($classroom->tags);

            $("#label").select2({
                maximumSelectionSize: 20,
                placeholder: '请输入标签名称',
                createSearchChoice: function (term, data) {
                    if ($(data).filter(function () {
                        return this.text.localeCompare(term) === 0;
                    }).length === 0) {
                        return {id: term, text: term};
                    }
                },
                multiple: true,
                data: labels,
            });

            $.each(check, function (index, ele) {
                // Jason 因為原本資料不存在，所以會被加入，其他則僅是替換
                var option = new Option(ele.name, ele.id, true, true);
                $("#label").append(option).trigger('change');
            });


            /**
             * 视频上传触发的函数
             */
//            $('#video-up').change(function (event) {
//                var files = event.target.files;
//                uploadFile(files, $(this).data('token'), 'zip', '', function (type, res) {
//                    $('#video_progress').show();
//                    if (type == 'complete') {
//                        // 上传成功之后将文件key放入隐藏id
//                        $('#file-video-key').val(res.key);
//                        $('#file-video-hash').val(res.hash);
//                        edu.alert('success', '视频上传成功!');
//                    } else if (type == 'next') {
//                        $('#video_progress_show').html(res.total.percent.toFixed(2)+'%');
//                        $('#video_progress_show').css('left', res.total.percent.toFixed(2)+'%');
//                        $('#video_progress_color').css('width', res.total.percent.toFixed(2)+'%');
//
//                    } else if (type == 'exist') {
//                        $('#file-video-key').val(res.data.url);
//                        $('#file-video-hash').val(res.data.hash);
//
//                        $('#video_progress_show').html('100%');
//                        $('#video_progress_show').css('left', '100%');
//                        $('#video_progress_color').css('width', '100%');
//
//                        edu.alert('success', '视频上传成功!');
//
//                    } else if (type == 'err') {
//                        edu.alert('danger', res.message);
//                    }
//                });
//            });

            $('#upload_file').change(function (event) {
                var files = event.target.files;

                if (files[0].size > 10485760) {
                    edu.alert('danger', '请上传小于10M的视频');
                    return false;
                }

                // 显示进度条并显示文件名
                $('#progress_bar').show();
                $('#file_name').html(files[0].name);

                // 获取当前的配置环境
                @php
                    $setting = \Facades\App\Models\Setting::namespace('qiniu');

                    $driver = data_get($setting, 'driver', 'local');
                @endphp

                @if($driver == 'local')
                aetherupload(files[0], 'video').success(function () {
                    $('#preview').val(this.savedPath);

                    $('#show_video').attr('src', $('#show_video').data('domain') + '/' + this.savedPath);
                }).upload();
                @else
                uploadFile(files, $(this).data('token'), 'img', '', function (type, res) {
                    $('.progress-wrapper').show();
                    if (type == 'complete') {
                        // 上传成功之后将文件key放入隐藏id
                        $('#preview').val(res.key);

                        $('#show_video').attr('src', $('#show_video').data('domain') + '/' + res.key);

                        edu.alert('success', '预览视频上传成功!');

                    } else if (type == 'next') {

                        $('#classroomvideoprogress').html(res.total.percent.toFixed(2) + '%');
                        $('#classroomvideoprogress').css('left', res.total.percent.toFixed(2) + '%');
                        $('#_classroomvideoprogress').css('width', res.total.percent.toFixed(2) + '%');

                    } else if (type == 'exist') {
                        $('#preview').val(res.data.url);
                        $('#classroomvideoprogress').html('100%');
                        $('#classroomvideoprogress').css('left', '100%');
                        $('#_classroomvideoprogress').css('width', '100%');

                        edu.alert('success', '预览视频上传成功!');

                    } else if (type == 'err') {
                        edu.alert('danger', res.message);
                    }
                });
                @endif
            });

        })

    </script>
@endsection