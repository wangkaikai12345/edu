<link rel="stylesheet" href="{{ mix('/css/front/task/slideNav/index.css') }}">

<div class="zh_slide_nav">
    <div class="slide_hide_scroll">
        <div class="slide_wrap">
            <div class="slide_nav_item" data-toggle-slide-nav="directory">
                <span class="item_title">
                    <i class="iconfont">
                        &#xe607;
                    </i>
                    {{--@if($task->type == \App\Enums\TaskType::HOMEWORK)--}}
                        {{--作业--}}
                    {{--@elseif($task->type == \App\Enums\TaskType::EXTRA)--}}
                        {{--拓展--}}
                    {{--@else--}}
                        {{--任务--}}
                    {{--@endif--}}
                    目录
                </span>
            </div>
            <div class="slide_nav_item" data-toggle-slide-nav="note">
                <span class="item_title">
                    <i class="iconfont">
                        &#xe606;
                    </i>
                    评论
                </span>
            </div>
            {{--<div class="slide_nav_item" data-toggle-slide-nav="question">--}}
                {{--<span class="item_title">--}}
                    {{--<i class="iconfont">--}}
                        {{--&#xe608;--}}
                    {{--</i>--}}
                    {{--问答--}}
                {{--</span>--}}
            {{--</div>--}}
            {{--<div class="slide_nav_item">--}}
                {{--<span class="item_title">--}}
                    {{--<i class="iconfont">--}}
                        {{--&#xe605;--}}
                    {{--</i>--}}
                    {{--帮助--}}
                {{--</span>--}}
            {{--</div>--}}
            {{--@if( typeResult($chapter->id, \App\Enums\TaskType::HOMEWORK, auth('web')->id()) == 100  && !empty($member->classroom_id))--}}

                {{--<a href="{{ renderTaskRoute([$chapter, 'type' => 'f-extra', 'summary' => 'summary'], $member)  }}">--}}
                {{--<div class="slide_nav_item">--}}
                    {{--<span class="item_title">--}}
                        {{--<i class="iconfont" style="font-size: 48px;margin-left: -8px;">--}}
                            {{--&#xe657;--}}
                        {{--</i>--}}
                        {{--总结--}}
                    {{--</span>--}}
                {{--</div>--}}
                {{--</a>--}}
            {{--@endif--}}

        </div>
    </div>
</div>

<script>
    window.onresize = function () {
        // countSlideNavMarginTop();
    };

    window.onload = function () {
        // countSlideNavMarginTop();
    };

    function countSlideNavMarginTop() {
        var height = $(window).height();
        if(height < 1050) {
            var countHeight = 950 - height;
            $('.zh_slide_nav .slide_wrap .slide_nav_item').eq(0).css('marginTop', 190 - countHeight);
        }
    }
</script>