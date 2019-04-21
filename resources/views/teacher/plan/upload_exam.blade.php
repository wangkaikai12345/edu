<div class="video_upload test_upload exam_upload content_upload" style="display:none"
     id="{{ $editStatus }}content-paper">
    <div class="row justify-content-center">
        <div class="col-10">
            <div class="form-group">
                <label class="form-control-label col-2">
                    <i class="iconfont">
                        &#xe62f;
                    </i>
                    标题名称
                </label>
                <input class="form-control col-9"
                       type="text" placeholder="" id="{{ $editStatus }}task-title-paper"
                       value="{{ !empty($task) ? $task->title : '' }}"
                />
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
                    选择试卷
                </label>
                <div class="col-9 p-0">

                    <div class="">

                        <div class="tab-content" id="myTabContent">

                            <div class="tab-pane fade show active" id="profile" role="tabpanel"
                                 aria-labelledby="profile-tab">
                                <select class="form-control col-md-6 video_select" id="{{ $editStatus }}file-paper-key"
                                        name=""
                                        data-toggle="select" title="Simple select" data-live-search="true"
                                        data-live-search-placeholder="Search ..."
                                        style="width: 300px;">
                                    <option value="">请选择试卷</option>
                                    @foreach(\App\Models\Paper::where('status', 'valid')->get() as $value)
                                        <option value="{{ $value->id }}" {{ !empty($task) ? ($task->target->id == $value->id ? 'selected' :'' ): '' }}>{{ $value->title }}</option>
                                    @endforeach
                                </select>
                                {{--<select name="" class="form-control col-3 video_select" id="{{ $editStatus }}file-paper-key">--}}
                                {{--<option value="">请选择试卷 </option>--}}
                                {{--@foreach(\App\Models\Paper::where('status', 'valid')->get() as $value)--}}
                                {{--<option value="{{ $value->id }}" {{ !empty($task) ? ($task->target->id == $value->id ? 'selected' :'' ): '' }}>{{ $value->title }}</option>--}}
                                {{--@endforeach--}}
                                {{--</select>--}}
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
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-10">
            <div class="form-group focused">
                <label class="form-control-label col-2">
                    <i class="iconfont">
                        &#xe62f;
                    </i>
                    建议时长
                </label>
                <input class="form-control col-6" id="{{ $editStatus }}task-length-paper" type="number"
                       placeholder="" value="{{ !empty($task) ? $task->length/60 : 0}}"/>
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
</div>