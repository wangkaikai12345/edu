import echarts from 'echarts';

var myChart = echarts.init(document.getElementById('grade'));

// 指定图表的配置项和数据
var option = {
    xAxis: {
        type: 'category',
        show: false,
    },
    grid:{
        y: '10%',
        x2: '20%',
        y2: '10%'
    },
    yAxis: {
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
        "axisTick":{       //y轴刻度线
            "show":false
        },
        scale: true,
    },
    series: [{
        data:$('#grade').data('score'),
        type: 'line',
        itemStyle: {
            normal: {
                color: '#fff',
                lineStyle: {
                    color: 'rgba(255,255,255,0.7)',
                    width: 2
                },
            }
        },
        areaStyle: {
            color: 'rgba(255,255,255,0.3)',
        },
    }],

    backgroundColor: new echarts.graphic.LinearGradient(
        0, 0, 0, 1,
            [
                {offset: 0, color: '#6B91FF'},
                {offset: 1, color: '#A4C4FF'}
            ]
    ),
};

$.each(option.series[0].data,function(index, item){
    if(item >= 100) {
        option.series[0].data.splice(index, 1, {
            value: item,
            symbol: 'image:///imgs/star-on.svg',
            symbolSize: 17,
        })
    }
});

// 使用刚指定的配置项和数据显示图表。
myChart.setOption(option);
