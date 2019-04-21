$('[data-toggle="popover"]').popover({}).on('shown.bs.popover', function (event) {
    var that = this;
    $('body').find('div.popover').on('mouseenter', function () {
        $(that).attr('in', true);
    }).on('mouseleave', function () {
        $(that).removeAttr('in');
        $(that).popover('hide');
    });
}).on('hide.bs.popover', function (event) {
    if ($(this).attr('in')) {
        event.preventDefault();
    }
});