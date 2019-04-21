$('.notice .nav_item').on({
    click: function () {
        $('.notice .nav_item').removeClass('active');
        $(this).addClass('active');
        $('.notice_lists').removeClass('active').eq($(this).index()).addClass('active');
    }
});
$('.nav_item').click(function(){
    var route = $(this).data('route');

    $('#all').attr('href', route);
});