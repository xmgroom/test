

@extends('framework.main')

@section('content')
    <link rel="stylesheet" href="/css/temp.css">
    <link rel="stylesheet" href="/css/countdown.css">
    <link rel="stylesheet" href="/dist/datetimepicker/bootstrap-datetimepicker.min.css">

    <div class="container-fluid no-padding no-margin main-box">
        <div class="main-header">
            <ol class="breadcrumb" style="background:transparent;margin-bottom: 5px;">
                <li><a href="/">首页</a></li>
                <li class="active">倒计时设置</li>
            </ol>
        </div>
        <div class="main-title">
            <select class="form-control channel-select"
                    onchange="javascript:location.href='/channel/'+this.value +  '/control'">

                @if($channels = Session::get('channels'))
                    @foreach($channels as $value)
                        <option @if($channel->id == $value->id ) selected @endif value="{{$value->id}}">{{$value->name}}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="main-body">
            @include('channel.sub_menu',['channel_id'=>$channel->id])
            <div class="main-body-right">
                <div class="main-body-right-left">
                    <div class="main-body-right-left-body">
                        <div class="body-content">
                            <form id="form" class="form-horizontal" action="/channel/{{$channel->id}}/countdown" method="post" >
                                <input name="countdown" class="form-control" id="count_select" style="display: none" value="{{ $channel->countdown }}">
                                <div class="form-group">
                                    <label for="" style="text-align:left;width:120px;" class="col-sm-2  control-label">倒计时</label>
                                    <div class="col-sm-4">
                                        @if($channel->countdown)
                                            <img id="switch" style="cursor:pointer;" src="/image/switch2_on.png" alt="">
                                        @else
                                            <img id="switch" style="cursor:pointer;" src="/image/switch2_off.png" alt="">
                                        @endif

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label style="text-align:left;width:120px;" for="" class="col-sm-2 control-label">提示文字</label>
                                    <div class="col-sm-4">
                                        <input name="countdown_text" value="{{$channel->countdown_text}}"
                                               class="form-control" id="tip-text" placeholder="距离开始时间还有">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label style="text-align:left;width:120px;" for="" class="col-sm-2 control-label">开始时间</label>
                                    <div class="col-sm-4">
                                        <input readonly class="form-control" name="countdown_time"
                                               value="{{$channel->countdown_time}}"   id="countdown_time" >

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label style="text-align:left;width:120px;" for="" class="col-sm-2 control-label">剩余时间</label>
                                    <label  class="col-sm-4  control-label" id="endTime" style="text-align:left;"></label>
                                </div>


                        </div>
                                                                                <div class="line center-block"></div>

                        <div style="clear:both"></div>
                        @include('channel.qr_code',['url'=>Config::get('app.url') ."/live/" . $channel->id])
                        <button type="submit" class="btn center-block" style="background:#15ae8f;color:#fff;width:100px;margin-bottom: 40px;">提交</button>
                        </form>
                    </div>
                </div>
                <div class="main-body-right-right">
                    <div class="body-right-body">
                        <p>预览效果</p>
                        <div class="mobile" style="background:url(/image/mobile.png) center/100% no-repeat;position: relative;">
                            <img src="/image/bg_cd.jpg" width="100%" height="100%" alt="">
                            <div class="info-container" id="info-container">
                                <span id="info">{{$channel->countdown_text}}</span>
                                <span id="time" style="margin-left: 5px;"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/dist/jquery/jquery.min.js"></script>
    <script src="/dist/datetimepicker/bootstrap-datetimepicker.min.js"></script>
    <script src="/dist/datetimepicker/datetimepicker-CH-zn.js"></script>
    <script src="/js/control/countdown.js"></script>
    <script>
        $(function(){
            countdown('{{$channel->countdown_time ?: date('Y-m-d H:m:s')}}');
           
        });
        $('#countdown_time').change(function () {

            countdown( $('#countdown_time').val());
            console.log($("#endTime").html());
            $("#time").html($("#endTime").html());

        });
         console.log($("#countdown_time").val());
            if($('#countdown_time').val()===' ') {
                $('#countdown_time').val('{{date('Y-m-d H:m:s')}}');
            }else if(!$('#countdown_time').val()) {
                $('#countdown_time').val('{{date('Y-m-d H:m:s')}}');
            }
        function countdown(datetime) {

            var date1 =new Date();  //开始时间
            var date2 = new Date(Date.parse(datetime.replace(/-/g, "/")));    //结束时间
            var date3 = date2.getTime() - date1.getTime(); //时间差的毫秒数
            var leave1 = date3 % (24 * 3600 * 1000);   //计算天数后剩余的毫秒数
            var hours = Math.floor(leave1 / (3600 * 1000));
            var days = Math.floor(date3 / (24 * 3600 * 1000));

            //计算相差分钟数
            var leave2 = leave1 % (3600 * 1000);     //计算小时数后剩余的毫秒数
            var minutes = Math.floor(leave2 / (60 * 1000));
            //计算相差秒数
            var leave3 = leave2 % (60 * 1000);   //计算分钟数后剩余的毫秒数
            var seconds = Math.round(leave3 / 1000);
            if({{$channel->countdown ?:0 }}){
                $('#endTime').html( days + " 天 " + hours + " 小时 " + minutes + " 分钟 " + seconds + " 秒");
                $("#time").html(days + " 天 " + hours + " 小时 " + minutes + " 分钟 " + seconds + " 秒 ");
            }else {
                $('#endTime').html( "未设置倒计时");
                $("#time").html("未设置倒计时")
            }

        }

    </script>



@endsection


