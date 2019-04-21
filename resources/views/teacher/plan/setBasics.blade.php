@extends('teacher.plan.plan_layout')
@section('plan_content')
    <div class="czh_task_content col-xl-9 col-md-9">
        <form method="post" action="{{ route('manage.plans.update', [$course, $plan]) }}">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <div class="operation_header">
                <p>基础设置</p>
            </div>
            <div class="basics_information">
                <div class="parentNode">
                    <span>基础信息</span>
                </div>
                <div class="plan_title_data">
                    <div class="title_tips">
                        <p>版本名称</p>
                    </div>
                    <div class="title_input">
                        <input type="text" name="title" value="{{ $plan->title }}">
                    </div>
                </div>
                <div class="plan_desc_data" style="overflow: hidden">
                    <div class="desc_tips">
                        <p>版本简介</p>
                    </div>
                    <div class="desc_input">
                        <script id="editor" name="about" type="text/plain">{!! $plan->about !!}</script>
                    </div>
                </div>

                <div class="plan_desc_data" style="overflow: hidden">
                    <div class="desc_tips">
                        <p>版本目标</p>
                    </div>
                    <div class="desc_input">
                        <input type="text" class="form-control" aria-label="Recipient's username"
                               aria-describedby="basic-addon2" id="target-add-input">
                        <div class="input-group-append">
                            <button class="btn btn-primary button-input" id="target-add-btn" type="button">添加</button>
                        </div>
                        <div class="list-target" id="list-target" style="{{ !$plan->goals ? 'display:none;' : '' }}">
                            @if ($plan->goals)
                                @foreach($plan->goals as $goal)
                                    <p>{{ $goal }}<a href="javascript:;">×</a><input type="hidden" name="goals[]"
                                                                                     value="{{ $goal }}"/></p>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="plan_desc_data" style="overflow: hidden">
                    <div class="desc_tips">适应人群</div>
                    <div class="desc_input">
                        <input type="text" class="form-control" aria-label="Recipient's username"
                               aria-describedby="basic-addon2" id="person-add-input">
                        <div class="input-group-append">
                            <button class="btn btn-primary button-input" type="button" id="person-add-btn">添加</button>
                        </div>
                        <div class="list-target" id="list-person" style="{{ !$plan->audiences ? 'display:none;' : '' }}">
                            @if ($plan->audiences)
                                @foreach($plan->audiences as $audience)
                                    <p>{{ $audience }}<a href="javascript:;">×</a><input type="hidden"
                                                                                         name="audiences[]"
                                                                                         value="{{ $audience }}"/></p>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="plan_desc_data">
                    <div class="desc_tips">
                        <p>预览视频</p>
                    </div>
                    <div class="desc_input">
                        <video src="{{ $plan->preview ? render_cover($plan->preview, '') : '' }}" controls="controls"
                               id="show_video"
                               data-domain="{{ fileDomain() }}"
                        ></video>
                    </div>
                    <div class="desc_input" style="margin-top:30px">
                        <input type="file" name="video" id="upload_file" accept="video/*"
                               class="custom-input-file custom-input-file--2"
                               data-token="{{ route('manage.qiniu.token.hash') }}"/>
                        <label for="upload_file">
                            <i class="iconfont" style="float:left;margin-top:5px;font-size:25px;">&#xe658;</i>
                            <span id="file_name" style="display:block;margin-top: 5px;">请选择文件</span>
                        </label>
                        <input type="hidden" class="form-con" name="preview" id="preview">
                    </div>
                    <div id="progress_bar" style="display: none" class="desc_input">
                        @include('vendor.progress.progress', ['editStatus' => '', 'type'=> 'prevideo'])

                        <div class="aetherupload-progressbar" style="background:blue;height:6px;width:0;"></div>
                        <!--需要一个名为aetherupload-progressbar的id，用以标识进度条-->

                        <span style="font-size:12px;color:#aaa;" class="aetherupload-output"></span>
                    </div>

                </div>
            </div>

            {{--<div class="marketing_setup">--}}
                {{--<div class="parentNode">--}}
                    {{--<span>营销设置</span>--}}
                {{--</div>--}}
                {{--<div class="form_item">--}}
                    {{--<div class="row mt-3 m-0 ml-8 input-content justify-content-center">--}}
                        {{--<div class="col-md-12 col-11">--}}
                            {{--<div class="form-group">--}}
                                {{--<div class="input-group input-group-transparent">--}}
                                    {{--<label class="control-label col-md-2 col-lg-2 col-xl-3 text-right modal-label">--}}
                                        {{--<span>是否锁定</span>--}}
                                    {{--</label>--}}
                                    {{--<div class="col-md-12 col-xl-7 col-lg-9 row ml-2 pl-0">--}}
                                        {{--<div class="col-md-2 col-xl-2 pl-0">--}}
                                            {{--<div class="custom-control custom-radio">--}}
                                                {{--<input type="radio" name="locked"--}}
                                                       {{--class="custom-control-input"--}}
                                                       {{--id="locked_1"--}}
                                                       {{--value="1" {{ $plan->locked == 1 ? 'checked' : '' }}>--}}
                                                {{--<label class="custom-control-label" for="locked_1">开启</label>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-2 col-xl-2 pl-0">--}}
                                            {{--<div class="custom-control custom-radio">--}}
                                                {{--<input type="radio" name="locked"--}}
                                                       {{--class="custom-control-input"--}}
                                                       {{--id="locked_0"--}}
                                                       {{--value="0" {{ $plan->locked == 0 ? 'checked' : '' }} >--}}
                                                {{--<label class="custom-control-label" for="locked_0">关闭</label>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form_item">--}}
                    {{--<div class="row mt-3 m-0 ml-8 input-content justify-content-center">--}}
                        {{--<div class="col-md-12 col-11">--}}
                            {{--<div class="form-group">--}}
                                {{--<div class="input-group input-group-transparent">--}}
                                    {{--<label class="control-label col-md-2 col-lg-2 col-xl-3 text-right modal-label modal-last-label">--}}
                                        {{--<span style="color:#f0a818;font-size:12px;">*</span>--}}
                                        {{--<span>价格</span>--}}
                                    {{--</label>--}}
                                    {{--<div class="col-md-12 col-xl-7 col-lg-9 row ml-2 pl-0">--}}
                                        {{--<div class="col-md-2 col-xl-2 pl-0">--}}
                                            {{--<div class="custom-control custom-radio">--}}
                                                {{--<input type="radio" name="is_free"--}}
                                                       {{--class="custom-control-input"--}}
                                                       {{--id="is_free_0"--}}
                                                       {{--value="1" {{ $plan->is_free == 1 ? 'checked' : '' }}>--}}
                                                {{--<label class="custom-control-label" for="is_free_0">免费</label>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-2 col-xl-2 pl-0">--}}
                                            {{--<div class="custom-control custom-radio">--}}
                                                {{--<input type="radio" name="is_free"--}}
                                                       {{--class="custom-control-input"--}}
                                                       {{--id="is_free_1"--}}
                                                       {{--value="0" {{ $plan->is_free == 0 ? 'checked' : '' }}>--}}
                                                {{--<label class="custom-control-label" for="is_free_1">收费</label>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form_item" id="set_price" style="display: {{ $plan->is_free ? 'none' : 'block' }};">--}}
                    {{--<div class="row mt-3 m-0 ml-8 input-content justify-content-center">--}}
                        {{--<div class="col-md-12 col-11">--}}
                            {{--<div class="form-group">--}}
                                {{--<div class="input-group input-group-transparent">--}}
                                    {{--<label class="control-label col-md-2 col-lg-2 col-xl-3 text-right modal-label modal-last-label">--}}
                                        {{--<span style="color:#f0a818;font-size:12px;">*</span>--}}
                                        {{--<span>价格设置</span>--}}
                                    {{--</label>--}}
                                    {{--<div class="col-md-12 col-xl-5 col-lg-9 row pl-0">--}}
                                        {{--<div class="col-md-2 col-xl-12 pl-0">--}}
                                            {{--<div class="custom-control set_server_inp">--}}
                                                {{--<span style="margin-right: 5px;">原价</span>--}}
                                                {{--<input class="form-control" type="number" name="origin_price" min="0"--}}
                                                       {{--value="{{ $plan->origin_price ? ftoy($plan->origin_price) : '' }}">--}}
                                                {{--<span>元</span>--}}
                                            {{--</div>--}}
                                            {{--<div class="custom-control set_server_inp">--}}
                                                {{--<span style="margin-right: 5px;">现价</span>--}}
                                                {{--<input class="form-control" type="number" name="price" min="0"--}}
                                                       {{--value="{{ $plan->price ? ftoy($plan->price) : '' }}">--}}
                                                {{--<span>元</span>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-2 col-xl-12 pl-0">--}}
                                            {{--<div class="custom-control set_server_inp">--}}
                                                {{--<span style="margin-right: 5px;">原价</span>--}}
                                                {{--<input class="form-control" type="number" name="origin_coin_price" min="0"--}}
                                                       {{--value="{{ $plan->origin_coin_price ? $plan->origin_coin_price :'' }}">--}}
                                                {{--<span>虚拟币</span>--}}
                                            {{--</div>--}}
                                            {{--<div class="custom-control set_server_inp mb-0">--}}
                                                {{--<span style="margin-right: 5px;">现价</span>--}}
                                                {{--<input class="form-control" type="number" name="coin_price" min="0"--}}
                                                       {{--value="{{ $plan->coin_price ? $plan->coin_price :'' }}">--}}
                                                {{--<span>虚拟币</span>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--@if ($plan->learn_mode == 'lock')--}}
                    {{--<div class="form_item">--}}
                        {{--<div class="row mt-3 m-0 ml-8 input-content justify-content-center">--}}
                            {{--<div class="col-md-12 col-11">--}}
                                {{--<div class="form-group">--}}
                                    {{--<div class="input-group input-group-transparent">--}}
                                        {{--<label class="control-label col-md-2 col-lg-2 col-xl-3 text-right modal-label modal-last-label">--}}
                                            {{--<span>展示承诺服务</span>--}}
                                        {{--</label>--}}
                                        {{--<div class="col-md-12 col-xl-5 col-lg-9 row ml-2 pl-0">--}}
                                            {{--<div class="col-md-2 col-xl-2 pl-0">--}}
                                                {{--<div class="custom-control custom-radio">--}}
                                                    {{--<input type="radio" name="show_services"--}}
                                                           {{--class="custom-control-input"--}}
                                                           {{--id="show_services_1"--}}
                                                           {{--value="1" {{ $plan->show_services == 1 ? 'checked' : '' }}>--}}
                                                    {{--<label class="custom-control-label" for="show_services_1">是</label>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="col-md-2 col-xl-2 pl-0">--}}
                                                {{--<div class="custom-control custom-radio">--}}
                                                    {{--<input type="radio" name="show_services"--}}
                                                           {{--class="custom-control-input"--}}
                                                           {{--id="show_services_0"--}}
                                                           {{--value="0" {{ $plan->show_services == 0 ? 'checked' : '' }}>--}}
                                                    {{--<label class="custom-control-label" for="show_services_0">否</label>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="form_item" id="set_server">--}}
                        {{--<div class="row mt-3 m-0 ml-8 input-content justify-content-center">--}}
                            {{--<div class="col-md-12 col-11">--}}
                                {{--<div class="form-group">--}}
                                    {{--<div class="input-group input-group-transparent">--}}
                                        {{--<label class="control-label col-md-2 col-lg-2 col-xl-3 text-right modal-label modal-last-label">--}}
                                            {{--<span>承诺提供服务</span>--}}
                                        {{--</label>--}}
                                        {{--<div class="col-md-12 col-xl-7 col-lg-9 row ml-2 pl-0">--}}
                                            {{--@if ( !empty(config('theme.services')))--}}
                                                {{--@foreach(config('theme.services') as $value)--}}
                                                    {{--<div class="col-md-3 col-xl-3 pl-0">--}}
                                                        {{--<div class="custom-control custom-radio plan_service p-0">--}}
                                                            {{--{{ $value }}--}}
                                                        {{--</div>--}}
                                                    {{--</div>--}}
                                                {{--@endforeach--}}
                                            {{--@endif--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--@endif--}}
            {{--</div>--}}

            {{--<div class="marketing_setup">--}}
                {{--<div class="parentNode">--}}
                    {{--<span>基础规则</span>--}}
                {{--</div>--}}
                {{-- 隐藏编辑 --}}
                {{--<div class="form_item">--}}
                    {{--<div class="row mt-3 m-0 ml-8 input-content justify-content-center">--}}
                        {{--<div class="col-md-12 col-11">--}}
                            {{--<div class="form-group">--}}
                                {{--<div class="input-group input-group-transparent">--}}
                                    {{--<label class="control-label col-md-2 col-lg-2 col-xl-3 text-right modal-label">--}}
                                        {{--<span>学习模式</span>--}}
                                    {{--</label>--}}
                                    {{--<div class="col-md-12 col-xl-7 col-lg-9 row ml-2 pl-0">--}}
                                        {{--@foreach(\App\Enums\LearnMode::toSelectArray() as $k => $v)--}}
                                            {{--<div class="col-md-2 col-xl-3 pl-0">--}}
                                                {{--<div class="custom-control custom-radio">--}}
                                                    {{--<input type="radio" name="learn_mode"--}}
                                                           {{--class="custom-control-input"--}}
                                                           {{--id="show_services_{{ $k }}"--}}
                                                           {{--value="{{ $k }}" {{ $plan->show_services == 1 ? 'checked' : '' }}>--}}
                                                    {{--<label class="custom-control-label"--}}
                                                           {{--for="show_services_{{ $k }}">{{ $v }}</label>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--@endforeach--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="form_item">--}}
                    {{--<div class="row mt-3 m-0 ml-8 input-content justify-content-center">--}}
                        {{--<div class="col-md-12 col-11">--}}
                            {{--<div class="form-group">--}}
                                {{--<div class="input-group input-group-transparent">--}}
                                    {{--<label class="control-label col-md-2 col-lg-2 col-xl-3 text-right modal-label modal-last-label">--}}
                                        {{--<span>学习有效期</span>--}}
                                    {{--</label>--}}
                                    {{--<div class="col-md-12 col-xl-5 col-lg-9 row ml-2 pl-0">--}}
                                        {{--<div class="col-md-2 col-xl-4 pl-0">--}}
                                            {{--<div class="custom-control custom-radio">--}}
                                                {{--<input type="radio" name="expiry_mode"--}}
                                                       {{--class="custom-control-input"--}}
                                                       {{--id="expiry_mode_period" {{ $plan->status == 'published' ? 'disabled' : '' }}--}}
                                                       {{--value="period" {{ $plan->expiry_mode == 'period' ? 'checked' : '' }}>--}}
                                                {{--<label class="custom-control-label"--}}
                                                       {{--for="expiry_mode_period">时间范围</label>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-2 col-xl-4 pl-0">--}}
                                            {{--<div class="custom-control custom-radio">--}}
                                                {{--<input type="radio" name="expiry_mode"--}}
                                                       {{--class="custom-control-input"--}}
                                                       {{--id="expiry_mode_valid" {{ $plan->status == 'published' ? 'disabled' : '' }}--}}
                                                       {{--value="valid" {{ $plan->expiry_mode == 'valid' ? 'checked' : '' }}>--}}
                                                {{--<label class="custom-control-label" for="expiry_mode_valid">有效时间</label>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-2 col-xl-4 pl-0">--}}
                                            {{--<div class="custom-control custom-radio">--}}
                                                {{--<input type="radio" name="expiry_mode"--}}
                                                       {{--class="custom-control-input"--}}
                                                       {{--id="expiry_mode_forever" {{ $plan->status == 'published' ? 'disabled' : '' }}--}}
                                                       {{--value="forever" {{ $plan->expiry_mode == 'forever' ? 'checked' : '' }}>--}}
                                                {{--<label class="custom-control-label"--}}
                                                       {{--for="expiry_mode_forever">永久有效</label>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<label style="font-size:12px;color:#f38a3e" class="col-12 pl-0 message-label">教学版本一旦发布，有效期类型不能修改；管理员在后台关闭课程后，可以修改日期。</label>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form_item" id="effective_time" style="display: none;">--}}
                    {{--<div class="row mt-3 m-0 ml-8 input-content justify-content-center">--}}
                        {{--<div class="col-md-12 col-11">--}}
                            {{--<div class="form-group">--}}
                                {{--<div class="input-group input-group-transparent">--}}
                                    {{--<label class="control-label col-md-2 col-lg-2 col-xl-3 text-right modal-label modal-last-label">--}}
                                        {{--<span>有效时间</span>--}}
                                    {{--</label>--}}
                                    {{--<div class="col-md-12 col-xl-5 col-lg-9 row pl-0">--}}
                                        {{--<div class="col-md-2 col-xl-6 pl-0">--}}
                                            {{--<div class="custom-control inp_num_div">--}}
                                                {{--<input class="form-control" type="number" name="expiry_days" value="{{ $plan->expiry_days }}">--}}
                                                {{--<span>天</span>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form_item" id="end_time_sec" style="display: none;">--}}
                    {{--<div class="row mt-3 m-0 ml-8 input-content justify-content-center">--}}
                        {{--<div class="col-md-12 col-11">--}}
                            {{--<div class="form-group">--}}
                                {{--<div class="input-group input-group-transparent">--}}
                                    {{--<label class="control-label col-md-2 col-lg-2 col-xl-3 text-right modal-label modal-last-label">--}}
                                        {{--<span>时间范围</span>--}}
                                    {{--</label>--}}
                                    {{--<div class="col-md-12 col-xl-3 col-lg-9 row ml-2 pl-0">--}}
                                        {{--<div class="form-group">--}}
                                            {{--<div class="input-group input-group-transparent">--}}
                                                {{--<input name="expiry_started_at" type="date"--}}
                                                       {{--class="form-control p-0"--}}
                                                       {{--value="{{ $plan->expiry_started_at ? $plan->expiry_started_at->toDateString() : now()->toDateString() }}"--}}
                                                       {{--style="height: 30px;padding-left: 10px !important;">--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--&nbsp;&nbsp;&nbsp;&nbsp;~--}}
                                    {{--</div>--}}
                                    {{--<div class="col-md-12 col-xl-2 col-lg-9 row ml-2 pl-0">--}}
                                        {{--<div class="form-group">--}}
                                            {{--<div class="input-group input-group-transparent">--}}
                                                {{--<input name="expiry_ended_at" type="date"--}}
                                                       {{--class="form-control p-0"--}}
                                                       {{--value="{{ $plan->expiry_ended_at ? $plan->expiry_ended_at->toDateString() : now()->toDateString()}}"--}}
                                                       {{--style="height: 30px;padding-left: 10px !important;">--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="form_item">--}}
                    {{--<div class="row mt-3 m-0 ml-8 input-content justify-content-center">--}}
                        {{--<div class="col-md-12 col-11">--}}
                            {{--<div class="form-group">--}}
                                {{--<div class="input-group input-group-transparent">--}}
                                    {{--<label class="control-label col-md-2 col-lg-2 col-xl-3 text-right modal-label modal-last-label">--}}
                                        {{--<span>任务完成规则</span>--}}
                                    {{--</label>--}}
                                    {{--<div class="col-md-12 col-xl-9 col-lg-9 row ml-2 pl-0">--}}
                                        {{--<div class="col-md-2 col-xl-4 pl-0">--}}
                                            {{--<div class="custom-control custom-radio">--}}
                                                {{--<input type="radio" name="enable_finish"--}}
                                                       {{--class="custom-control-input"--}}
                                                       {{--id="enable_finish_1"--}}
                                                       {{--value="1" {{ $plan->enable_finish == '1' ? 'checked' : '' }}>--}}
                                                {{--<label class="custom-control-label"--}}
                                                       {{--for="enable_finish_1">由任务完成条件决定</label>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-2 col-xl-2 pl-0">--}}
                                            {{--<div class="custom-control custom-radio">--}}
                                                {{--<input type="radio" name="enable_finish"--}}
                                                       {{--class="custom-control-input"--}}
                                                       {{--id="enable_finish_0"--}}
                                                       {{--value="0" {{ $plan->enable_finish == '0' ? 'checked' : '' }}>--}}
                                                {{--<label class="custom-control-label" for="enable_finish_0">不限时间</label>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}

            <div class="form_item">
                <div class="row mt-3 m-0 ml-8 input-content justify-content-center">
                    <div class="col-md-12 col-11">
                        <div class="form-group">
                            <div class="input-group input-group-transparent">
                                <button id="save_data_btn" type="submit" class="btn btn-sm btn-info">保存</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
@section('script')
    <script src="/vendor/ueditor/ueditor.config.js"></script>
    <script src="/vendor/ueditor/ueditor.all.js"></script>
{{--    <script src="{{ '/vendor/jquery/dist/jquery.min.js' }}"></script>--}}
    <script src="/tools/qiniu/qiniu2.min.js"></script>
    <script src="/tools/sha1.js"></script>
    <script src="/tools/qetag.js"></script>
    <script src="/tools/qiniu/qiniu-luwnto.js"></script>

    {{--本地文件上传--}}
    <script src="{{ mix('/js/upload/image-aetherupload.js') }}"></script>

    <script type="text/javascript">
        window.onload = () => {
            var ue = UE.getEditor('editor', {
                UEDITOR_HOME_URL: '/vendor/ueditor/',
                serverUrl: window.location.origin + '/vendor/ueditor/php/controller.php'
            });
        }

        $(document).ready(function () {
            // 预览视频上传
            $('#upload_file').change(function (event) {
                var files = event.target.files;

                if (files[0] && files[0].size > 10485760) {
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
                    aetherupload(files[0], 'video').success(function(){

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

                        $('#prevideoprogress').html(res.total.percent.toFixed(2) + '%');
                        $('#prevideoprogress').css('left', res.total.percent.toFixed(2) + '%');
                        $('#_prevideoprogress').css('width', res.total.percent.toFixed(2) + '%');

                    } else if (type == 'exist') {
                        $('#preview').val(res.data.url);
                        $('#prevideoprogress').html('100%');
                        $('#prevideoprogress').css('left', '100%');
                        $('#_prevideoprogress').css('width', '100%');

                        edu.alert('success', '预览视频上传成功!');

                    } else if (type == 'err') {
                        edu.alert('danger', res.message);
                    }
                });
                @endif
            });

            /**
             * 版本相关的操作, 适应人群相关操作
             */
            $(document).on('click', '.list-target a', function () {
                // 获取总共的数量
                var length = $(this).parent().parent().children().length;
                // 如果数量等于1的时候证明他的子元素已经没有。删除全部的元素
                if (length == 1) {
                    $(this).parent().parent().hide();
                }
                $(this).parent().remove();
            });
            $('#target-add-btn').click(function () {
                $('#list-target').show();
                var content = $('#target-add-input').val();
                if (content.length > 0) {
                    $('#list-target').append('<p>' + content + '<a href="javascript:;">×</a><input type="hidden" name="goals[]" value="' + content + '"/></p>');
                    $('#target-add-input').val('');
                }
            });

            $('#person-add-btn').click(function () {
                $('#list-person').show();
                var content = $('#person-add-input').val();
                if (content.length > 0) {
                    $('#list-person').append('<p>' + content + '<a href="javascript:;">×</a><input type="hidden" name="audiences[]" value="' + content + '"/></p>');
                    $('#person-add-input').val('');
                }
            });

            // 学习有效期
            $('input[type=radio][name=expiry_mode]').change(function () {
                if (this.value == 'period') {
                    $('#end_time_sec').show();
                    $('#effective_time').hide();
                } else if (this.value == 'valid') {
                    $('#effective_time').show();
                    $('#end_time_sec').hide();
                } else {
                    $('#end_time_sec').hide();
                    $('#effective_time').hide();
                }
            });

            // 加入截止日期  挂页面请更换选择器的name
            $('input[type=radio][name=test]').change(function () {
                if (this.value == 'test1') {
                    $('#end_time_one').show();
                } else {
                    $('#end_time_one').hide();
                }
            });

            // 设置价格
            $('input[type=radio][name=is_free]').change(function () {

                if (this.value == '1') {
                    $('#set_price').hide();
                } else {
                    $('#set_price').show();
                }
            });

            // 承诺服务
            $('input[type=radio][name=show_services]').change(function () {
                if (this.value == '1') {
                    $('#set_server').show();
                } else {
                    $('#set_server').hide();
                }
            });


            // 页面加载完毕选中的单选框 显示对应的选项
            if ($('input[type=radio][name=test][value=test1]').attr('checked')) {
                $('#end_time_one').show();
            }

            if ($('input[type=radio][name=expiry_mode][value=period]').attr('checked')) {
                $('#end_time_sec').show();
            }

            if ($('input[type=radio][name=expiry_mode][value=valid]').attr('checked')) {
                $('#effective_time').show();
            }

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


        });
    </script>
@endsection
