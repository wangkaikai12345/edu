<link rel="stylesheet" href="{{ mix('/css/front/task/guide/index.css') }}">
<div class="guide_body" id="guide" style="display:none">
    <div class="guide_content">
        <div class="guide_top">
            <div class="guide_img">
                <img src="/imgs/user/front/task/guide/guide-1.png" alt="">
            </div>
            <div class="guide_img">
                <img src="/imgs/user/front/task/guide/guide-2.png" alt="">
            </div>
            <div class="guide_img">
                <img src="/imgs/user/front/task/guide/guide-3.png" alt="">
            </div>
        </div>
        <div class="guide_bottom row">
            <div class="col-md-3">
                <p class="guide_bottom_left">
                    请您仔细阅读操作引导
                </p>
            </div>
            <div class="col-md-6">
                <div class="page_btn" data-number="1">

                </div>
            </div>
            <div class="col-md-3 p-0 row">
                <div class="col-md-7 p-0 pull-right"><a class="guide_btn_skip" href="javascript:;">不用了，蟹蟹~</a></div>
                <div class="col-md-5 p-0"><a class="guide_btn_continue" href="javascript:;">继续</a></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {

        if ( window.localStorage.getItem('guide') ) {
            $('#guide').css('display', 'none');
        }

        // 声明空变量
        var str = '';
        // 获取引导页个数
        var img_item = $('.guide_img').length;

        // 循环
        for (var guide_num = 0; guide_num < img_item; guide_num++) {
            $('.guide_img').eq(guide_num).css('left', (guide_num * 1080 + 10) + 'px');
        }

        for (var i = 1; i <= img_item; i++) {
            if (i == 1) {
                str += '<div class="page_btn_item active" onclick="show($(this))" data-number="' + i + '">';
            } else {
                str += '<div class="page_btn_item" onclick="show($(this),' + i + ')" data-number="' + i + '">';
            }
            str += '</div>';
        }

        $('.page_btn').append(str);

        // 继续学习
        $('.guide_btn_continue').click(function () {
            show($('.page_btn').children().eq($('.page_btn').attr('data-number') * 1), $('.page_btn').attr('data-number') * 1 + 1);
        });

        function show(dom_this, num = 1) {
            if (num > img_item) {
                $('.guide_btn_continue').addClass('start_learn')
                return false;
            }

            if (num == img_item) {
                $('.guide_btn_skip').hide();
                $('.guide_btn_continue').html('开始学习');
            } else {
                $('.guide_btn_skip').show();
                $('.guide_btn_continue').removeClass('start_learn').html('继续');
            }
            dom_this.addClass('active').siblings().removeClass('active');
            // $('.guide_top').children().eq(num - 1).show().siblings().hide();
            $('.guide_top').css('left', '-' + ((num - 1) * 1080) + 'px');
            $('.page_btn').attr('data-number', num);
        }

        $(document).on('click', '.start_learn', function () {
            if(typeof dp != 'undefined') {
                dp.play();
            }

            window.localStorage.setItem('guide', true);

            $('.guide_body').hide();
        });

        $(document).on('click', '.guide_btn_skip', function () {
            if(typeof dp != 'undefined') {
                dp.play();
            }

            window.localStorage.setItem('guide', true);
            $('.guide_body').hide();
        });
    })

</script>