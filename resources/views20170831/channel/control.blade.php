@extends('framework.main')

@section('content')
    <link rel="stylesheet" href="/css/temp.css">
    <style>

    </style>
    <link rel="stylesheet" href="/css/control.css">
    <link href="/dist/videojs/css/video.css" rel="stylesheet">
    <div class="container-fluid no-padding no-margin main-box">
        <div class="main-header">
            <ol class="breadcrumb" style="background:transparent;margin-bottom: 5px;">
                <li><a href="/">首页</a></li>
                <li class="active">直播间管理</li>
            </ol>
        </div>
        <div class="main-title">
            <select class="form-control channel-select"
                    onchange="javascript:location.href='/channel/'+this.value +  '/control'">

                @if($channels = Session::get('channels'))
                    @foreach($channels as $value)
                        <option @if($channel->id == $value->id ) selected
                                @endif value="{{$value->id}}">{{$value->name}}</option>
                    @endforeach
                @endif
            </select>

        </div>
        <div class="main-body">
            @include('channel.sub_menu',['channel_id'=>$channel->id])
            <div class="main-body-right">
                <div class="main-body-right-left">
                    <div class="main-body-right-left-header">
                        直播监控
                        <span class="left-header-control">
                            @if($channel->open)
                              <img style="cursor: pointer;" id="liveState" onclick="stopLive()" src="/image/switch_on.png">
                            @else
                               <img style="cursor: pointer;" id="liveState" onclick="startLive()" src="/image/switch_off.png">
                            @endif
                            <span id="close-live-text">开启直播</span>
                        </span>
                    </div>
                    <div class="main-body-right-left-body">
                        <div style="text-align: center;height:360px;" id="video-container">
                            <video autoplay id="AuditionVodie" class="video-js vjs-default-skin" controls preload="auto"
                                   width="100%" data-setup='{}'>
                                <source src="{{$video_src}}"></source>
                            </video>
                        </div>
                        <div class="body-control">
                            <button class="btn btn-default" onclick="changeSrc()">预览</button>
                            @if($channel->open)
                                <a id="liveControlButton" class="btn btn-default" onclick="stopLive(this);">停止直播</a>
                            @else
                                <a id="liveControlButton" class="btn btn-default" onclick="startLive(this)">开始直播</a>

                            @endif
                            @if($channel->record)
                                <a class="btn btn-default" disabled="disabled" >录制中</a>
                            @else
                                <a class="btn btn-default" href="/channel/{{$channel->id}}/record">开始录制</a>
                            @endif


                        </div>


                        <div class="body-address container-fluid">

                            <h3>推流地址</h3>
                            <div class=" row">
                                <input id="copy" type="text" class="push-address form-control" value="{{$live_url}}">
                                <button type="button" class="push-copy btn btn-default" onclick="copyUrl('copy')">复制
                                </button>
                            </div>
                            <p class="push-info">建议直播网络上行宽带是码率的2倍以上</p>
                            <p class="push-info1" style="color:red">如:码率1500K，则推荐上行网络宽带不少于3M</p>
                            <h3>观看地址</h3>
                            <div class=" row">
                                <input id="copy1" type="text" class="push-address form-control"
                                       value="{{Config::get('app.url') ."/live/" . $channel->id}}">
                                <button type="button" class="push-copy btn btn-default" onclick="copyUrl('copy1')">复制
                                </button>
                            </div>

                            <div class="container no-padding no-margin">
                                {!! QrCode::size(220)->generate(Config::get('app.url') ."/live/" . $channel->id) !!}
                                <p style="padding-left: 44px;">扫一扫，手机观看</p>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="main-body-right-right">
                    <div class="main-body-right-right-header">
                        直播互动
                    </div>
                    <div class="body-right-body">
                        <div class="body-right-body-header">
                            <span class="show-comments">
                                @if($channel->chatroom==1)
                                    <a href="/channel/{{$channel->id}}/chatroom"><img id="show-comment"
                                                                                      style="cursor: pointer;margin-right:10px;"
                                                                                      src="/image/switch_on.png" alt=""></a>
                                @else
                                    <a href="/channel/{{$channel->id}}/chatroom"><img id="show-comment"
                                                                                      style="cursor: pointer;margin-right:10px;"
                                                                                      src="/image/switch_off.png"
                                                                                      alt=""></a>
                                @endif

                                显示评论</span>

                            <span class="show-control">
                                        <button id="chat-config" style="background:#f5f6f6;" class="btn btn-default">聊天设置</button>
                                        <button data-toggle="modal" data-target="#clearAllComment"
                                                style="background:#f5f6f6;" class="btn btn-default"
                                        >清空</button>
                                        <a style="background:#f5f6f6;" class="btn btn-default"
                                           href="/api/channel/export_message/{{$channel->id}}">导出</a>
                                <div class="chat-config hide" id="config-container" style="background: white">
                                    <ul>
                                        <li>
                                            <input type="checkbox" id="blankCheckbox" value="option1" aria-label="..."
                                                   onclick="changeConfig('chatroom')"
                                                   @if($channel->chatroom == 1) checked @endif>允许观众聊天
                                        </li>
                                        <li>
                                           <input onclick="changeConfig('chatroom_any')" type="checkbox"
                                                   @if($channel->chatroom_any == 1) checked @endif
                                                  value="option1" aria-label="...">允许观众匿名聊天
                                        </li>
                                        <li><input onclick="changeConfig('chatroom_audit')" type="checkbox"
                                                   @if($channel->chatroom_audit == 1) checked @endif
                                                   value="option1" aria-label="...">发送内容需要审核</li>
                                        <li>
                                            <input onclick="changeConfig('chatroom_danmu')" type="checkbox" @if($channel->chatroom_danmu == 1) checked @endif
                                                   value="option1" aria-label="...">发送弹幕需要审核
                                        </li>
                                    </ul>
                                </div>
                            </span>
                        </div>

                        <div class="body-right-body-body">
                            <ul class="body-comment-box" style="height:300px;" id="chatRoom">
                                @if($channel->chatroom==1)
                                    <li class="coment-content" v-for="(message,$index) in messages" @mouseleave="hide_comment" @mouseover="show_comment($index)">

                                        <img v-bind:src="message.image" width="50px" height="50px" alt="">
                                        <span class="content-item-top-right">
                                                <h4 style="font-family:'微软雅黑';margin:10px 0 20px 0;">@{{message.name}}</h4>
                                                <p style="margin-bottom:0;display:inline-block;float:right;margin-top: 8px;"> <span>@{{message.created_at}}</span></p>
                                                <p>@{{message.message}}</p>
                                            </span>
                                        <span class="comment-control" v-show="$index == i" v-if="message.name!='管理员'">
                                            <button class="btn btn-default comment_btn_default"
                                                    @click="send(message.id)" style="height:28px;line-height:14px;"
                                                    v-show="chatroom_audit==1 && message.state!=1">通过</button>
                                            <button class="btn btn-default comment_btn_gray" disabled
                                                    style="height:28px;line-height:14px;"
                                                    v-show="chatroom_audit==1&&message.state==1">通过</button>
                                            <button class="btn btn-default comment_btn_default"
                                                    @click="send_danmu(message.id)"
                                                    style="height:28px;line-height:14px;"
                                                    v-show="chatroom_danmu==1 && message.danmu_state!=1">弹幕</button>
                                            <button class="btn btn-default comment_btn_gray" disabled
                                                    style="height:28px;line-height:14px;"
                                                    v-show="chatroom_danmu==1 &&message.danmu_state==1">弹幕</button>
                                            <button class="btn btn-default"
                                                    style="border:1px solid #2ba6ee;color:#2ba6ee;height:28px;line-height:14px;"
                                                    @click="remove(message.id)">删除</button>
                                            <button class="btn btn-default"
                                                    style="border:1px solid #2ba6ee;color:#2ba6ee;height:28px;line-height:14px;"
                                                    @click="blacklist(message.openid)">禁言</button>
                                            </span>
                                    </li>

                                @endif
                            </ul>
                        </div>
                        <div class="body-right-body-footer">
                            <textarea name="" id="adminText" rows="13" placeholder="说点什么..."></textarea>
                            <button class="btn btn-success" style="background:#15ae8f;color:#fff;margin-top: 20px;
    float: right;
    margin: 20px;
    width: 100px;" onclick="sendAdmin()">发送
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="clearAllComment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">清屏</h4>
                </div>
                <div class="modal-body">
                    确定要清空所有聊天吗？
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="clearComment()">确定
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="/dist/jquery/jquery.min.js"></script>
    <script src="/dist/jquery.cookie/jquery.cookie.js"></script>
    <script src="/dist/socket.io/socket.io.js"></script>
    <script src="/js/control/control.js"></script>
    <script src="/js/resize.js"></script>

    <script src="/dist/vue/vue.js"></script>
    <script src="/dist/videojs/video.js"></script>
    <script src="/dist/videojs/videojs-live.js"></script>
    <script type="text/javascript">
        function loadingToast(text){
            iziToast.info({
                message:text,
                ballon:true,
                timeout:60000,
                close:false,
                position:"center"
            })
        }

        function stopLive(){
            loadingToast('正在停止直播,请稍后');
            $.get('/channel/{{$channel->id}}/stop',function(res){
                if(res.code==0){
                    iziToast.destroy();
                    iziToast.warning({
                        message:"停止直播成功",
                        ballon:true,
                        timeout:3000,
                        position:'topRight'
                    });
                    $("#liveControlButton").html('开始直播').attr('onclick','startLive()');
                    $("#liveState").attr('src','/image/switch_off.png').attr('onclick','startLive()')
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
        function startLive(){
            loadingToast('正在开启直播,请稍后');
            $.get('/channel/{{$channel->id}}/start',function(res){
                if(res.code==0){
                    iziToast.destroy();
                    iziToast.warning({
                        message:"开始直播成功",
                        ballon:true,
                        timeout:3000,
                        position:'topRight'
                    });
                    $("#liveControlButton").html('关闭直播').attr('onclick','stopLive()');
                    $("#liveState").attr('src','/image/switch_on.png').attr('onclick','stopLive()')
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
        function clearComment() {
            $.post("/api/channel/clean_message", {channel: {{$channel->id}}}, function (res) {
                $("#chatRoom").empty()
            });
        }
        var chatRoom = new Vue({
            el: '#chatRoom',
            data: {
                messages: {!! $messages !!},
                chatroom_audit: {{$channel->chatroom_audit}},
                chatroom_danmu: {{$channel->chatroom_danmu}},
                btn_gray: {'': true},
                btn_default: {'comment_btn_default': true},
                i:-1
            },
            methods: {
                send(id) {
                    $.post("/api/channel/send", {id: id}, function (res) {
                        chatRoom.messages.find(function (value, index, arr) {
                            if (value.id == id) value.state = 1;
                        });
                           iziToast.warning({
                            message:"发送成功",
                            ballon:true,
                            timeout:3000,
                            position:'topRight'
                     });
                      
                    });
                },
                send_danmu(id) {
                    $.post("/api/channel/send_danmu", {id: id}, function (res) {
                        chatRoom.messages.find(function (value, index, arr) {
                            if (value.id == id) value.danmu_state = 1;
                        });
                       iziToast.warning({
                            message:"发送成功",
                            ballon:true,
                            timeout:3000,
                            position:'topRight'
                     });
                    });
                },
                remove(id) {
                    $.post("/api/channel/remove", {id: id}, function (res) {
                        if (res == "success") {

                            chatRoom.messages.find(function (value, index, arr) {
                                if (value.id == id) arr.splice(index, 1);

                            });
                            iziToast.warning({
                            message:"删除成功",
                            ballon:true,
                            timeout:3000,
                            position:'topRight'
                     });
                        }
                        else {
                            iziToast.warning({
                            message:"删除失败",
                            ballon:true,
                            timeout:3000,
                            position:'topRight'
                     });
                        }
                    });
                },
                blacklist(openid){
                    $.post("/api/channel/blacklist", {
                        "openid": openid,
                        "channel_id":{{$channel->id}}}, function (res) {
                        iziToast.warning({
                            message:"屏蔽成功",
                            ballon:true,
                            timeout:3000,
                            position:'topRight'
                     });
                    });
                },
                hide_comment(){
                    this.i = -1;

                },
                show_comment(index){
                    console.log(index);
                    this.i = index;
                }

            }
        });

        var audition = null;
        var videoContainerWidth = $("#video-container").width() - 10;
        videojs("AuditionVodie").ready(function () {   //初始化播放器
            audition = this;
            videojs("AuditionVodie").play();
            videojs.options.flash.swf = "/video/video-js.swf";
            @if($channel->screen==1)
                this.width(videoContainerWidth);
            this.height(360);
            @else
                this.width(videoContainerWidth);
            this.height(360);
            @endif
        });
        window.onresize = function () {
            document.location.reload();
        }

        function changeSrc() {
            audition.src('{{$video_src}}');
            audition.load('{{$video_src}}');
            audition.play();
        }

        function sendAdmin() {

            $.post("/api/channel/sendAdmin", {
                channel_id:{{$channel->id}},
                message: $('#adminText').val()
            }, function (res) {
                if (res == "success") {
                    chatRoom.messages.unshift({
                        'name': '管理员',
                        'message': $('#adminText').val(),
                        'created_at': getDateTime(),
                        'image': '/image/admin_avatar.jpg'
                    });

                   iziToast.warning({
                            message:"发送成功",
                            ballon:true,
                            timeout:3000,
                            position:'topRight'
                     });

                    $('#adminText').val('');
                }
                else {

                     iziToast.warning({
                            message:"发送失败",
                            ballon:true,
                            timeout:3000,
                            position:'topRight'
                     });

                }
            });
        }

        var channel_id = '{{$channel->id}}';
        var openid = channel_id + '_admin';
        $.cookie('channel', channel_id, {path: "/"});
        $.cookie('openid', openid, {path: "/"});

        var socket = io(':6001');

        socket.on('connect', function () {
            socket.emit('login', {channel: channel_id, openid: openid});
        });

        socket.on(channel_id + ':OnPushAdminMessage', function (res) {
            console.log(chatRoom.messages);
            chatRoom.messages.unshift(res);


        });

        function changeConfig(type) {
            if (type == 'chatroom_audit') {
                if (chatRoom.chatroom_audit == 1) chatRoom.chatroom_audit = 0;
                else chatRoom.chatroom_audit = 1;
            }
            if (type == 'chatroom_danmu') {
                if (chatRoom.chatroom_danmu == 1) chatRoom.chatroom_danmu = 0;
                else chatRoom.chatroom_danmu = 1;
            }
            url = '/api/channel/config/' + type;
            $.post(url, {channel:{{$channel->id}}});
        }

        function copyUrl(id) {
            var copyObj = document.getElementById(id);
            copyObj.select();
            document.execCommand("Copy"); // 执行浏览器复制命令
            $("#copy-success").show();
            setTimeout(function () {
                $("#copy-success").hide()
            }, 1000)
        }




    </script>
@endsection

