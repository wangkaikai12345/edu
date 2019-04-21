import echarts from 'echarts';

$(function () {
    var myChart = echarts.init(document.getElementById('grade'));

// 指定图表的配置项和数据
    var option = {
        xAxis: {
            type: 'category',
            show: false,
        },
        grid: {
            y: '9%',
            x: '0%',
            x2: '6%',
            y2: '5%'
        },
        tooltip: {
            trigger: 'axis'
        },
        yAxis: {
            show: false,
            type: 'value',
            position: 'right',
            axisLine: {
                lineStyle: {
                    type: 'solid',
                    color: 'rgba(255,255,255,0.7)',
                    width: 2
                }
            },
            axisLabel: {
                textStyle: {
                    color: 'rgba(255,255,255,0.7)',
                },
                formatter: '{value}分'
            },
            splitLine: {
                show: true,
                lineStyle: {
                    color: 'rgba(255,255,255,0.7)',
                    width: 2,
                    type: 'solid'
                }
            },
            "axisTick": {       //y轴刻度线
                "show": false
            },
        },
        series: [{
            data: $('#grade').data('score'),
            type: 'line',
            symbol: 'circle',     //设定为实心点
            symbolSize: 6,
            itemStyle: {
                normal: {
                    color: '#6B91FF',
                    lineStyle: {
                        color: 'rgba(255,255,255,0.7)',
                        width: 2
                    },
                }
            },
            areaStyle: {
                color: new echarts.graphic.LinearGradient(
                    0, 0, 0, 1,
                    [
                        {offset: 0, color: '#6B91FF'},
                        {offset: 1, color: '#fff'}
                    ]
                ),
            },
        }],


        // backgroundColor: new echarts.graphic.LinearGradient(
        //     0, 0, 0, 1,
        //     [
        //         {offset: 0, color: '#6B91FF'},
        //         {offset: 1, color: '#A4C4FF'}
        //     ]
        // ),
    };


    $.each(option.series[0].data, function (index, item) {
        if (item >= 100) {
            option.series[0].data.splice(index, 1, {
                value: item,
                symbol: 'image:///imgs/star-on.svg',
                symbolSize: 17,
            })
        }
    });

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);

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

    // 关注
    $(document).on('click', '.follow', function () {

        var follow = $(this);
        var id = $(this).data('id');

        if (!id) {
            edu.alert('danger', '关注错误');
            return false;
        }
        follow.removeClass('follow');
        $.ajax({
            url: '/users',
            method: 'post',
            data: {follow_id: id},
            success: function (res) {

                if (res.status == '200') {

                    if (res.data.length == 0) {
                        follow.html('关注');
                        edu.alert('success', '取消关注成功');
                    } else {
                        follow.html('取消关注');
                        edu.alert('success', '关注成功');
                    }

                    follow.addClass('follow');
                }
            }
        })
        return false;
    })

    // 发送私信的模态框
    $(document).on('click', '.message', function () {
        var id = $(this).data('id');

        var username = $(this).data('username');

        $('#username').html(username);

        $('#user_id').val(id);

        $('#modal_6').modal('show');

        return false;
    })

    // 发送私信
    $('#sendMessage').click(function () {

        // 数据验证
        var id = $('#user_id').val();

        var message = $('#message').val();

        if (!id || !message) {
            edu.alert('danger', '请完善私信信息');
            return false;
        }

        $.ajax({
            url: '/my/message',
            method: 'post',
            data: {user_id: id, message: message},
            success: function (res) {

                if (res.status == '200') {
                    // 清空信息
                    $('#username').html('');

                    $('#user_id').val('');

                    $('#message').val('');

                    edu.alert('success', '发送成功');

                    $('#modal_6').modal('hide');
                }
            }
        })
    })
});