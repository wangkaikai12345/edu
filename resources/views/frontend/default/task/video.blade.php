@extends('frontend.default.task.index')
@section('style')
    <link href="{{ asset('dist/video/css/index.css') }}" rel="stylesheet">
@endsection

@section('iframe')
    <div id="dplayer"
         data-url="{{ render_task_source($task->target['media_uri']) }}"
         data-logo=""
         data-route="{{ route('tasks.result.store', $task['id']) }}">
    </div>
    <script>
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
                url: $('#dplayer').data('route'),
                method: 'PUT',
                data: {
                    time: dp.video.currentTime
                },
                disabled_pop: true,
                callback: () => {}
            })
        }

        window.onload = function () {
            dp.on('play', function () {
                timer = setInterval(timerInit, 5000);
                judgeInterVal();
            });
            dp.on('pause', () => {
                clearInterval(timer);
            });
            dp.on('ended', () => {
                clearInterval(timer);
            });

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

@section('script')
    <script type="text/javascript" src="{{ asset('dist/video/js/index.js') }}"></script>
@endsection
