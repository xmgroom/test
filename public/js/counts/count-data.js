

// 观看人数


 var myChart = echarts.init(document.getElementById('live'));

 option = {

     xAxis: {
         data: ['00:00','03:00','06:00', '09:00', '12:00', '15:00', '18:00','21:00','24:00']
     },
     tooltip: {
         trigger: 'axis'
     },
     yAxis: {
         name:"人数(UV)",
         nameGap:24,
         nameTextStyle:{
             color:"#ccc"
         }
         },
     series: [{
         type: 'line',
         data: [19, 23, 48, 86, 290, 300, 200, 125,88],
         areaStyle: {
             normal: {
                 color: {
                     type: 'linear',
                     x: 0,
                     y: 0,
                     x2: 0,
                     y2: 1,
                     colorStops: [{
                         offset: 0,
                         color: '#00d5ff' // 0% 处的颜色
                     }, {
                         offset: 1,
                         color: '#fff' // 100% 处的颜色
                     }],
                     globalCoord: false // 缺省为 false
                 }
             }
         },
         symbol:'circle',
         symbolSize:8,
     }],
     color: [
         '#00d5ff'
     ]
 };
 myChart.setOption(option);


 //地域统计

 var mapChart = echarts.init(document.getElementById('map'));


  function chooseTime(timeInterval){
            switch(timeInterval){
                case 1:
                 console.log('today');
                 $.get('',function(res){
                     
                 })
                 break;
                case 2:
                 console.log('yesterday');
                  $.get('',function(res){
                     
                 })
                 break;
                case 3:
                 console.log('lastweek');
                  $.get('',function(res){
                     
                 })
                 break;
                case 4:
                console.log('lastmonth');
                 $.get('',function(res){
                     
                 })
            }
        }

function randomData() {
    return Math.round(Math.random() * 1000);
}

mapOption = {
    tooltip: {
        trigger: 'item'
    },

    visualMap: {
        min: 0,
        max: 45000,
        left: "80%",
        top: 'top',
        text: ['高','低'], // 文本，默认为数值文本
        textGap:10,
        calculable: false,
        itemHeight: 230,
        inRange: {
            color: ["#c8f0fa", "#a3e9fa", '#6ee0fa', '#0fd7ff', '#00d5ff']
        }
    },
    series: [{
        name: '2016年',
        type: 'map',
        mapType: 'china',
        roam: false,
        label: {
            normal: {
                show: false
            },
            emphasis: {
                show: false
            }
        },
        itemStyle:{
            normal:{
                borderColor:"transparent"
            }
        },
        data: [{
            name: '北京',
            value: 21300
        }, {
            name: '天津',
            value: 5816
        }, {
            name: '上海',
            value: 28408
        }, {
            name: '重庆',
            value: 7890
        }, {
            name: '河北',
            value: 7120
        }, {
            name: '河南',
            value: 12406
        }, {
            name: '云南',
            value: 6870
        }, {
            name: '辽宁',
            value: 9845
        }, {
            name: '黑龙江',
            value: 5222
        }, {
            name: '湖南',
            value: 12658
        }, {
            name: '安徽',
            value: 10783
        }, {
            name: '山东',
            value: 16253
        }, {
            name: '新疆',
            value: 3536
        }, {
            name: '江苏',
            value: 31939
        }, {
            name: '浙江',
            value: 33156
        }, {
            name: '江西',
            value: 8684
        }, {
            name: '湖北',
            value: 14803
        }, {
            name: '广西',
            value: 7531
        }, {
            name: '甘肃',
            value: 2880
        }, {
            name: '山西',
            value: 5444
        }, {
            name: '内蒙古',
            value: 3775
        }, {
            name: '陕西',
            value: 7435
        }, {
            name: '吉林',
            value: 4019
        }, {
            name: '福建',
            value: 14269
        }, {
            name: '贵州',
            value: 5106
        }, {
            name: '广东',
            value: 41800
        }, {
            name: '青海',
            value: 600
        }, {
            name: '西藏',
            value: 365
        }, {
            name: '四川',
            value: 16160
        }, {
            name: '宁夏',
            value: 1027
        }, {
            name: '海南',
            value: 2149
        }, {
            name: '台湾',
            value: randomData()
        }, {
            name: '香港',
            value: randomData()
        }, {
            name: '澳门',
            value: 7
        }]
    }, ],

};

mapChart.setOption(mapOption);

//访问统计

var accChart = echarts.init(document.getElementById('access'));
accOption = {
     title: [ {
            text: '运营商',
            left: '50%',
            top: '49%',
            textAlign: 'center',
            textBaseline: 'middle',
            textStyle: {
                color: '#666',
                fontWeight: 'normal',
                fontSize: 30
            }
        }],
    tooltip: {
        trigger: 'item',
        formatter: "{a} <br/>{b}: {c} ({d}%)",

    },
    legend: {

        orient: 'vertical',
        x: 'left',
        y:'bottom',
        align: 'left',
       data:['直接访问','链接访问','其他'],

        textStyle: {
            color: '#ccc'
        }
    },
    series: [

        {
            name: '访问来源',
            type: 'pie',
            radius: ['80', '120'],
            color: ['#51b8fe','#59c5a7','#fa827d'],
            label: {
                normal: {
                    formatter: '{b}\n{d}%'
                },

            },
            data: [{value:335, name:'直接访问'},
                {value:310, name:'链接访问'},
                {value:234, name:'其他'}]
        }
    ]
};
 accChart.setOption(accOption, true);


//终端分析

// 手机端

var mobileChart = echarts.init(document.getElementById('mobile'));

mobileOption = {
    color: ['#3398DB'],
    tooltip : {
        trigger: 'axis',
        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
        }
    },
    grid: {
        left: '3%', 
        right: '4%',
        bottom: '3%',
        containLabel: true
    },
    xAxis : [
        {
            type : 'category',
            data : ['android设备', 'ios设备', '其他'],
            axisTick: {
                alignWithLabel: true
            }
        }
    ],
    yAxis : [
        {
            // type : 'category',
            // data : ['10','20','30','40'],
            axisTick: {
                show:true,
            },
            axisLabel: {
                show:false,
            }
        }
    ],
    series : [
        {
            name:'',
            type:'bar',
            barWidth: '40%',
            data:[100, 120, 30]
        },
        
    ],
    label: {
            normal: {
                show: true,
                position: 'top',
                formatter: '{c}'
            }
        },
    itemStyle: {
                normal: {
                 
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                        offset: 0,
                        color: 'rgba(255,153,102, 1)'
                    }, {
                        offset: 1,
                        color: 'rgba(255,153,102, .1)'
                    }]),
                    shadowColor: 'rgba(0, 0, 0, 0.1)',
                    shadowBlur: 10
                }
            }
};

mobileChart.setOption(mobileOption);


//浏览器


var browserChart = echarts.init(document.getElementById('browser'));

browserOption = {
    color: ['#3398DB'],
    tooltip : {
        trigger: 'axis',
        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
        }
    },
    grid: {
        left: '3%', 
        right: '4%',
        bottom: '3%',
        containLabel: true
    },
    xAxis : [
        {
            type : 'category',
            data : ['safari', 'chrome', '其他'],
            axisTick: {
                alignWithLabel: true
            }
        }
    ],
    yAxis : [
        {
            // type : 'category',
            // data : ['10','20','30','40'],
            axisTick: {
                show:true,
            },
            axisLabel: {
                show:false,
            }
        }
    ],
    series : [
        {
            name:'',
            type:'bar',
            barWidth: '40%',
            data:[100, 120, 30]
        },
        
    ],
    label: {
            normal: {
                show: true,
                position: 'top',
                formatter: '{c}'
            }
        },
    itemStyle: {
                normal: {
                 
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                        offset: 0,
                        color: 'rgba(102, 204,255, 1)'
                    }, {
                        offset: 1,
                        color: 'rgba(102, 204,255, .1)'                        
                    }]),
                    shadowColor: 'rgba(0, 0, 0, 0.1)',
                    shadowBlur: 10
                }
            }
};

browserChart.setOption(browserOption);
