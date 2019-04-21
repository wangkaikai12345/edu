<style>
    .select2-selection__rendered {
        line-height: 40px !important;
    }

    .select2-selection__arrow {
        height: 40px !important;
    }

    .zh_modal .modal-body .test_upload .select2 {
        height: 40px !important;
    }
</style>
<form action="" class="video_upload test_upload content_upload" style="display:none"
      id="{{ $editStatus }}content-homework">

    <div class="row justify-content-center">
        <div class="col-10">
            <div class="form-group">
                <label class="form-control-label col-2">
                    <i class="iconfont">
                        &#xe62f;
                    </i>
                    标题名称
                </label>
                <input class="form-control col-8" type="text" placeholder=""
                       id="{{ $editStatus }}task-title-homework" value="{{ !empty($task) ? $task->title : '' }}"/>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-10">
            <div class="form-group">
                <label class="form-control-label col-2">
                    <i class="iconfont">
                        &#xe62f;
                    </i>
                    选择作业
                </label>
                <div class="col-9 p-0">
                    <select class="form-control col-md-6" id="{{ $editStatus }}file-homework-key"
                            name=""
                            data-toggle="select" title="Simple select" data-live-search="true"
                            data-live-search-placeholder="Search ..."
                            style="width: 300px;">
                        <option value="">请选择作业</option>
                        @foreach(\App\Models\Homework::where(['status' => 'published', 'type' => 'homework'])->get() as $value)
                            <option value="{{ $value->id }}" {{ !empty($task) ? ($task->target->id == $value->id ? 'selected' :'' ): '' }}>{{ $value->title }}</option>
                        @endforeach
                    </select>
                    {{--<div class="data_select_list">--}}
                    {{--<div class="data_select_item">--}}
                    {{--<span class="data_item_name">--}}
                    {{--文件名啊文件啊文件名啊文件名啊文件名啊文件名啊文件名啊--}}
                    {{--</span>--}}
                    {{--<a href="" class="item_delete float-right">删除</a>--}}
                    {{--</div>--}}
                    {{--<div class="data_select_item">--}}
                    {{--<span class="data_item_name">--}}
                    {{--文件名啊文件啊文件名啊文件名啊文件名啊文件名啊文件名啊--}}
                    {{--</span>--}}
                    {{--<a href="" class="item_delete float-right">删除</a>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="video_select">--}}
                    {{--<ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="myTab" role="tablist">--}}
                    {{--<li class="nav-item">--}}
                    {{--<a class="nav-link mb-sm-3 active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">上传资料</a>--}}
                    {{--</li>--}}
                    {{--<li class="nav-item">--}}
                    {{--<a class="nav-link mb-sm-3" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">从资料库里选择</a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--<div class="tab-content" id="myTabContent">--}}
                    {{--<div class="tab-pane fade show active _upload" id="home" role="tabpanel" aria-labelledby="home-tab">--}}
                    {{--<input type="file" name="file-2[]" id="file-2" class="custom-input-file custom-input-file--2" data-multiple-caption="{count} files selected" multiple />--}}
                    {{--<label for="file-2" class="file_upload text-center">--}}
                    {{--<div class="file_upload_center">--}}
                    {{--<span>将文件拖拽至此，或</span>--}}
                    {{--<button class="btn btn-sm btn-primary">点击上传</button>--}}
                    {{--</div>--}}
                    {{--</label>--}}
                    {{--</div>--}}
                    {{--<div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">--}}

                    {{--<div class="database_control">--}}
                    {{--<select class="form-control" data-toggle="select" title="Option groups" data-live-search="true" data-live-search-placeholder="Search ..." multiple>--}}
                    {{--<option>出题人</option>--}}
                    {{--<option>出题人</option>--}}
                    {{--<option>出题人</option>--}}
                    {{--</select>--}}
                    {{--<div class="input_wrap">--}}
                    {{--<select class="form-control" data-toggle="select" title="Option groups" data-live-search="true" data-live-search-placeholder="Search ..." multiple>--}}
                    {{--<option>出题人</option>--}}
                    {{--</select>--}}
                    {{--<button class="btn btn-primary btn-sm">搜索</button>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="database_lists">--}}
                    {{--<div class="database_item active" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="傻大肥哈酒舒服哈监考老师房间卡红烧豆腐接口会加快速度后方可建行卡就收费">--}}
                    {{--<div class="item_name">--}}
                    {{--练习名称练习名称练习名称练习名称练习名称练习名称练习名称练习名称--}}
                    {{--</div>--}}
                    {{--<div class="item_size">--}}
                    {{--出题人--}}
                    {{--</div>--}}
                    {{--<div class="item_time">--}}
                    {{--2019/02/06--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="database_item">--}}
                    {{--<div class="item_name">--}}
                    {{--练习名称练习名称练习名称练习名称练习名称练习名称练习名称练习名称--}}
                    {{--</div>--}}
                    {{--<div class="item_size">--}}
                    {{--出题人--}}
                    {{--</div>--}}
                    {{--<div class="item_time">--}}
                    {{--2019/02/06--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}


                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="data_name row">--}}
                    {{--<span class="data_name_title col-3">资料名称：</span>--}}
                    {{--<span class="dataname col-9">文件名啊文件啊文件名啊文件名啊文件名啊文件名啊文件名啊</span>--}}
                    {{--</div>--}}
                    {{--<div class="data_description row m-0">--}}
                    {{--<input class="form-control col-9 p-0" type="text" placeholder="请填写资料简介(可选)">--}}
                    {{--<div class="col-3">--}}
                    {{--<button class="btn btn-primary btn-sm">添加资料</button>--}}
                    {{--</div>--}}
                    {{--<span class="col-12 text-warning p-0">--}}
                    {{--注意：资料文件在移动端上无法被下载!--}}
                    {{--</span>--}}
                    {{--</div>--}}

                </div>

            </div>
        </div>

    </div>
    <div class="row justify-content-center">
        <div class="col-10">
            <div class="form-group">
                <label class="form-control-label col-2">
                    <i class="iconfont">
                        &#xe62f;
                    </i>
                    建议时长
                </label>
                <input class="form-control col-6" id="{{ $editStatus }}task-length-homework" type="number"
                       placeholder="作业时长" value="{{ !empty($task) ? $task->length/60 : 0}}"/>
                <span class="float-left" style="line-height: 40px;margin: 0 10px;">分</span>
            </div>
        </div>

        {{--<div class="form-group">--}}
        {{--<label class="form-control-label col-2">--}}
        {{--<i class="iconfont">--}}
        {{--&#xe62f;--}}
        {{--</i>--}}
        {{--考试次数--}}
        {{--</label>--}}
        {{--<div class="col-9 exam_num">--}}
        {{--<div class="custom-control custom-radio float-left">--}}
        {{--<input type="radio" name="custom-radio-1" class="custom-control-input" id="customRadio1">--}}
        {{--<label class="custom-control-label" for="customRadio1">不限</label>--}}
        {{--</div>--}}
        {{--<div class="custom-control custom-radio float-left">--}}
        {{--<input type="radio" name="custom-radio-1" class="custom-control-input" id="customRadio2">--}}
        {{--<label class="custom-control-label" for="customRadio2">单次</label>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
    </div>

</form>