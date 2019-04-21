<form action="" class="video_upload audio_upload" style="display:none" id="last_step">
    <div class="row justify-content-center">
        <div class="col-10">
            <div class="form-group">
                <label class="form-control-label col-2">
                    <i class="iconfont">
                        &#xe62f;
                    </i>
                    完成条件
                </label>
                {{--<span class="col-9 audio_condition p-0">听完音频</span>--}}
                <select class="form-control col-3 video_select" id="task-finish-type">

                    @foreach(\App\Enums\FinishType::toSelectArray() as $typeValue => $typeName)
                        <option value="{{ $typeValue }}">{{ $typeName }}</option>
                    @endforeach

                    {{--  作业  --}}
                    {{--<option>提交及完成</option>--}}
                    {{--<option>批阅完成</option>--}}
                    {{--  考试  --}}
                    {{--<option>提交试卷</option>--}}
                    {{--<option>分数达标</option>--}}
                </select>
            </div>
        </div>
    </div>

    <div class="row justify-content-center" style="display:none" id="task-finish-detail-con">
        <div class="col-10">
            <div class="form-group">
                <label class="form-control-label col-2">
                    <i class="iconfont">
                        &#xe62f;
                    </i>
                    至少学习
                </label>
                <input class="form-control col-3" id="task-finish-detail" type="number" placeholder="默认为0" style="min-width: 300px;" value="0">
                <span class="float-left" style="line-height: 40px;margin: 0 10px;">分</span>
            </div>
        </div>
    </div>
    {{--  练习  --}}
    {{--<div class="row justify-content-center">--}}
        {{--<div class="col-10">--}}
            {{--<div class="form-group">--}}
                {{--<label class="form-control-label col-2">--}}
                    {{--练习总分--}}
                {{--</label>--}}
                {{--<span class="col-9 audio_condition p-0" style="line-height: 32px;">100分</span>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<div class="row justify-content-center">--}}
        {{--<div class="col-10">--}}
            {{--<div class="form-group">--}}
                {{--<label class="form-control-label col-2">--}}
                    {{--练习分数--}}
                {{--</label>--}}
                {{--<span class="col-9 audio_condition p-0" style="line-height: 32px;">60分</span>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<div class="row justify-content-center">--}}
        {{--<div class="col-10">--}}
            {{--<div class="form-group">--}}
                {{--<label class="form-control-label col-2">--}}
                    {{--练习时长--}}
                {{--</label>--}}
                {{--<span class="col-9 audio_condition p-0" style="line-height: 32px;">40分</span>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--  作业  --}}
    {{--<div class="row justify-content-center">--}}
        {{--<div class="col-10">--}}
            {{--<div class="form-group">--}}
                {{--<label class="form-control-label col-2">--}}
                    {{--作业总分--}}
                {{--</label>--}}
                {{--<span class="col-9 audio_condition p-0" style="line-height: 32px;">100分</span>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<div class="row justify-content-center">--}}
        {{--<div class="col-10">--}}
            {{--<div class="form-group">--}}
                {{--<label class="form-control-label col-2">--}}
                    {{--及格分数--}}
                {{--</label>--}}
                {{--<span class="col-9 audio_condition p-0" style="line-height: 32px;">60分</span>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<div class="row justify-content-center">--}}
        {{--<div class="col-10">--}}
            {{--<div class="form-group">--}}
                {{--<label class="form-control-label col-2">--}}
                    {{--作业时长--}}
                {{--</label>--}}
                {{--<span class="col-9 audio_condition p-0" style="line-height: 32px;">40分</span>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<div class="row justify-content-center">--}}
        {{--<div class="col-10">--}}
            {{--<div class="form-group">--}}
                {{--<label class="form-control-label col-2">--}}
                    {{--SEO关键字--}}
                {{--</label>--}}
                {{--<input class="form-control col-9" type="text" placeholder="">--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--  考试  --}}
    {{--<div class="row justify-content-center">--}}
        {{--<div class="col-10">--}}
            {{--<div class="form-group">--}}
                {{--<label class="form-control-label col-2">--}}
                    {{--试卷总分--}}
                {{--</label>--}}
                {{--<span class="col-9 audio_condition p-0" style="line-height: 32px;">100分</span>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<div class="row justify-content-center">--}}
        {{--<div class="col-10">--}}
            {{--<div class="form-group">--}}
                {{--<label class="form-control-label col-2">--}}
                    {{--及格分数--}}
                {{--</label>--}}
                {{--<span class="col-9 audio_condition p-0" style="line-height: 32px;">80分</span>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<div class="row justify-content-center">--}}
        {{--<div class="col-10">--}}
            {{--<div class="form-group">--}}
                {{--<label class="form-control-label col-2">--}}
                    {{--考试时长--}}
                {{--</label>--}}
                {{--<span class="col-9 audio_condition p-0" style="line-height: 32px;">6</span>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    <div class="row justify-content-center">
        <div class="col-10">
            <div class="form-group">
                <label class="form-control-label col-2">
                    SEO关键字
                </label>
                <input class="form-control col-9" type="text" placeholder="" id="seo_key">
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-10">
            <div class="form-group">
                <label class="form-control-label col-2">
                    SEO描述
                </label>
                <div class="col-9 p-0">
                    <textarea name="" cols="10" rows="3" id="seo_des"></textarea>
                </div>
            </div>
        </div>
    </div>
    {{--<div class="row justify-content-center">--}}
        {{--<div class="col-10">--}}
            {{--<div class="form-group">--}}
                {{--<label class="form-control-label col-2">--}}

                {{--</label>--}}
                {{--<div class="col-9 p-0">--}}
                    {{--<div class="custom-control custom-checkbox">--}}
                        {{--<input type="checkbox" class="custom-control-input" value="1" name="task-is-optional" id="customCheck1">--}}
                        {{--<label class="custom-control-label" for="customCheck1">设为选修</label>--}}
                    {{--</div>--}}
                    {{--<p class="xuanxiu_warring">--}}
                        {{--选修任务是否学习，不会影响下一任务的解锁，学习结果不会计入学习进度、学习统计中。在课程页面的目录中，将不会显示选修任务。--}}
                    {{--</p>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    <div class="row justify-content-center">
        <div class="col-10">
            <div class="form-group">
                <label class="form-control-label col-2">
                免费设置
                </label>
                <div class="col-9 p-0">
                    <div class="custom-control custom-checkbox" style="margin-top: 0.2rem;">
                        <input type="checkbox" class="custom-control-input" name="task-is-free" value="1" id="task-is-free">
                        <label class="custom-control-label" for="task-is-free">是否免费</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
