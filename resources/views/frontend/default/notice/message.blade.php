@extends('frontend.default.notice.index')
@section('title', '我的私信')

@section('partStyle')
    <link href="{{ asset('dist/message/css/index.css') }}" rel="stylesheet">
@endsection

@section('rightBody')
    <style>
        .select-wrapper .select-dropdown {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none
        }

        .select-label {
            position: absolute
        }

        .select-wrapper {
            position: relative
        }

        .select-wrapper input.select-dropdown {
            position: relative;
            cursor: pointer;
            background-color: transparent;
            border: none;
            border-bottom: 1px solid #ced4da;
            outline: 0;
            height: 38px;
            line-height: 2.9rem;
            width: 100%;
            font-size: 1rem;
            margin: 0 0 .94rem 0;
            padding: 0;
            display: block;
            -o-text-overflow: ellipsis;
            text-overflow: ellipsis
        }

        .select-wrapper input.select-dropdown:disabled {
            color: rgba(0,0,0,.3);
            border-bottom-color: rgba(0,0,0,.3);
            cursor: default
        }

        .select-wrapper input.select-dropdown .selected,.select-wrapper input.select-dropdown li:focus {
            background-color: rgba(0,0,0,.15)
        }

        .select-wrapper input.select-dropdown li.active {
            background: 0 0
        }

        .select-wrapper input.select-dropdown .fab,.select-wrapper input.select-dropdown .far,.select-wrapper input.select-dropdown .fas {
            color: inherit
        }

        .select-wrapper .search-wrap {
            padding: 1rem 0 0;
            display: block;
            margin: 0 .7rem
        }

        .select-wrapper .search-wrap .md-form {
            margin-top: 0;
            margin-bottom: 1rem
        }

        .select-wrapper .search-wrap .md-form input {
            padding-bottom: .4rem;
            margin-bottom: 0
        }

        .select-wrapper span.caret {
            color: initial;
            position: absolute;
            right: 0;
            top: .8rem;
            font-size: .63rem
        }

        .select-wrapper span.caret.disabled {
            color: rgba(0,0,0,.46)
        }

        .select-wrapper+label {
            position: absolute;
            top: 7px;
            font-size: .8rem
        }

        .select-wrapper i {
            color: rgba(0,0,0,.3)
        }

        .select-wrapper ul {
            list-style-type: none;
            padding-left: 0
        }

        .select-wrapper.md-form>ul li label {
            top: 0;
            color: #4285f4;
            font-size: .9rem
        }

        .select-wrapper.md-form>ul li.select-toggle-all label {
            padding-left: 38px
        }

        .select-wrapper.md-form.colorful-select>ul li.select-toggle-all:hover label {
            color: #fff
        }

        select {
            font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
            display: none!important
        }

        select.browser-default {
            display: block!important
        }

        select:disabled {
            color: rgba(0,0,0,.3)
        }

        .select-dropdown [type=checkbox]:disabled:not(:checked)+label:before {
            margin-left: 0;
            margin-top: 0
        }

        .select-dropdown ul {
            list-style-type: none;
            padding: 0
        }

        .select-dropdown li img {
            height: 30px;
            width: 30px;
            margin: .3rem .75rem;
            float: right
        }

        .select-dropdown li.disabled,.select-dropdown li.disabled>span,.select-dropdown li.optgroup {
            color: rgba(0,0,0,.3);
            background-color: transparent!important;
            cursor: context-menu
        }

        .select-dropdown li.optgroup {
            border-top: 1px solid #eee
        }

        .select-dropdown li.optgroup.selected>span {
            color: rgba(0,0,0,.7)
        }

        .select-dropdown li.optgroup>span {
            color: rgba(0,0,0,.4)
        }

        .multiple-select-dropdown li [type=checkbox]+label {
            height: .63rem
        }

        .dropdown-content {
            -webkit-box-shadow: 0 2px 5px 0 rgba(0,0,0,.16),0 2px 10px 0 rgba(0,0,0,.12);
            box-shadow: 0 2px 5px 0 rgba(0,0,0,.16),0 2px 10px 0 rgba(0,0,0,.12);
            background-color: #fff;
            margin: 0;
            display: none;
            min-width: 6.25rem;
            max-height: 40.625rem;
            overflow-y: auto;
            opacity: 0;
            position: absolute;
            z-index: 999;
            will-change: width,height
        }

        .dropdown-content li {
            clear: both;
            color: #000;
            cursor: pointer;
            line-height: 1.3rem;
            width: 100%;
            text-align: left;
            text-transform: none
        }

        .dropdown-content li.active,.dropdown-content li:hover {
            background-color: #eee
        }

        .dropdown-content li>a,.dropdown-content li>span {
            font-size: .9rem;
            color: #4285f4;
            display: block;
            padding: .5rem
        }

        .dropdown-content li>a>i {
            height: inherit;
            line-height: inherit
        }

        .colorful-select .dropdown-content {
            padding: .5rem
        }

        .colorful-select .dropdown-content li.active span {
            color: #fff!important;
            -webkit-box-shadow: 0 5px 11px 0 rgba(0,0,0,.18),0 4px 15px 0 rgba(0,0,0,.15);
            box-shadow: 0 5px 11px 0 rgba(0,0,0,.18),0 4px 15px 0 rgba(0,0,0,.15)
        }

        .colorful-select .dropdown-content li.active span [type=checkbox]:checked+label:before {
            border-color: transparent #fff #fff transparent
        }

        .colorful-select .dropdown-content li a:hover,.colorful-select .dropdown-content li span:hover {
            -webkit-box-shadow: 0 8px 17px 0 rgba(0,0,0,.2),0 6px 20px 0 rgba(0,0,0,.19);
            box-shadow: 0 8px 17px 0 rgba(0,0,0,.2),0 6px 20px 0 rgba(0,0,0,.19);
            color: #fff!important;
            -webkit-transition: .15s;
            -o-transition: .15s;
            transition: .15s;
            -webkit-border-radius: .125rem;
            border-radius: .125rem
        }

        .colorful-select .dropdown-content li a:hover [type=checkbox]+label:before,.colorful-select .dropdown-content li span:hover [type=checkbox]+label:before {
            border-color: #fff
        }

        .colorful-select .dropdown-content li a:hover [type=checkbox]:checked+label:before,.colorful-select .dropdown-content li span:hover [type=checkbox]:checked+label:before {
            border-color: transparent #fff #fff transparent
        }

        .colorful-select .dropdown-content li.disabled.active span,.colorful-select .dropdown-content li.optgroup.active span,.colorful-select .dropdown-content li:disabled.active span {
            -webkit-box-shadow: none;
            box-shadow: none;
            color: rgba(0,0,0,.3)!important;
            border-bottom-color: rgba(0,0,0,.3);
            cursor: default
        }

        .colorful-select .dropdown-content li.disabled a:hover,.colorful-select .dropdown-content li.disabled span:hover,.colorful-select .dropdown-content li.optgroup a:hover,.colorful-select .dropdown-content li.optgroup span:hover,.colorful-select .dropdown-content li:disabled a:hover,.colorful-select .dropdown-content li:disabled span:hover {
            -webkit-box-shadow: none;
            box-shadow: none;
            color: rgba(0,0,0,.3)!important;
            border-bottom-color: rgba(0,0,0,.3);
            cursor: default;
            background-color: #fff!important
        }

        .colorful-select .dropdown-content li.disabled label,.colorful-select .dropdown-content li.optgroup label,.colorful-select .dropdown-content li:disabled label {
            cursor: default
        }
    </style>
    <div class="col-xl-12 view p-0">
        <div class="card">
            <div class="card-body">
                <!--Title-->
                <h6 class="card-title">我的私信 <a href="" class="float-right font-small" data-route="{{ route('users.message.store') }}" data-toggle="modal" data-target="#basicExampleModal">写私信</a></h6>
                <hr>
                <!--Text-->
                <section class="section">

                    <!--Grid row-->
                    <div class="row">

                        <!--Grid column-->
                        <div class="col-lg-4">

                            <!--Name-->
                            <div class="md-form mb-4">
                                <h6>联系人</h6>
                            </div>
                            <!-- Messages -->
                            <div class="user-list">
                                <div class="list-group">

                                    @foreach($conversations as $conversation)
                                        <!-- Single message -->
                                            <a href="#" class="list-group-item list-group-item-action media" data-route="{{ route('users.message.show', $conversation['id']) }}" data-another="{{ $conversation['another_id'] }}">
                                                <!-- Avatar -->
                                                <img class="mr-3 avatar-sm float-left" src="{{ render_cover($conversation['another']['avatar'], 'avatar') }}">

                                                <!-- Author -->
                                                <div class="d-flex justify-content-between mb-1 username">
                                                    <hp class="mb-1"><strong>{{ $conversation['another']['username'] }}</strong></hp>
                                                    <small></small>
                                                </div>

                                                <!-- Message -->
                                                <p class="text-truncate"><strong></strong></p>
                                            </a>
                                    @endforeach
                                </div>
                            </div>
                            <!-- Messages -->

                        </div>
                        <!--Grid column-->

                        <!--Grid column-->
                        <div class="col-lg-8 mt-lg-0 mt-5">

                            <!-- Conversation -->
                            <div class="border pl-4 right-wrap">

                                <div class="message-wrap">
                                    <!-- My Message -->
                                    <div class="message-list">

                                    </div>
                                </div>

                                <!-- New message -->
                                <div class="row send-message">
                                    <div class="col-md-12">

                                        <div class="d-flex flex-row">

                                            <div class="md-form chat-message-type">
                                                <textarea type="text" id="form7" class="md-textarea form-control" rows="3"></textarea>
                                                <label for="form7">输入你要发送的消息</label>
                                            </div>

                                            <div class="mt-5">
                                                <a class="btn btn-primary waves-effect waves-light sendMessage" id="sendMessage">发送</a>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                                <!-- /.New message -->

                            </div>
                            <!-- Conversation -->

                        </div>
                        <!--Grid column-->

                    </div>
                    <!--/.Grid row-->

                </section>
            </div>
        </div>
        <div class="mask flex-center rgba-white-strong" id="mainLoading">
            <div class="preloader-wrapper big active">
                <div class="spinner-layer spinner-blue-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade right" id="basicExampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-side modal-bottom-right view" role="document">
            <div class="mask flex-center rgba-white-strong d-none" id="sendNewMessageLoading" style="z-index: 9999999;">
                <div class="preloader-wrapper active">
                    <div class="spinner-layer spinner-blue-only">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
                        </div>
                        <div class="gap-patch">
                            <div class="circle"></div>
                        </div>
                        <div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">新发私信</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <select class="mdb-select md-form" searchable="请输入" id="searchUsers">
                        <option value="" disabled selected>请输入联系人名称</option>
                    </select>
                    <div class="form-group shadow-textarea mt-4">
                        <textarea class="form-control z-depth-1" id="exampleFormControlTextarea6" rows="5" placeholder="请输入您要发送的消息..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-sm btn-primary sendMessage">发送</button>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('partScript')
    <script type="text/javascript" src="{{ asset('dist/message/js/index.js') }}"></script>
    <script>
        let UserListActiveIndex = 0,
            resultData = [],
            timer = null,
            judegTimer = null,
            debug = false,
            timing = 0;

        function getDate(dateTimeStamp) {

            if(dateTimeStamp === 'undefined'){
                return '';
            }else {
                const minute = 1000 * 60
                    ,hour = minute * 60
                    ,day = hour * 24
                    ,halfamonth = day * 15
                    ,month = day * 30;

                dateTimeStamp = dateTimeStamp.replace(/\-/g, "/");
                const sTime = new Date(dateTimeStamp).getTime();

                const now = new Date().getTime();

                const diffValue = now - sTime;

                if(diffValue < 0){
                    return ''
                }

                var monthC =diffValue/month;
                var weekC =diffValue/(7*day);
                var dayC =diffValue/day;
                var hourC =diffValue/hour;
                var minC =diffValue/minute;

                if(monthC>=1){
                    return parseInt(monthC) + "个月前"
                }
                else if(weekC >= 1){
                    return parseInt(weekC) + "周前"
                }
                else if(dayC>=1){
                    return parseInt(dayC) + "天前"
                }
                else if(hourC>=1){
                    return parseInt(hourC) + "个小时前"
                }
                else if(minC>=1){
                    return parseInt(minC) + "分钟前"
                }else{
                    return "刚刚"
                }
            }
        }

        function appendMessage(index, userId, data, elmItem) {
            data.map((dataItem) => {
                let html = '';
                if(dataItem.sender_id === userId) {
                    //    对方发
                    html = `<div class="text-center">
                            <small>${dataItem.created_at}</small>
                        </div>
                        <div class="d-flex justify-content-start media">
                            <img class="mr-3 avatar-sm float-left" src="${$(elmItem).find('img').attr('src')}">
                            <p class="grey lighten-3 rounded p-3 w-75">
                                ${dataItem.body}
                            </p>
                        </div>`
                }else {
                    //   己方发
                    html = `<div class="text-center">
                            <small>${dataItem.created_at}</small>
                        </div>
                        <div class="d-flex justify-content-end">
                            <p class="primary-color rounded p-3 text-white w-75">
                                ${dataItem.body}
                            </p>
                        </div>`;
                }


                $('.message-wrap')
                    .eq(index)
                    .find('.message-list')
                    .append(html)
                    .end()
                    .scrollTop(9999999999);
            });
            if(data.length > 0) {
                const lastMessage = data[data.length-1];
                $(elmItem)
                    .find('.username small')
                    .text(getDate(lastMessage['created_at']))
                    .end()
                    .find('.text-truncate')
                    .html(`<strong>${lastMessage.sender_id === userId ? lastMessage.sender.username : '你'} :</strong>${lastMessage.body}`)
            }
        }

        function messageInit(loop) {
            if(loop) {
                window.localStorage.setItem('timer', JSON.stringify({ 'status': true, 'time': new Date().getTime() }));
                timing++;
            }
            var activeMessage = null;

            if($('.user-list .list-group a').length === 0) {
                $('#mainLoading').toggleClass('d-none');
            }

            $('.user-list .list-group a')
                .each((index, elmItem) => {
                    const url = $(elmItem).data('route')
                        ,userId = $(elmItem).data('another');

                    if(!loop) {
                        if(index !== 0) {
                            $('.right-wrap .message-wrap').eq(index - 1)
                                .after(`<div class="message-wrap d-none"><div class="message-list"></div></div>`);
                        }
                    }
                    if(loop && !$(elmItem).hasClass('active')) {
                        if(timing < 12) {
                            return true;
                        }
                    }

                    setTimeout(() => {
                        edu.ajax({
                            url: $(elmItem).data('route'),
                            method: 'get',
                            disabled_pop: true,
                            callback: (res) => {
                                // console.log(res);
                                if(res.status === 'success') {
                                    if(loop) {
                                        // console.log(res.data);
                                        debug && console.log(`第${index}个`, resultData[index], res.data);
                                        if(resultData[index].length === res.data.length) {
                                            // 无新消息
                                        }else {
                                            let data = res.data.slice(resultData[index].length, res.data.length);
                                            appendMessage(index, userId, data, elmItem);
                                        }
                                        resultData[index] = res.data;
                                        return;
                                    }

                                    const data = res.data;
                                    // 添加右侧消息 DOM
                                    appendMessage(index, userId, data, elmItem);
                                    // 存在聊天记录 追加联系人列表 消息日志

                                    resultData.push(res.data);
                                    if(index >= $('.user-list .list-group a').length - 1) {
                                        $('#mainLoading').toggleClass('d-none');
                                        $('.user-list .list-group a').eq(0).addClass('active');
                                    }
                                }
                            }
                        });
                    }, loop && index * 1000)
                });

            if(timing >= 12) {
                timing = 0;
            }
        }

        function judgeInterVal() {
            const localTimer = JSON.parse(window.localStorage.getItem('timer'));
            if(!localTimer || !localTimer.status) {
                // 如果没有设置 localstorage
                debug && console.log('其它网页没有设置，开启轮询...');
                timer = setInterval(() => {
                    messageInit(true);
                }, 5000);
            }else {
                // 如果有设置 localstorage
                if((new Date().getTime() - JSON.parse(window.localStorage.getItem('timer')).time) / 1000 > 10) {
                    debug && console.log('其它网页有设置，且距离上次设置时间大于10s，开启轮询...');
                    //   如果 localstorage 存储时间 距离当前时间大于10s,本页面开启轮询
                    timer = setInterval(() => {
                        messageInit(true);
                    }, 5000);
                }else {
                    debug && console.log('其它网页在线...');
                    setTimeout(() => {
                        judgeInterVal();
                    }, 5000)
                }
            }
        }

        $(function () {
            messageInit();
            judgeInterVal();

            // 监听storage，若其它页面更新storage，即时清除本页面计时器，启动一个计时器判断其它网页是否online
            window.addEventListener("storage", function (e) {
                if(timer) {
                    debug && console.log('清除轮询计时器...');
                    clearInterval(timer);
                }
                if(!judegTimer) {
                    debug && console.log('开始检测online计时器...');
                    judegTimer = setInterval(judgeInterVal, 10000);
                }
            });

            // 联系人列表切换
            $(document).on('click', '.user-list .list-group a', function () {
                const idx = $(this).index();
                $('.user-list .list-group a').removeClass('active').eq(idx).addClass('active');
                $('.right-wrap .message-wrap').addClass('d-none').eq(idx).removeClass('d-none');
                UserListActiveIndex = idx;
                $('.message-wrap').eq(idx).scrollTop(99999999999);
                return false;
            });

            $('#form7').bind('keydown', function(event) {
                if (event.keyCode === 13) {
                    //回车执行查询
                    event.preventDefault();

                    $('#sendMessage').click();
                }
            });

            // 聊天 发信息
            $('.sendMessage').on({
                click: function () {
                    const url = $('h6.card-title a.float-right').data('route')
                        ,elId = $(this)[0].id
                        ,isNewMessage = elId !== 'sendMessage'
                        ,userListActiveElm = $('.user-list .list-group a.active')
                        ,message = $(`#${ !isNewMessage ? 'form7' : 'exampleFormControlTextarea6'}`).val().replace(/(^\s*)|(\s*$)/g, '')
                        ,index = $('.user-list .list-group a').index(userListActiveElm)
                        ,user_id = !isNewMessage ? userListActiveElm.data('another') : selectSelf.$materialOptionsList.find('li.selected').data('userid');

                    if(isNewMessage) {
                        $('#sendNewMessageLoading').removeClass('d-none');
                    }

                    if(message === '' || message === undefined || message == null) {
                        $(`${ !isNewMessage ? 'form7' : 'exampleFormControlTextarea6'}`).val('');
                        return;
                    };
                    setTimeout(() => {
                        edu.ajax({
                            url: url,
                            method: 'post',
                            elm: '#basicExampleModal button, #basicExampleModal #exampleFormControlTextarea6,#basicExampleModal .select-dropdown',
                            data: {
                                user_id: user_id,
                                message: message
                            },
                            callback: (res) => {
                                if(res.status === 'success') {
                                    if(isNewMessage) {
                                        let userListHas = false;
                                        $('.user-list .list-group a').each((index, item) => {
                                            if($(item).data('another') === user_id) {
                                                $('.right-wrap').prepend($('.right-wrap .message-wrap').addClass('d-none').eq(index).removeClass('d-none'));
                                                $('.user-list .list-group').find('a').removeClass('active');
                                                $(item).addClass('active');
                                                $('.user-list .list-group').prepend($(item));
                                                debug && console.log('新消息change start...' ,resultData);

                                                let arrIndex = resultData[index];
                                                resultData.splice(index, 1);
                                                resultData.unshift(arrIndex);

                                                debug && console.log('新消息change end...' ,resultData);

                                                messageInit(true);
                                                userListHas = true;
                                                debug && console.log(resultData)
                                            }
                                        });
                                        const userName = selectSelf.$materialOptionsList.find('li.selected').text()
                                            ,userAvatar = selectSelf.$materialOptionsList.find('li.selected img').attr('src');

                                        edu.ajax({
                                            url: res.data.route,
                                            disabled_pop: true,
                                            method: 'get',
                                            callback: (res1) => {
                                                if(!userListHas) {
                                                    resultData.unshift(res1.data);
                                                    let html =
                                                        `<a href="#" class="list-group-item list-group-item-action media active" data-route="${res.data.route}" data-another="${user_id}">
                                                    <img class="mr-3 avatar-sm float-left" src="${userAvatar}">
                                                    <div class="d-flex justify-content-between mb-1 username">
                                                        <hp class="mb-1"><strong>${userName}</strong></hp>
                                                        <small>刚刚</small>
                                                    </div>
                                                    <p class="text-truncate"><strong>你 :</strong>${message}</p>
                                                </a>`;
                                                    let html2 =
                                                        `<div class="message-wrap">
                                                    <div class="message-list">
                                                        <div class="text-center">
                                                            <small>${res1.data[0].created_at}</small>
                                                        </div>
                                                        <div class="d-flex justify-content-end">
                                                            <p class="primary-color rounded p-3 text-white w-75">
                                                            ${res1.data[0].body}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>`;
                                                    $('.user-list .list-group').find('a.active').removeClass('active').end().prepend(html);
                                                    $('.right-wrap').find('.message-wrap').addClass('d-none').end().prepend(html2);
                                                }
                                                $('.select-dropdown').val('请输入联系人名称');
                                                $('#exampleFormControlTextarea6').val('');
                                                $('#basicExampleModal').modal('hide');
                                            }
                                        });

                                    }else {
                                        const data = [
                                            {
                                                created_at: '刚刚',
                                                sender_id: 0,
                                                body: message
                                            }
                                        ];
                                        edu.ajax({
                                            url: $('.user-list .list-group a.active').data('route'),
                                            method: 'get',
                                            disabled_pop: true,
                                            callback: (res) => {
                                                if(res.status === 'success') {

                                                    // 添加右侧消息 DOM
                                                    appendMessage(index, user_id, data, $('.user-list .list-group a.active'));
                                                    // 存在聊天记录 追加联系人列表 消息日志

                                                    resultData[index] = res.data;
                                                }
                                            }
                                        });
                                        $('#form7').val('');
                                    }
                                    setTimeout(() => {
                                        $('#sendNewMessageLoading').addClass('d-none');
                                        $('.user-list').scrollTop(0);
                                    }, 200)
                                }
                            }
                        })
                    }, debug ? 5000 : 0);
                }
            })
        });
        let selectSelf = null;
        function _defineProperties(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                    i.configurable = !0,
                "value"in i && (i.writable = !0),
                    Object.defineProperty(e, i.key, i)
            }
        }
        function _createClass(e, t, n) {
            return t && _defineProperties(e.prototype, t),
            n && _defineProperties(e, n),
                e
        }
        function _classCallCheck(e, t) {
            if (!(e instanceof t))
                throw new TypeError("Cannot call a class as a function")
        }
        function a(e) {
            var t, n = function() {
                function t(n, i) {
                    _classCallCheck(this, t),
                        this.options = i,
                        this.$nativeSelect = n,
                        this.isMultiple = Boolean(this.$nativeSelect.attr("multiple")),
                        this.isSearchable = Boolean(this.$nativeSelect.attr("searchable")),
                        this.isRequired = Boolean(this.$nativeSelect.attr("required")),
                        this.uuid = this._randomUUID(),
                        this.$selectWrapper = e('<div class="select-wrapper"></div>'),
                        this.$materialOptionsList = e('<ul id="select-options-'.concat(this.uuid, '" class="dropdown-content select-dropdown w-100 ').concat(this.isMultiple ? "multiple-select-dropdown" : "", '"></ul>')),
                        this.$materialSelectInitialOption = n.find("option:selected").html() || n.find("option:first").html() || "",
                        this.$nativeSelectChildren = this.$nativeSelect.children("option, optgroup"),
                        this.$materialSelect = e('<input type="text" class="select-dropdown" readonly="true" '.concat(this.$nativeSelect.is(":disabled") ? "disabled" : "", ' data-activates="select-options-').concat(this.uuid, '" value=""/>')),
                        this.$dropdownIcon = e('<span class="caret">&#9660;</span>'),
                        this.$searchInput = null,
                        this.$toggleAll = e('<li class="select-toggle-all"><span><input type="checkbox" class="form-check-input"><label>Select all</label></span></li>'),
                        this.valuesSelected = [],
                        this.keyCodes = {
                            tab: 9,
                            esc: 27,
                            enter: 13,
                            arrowUp: 38,
                            arrowDown: 40
                        },
                        t.mutationObservers = []
                }
                return _createClass(t, [{
                    key: "init",
                    value: function() {
                        if (Boolean(this.$nativeSelect.data("select-id")) && this._removeMaterialWrapper(),
                        "destroy" !== this.options) {
                            this.$nativeSelect.data("select-id", this.uuid),
                                this.$selectWrapper.addClass(this.$nativeSelect.attr("class"));
                            var e = this.$materialSelectInitialOption.replace(/"/g, "&quot;");
                            this.$materialSelect.val(e),
                                this.renderMaterialSelect(),
                                this.bindEvents(),
                            this.isRequired && this.enableValidation(),
                                selectSelf = this
                        } else
                            this.$nativeSelect.data("select-id", null).removeClass("initialized")
                    }
                }, {
                    key: "_removeMaterialWrapper",
                    value: function() {
                        var t = this.$nativeSelect.data("select-id");
                        this.$nativeSelect.parent().find("span.caret").remove(),
                            this.$nativeSelect.parent().find("input").remove(),
                            this.$nativeSelect.unwrap(),
                            e("ul#select-options-".concat(t)).remove()
                    }
                }, {
                    key: "renderMaterialSelect",
                    value: function() {
                        var t = this;
                        if (this.$nativeSelect.before(this.$selectWrapper),
                            this.appendDropdownIcon(),
                            this.appendMaterialSelect(),
                            this.appendMaterialOptionsList(),
                            this.appendNativeSelect(),
                            this.appendSaveSelectButton(),
                        this.$nativeSelect.is(":disabled") || this.$materialSelect.dropdown({
                            hover: !1,
                            closeOnClick: !1
                        }),
                        !1 !== this.$nativeSelect.data("inherit-tabindex") && this.$materialSelect.attr("tabindex", this.$nativeSelect.attr("tabindex")),
                            this.isMultiple)
                            this.$nativeSelect.find("option:selected:not(:disabled)").each(function(n, i) {
                                var r = e(i).index();
                                t._toggleSelectedValue(r),
                                    t.$materialOptionsList.find("li:not(.optgroup):not(.select-toggle-all)").eq(r).find(":checkbox").prop("checked", !0)
                            });
                        else {
                            var n = this.$nativeSelect.find("option:selected").index();
                            this.$materialOptionsList.find("li").eq(n).addClass("active")
                        }
                        this.$nativeSelect.addClass("initialized")
                    }
                }, {
                    key: "appendDropdownIcon",
                    value: function() {
                        this.$nativeSelect.is(":disabled") && this.$dropdownIcon.addClass("disabled"),
                            this.$selectWrapper.append(this.$dropdownIcon)
                    }
                }, {
                    key: "appendMaterialSelect",
                    value: function() {
                        this.$selectWrapper.append(this.$materialSelect)
                    }
                }, {
                    key: "appendMaterialOptionsList",
                    value: function() {
                        this.isSearchable && this.appendSearchInputOption(),
                            this.buildMaterialOptions(),
                        this.isMultiple && this.appendToggleAllCheckbox(),
                            this.$selectWrapper.append(this.$materialOptionsList)
                    }
                }, {
                    key: "appendNativeSelect",
                    value: function() {
                        this.$nativeSelect.appendTo(this.$selectWrapper)
                    }
                }, {
                    key: "appendSearchInputOption",
                    value: function() {
                        var t = this.$nativeSelect.attr("searchable");
                        this.$searchInput = e('<span class="search-wrap ml-2"><div class="md-form mt-0"><input type="text" class="search form-control w-100 d-block" placeholder="'.concat(t, '"></div></span>')),
                            this.$materialOptionsList.append(this.$searchInput)
                    }
                }, {
                    key: "appendToggleAllCheckbox",
                    value: function() {
                        this.$materialOptionsList.find("li.disabled").first().after(this.$toggleAll)
                    }
                }, {
                    key: "appendSaveSelectButton",
                    value: function() {
                        this.$selectWrapper.parent().find("button.btn-save").appendTo(this.$materialOptionsList)
                    }
                }, {
                    key: "buildMaterialOptions",
                    value: function() {
                        var t = this;
                        this.$nativeSelectChildren.each(function(n, i) {
                            var r = e(i);
                            if (r.is("option"))
                                t.buildSingleOption(r, t.isMultiple ? "multiple" : "");
                            else if (r.is("optgroup")) {
                                var o = e('<li class="optgroup"><span>'.concat(r.attr("label"), "</span></li>"));
                                t.$materialOptionsList.append(o),
                                    r.children("option").each(function(n, i) {
                                        t.buildSingleOption(e(i), "optgroup-option")
                                    })
                            }
                        })
                    }
                }, {
                    key: "buildSingleOption",
                    value: function(t, n) {
                        var i = t.is(":disabled") ? "disabled" : ""
                            , r = "optgroup-option" === n ? "optgroup-option" : ""
                            , o = t.data("icon")
                            , s = t.data("fas") ? '<i class="fas fa-'.concat(t.data("fas"), '"></i>') : ""
                            , a = t.attr("class")
                            , l = o ? '<img alt="" src="'.concat(o, '" class="').concat(a, '">') : ""
                            , c = this.isMultiple ? '<input type="checkbox" class="form-check-input" '.concat(i, "/><label></label>") : "";
                        this.$materialOptionsList.append(e('<li class="'.concat(i, " ").concat(r, '">').concat(l, '<span class="filtrable">').concat(c, " ").concat(s, " ").concat(t.html(), "</span></li>")))
                    }
                }, {
                    key: "enableValidation",
                    value: function() {
                        this.$nativeSelect.css({
                            position: "absolute",
                            top: "1rem",
                            left: "0",
                            height: "0",
                            width: "0",
                            opacity: "0",
                            padding: "0",
                            "pointer-events": "none"
                        }),
                        -1 === this.$nativeSelect.attr("style").indexOf("inline!important") && this.$nativeSelect.attr("style", "".concat(this.$nativeSelect.attr("style"), " display: inline!important;")),
                            this.$nativeSelect.attr("tabindex", -1),
                            this.$nativeSelect.data("inherit-tabindex", !1)
                    }
                }, {
                    key: "bindEvents",
                    value: function() {
                        var n = this
                            , i = new MutationObserver(this._onMutationObserverChange.bind(this));
                        i.observe(this.$nativeSelect.get(0), {
                            attributes: !0,
                            childList: !0,
                            characterData: !0,
                            subtree: !0
                        }),
                            i.customId = this.uuid,
                            i.customStatus = "observing",
                            t.clearMutationObservers(),
                            t.mutationObservers.push(i),
                            this.$nativeSelect.parent().find("button.btn-save").on("click", this._onSaveSelectBtnClick),
                            this.$materialSelect.on("focus", this._onMaterialSelectFocus.bind(this)),
                            this.$materialSelect.on("click", this._onMaterialSelectClick.bind(this)),
                            this.$materialSelect.on("blur", this._onMaterialSelectBlur.bind(this)),
                            this.$materialSelect.on("keydown", this._onMaterialSelectKeydown.bind(this)),
                            this.$toggleAll.on("click", this._onToggleAllClick.bind(this)),
                            this.$materialOptionsList.on("mousedown", this._onEachMaterialOptionMousedown.bind(this)),
                            this.$materialOptionsList.find("li:not(.optgroup)").not(this.$toggleAll).each(function(t, i) {
                                e(i).on("click", n._onEachMaterialOptionClick.bind(n, t, i))
                            }),
                        !this.isMultiple && this.isSearchable && this.$materialOptionsList.find("li").on("click", this._onSingleMaterialOptionClick.bind(this)),
                        this.isSearchable && this.$searchInput.find(".search").on("keyup", this._onSearchInputKeyup),
                            e("html").on("click", this._onHTMLClick.bind(this))
                    }
                }, {
                    key: "_onMutationObserverChange",
                    value: function(n) {
                        n.forEach(function(n) {
                            console.log(n);
                            var i = e(n.target).closest("select");
                            !0 !== i.data("stop-refresh") && ("childList" === n.type || "attributes" === n.type && e(n.target).is("option")) && (t.clearMutationObservers(),
                                i.materialSelect("destroy"),
                                i.materialSelect())
                        })
                    }
                }, {
                    key: "_onSaveSelectBtnClick",
                    value: function() {
                        e("input.select-dropdown").trigger("close")
                    }
                }, {
                    key: "_onEachMaterialOptionClick",
                    value: function(t, n, i) {
                        i.stopPropagation();
                        var r = e(n);
                        if (!r.hasClass("disabled") && !r.hasClass("optgroup")) {
                            var o = !0;
                            if (this.isMultiple) {
                                r.find('input[type="checkbox"]').prop("checked", function(e, t) {
                                    return !t
                                });
                                var s = Boolean(this.$nativeSelect.find("optgroup").length)
                                    , a = this._isToggleAllPresent() ? r.index() - 1 : r.index();
                                o = this.isSearchable && s ? this._toggleSelectedValue(a - r.prevAll(".optgroup").length - 1) : this.isSearchable ? this._toggleSelectedValue(a - 1) : s ? this._toggleSelectedValue(a - r.prevAll(".optgroup").length) : this._toggleSelectedValue(a),
                                this._isToggleAllPresent() && this._updateToggleAllOption(),
                                    this.$materialSelect.trigger("focus")
                            } else
                                this.$materialOptionsList.find("li").removeClass("active"),
                                    r.toggleClass("active"),
                                    this.$materialSelect.val(r.text()),
                                    this.$materialSelect.trigger("close");
                            this._selectSingleOption(r),
                                this.$nativeSelect.data("stop-refresh", !0),
                                this.$nativeSelect.find("option").eq(t).prop("selected", o),
                                this.$nativeSelect.removeData("stop-refresh"),
                                this._triggerChangeOnNativeSelect(),
                            "function" == typeof this.options && this.options()
                        }
                    }
                }, {
                    key: "_triggerChangeOnNativeSelect",
                    value: function() {
                        var e = new KeyboardEvent("change",{
                            bubbles: !0,
                            cancelable: !0
                        });
                        this.$nativeSelect.get(0).dispatchEvent(e)
                    }
                }, {
                    key: "_onMaterialSelectFocus",
                    value: function(t) {
                        var n = e(t.target);
                        if (e("ul.select-dropdown").not(this.$materialOptionsList.get(0)).is(":visible") && e("input.select-dropdown").trigger("close"),
                            !this.$materialOptionsList.is(":visible")) {
                            n.trigger("open", ["focus"]);
                            var i = n.val()
                                , r = this.$materialOptionsList.find("li").filter(function() {
                                return e(this).text().toLowerCase() === i.toLowerCase()
                            })[0];
                            this._selectSingleOption(r)
                        }
                    }
                }, {
                    key: "_onMaterialSelectClick",
                    value: function(e) {
                        e.stopPropagation()
                    }
                }, {
                    key: "_onMaterialSelectBlur",
                    value: function(t) {
                        var n = e(t);
                        this.isMultiple || this.isSearchable || n.trigger("close"),
                            this.$materialOptionsList.find("li.selected").removeClass("selected")
                    }
                }, {
                    key: "_onSingleMaterialOptionClick",
                    value: function() {
                        this.$materialSelect.trigger("close")
                    }
                }, {
                    key: "_onEachMaterialOptionMousedown",
                    value: function(t) {
                        var n = t.target;
                        e(".modal-content").find(this.$materialOptionsList).length && n.scrollHeight > n.offsetHeight && t.preventDefault()
                    }
                }, {
                    key: "_onHTMLClick",
                    value: function(t) {
                        e(t.target).closest("#select-options-".concat(this.uuid)).length || this.$materialSelect.trigger("close")
                    }
                }, {
                    key: "_onToggleAllClick",
                    value: function() {
                        var t = this
                            , n = e(this.$toggleAll).find('input[type="checkbox"]').first()
                            , i = !e(n).prop("checked");
                        e(n).prop("checked", i),
                            this.$materialOptionsList.find("li:not(.optgroup):not(.disabled):not(.select-toggle-all)").each(function(n, r) {
                                var o = e(r).find('input[type="checkbox"]');
                                i && o.is(":checked") || !i && !o.is(":checked") || (t._isToggleAllPresent() && n++,
                                    o.prop("checked", i),
                                    t.$nativeSelect.find("option").eq(n).prop("selected", i),
                                    i ? e(r).removeClass("active") : e(r).addClass("active"),
                                    t._toggleSelectedValue(n),
                                    t._selectOption(r),
                                    t._setValueToMaterialSelect())
                            }),
                            this.$nativeSelect.data("stop-refresh", !0),
                            this._triggerChangeOnNativeSelect(),
                            this.$nativeSelect.removeData("stop-refresh")
                    }
                }, {
                    key: "_onMaterialSelectKeydown",
                    value: function(t) {
                        var n = e(t.target)
                            , i = t.which === this.keyCodes.tab
                            , r = t.which === this.keyCodes.esc
                            , o = t.which === this.keyCodes.enter
                            , s = t.which === this.keyCodes.arrowUp
                            , a = t.which === this.keyCodes.arrowDown
                            , l = this.$materialOptionsList.is(":visible");
                        i ? this._handleTabKey(n) : !a || l ? o && !l || (t.preventDefault(),
                            o ? this._handleEnterKey(n) : a ? this._handleArrowDownKey() : s ? this._handleArrowUpKey() : r ? this._handleEscKey(n) : this._handleLetterKey(t)) : n.trigger("open")
                    }
                }, {
                    key: "_handleTabKey",
                    value: function(e) {
                        this._handleEscKey(e)
                    }
                }, {
                    key: "_handleEnterKey",
                    value: function(t) {
                        var n = e(t);
                        this.$materialOptionsList.find("li.selected:not(.disabled)").trigger("click"),
                        this.isMultiple || n.trigger("close")
                    }
                }, {
                    key: "_handleArrowDownKey",
                    value: function() {
                        var e = this.$materialOptionsList.find("li").not(".disabled").not(".select-toggle-all").first()
                            , t = this.$materialOptionsList.find("li").not(".disabled").not(".select-toggle-all").last()
                            , n = this.$materialOptionsList.find("li.selected").length > 0
                            , i = n ? this.$materialOptionsList.find("li.selected") : e
                            , r = i.is(t) || !n ? i : i.next("li:not(.disabled)");
                        this._selectSingleOption(r),
                            this.$materialOptionsList.find("li").removeClass("active"),
                            r.toggleClass("active")
                    }
                }, {
                    key: "_handleArrowUpKey",
                    value: function() {
                        var e = this.$materialOptionsList.find("li").not(".disabled").not(".select-toggle-all").first()
                            , t = this.$materialOptionsList.find("li").not(".disabled").not(".select-toggle-all").last()
                            , n = this.$materialOptionsList.find("li.selected").length > 0
                            , i = n ? this.$materialOptionsList.find("li.selected") : t
                            , r = i.is(e) || !n ? i : i.prev("li:not(.disabled)");
                        this._selectSingleOption(r),
                            this.$materialOptionsList.find("li").removeClass("active"),
                            r.toggleClass("active")
                    }
                }, {
                    key: "_handleEscKey",
                    value: function(t) {
                        e(t).trigger("close")
                    }
                }, {
                    key: "_handleLetterKey",
                    value: function(t) {
                        var n = this
                            , i = ""
                            , r = String.fromCharCode(t.which).toLowerCase()
                            , o = Object.keys(this.keyCodes).map(function(e) {
                            return n.keyCodes[e]
                        });
                        if (r && -1 === o.indexOf(t.which)) {
                            i += r;
                            var s = this.$materialOptionsList.find("li").filter(function() {
                                return -1 !== e(this).text().toLowerCase().indexOf(i)
                            }).first();
                            this.isMultiple || this.$materialOptionsList.find("li").removeClass("active"),
                                s.addClass("active"),
                                this._selectSingleOption(s)
                        }
                    }
                }, {
                    key: "_onSearchInputKeyup",
                    value: edu.throttle(function(t) {
                        var n = e(t && t.target)
                                , i = n.closest("ul")
                                , r = n.val();
                        i.find('li').remove();
                        i.append(`
                            <li class="disabled active">
                                <span class="filtrable" style="display: inline-block;">请稍等</span>
                                <div class="preloader-wrapper small active" style="width: 1rem;height: 1rem;">
                                  <div class="spinner-layer spinner-blue-only">
                                    <div class="circle-clipper left">
                                      <div class="circle" style="border-width: 0.075rem"></div>
                                    </div>
                                    <div class="gap-patch">
                                      <div class="circle"></div>
                                    </div>
                                    <div class="circle-clipper right">
                                      <div class="circle" style="border-width: 0.075rem"></div>
                                    </div>
                                  </div>
                                </div>
                            </li>`);
                        setTimeout(() => {
                            edu.ajax({
                                url: '/api/users',
                                method: 'get',
                                data: {
                                    username: `%${r}%`
                                },
                                callback: (res) => {
                                    i.find('li').remove();
                                    let html = '<li class="disabled active"><span class="filtrable">请选择（最多展示前五十名）</span></li>';
                                    res.data.data.map((item, index) => {
                                        if(index <= 50) {
                                            html += `<li class="" data-userId="${item.id}"><img alt="" src="${item.avatar || '/images/avatar.png'}" class="rounded-circle"><span class="filtrable">${item.username}</span></li>`
                                        }
                                    });
                                    i.append(html);
                                    selectSelf.bindEvents();
                                }
                            })
                        }, debug ? 5000 : 0)
                    })
                }, {
                    key: "_isToggleAllPresent",
                    value: function() {
                        return this.$materialOptionsList.find(this.$toggleAll).length
                    }
                }, {
                    key: "_updateToggleAllOption",
                    value: function() {
                        var e = this.$materialOptionsList.find("li").not(".select-toggle-all, .disabled").find("[type=checkbox]")
                            , t = e.filter(":checked")
                            , n = this.$toggleAll.find("[type=checkbox]").is(":checked");
                        t.length !== e.length || n ? t.length < e.length && n && this.$toggleAll.find("[type=checkbox]").prop("checked", !1) : this.$toggleAll.find("[type=checkbox]").prop("checked", !0)
                    }
                }, {
                    key: "_toggleSelectedValue",
                    value: function(e) {
                        var t = this.valuesSelected.indexOf(e)
                            , n = -1 !== t;
                        return n ? this.valuesSelected.splice(t, 1) : this.valuesSelected.push(e),
                            this.$materialOptionsList.find("li:not(.optgroup):not(.select-toggle-all)").eq(e).toggleClass("active"),
                            this.$nativeSelect.find("option").eq(e).prop("selected", !n),
                            this._setValueToMaterialSelect(),
                            !n
                    }
                }, {
                    key: "_selectSingleOption",
                    value: function(e) {
                        this.$materialOptionsList.find("li.selected").removeClass("selected"),
                            this._selectOption(e)
                    }
                }, {
                    key: "_selectOption",
                    value: function(t) {
                        e(t).addClass("selected")
                    }
                }, {
                    key: "_setValueToMaterialSelect",
                    value: function() {
                        for (var e = "", t = this.valuesSelected.length, n = 0; n < t; n++) {
                            var i = this.$nativeSelect.find("option").eq(this.valuesSelected[n]).text();
                            e += ",".concat(i)
                        }
                        0 === (e = t >= 5 ? "".concat(t, " options selected") : e.substring(2)).length && (e = this.$nativeSelect.find("option:disabled").eq(0).text()),
                            this.$nativeSelect.siblings("input.select-dropdown").val(e)
                    }
                }, {
                    key: "_randomUUID",
                    value: function() {
                        var e = (new Date).getTime();
                        return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, function(t) {
                            var n = (e + 16 * Math.random()) % 16 | 0;
                            return e = Math.floor(e / 16),
                                ("x" === t ? n : 3 & n | 8).toString(16)
                        })
                    }
                }], [{
                    key: "clearMutationObservers",
                    value: function() {
                        t.mutationObservers.forEach(function(e) {
                            e.disconnect(),
                                e.customStatus = "stopped"
                        })
                    }
                }]),
                    t
            }();
            e.fn.materialSelect = function(t) {
                e(this).not(".browser-default").not(".custom-select").each(function() {
                    new n(e(this),t).init()
                })
            }
                ,
                e.fn.material_select = e.fn.materialSelect,
                t = e.fn.val,
                e.fn.val = function(e) {
                    if (!arguments.length)
                        return t.call(this);
                    if (!0 !== this.data("stop-refresh") && this.hasClass("mdb-select") && this.hasClass("initialized") && !this.hasClass("browser-default") && !this.hasClass("custom-select")) {
                        n.clearMutationObservers(),
                            this.materialSelect("destroy");
                        var i = t.call(this, e);
                        return this.materialSelect(),
                            i
                    }
                    return t.call(this, e)
                }
            console.dir(e)

        }
        a($);
        $("select").siblings("input.select-dropdown").on("mousedown", function(e) {
            /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) && (e.clientX >= e.target.clientWidth || e.clientY >= e.target.clientHeight) && e.preventDefault()
        });
        $('select').materialSelect();
    </script>
@endsection