@extends('framework.main')

@section('content')

    <link rel="stylesheet" href="/css/temp.css">
    <link rel="stylesheet" href="/css/count.css">
    <div class="container-fluid main-box">
        <div class="main-title">
            <select class="form-control channel-select"
                    onchange="javascript:location.href='/channel/'+this.value +  '/count'">

                @if($channels = Session::get('channels'))
                    @foreach($channels as $value)
                        <option @if($id == $value->id ) selected @endif value="{{$value->id}}">{{$value->name}}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="main-body">
            <div class="main-body-title">
                直播概况
            </div>
            <div class="main-channel row ">
                <div class="col-md-2">
                    <h2 style="color:red">{{$uv}}</h2>
                    <p>观看人数(UV)</p>
                </div>
                <div class="col-md-2">
                    <h2 style="color:#FF8000">20</h2>
                    <p>观看总时长(分钟)</p>
                </div>
                <div class="col-md-2">
                    <h2 style="color:#3fbd8c">20</h2>
                    <p>直播时长(分钟)</p>
                </div>
                <div class="col-md-2">
                    <h2 style="color:#e166b8">{{$pv}}</h2>
                    <p>观看次数(PV)</p>
                </div>
                <div class="col-md-2">
                    <h2 style="color:#6259c6">{{$ip}}</h2>
                    <p>IP 数(个)</p>
                </div>
            </div>
            <div class="container-fluid no-padding">
                <div class="chart-container">
                    <div class="chart-title">观看人数</div>
                    <div class="chart-main" id="live">

                    </div>
                </div>
                <div class="chart-container fr">
                    <div class="chart-title">地域统计</div>
                    <div class="chart-main" id="map">

                    </div>
                    {{--<div class="timeline row">--}}
                        {{--<div onclick="chooseTime(1)" class="col-sm-3 active">今天</div>--}}
                        {{--<div onclick="chooseTime(2)" class="col-sm-3">昨天</div>--}}
                        {{--<div onclick="chooseTime(3)" class="col-sm-3">近7天</div>--}}
                        {{--<div onclick="chooseTime(4)" class="col-sm-3">近30天</div>--}}
                    {{--</div>--}}
                </div>
                <div class="chart-container">
                    <div class="chart-title">访问统计</div>
                    <div class="chart-main" id="access">

                    </div>
                </div>
                <div class="chart-container fr">
                    <div class="chart-title">终端分析</div>
                    <div class="chart-main" >
                        <div class="chart-main-left" id="mobile">

                        </div>
                        <div class="chart-main-left" id="browser">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <script src="/dist/jquery/jquery.min.js"></script>
    <script src="/dist/bootstrap/js/bootstrap.min.js"></script>
    <script src="/dist/echarts/echarts.js"></script>
    <script src="/dist/echarts/china.js"></script>


    <script>
        $('body').css('height', document.documentElement.offsetHeight + 'px');
        $('.left-menu').css('height', document.body.offsetHeight + 'px');
        $('.box-container').css('height', document.body.offsetHeight + 'px');

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
                data: [0,{{$_00_03}}, {{$_03_06}}, {{$_06_09}}, {{$_09_12}}, {{$_12_15}}, {{$_15_18}}, {{$_18_21}}, {{$_21_24}}],
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
                data: $.parseJSON('{!! $province !!}')
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
                    data: [{value:{{$direct}}, name:'直接访问'},
                        {value:{{$indirect}}, name:'链接访问'},
                        {value:{{$other_source}}, name:'其他'}]
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
                    data:[{{$android}}, {{$ios}}, {{$other_platform}}]
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
                    data:[{{$safari}}, {{$chrome}}, {{$other_browser}}]
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

    </script>

@endsection

