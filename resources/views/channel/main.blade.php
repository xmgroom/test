@extends('framework.main')

@section('content')
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/dist/daterangepicker/daterangepicker.css">

    <div class="container-fluid main-box">

        <div class="main-top row">
            <div class="col-xs-3 top-item">
                <img src="/image/combo.png" alt="">
                <span class="main-box-right right">
                            <h2 style="color:#e74b3c">{{$combo}}</h2>
                            <p style="text-align:right">套餐类型</p>
                        </span>
            </div>
            <div class="col-xs-3 top-item">
                <img src="/image/channel.png" alt="">
                <span class="main-box-right right">
                            <h2 style="color:#3fbd8c">{{$channels_count}}</h2>
                            <p style="text-align:right">建立频道数(个)</p>
                        </span>
            </div>
            <div class="col-xs-3 top-item">
                <img src="/image/video.png" alt="">
                <span class="main-box-right right">
                            <h2 style="color:#e166b8">{{$videos_count}}</h2>
                            <p style="text-align:right">视频个数(个)</p>
                        </span>
            </div>
            <div class="col-xs-3 top-item">
                <img src="/image/space.png" alt="">
                <span class="main-box-right right">
                            <h2 id="kongjian" style="color:#6259c6"><span>{{$usedSpace}}</span>/{{$totalSpace}}G</h2>
                        <p style="text-align: right;">已用空间(G)</p>
                        </span>
            </div>
        </div>
        <div class="main-body row">
            <div class="main-body-head">
                        <span>
                            <img class="hand" data-toggle="modal" data-target="#myModal" src="/image/add_n.png" alt="">

                        </span>
                <span class="main-body-head-right form-inline">
                      <form action="/" method="post" id="dateForm">

                                <div class="form-group">
                                     <label>排序:</label>
                                       <select class="form-control sort-select" onchange="orderRedirect(this.value)">
                                            <option value="1">默认排序</option>
                                            <option value="2" @if(isset($type)) @if($type==2) selected @endif  @endif)>按状态</option>
                                        </select>
                                </div>
                                <div class="form-group">
                                    @if(isset($type))
                                        @if($type==3)
                                            <a href="/type/4"><img style=" cursor: pointer;" src="/image/sort_n.png"
                                                                   alt=""></a>
                                        @else
                                            <a href="/type/3"><img style=" cursor: pointer;" src="/image/sort_n.png"
                                                                   alt=""></a>
                                        @endif
                                    @endif

                                </div>
                                <div class="form-group" style="margin-left:20px;">
                                    <label>最后直播时间:</label>
                                    <input class="form-control" style="height:28px;border-radius: 0;" type="text"
                                           name="date" id="timer" value="{{ Session::pull('home.date')}}" />
                                    <span onclick="location.href='/';"
                                          style="height:28px;line-height:14px;font-weight: 600;border-radius: 0;"
                                          class="btn btn-default"><i class="fa fa-refresh"></i></span>
                                </div>
                                <div class="form-group" style="margin-left:30px;">
                                    <input class="form-control" style="height:28px;border-radius: 0;" type="text"
                                           name="search" value="{{ Session::pull('home.search')}}">
                                    <span onclick="javascript:$('#dateForm').submit();"
                                          style="height:28px;line-height:14px;font-weight: 600;border-radius: 0;"
                                          class="btn btn-default">搜索</span>
                                </div>
                                                    </form>
                        </span>

            </div>
            <div class="main-body-content">

                @foreach($channels as $channel)

                    <div class="content-item ">
                        <div class="content-item-top">
                            <img class="channel-logo" src="{{$channel->logo}}" width="20%" alt="">
                            <span class="content-item-top-right">
                                    <h4 style="overflow:hidden;font-size:16px;font-family:'微软雅黑';font-weight:600;margin:10px 0 16px 0;    padding: 3px;">{{$channel
                                ->name}}</h4>
                                   <h4 style="overflow:hidden;font-size:16px;font-family:'微软雅黑';font-weight:600;margin:10px 0 16px 0;    padding: 3px;">管理员：{{$user}}</h4>
                                    <p style="margin-bottom:0;color:#c0c0c1;">最近直播时间: <span
                                                style="color:#c0c0c1;">{{$channel->updated_at}}</span></p>
                                    <p style="color:#c0c0c1">观看人数: <span
                                                style="font-size:20px;font-family:'微软雅黑';color:#97e1dd"
                                                id="live_peo_count_{{$channel->id}}">0</span></p>
                                </span>
                        </div>
                        <div class="content-item-bottom">
                            <ul id="bottom-btn">
                                <li data-toggle="tooltip" data-placement="top" title="编辑"><a
                                            href="/channel/{{$channel->id}}/control"><img
                                                src="/image/edit_n.png"
                                                alt=""></a></li>
                                <li data-toggle="tooltip" data-placement="top" title="数据"><a
                                            href="/channel/{{$channel->id}}/count"><img
                                                src="/image/statistics_n.png" alt=""></a></li>
                                <li data-toggle="tooltip" data-placement="top" title="观众"><a
                                            href="/channel/{{$channel->id}}/control"><img
                                                src="/image/user_n.png"
                                                alt=""></a></li>
                                <li data-toggle="tooltip" onclick="showShareModal({{$channel->id}})" data-placement="top" title="分享"><img src="/image/share_n.png"
                                                                                                                                          alt=""></li>
                                <div style="clear:both"></div>
                            </ul>
                        </div>
                        <div class="control hide">

                            @if($channel->open)
                                <img id="liveControl" src="/image/switch_on.png" onclick="stopLive({{$channel->id}})"  >
                            @else
                                <img id="liveControl" src="/image/switch_off.png" onclick="startLive({{$channel->id}})" >
                            @endif

                            <img src="/image/trash.png" data-toggle="modal" data-target="#myModal3"
                                 onclick="channel_id={{$channel->id}}">
                        </div>
                    </div>

                @endforeach
                <a href="http://192.168.1.134/li/1">第1页</a>
                <a href="http://192.168.1.134/li/2">第2页</a>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header" style="background:#ebf1f4">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" style="color:#282828;font-size: 20px;" id="myModalLabel">创建直播频道</h4>
                </div>
                <form action="/channel/create" class="form-horizontal" method="post">

                    <div class="modal-body" style="padding-left:0;">

                        <div class="form-group">
                            <label for="inputEmail3" style="text-align: center;"
                                   class="col-sm-3 control-label">频道名称：</label>
                            <div class="col-sm-8" style="padding:0;">
                                <input type="text" class="form-control" id="inputEmail3" name="name" placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" style="text-align: center;" class="col-sm-3 control-label">横竖屏：</label>
                            <div class="col-sm-8" style="margin:0 0 15px 0">
                                <label class="radio-inline">
                                    <input checked id="radio-h" type="radio" name="screen" value="1" >
                                    横屏
                                </label>
                                <label class="radio-inline">
                                    <input id="radio-v" type="radio" name="screen" value="2">
                                    竖屏
                                </label>
                            </div>
                            <div style="margin-left:28%;display:inline-block;position:relative">
                            <div style="position:absolute;width:100%;height:100%;top:0;left:0;background:rgba(255,255,255,.5)"  id="pre-h-mask" class="hide"></div>
                                    <img src="/image/pre_h.png" style="" alt="">
                            </div>
                           <div style="display:inline-block;position:relative">
                            <div style="position:absolute;width:100%;height:100%;top:0;left:0;background:rgba(255,255,255,.5)" id="pre-v-mask" ></div>

                                <img src="/image/pre_v.png" style="" alt="">

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" style="text-align: center;"
                                   class="col-sm-3 control-label">法律条文：</label>
                            <div class="col-sm-8" style="border: 1px solid #ccc;">
                                <p style="padding:10px;text-indent:2em;">
                                    严禁上传包括反动、暴力、色情、违法及侵权内容的文件。平台有义务配合有关部门将上传违规文件的用户信息保存，并保留配合调查及冻结账号的权利。</p>
                                <input style="margin-left:40%" type="checkbox" value="" id="read">已阅读
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button id="add" type="button" class="btn" disabled>确定</button>
                        <button type="submit" id="add1" class="btn btn-info hide">确定</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background:#ebf1f4">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" style="color:#282828;font-size: 20px;" id="myModalLabel">删除直播频道</h4>
                </div>
                <div class="modal-body" style="padding-left:20px;">
                    <p>是否删除直播频道</p>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button id="remove" type="button" class="btn btn-info">确定</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade bs-example-modal-sm" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="border-radius: 0;">
                <div class="modal-header" style="background:#ebf1f4;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">分享</h4>
                </div>
                <div class="modal-body" id="modalQrContent">
                   <div id="qrcode" style="text-align: center;width: 160px;height:160px;margin:0 auto;">
                    </div>
                    <div id="shareaddress" style="display:flex;flex-wrap:nowrap;align-items:center;margin-top:20px;padding:0 40px;">

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="/dist/daterangepicker/daterangepicker.js"></script>
    <script src="/js/home.js"></script>
    <script src="/dist/jquery.cookie/jquery.cookie.js"></script>
    <script src="/dist/socket.io/socket.io.js"></script>
    <script src="/dist/qrcode/qrcode.js"></script>
    <script src="/dist/clipboard/clipboard.min.js"></script>
    <script type="text/javascript">


    $("#radio-h").on('click',function(){
       if($("#radio-h").prop('checked')){
                $("#pre-h-mask").addClass('hide');
                $("#pre-v-mask").removeClass('hide')
        }
    })
       $("#radio-v").on('click',function(){
            if($("#radio-v").prop('checked')){
        $("#pre-h-mask").removeClass('hide');
        $("#pre-v-mask").addClass('hide')
            }
    })




        function loadingToast(text){
            iziToast.info({
                message:text,
                ballon:true,
                timeout:60000,
                close:false,
                position:"center"
            })
        }
        function startLive(id){
            loadingToast('正在开启直播,请稍后');
            $.get('/channel/' + id + '/start',function(res){
                if(res.code==0){
                    iziToast.destroy();
                    iziToast.warning({
                        message:"开始直播成功",
                        ballon:true,
                        timeout:3000,
                        position:'topRight'
                    });
                    $("#liveControl").attr('src','/image/switch_on.png').attr('onclick','stopLive('+ id +')');
                }else{
                    iziToast.destroy();
                    iziToast.warning({
                        message:res.message,
                        ballon:true,
                        timeout:3000,
                        position:'topRight'
                    });

                }
            });
        }
        function stopLive(id){
            loadingToast('正在停止直播,请稍后');
            $.get('/channel/' + id + '/stop',function(res){
                iziToast.destroy();
                if(res.code==0){
                    iziToast.warning({
                        message:"停止直播成功",
                        ballon:true,
                        timeout:3000,
                        position:'topRight'
                    });
                    $("#liveControl").attr('src','/image/switch_off.png').attr('onclick','startLive('+ id +')')
                }else{
                    iziToast.destroy();
                    iziToast.warning({
                        message:res.message,
                        ballon:true,
                        timeout:3000,
                        position:'topRight'
                    });

                }
            });
        }
        //qrcode
         var qrcode = new QRCode('qrcode',{
             width:160,
             height:160,
             colorDark:"#000000",
             colorLight : '#ffffff',
             correctLevel : QRCode.CorrectLevel.H
         })
        //qrcode end


        $.cookie('channel', 0, {path: "/"});
        $.cookie('openid', '0_admin', {path: "/"});

        function orderRedirect(type) {
            location.href = "/type/" + type;
        }

        function showShareModal(id){
             $("#shareaddress").empty();
            $("#shareModal").modal();
            $("#modalQrContent").html();
            var address = "{{Config::get('app.url') . '/live/' }}"+id;
            var foo =  '<span>直播地址:</span><input  type="text" id="address" readonly value=" '+address +'"  style="flex-grow:1;flex-basis:200px;margin:0 40px;color:#ccc;"> <span data-clipboard-action="copy" data-clipboard-target="#address" style="    height: 26px;    line-height: 13px;"  class="btn btn-default copyaddress">复制</span>    ';

            $("#shareaddress").append(foo);
            qrcode.makeCode(address);

        }

        var socket = io(':6001');
        socket.on('connect', function () {

            socket.emit('login', {channel: 0, openid: '0_admin'});
            socket.emit('user.count');

        });

        socket.on('user.count', function (res) {

            for (var i in res) {

                if (res[i] != null) $('#live_peo_count_' + i).html(res[i]);

            }

        });


        $('#timer').daterangepicker({
            // startDate: moment().startOf('day'),
            //endDate: moment(),
            //minDate: '01/01/2012',    //最小时间
            maxDate: moment(), //最大时间
            dateLimit: {
                days: 30
            }, //起止时间的最大间隔
            showDropdowns: true,
            showWeekNumbers: false, //是否显示第几周
            timePicker: false, //是否显示小时和分钟
            ranges: {
                //'最近1小时': [moment().subtract('hours',1), moment()],
                '今日': [moment().startOf('day'), moment()],
                '昨日': [moment().subtract('days', 1).startOf('day'), moment().subtract('days', 1).endOf('day')],
                '最近7日': [moment().subtract('days', 6), moment()],
                '最近30日': [moment().subtract('days', 29), moment()]
            },
            opens: 'right', //日期选择框的弹出位置
            buttonClasses: ['btn btn-default'],
            applyClass: 'btn-small btn-primary blue',
            cancelClass: 'btn-small',
            format: 'YYYY-MM-DD ', //控件中from和to 显示的日期格式
            separator: ' to ',
            locale: {
                applyLabel: '确定',
                cancelLabel: '取消',
                fromLabel: '起始时间',
                toLabel: '结束时间',
                customRangeLabel: '自定义',
                daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
                monthNames: ['一月', '二月', '三月', '四月', '五月', '六月',
                    '七月', '八月', '九月', '十月', '十一月', '十二月'
                ],
                firstDay: 1
            },

        }, function (start, end, label) { //格式化日期显示框

            $('#reportrange span').html(start.format('YYYY-MM-DD HH:mm:ss') + ' - ' + end.format('YYYY-MM-DD HH:mm:ss'));
        });


        $('#remove').click(function () {
            location.href = '/channel/' + id + '/delete';
        });
        $("#read").click(function () {
            if ($('#read').prop('checked')) {
                $('#add').addClass('hide');
                $('#add1').removeClass('hide');
            } else {
                $('#add').removeClass('hide');
                $('#add1').addClass('hide');
            }
        })
        $("#bottom-btn li").each(function(){
            $(this).bind('mouseover',function(){
                var icon = $(this).find("img");
                switch (icon.attr('src')){
                    case "/image/share_n.png":
                        icon.attr('src','/image/share_n_w.png');
                        break;
                    case "/image/user_n.png":
                        icon.attr('src','/image/user_n_w.png');
                        break;
                    case "/image/edit_n.png":
                        icon.attr('src','/image/edit_n_w.png');
                        break;
                    default:
                        icon.attr('src','/image/statistics_n_w.png');
                        break;
                }
            });
            $(this).bind('mouseleave',function(){
                var icon = $(this).find("img");
                switch (icon.attr('src')){
                    case "/image/share_n_w.png":
                        icon.attr('src','/image/share_n.png');
                        break;
                    case "/image/user_n_w.png":
                        icon.attr('src','/image/user_n.png');
                        break;
                    case "/image/edit_n_w.png":
                        icon.attr('src','/image/edit_n.png');
                        break;
                    default:
                        icon.attr('src','/image/statistics_n.png');
                        break;
                }
            });
        });
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
            var clipboard3 = new Clipboard('.copyaddress');
            clipboard3.on('success', function (e) {
                console.log(e);
            });
            clipboard3.on('error', function (e) {
                console.log(e);
                alert("复制失败！请手动复制")
            });
        })
    </script>
@endsection

