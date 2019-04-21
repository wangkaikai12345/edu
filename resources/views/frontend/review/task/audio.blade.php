<link rel="stylesheet" href="{{ mix('/css/front/audio/index.css') }}">

<div id="dplayer">
    <section class="mcon-maindemo dzsparallaxer height-is-based-on-content use-loading"
             style="position: relative;top: 50%;-webkit-transform: translateY(-50%);-moz-transform: translateY(-50%);-ms-transform: translateY(-50%);-o-transform: translateY(-50%);transform: translateY(-50%);">
        <div class="semi-black-overlay" style="display: none"></div>
        <div class="row pl-5 pr-5 d-flex align-items-center">
            <div class="col-md-12" style="height: 210px;">
                <div class="the-bg" style=""></div>
                <div class="overlay-comments" title="comment clicking on bottom scrubbar"></div>
                <div id="ap1"
                     class="audiogallery alternate-layout is-preview button-aspect-noir button-aspect-noir--filled theme-light skin-wave skin-wave-mode-normal skin-wave-wave-mode-canvas skin-wave-is-spectrum skin-wave-wave-mode-canvas-mode-normal skin-wave-no-reflect design-animateplaypause"
                     style="width:100%; margin-top:40px; margin-bottom: 40px;"
                     data-thumb="/imgs/timg.jpeg"
                     data-thumb_link="/imgs/timg.jpeg"
                     data-bgimage="/imgs/timg.jpeg"
                     data-type="normal"
                     data-source="{{ render_other_source($task->target['media_uri']) }}"
                     data-route="{{ renderTaskResultRoute('tasks.result.store',[$task], $member) }}"
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
</div>

<script>
    window.onload = function () {
        const debug = false;
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

        function autoNextPlan() {
            const nextElm = $('#zh_directory .directory_list .directory_item.active').next();
            nextElm.length && nextElm.find('a').trigger('click');
        }

        let lastTime = 0;

        setInterval(function () {
            const audioElm = $('audio')[0]
                , currentTime = Number(audioElm.currentTime.toFixed(0))
                , durationTime = Number(audioElm.duration.toFixed(0));


            if(durationTime - currentTime - (lastTime - currentTime) < 3) {
                autoNextPlan();
                return;
            }

            if(durationTime - currentTime < 2) {
                lastTime = currentTime;
            }
        }, 1000);

        function timerInit() {
            const audioElm = $('audio')[0];

            if(audioElm.paused || audioElm.ended) {
                return;
            }

            window.localStorage.setItem('task_timer', JSON.stringify({ 'status': true, 'time': new Date().getTime() }));
            $.ajax({
                url: $('#ap1').data('route'),
                method: 'PUT',
                data: {
                    time: audioElm.currentTime
                },
                disabled_pop: true,
                callback: (res) => {
                    if(res && res.data && res.data.status) {
                        const aElm = $('#zh_directory .directory_footer a');
                        aElm && aElm.removeClass('disabled').attr('disabled', false).attr('href', aElm.attr('data-href')).text('任务完成，开始作业');
                    }
                }
            })
        }

        timer = setInterval(timerInit, 5000);
        judgeInterVal();

        document.getElementsByTagName('audio')[0].ended = function() {
            clearInterval(timer);
        };

        document.getElementsByTagName('audio')[0].paused = function() {
            clearInterval(timer);
        };

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
<script src=" {{ mix('/js/front/task/audio/audioplayer.js') }} "></script>
<script src=" {{ mix('/js/front/task/audio/index.js') }} "></script>
