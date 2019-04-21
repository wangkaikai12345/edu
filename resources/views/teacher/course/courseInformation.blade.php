<!----------------------- 课程信息页 ------------------------>
<link rel="stylesheet" href="{{ mix('/css/front/course/courseInformation/index.css') }}">
<style>
    /*    课程目标和适应人群相关css    */
    .list-target{
        border: 1px solid #cfcfcf;
        border-radius: 5px;
        padding: 0 10px;
        padding-top: 10px;
        margin-top: 50px;
        min-height: 30px;
    }
    .list-target p{
        display:block;
        border-bottom: 1px solid #cfcfcf;
        padding-bottom: 10px;
        position:relative;
        padding-right: 50px;
    }
    .list-target p:last-child{
        border-bottom: 0;
        margin-bottom: 0px;
    }
    .list-target a{
        display: block;
        position: absolute;
        right: 20px;
        top: -5px;
        font-size: 20px;
        color:#807979;
    }

    /*  select2 相关css */
    .select2-search__field{
        margin-left: -5px;
        padding: 0px 10px;
        margin-top: -18px;
    }
    .select2-selection--single-tt{
        height: 40px !important;
        padding-top: 5px !important;
    }
    .select2-selection__arrow{
        margin-top:6px;
    }
    .select2-container--default .select2-selection--multiple {
        padding-top:5px !important;
    }
    .select2-search__field {
        margin-top:0 !important;
        padding-left:5px !important;
    }
</style>



<div class="col-xl-9 col-md-12 col-12 form_content">

    <!-- Attach a new card -->
    {{--<input id="upload-lesson-token-img" type="hidden" name="token"--}}
           {{--value="{{ route('manage.qiniu.token.img') }}"/>--}}
    {{--<input id="upload-lesson-domain-img" type="hidden" name="domain" value="{{ config('filesystems.disks.imgs.domains.default') }}"/>--}}
    <form class="form-default" method="POST" id="course-edit-form" action="{{ route('manage.courses.update', $course) }}">
        {{ method_field('PUT') }}
        {{csrf_field()}}
        {{--<div id="post-imgs">--}}
            {{--<input type="hidden" name="cover" value="{{ $course->cover }}">--}}
        {{--</div>--}}
        <div id="labels-input">
            @if ($course->tags->count())
                @foreach($course->tags as $tag)
                    <input type="hidden" name="labels[]" value="{{ $tag->id }}"/>
                @endforeach
            @endif
        </div>

        <div class="card teacher_style">
            <div class="card-body row_content">
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
                                <input  required
                                        type="text" name="title" id="title" class="form-control col-md-12 col-lg-9 col-xl-9"
                                        placeholder="请输入标题" value="{{ $course->title }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3 ml-8 input-content">
                    <div class="col-md-10">
                        <div class="form-group">
                            <div class="input-group input-group-transparent">
                                <label class="control-label col-md-12 col-lg-3 col-xl-3 text-left">副标题</label>
                                <textarea type="text" name="subtitle" id="subtitle" class="form-control col-md-12 col-lg-9 col-xl-9">{{ $course->subtitle }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                {{--<div class="row mt-4 ml-8 input-content">--}}
                    {{--<div class="col-md-10">--}}
                        {{--<div class="form-group">--}}
                            {{--<div class="input-group input-group-transparent">--}}
                                {{--<label class="control-label col-md-12 col-lg-3 col-xl-3 text-center">标签</label>--}}
                                {{--<div class="col-md-12 col-lg-9 col-xl-9 p-0">--}}
                                    {{--<select class="form-control" data-toggle="select"--}}
                                            {{--title="Option groups" data-live-search="true"--}}
                                            {{--data-live-search-placeholder="Search ..."--}}
                                            {{--multiple id="label" name="labels[]">--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="row mt-3 ml-8 input-content">
                    <div class="col-md-10">
                        <div class="form-group">
                            <div class="input-group input-group-transparent classification_content">
                                <label class="control-label col-md-12 col-lg-3 col-xl-3 text-center">分类</label>
                                <select class="form-control col-md-12 col-lg-9 col-xl-9" id="category_id" name="category_id"  data-toggle="select" title="Simple select" data-live-search="true"
                                        data-live-search-placeholder="Search ...">
                                    @foreach($category as $c)
                                        <option {{ $course->category_id == $c->id ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                {{--<div class="row mt-3 ml-8 mb-4 input-content">--}}
                    {{--<div class="col-md-10 no-padding">--}}
                        {{--<div class="form-group">--}}
                            {{--<div class="input-group input-group-transparent">--}}
                                {{--<label class="control-label col-md-12 col-xl-3 col-lg-3 text-left connect-status" style="margin-left:8px;">连载状态</label>--}}
                                {{--<div class="col-md-12 col-xl-9 col-lg-9 row ml-0">--}}
                                    {{--<div class="col-md-2 col-xl-2 col-sm-2 col-lg-2 col-2 p-0 pl-3">--}}
                                        {{--<div class="custom-control custom-radio">--}}
                                            {{--<input type="radio" name="serialize_mode" class="custom-control-input" id="customRadio1" value="none" {{ $course->serialize_mode == 'none' ? 'checked' : '' }}>--}}
                                            {{--<label class="custom-control-label" for="customRadio1">无</label>--}}
                                        {{--</div>--}}

                                    {{--</div>--}}
                                    {{--<div class="col-md-3 col-xl-2 col-sm-3 col-3 col-lg-3">--}}
                                        {{--<div class="custom-control custom-radio">--}}
                                            {{--<input type="radio" name="serialize_mode"--}}
                                                   {{--class="custom-control-input" {{ $course->serialize_mode == 'serialized' ? 'checked' : '' }}--}}
                                                   {{--id="customRadio2" value="serialized">--}}
                                            {{--<label class="custom-control-label" for="customRadio2">连载中</label>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="col-md-4 col-xl-3 col-sm-4 col-4 col-lg-3">--}}
                                        {{--<div class="custom-control custom-radio">--}}
                                            {{--<input type="radio" name="serialize_mode"--}}
                                                   {{--class="custom-control-input"--}}
                                                   {{--id="customRadio3" value="finished" {{ $course->serialize_mode == 'finished' ? 'checked' : '' }}>--}}
                                            {{--<label class="custom-control-label" for="customRadio3">已完结</label>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            </div>
            <br>
            <hr style="margin:0;">
            <div class="card-body row_content">
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


                                    <div class="img-content" data-target="#imageIcoModal" data-toggle="modal"
                                         id="openCropperIco">
                                        <img src="{{ render_cover($course->cover, 'course') }}"
                                             width="284" height="160"
                                             id="image_ico"
                                             data-domain="{{ fileDomain() }}"
                                        />
                                    </div>

                                    <input type="hidden" name="cover" value="{{ $course->cover }}">
                                    <input type="hidden" name="current" value="{{ render_cover($course->cover, 'course') }}">

                                    @component('admin.layouts.image_format')
                                        @slot('modalId')
                                            imageIcoModal
                                        @endslot
                                        @slot('imageUrl')
                                            {{ render_cover($course->cover, 'course') }}
                                        @endslot
                                        @slot('imageHeight')
                                            500
                                        @endslot
                                        @slot('openCropper')
                                            openCropperIco
                                        @endslot
                                        @slot('imageScript')

                                            $('#image_ico').attr('src', $('#image_ico').data('domain')+'/'+imageName);
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
                                            $('#image_ico').attr('src', $('#image_ico').data('domain')+'/'+this.savedPath);
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
                                <label class="control-label col-md-12 col-lg-3 col-xl-3 text-left">课程简介</label>
                                <div class="col-md-12 col-lg-9 col-xl-9 p-0">
                                    <script id="editor" name="summary" type="text/plain">{!! $course->summary !!}</script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3 ml-8 input-content">
                    <div class="col-md-10">
                        <div class="form-group">
                            <div class="input-group input-group-transparent">
                                <label class="control-label col-md-12 col-lg-3 col-xl-3 text-left">课程目标</label>
                                <div class="col-md-9 col-lg-9 col-xl-9 p-0">
                                    <input type="text" class="form-control" aria-label="Recipient's username"
                                           aria-describedby="basic-addon2" id="target-add-input">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary button-input" id="target-add-btn" type="button">添加</button>
                                    </div>
                                    <div class="list-target" id="list-target" style="{{ !$course->goals ? 'display:none;' : '' }}">
                                        @if ($course->goals)
                                            @foreach($course->goals as $goal)
                                                <p>{{ $goal }}<a href="javascript:;">×</a><input type="hidden" name="goals[]" value="{{ $goal }}"/></p>
                                            @endforeach
                                        @endif
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
                                <label class="control-label col-md-12 col-lg-3 col-xl-3 text-left">适应人群</label>
                                <div class="col-md-9 col-lg-9 col-xl-9 p-0">
                                    <input type="text" class="form-control" aria-label="Recipient's username"
                                           aria-describedby="basic-addon2" id="person-add-input">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary button-input" type="button" id="person-add-btn">添加</button>
                                    </div>
                                    <div class="list-target" id="list-person" style="{{ !$course->audiences ? 'display:none;' : '' }}">
                                        @if ($course->audiences)
                                            @foreach($course->audiences as $audience)
                                                <p>{{ $audience }}<a href="javascript:;">×</a><input type="hidden" name="audiences[]" value="{{ $audience }}"/></p>
                                            @endforeach
                                        @endif
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
                                <label class="control-label col-md-12 col-lg-3 col-xl-3 text-left"></label>
                                <div class="col-md-9 col-lg-9 col-xl-9 p-0">
                                    <button type="submit" href="javascript:;" class="btn btn-sm btn-primary btn-submit" id="xxx">提交</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
