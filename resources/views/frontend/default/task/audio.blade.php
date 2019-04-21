@extends('frontend.default.task.index')

@section('style')
    <link href="{{ asset('dist/audio/css/index.css') }}" rel="stylesheet">
@endsection

@section('iframe')
    <section class="mcon-maindemo  dzsparallaxer  height-is-based-on-content  use-loading  "
             style="position: relative;">
        <div class="semi-black-overlay" style="display: none"></div>
        <div class="row pl-5 pr-5 d-flex align-items-center">
            <div class="col-md-12" style="height: 210px;">
                <div class="the-bg" style=""></div>
                <div class="overlay-comments" title="comment clicking on bottom scrubbar"></div>
                <div id="ap1"
                     class="audiogallery alternate-layout is-preview button-aspect-noir button-aspect-noir--filled theme-light skin-wave skin-wave-mode-normal skin-wave-wave-mode-canvas skin-wave-is-spectrum skin-wave-wave-mode-canvas-mode-normal skin-wave-no-reflect design-animateplaypause"
                     style="width:100%; margin-top:40px; margin-bottom: 40px;"
                     data-thumb="/images/logo.png"
                     data-thumb_link="/images/logo.png"
                     data-bgimage="/images/logo.png"
                     data-type="normal"
                     data-source="{{ render_task_source($task->target['media_uri']) }}"
                     data-route="{{ route('tasks.result.store', $task['id']) }}"
                >
                    <div class="the-comments">

                    </div>
                    {{--<div class="meta-artist">--}}
                        {{--<span class="the-artist">--}}
                            {{--<strong>王凯</strong>--}}
                        {{--</span>--}}
                        {{--<span class="the-name">--}}
                            {{--凉凉-杨宗纬&&张碧晨--}}
                        {{--</span>--}}
                    {{--</div>--}}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('dist/audio/js/index.js') }}"></script>

    <script>
        window.onload = function () {
            const debug = true;
            let timer = null,
                judegTimer = null;
            function judgeInterVal() {
                const localTimer = JSON.parse(window.localStorage.getItem('task_timer'));
                if(!localTimer || !localTimer.status) {
                    // 如果没有设置 localstorage
                    debug && console.log('其它网页没有设置，开启轮询...');
                    clearInterval(timer);
                    timer = setInterval(() => {
                        timerInit();
                    }, 5000);
                }else {
                    // 如果有设置 localstorage
                    if((new Date().getTime() - JSON.parse(window.localStorage.getItem('task_timer')).time) / 1000 > 10) {
                        debug && console.log('其它网页有设置，且距离上次设置时间大于10s，开启轮询...');
                        //   如果 localstorage 存储时间 距离当前时间大于10s,本页面开启轮询
                        clearInterval(timer);
                        timer = setInterval(() => {
                            timerInit();
                        }, 5000);
                    }else {
                        debug && console.log('其它网页在线...');
                        setTimeout(() => {
                            judgeInterVal();
                        }, 5000)
                    }
                }
            }

            function timerInit() {
                window.localStorage.setItem('task_timer', JSON.stringify({ 'status': true, 'time': new Date().getTime() }));
                edu.ajax({
                    url: $('#ap1').data('route'),
                    method: 'PUT',
                    data: {
                        time: $('audio')[0].currentTime
                    },
                    disabled_pop: true,
                    callback: () => {}
                })
            }

            timer = setInterval(timerInit, 5000);
            judgeInterVal();

            window.addEventListener("storage", function (e) {
                if(timer) {
                    debug && console.log('清除轮询计时器...');
                    clearInterval(timer);
                }
                if(!judegTimer && dp.video.currentTime !== 0) {
                    debug && console.log('开始检测online计时器...');
                    judegTimer = setInterval(judgeInterVal, 10000);
                }
            });
        }
    </script>
@endsection