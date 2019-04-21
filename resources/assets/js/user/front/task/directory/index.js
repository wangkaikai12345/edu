$('.target_type').on({
    click: function(){

        var _this = $(this);
        var route = $(this).attr('data-route');

        if (!route) {return false;}

        $.ajax({
            url: route,
            method: 'get',
            data: {},
            success: function (res) {

                // console.log(res);

            },
            error: function(res){
                console.log(res);
                if (res.status == '200') {
                    $('.directory_item').removeClass('active');
                    _this.parents('.directory_item').addClass('active');

                    $('#content').empty();
                    $('#content').append(res.responseText);

                    if(_this.attr('data-type') === 'paper') {
                        $('.zh_content_wrap').addClass('paper');
                        return;
                    }

                    $('.zh_content_wrap').removeClass('paper');
                }
            }
        })
    }
});
