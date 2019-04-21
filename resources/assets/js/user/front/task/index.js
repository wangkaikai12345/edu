$('.scroll_change').on({
    mousedown: function (e) {
        $('#dplayer,#zh_question .question_scroll,#czh_note .note_scroll').append(`<div class="move_shadow"></div>`);
        const originX = e.pageX
            , originY = e.pageY
            , self = $(this)
            , _width = self.parent().width()
            , videoWidth = $('#dplayer').width()
            , windowWidth = $(window).width();
        $(document).on('mousemove', function (e) {
            let nowX = e.pageX
                , nowY = e.pageY
                , wrapRightWidth = _width - Math.abs(nowX - originX)
                , wrapLeftWidth = _width + Math.abs(nowX - originX)
                , dplayerRightWidth = videoWidth + Math.abs(nowX - originX)
                , dplayerLeftWidth = videoWidth - Math.abs(nowX - originX);
            if (nowX > originX) {
                if (wrapRightWidth < 520) {
                    wrapRightWidth = 520;
                    dplayerRightWidth = windowWidth - 80 - wrapRightWidth;
                }
                $('.czhNote,.zh_question').css('width', wrapRightWidth);
                $('#dplayer').css('width', dplayerRightWidth);
                return;
            }else {
                if (dplayerLeftWidth < 440) {
                    dplayerLeftWidth = 440;
                    wrapLeftWidth = windowWidth - 80 - dplayerLeftWidth;
                }
            }
            $('.czhNote,.zh_question').css('width', wrapLeftWidth);
            $('#dplayer').css('width', dplayerLeftWidth);
        })
    },
});

$(document).on('mouseup', function () {
    $(document).off('mousemove');
    $('#dplayer .move_shadow,#zh_question .move_shadow,#czh_note .move_shadow').remove();
});

$(window).on('resize', edu.throttle(() => {
    const windowWidth = $(window).width()
        , noteWidth = $('#czh_note').width()
        , questionWidth = $('#zh_question').width();
    if(windowWidth < 450) {
        return;
    }
    if(noteWidth > windowWidth) {
        $('#czh_note,#zh_question').css({
            width: '640px'
        }).removeClass('active');
        $('#dplayer').addClass('full_page');
    }
}));

$('#zh_directory .directory_header .down_controls').on({
    click: () => {
        $('#zh_directory').toggleClass('header_active');
        $('#zh_directory .directory_header').toggleClass('active');
    }
});

$('#zh_directory .directory_footer .up_controls').on({
    click: () => {
        $('#zh_directory').toggleClass('footer_active');
        $('#zh_directory .directory_footer').toggleClass('active');
    }
});

$(document).on('click', '*[data-toggle-slide-nav=directory]', function () {
    $('#zh_directory').toggleClass('active');

    $(this).toggleClass('active');
});

$(document).on('click', '*[data-toggle-slide-nav=question]', function () {
    if($(window).width() < 720) {
        return;
    }
    if(!$('.czhNote').hasClass('active') || !$('.zh_question').hasClass('active')) {
        $('#dplayer').removeClass('full_page');
    }

    $('#czh_note,*[data-toggle-slide-nav=note]').removeClass('active');

    $(this).toggleClass('active');

    $('#zh_question').toggleClass('active');

    if(!$('.czhNote').hasClass('active') && !$('.zh_question').hasClass('active')) {
        $('#dplayer').addClass('full_page');
    }
});

$(document).on('click', '*[data-toggle-slide-nav=note]', function () {
    if($(window).width() < 720) {
        return;
    }
    if(!$('.czhNote').hasClass('active') || !$('.zh_question').hasClass('active')) {
        $('#dplayer').removeClass('full_page');
    }

    $(this).toggleClass('active');

    $('#zh_question,*[data-toggle-slide-nav=question]').removeClass('active');

    $('#czh_note').toggleClass('active');

    if(!$('.czhNote').hasClass('active') && !$('.zh_question').hasClass('active')) {
        $('#dplayer').addClass('full_page');
    }
});

$('#phone_controls').on({
    click: function () {
        $(this).toggleClass('active');
        $('#zh_directory').toggleClass('active');
    }
});


