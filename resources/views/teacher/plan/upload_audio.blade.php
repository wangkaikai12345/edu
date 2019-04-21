<div class="video_upload audio_upload content_upload" style="display:none" id="{{ $editStatus }}content-audio">
    <div class="row justify-content-center">
        <div class="col-10">
            <div class="form-group">
                <label class="form-control-label col-2">
                    <i class="iconfont">
                        &#xe62f;
                    </i>
                    标题名称
                </label>
                <input class="form-control col-9 form-con" id="{{ $editStatus }}task-title-audio" type="text"
                       placeholder="" value="{{ !empty($task) ? $task->title : '' }}">
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
                    音频
                </label>
                <div class="video_select col-9 p-0">
                    @if ($editStatus)
                        <div class="data_select_list" style="border: 0;border-bottom: 1px solid #d5d5d5;margin-bottom: 0;-webkit-border-radius: 0;-moz-border-radius: 0;border-radius: 0;">
                            <div class="data_select_item">
                            <span class="data_item_name">
                            已上传文件：{{ render_other_source($task->target['media_uri']) }}
                            </span>
                            </div>
                        </div>
                    @endif
                    {{--<ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="myTab" role="tablist">--}}
                        {{--<li class="nav-item">--}}
                            {{--<a class="nav-link mb-sm-3 active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">上传资料</a>--}}
                        {{--</li>--}}
                        {{--<li class="nav-item">--}}
                            {{--<a class="nav-link mb-sm-3" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">从资料库里选择</a>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active _upload" id="home" role="tabpanel" aria-labelledby="home-tab">
                            {{--<input type="file" name="file-2[]" id="file-2" class="custom-input-file custom-input-file--2" data-multiple-caption="{count} files selected" multiple />--}}
                            @include('vendor.progress.progress', ['editStatus' => $editStatus, 'type' => 'audio'])

                            <div class="aetherupload-progressbar" style="background:blue;height:6px;width:0;"></div>
                            <!--需要一个名为aetherupload-progressbar的id，用以标识进度条-->

                            <span style="font-size:12px;color:#aaa;" class="aetherupload-output"></span>

                            <div class="file_upload" style="overflow: hidden;">
                                <input type="file" id="{{ $editStatus }}audio-up" accept="audio/*"
                                       class="custom-input-file custom-input-file--2"
                                       data-token="{{ route('manage.qiniu.token.hash') }}"/>
                                <label for="{{ $editStatus }}audio-up" style="margin: 93px 50px 0;">
                                    <i class="iconfont" style="float:left;font-size:25px;">&#xe658;</i>
                                    <span id="file_name" style="display:block;margin-top: 5px;">请选择文件</span>
                                    <input type="hidden" class="form-con" id="{{ $editStatus }}file-audio-key" value="{{ !empty($task) && $task->target ? $task->target->media_uri : '' }}">
                                    <input type="hidden" class="form-con" id="{{ $editStatus }}file-audio-hash" value="{{ !empty($task) && $task->target ? $task->target->hash : '' }}">
                                </label>
                            </div>
                        </div>
                        {{--<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">--}}
                            {{--<div class="database_control">--}}
                                {{--<div class="custom-control custom-radio float-left">--}}
                                    {{--<input type="radio" name="custom-radio-1" class="custom-control-input" id="customRadio1">--}}
                                    {{--<label class="custom-control-label" for="customRadio1">来自上传</label>--}}
                                {{--</div>--}}
                                {{--<div class="custom-control custom-radio float-left">--}}
                                    {{--<input type="radio" name="custom-radio-1" class="custom-control-input" id="customRadio2">--}}
                                    {{--<label class="custom-control-label" for="customRadio2">来自分享</label>--}}
                                {{--</div>--}}
                                {{--<div class="custom-control custom-radio float-left">--}}
                                    {{--<input type="radio" name="custom-radio-1" class="custom-control-input" id="customRadio3">--}}
                                    {{--<label class="custom-control-label" for="customRadio3">公共资料</label>--}}
                                {{--</div>--}}
                                {{--<div class="input_wrap">--}}
                                    {{--<input type="text" placeholder="输入标题关键字">--}}
                                    {{--<button class="btn btn-primary btn-sm">搜索</button>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="database_lists">--}}
                                {{--<div class="database_item active" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="傻大肥哈酒舒服哈监考老师房间卡红烧豆腐接口会加快速度后方可建行卡就收费">--}}
                                    {{--<div class="item_name">--}}
                                        {{--文件名.mp42389r78123974891237489023748912374891237489017--}}
                                    {{--</div>--}}
                                    {{--<div class="item_size">--}}
                                        {{--文件的大小--}}
                                    {{--</div>--}}
                                    {{--<div class="item_time">--}}
                                        {{--2019/02/06--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="database_item">--}}
                                    {{--<div class="item_name">--}}
                                        {{--文件名.mp42389r78123974891237489023748912374891237489017--}}
                                    {{--</div>--}}
                                    {{--<div class="item_size">--}}
                                        {{--文件的大小--}}
                                    {{--</div>--}}
                                    {{--<div class="item_time">--}}
                                        {{--2019/02/06--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--<div class="row justify-content-center">--}}
        {{--<div class="col-10">--}}
            {{--<div class="form-group">--}}
                {{--<label class="form-control-label col-2">--}}
                    {{--<i class="iconfont">--}}
                        {{--&#xe62f;--}}
                    {{--</i>--}}
                    {{--音频时长--}}
                {{--</label>--}}
                {{--<input class="form-control col-3" type="text" placeholder="">--}}
                {{--<span class="float-left" style="line-height: 40px;margin: 0 10px;">分</span>--}}
                {{--<input class="form-control col-3" type="text" placeholder="">--}}
                {{--<span class="float-left" style="line-height: 40px;margin: 0 10px;">秒</span>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
</div>