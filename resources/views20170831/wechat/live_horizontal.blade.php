<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{$channel->name}}</title>
    <link rel="stylesheet" href="/dist/aui/aui.css"/>
    <link rel="stylesheet" href="/css/font-awesome.min.css">

    <link rel="stylesheet" href="http://static.hongshiyun.net/player/h5/v0.0.5/player.normal.rem.css">
    <link rel="stylesheet" href="/css/wechat/live_hor.css">
</head>

<body>

<div class="container" style="max-width:100%;overflow-X:hidden;">
    {{--  <h3 class="wechat-video-title" style="background:url({{$channel->live_image}}) center/cover no-repeat" id="header">
        <img src="{{$channel->logo}}" alt="" style="display: inline-block;width: 30px">
    </h3>  --}}

    <div class="live-container" style="position: relative;">
        <div id="PLAYERID"></div>

        @if($channel->live_logo_open==1)
            @if($channel->live_logo_position==1)
                <img src="{{$channel->live_logo}}" width="50px"
                     style="position:absolute;left:10px;top:10px;z-index: 300;">
            @elseif($channel->live_logo_position==3)
                <img src="{{$channel->live_logo}}" width="50px" style="position:absolute;right:10px;top:10px;z-index: 300;">
            @elseif($channel->live_logo_position==2)
                <img src="{{$channel->live_logo}}" width="50px" style="position:absolute;left:10px;bottom:10px;z-index: 300;">
            @elseif($channel->live_logo_position==4)
                <img src="{{$channel->live_logo}}" width="50px"
                     style="position:absolute;right:10px;bottom:10px;z-index: 300;">
            @endif
        @endif
    </div>
    <div class="footer-pgc">

        <ul id="infoList" class="aui-list aui-list-in ul-list" style="overflow: scroll">
            <li class="aui-list-item" style="background:#1d1d1d;color:#ccc;overflow: hidden">
                <div class="aui-list-item-inner" style="width:55%;left:50%">
                    <div class="pl5"><img src="/image/user_count.png" width="16px" height="16px"
                                          style="margin-right:5px;float:left;" alt=""><span><label
                                    id="count_user">0</label></span>
                    </div>
                    <div class="pl5"><img src="/image/comment.png" width="16px" height="16px"
                                          style="margin-right:5px;float:left;"
                                          alt=""><span><label id="count_msg">0</label></span></div>
                    <div class="pl5"><img id="danmu-control" data-danmuState=true src="/image/danmu.png" width="16px"
                                          height="16px"
                                          style="margin-right:4px;margin-top: -10px;"
                                          alt="" onclick="openDanmu(this)"></div>
                    <div class="pl5">@if($channel->chatroom_any == 1)<img src="/image/niming-off.png"
                                                                          onclick="openAnonymous(this)" width="16px"
                                                                          height="16px"
                                                                          style="margin-right: 20px;
    margin-bottom: 9px;"
                                                                          alt="">@endif

                    </div>
                </div>
            </li>
        </ul>
        <ul id="infoListB" style="position:absolute;width: 100%;padding-bottom:40px;" class="aui-list aui-media-list" >
            <li class="aui-list-item" v-for="message in messages">
                <div class="aui-media-list-item-inner">
                    <div class="aui-list-item-label-icon"><img
                                style="border-radius:50px;width:50px !important;height:40px !important;"
                                v-bind:src="message.image" alt=""></div>
                    <div class="aui-list-item-inner">
                        <div class="aui-list-item-text">
                            <div class="aui-list-item-title" v-text="message.name"></div>
                            <div class="aui-list-item-right" v-text="message.time"></div>
                        </div>
                        <div class="aui-list-item-text" v-text="message.message"></div>
                    </div>
                </div>
            </li>
        </ul>
        <div class="emoji" id="emoji-box">
        </div>
        <footer class="aui-bar aui-bar-tab aui-row-padded " id="footer">
            <div onclick="showEmoji()" class="aui-col-xs-2">
                <i class="fa fa-smile-o fa-2x"></i>
            </div>

            <div class="aui-col-xs-7">
                @if($channel->chatroom)
                    <input class="footer-input" id="comment-text" onfocus="hideEmoji()" type="text">

                @else

                    <input class="footer-input" id="comment-text" onfocus="hideEmoji()" type="text" placeholder="当前禁止发言"
                           disabled>

                @endif


            </div>
            <div class="aui-col-xs-3">

                @if($channel->chatroom)
                    <button type="button" onclick="sendMessage()"
                            class="send-btn">发送
                    </button>
                @else
                    <button type="button" style="background:grey;"
                            class="send-btn">发送
                    </button>
                @endif

            </div>
        </footer>
    </div>
</div>

<script src="/dist/jquery/jquery.js"></script>
<script src="/dist/jquery.cookie/jquery.cookie.js"></script>
<script src="/js/jquery.danmu.min.js"></script>
<script src="/dist/socket.io/socket.io.js"></script>

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="http://static.hongshiyun.net/player/h5/js/md5.js" charset="utf-8"></script>
<script type="text/javascript" src="http://static.hongshiyun.net/player/h5/v0.0.1/public.js"
        charset="utf-8"></script>
<script type="text/javascript" src="http://static.hongshiyun.net/player/h5/js/touch-0.2.14.min.js"
        charset="utf-8"></script>
<script type="text/javascript" src="http://static.hongshiyun.net/player/h5/v0.0.7/H_EV_XK_DEV.js"
        charset="utf-8"></script>
<script src="/dist/vue/vue.js" ></script>

<script>
    var channel_id = '{{$channel->id}}';
    var openid = '{{$user["id"]}}';
    var msg_count = 0;
    var open_danmu = true;
    var player = null;
    var anonymous = false;

    var infoList = new Vue({
        el: '#infoListB',
        data: {
            messages: []
        },
        methods: {}
    });
    function openAnonymous(obj) {

        if (!anonymous) {
            $(obj).attr('src', '/image/niming-on.png');
            anonymous = true;
        } else {
            $(obj).attr('src', '/image/niming-off.png');
            anonymous = false;
        }

    }
    function openDanmu(obj) {

        if (open_danmu) {
            $(obj).attr('src', '/image/danmu-off.png');
            open_danmu = false;
        } else {
            $(obj).attr('src', '/image/danmu.png');
            open_danmu = true;
        }

    }

    $(function () {
        player = new HONGSHIYUN().embed(
            "PLAYERID",
            {
                isRotate: false,
                src: '{{$video_src}}',
                poster: '{{$channel->live_image}}',
                onLive: true,
                autoPlay: true,
                eventCallback:function (e) {

                    switch (e){

                        case 'fullScreen':
                            $("#danmu").danmu({
                                height:$(window).height(), //区域的高度
                            });
                            break;
                        case 'outFullScreen':

                            $("#danmu").danmu({
                                height:$('.load-layer').find('img').height(), //区域的高度
                            });

                            break;


                    }

                    console.log(e);
                }
            });

        $("#danmu").danmu({
            left: 0,    //区域的起始位置x坐标
            top: 0,  //区域的起始位置y坐标
            height:$('.load-layer').find('img').height(), //区域的高度
            width: $('.load-layer').find('img').width(), //区域的宽度
            zindex: 300, //div的css样式zindex
            speed: 20000, //弹幕速度，飞过区域的毫秒数
            danmuss: {}, //danmuss对象，运行时的弹幕内容
            default_font_color: "#FFFFFF", //弹幕默认字体颜色
            font_size_small: 16, //小号弹幕的字体大小,注意此属性值只能是整数
            font_size_big: 24, //大号弹幕的字体大小
            opacity: "0.9", //弹幕默认透明度
            top_botton_danmu_time: 6000 //顶端底端弹幕持续时间
        });

        $('#danmu').danmu('danmu_start');
        $('#infoListB').css('overflow', 'scroll');
        iWindowWidth = window.innerWidth;
        //获取可视区高度
        iWindowHeight = window.innerHeight;
        var iScrollH = 3 * iWindowWidth / 4;
        $('#infoListB').css("height", iWindowHeight - iScrollH + $('#footer').height() + "px");
        $.cookie('channel', channel_id, {path: "/"});
        $.cookie('openid', openid, {path: "/"});

        var socket = io(':6001');
        socket.on('connect', function () {
            socket.emit('login', {channel: channel_id, openid: openid});
        });
        socket.on(channel_id + ':OnPushMessage', function (res) {
            if (res.danmu) {
                if (open_danmu) insert_danmu(res.message);
            } else {
                if ({{$channel->chatroom}}) {

                    infoList.messages.unshift(res);
                    msg_count += 1;
                    $('#count_msg').html(msg_count);

                }
            }
        });

        socket.on(channel_id + ':removeMessage', function (res) {
            infoList.messages.find(function (value, index, arr) {
                if (value.id == res.id) arr.splice(index, 1);
            });

        });


        socket.on(channel_id + ':UserChange', function (res) {
            $('#count_user').html(res);
        });

        var timer = null;
        $("#comment-text").on('focus',function(){
            clearInterval(timer);
            var index = 0;
            timer = setInterval(function(){
                if(index>5){
                    $('body').scrollTop(1000000);
                    clearInterval(timer);
                }
                document.getElementById('comment-text').scrollIntoView();
                index++;
            },50)
        })

    })
    function insert_danmu(text) {
        var nowtime = $('#danmu').data("nowtime");
        $('#danmu').danmu("add_danmu", {
            "text": text + "",
            "color": "white",
            "size": "1",
            "position": "0",
            "time": nowtime + 3
        });
    }
    function sendMessage() {
        hideEmoji();
        var content = $("#comment-text").val();
        var name = '';
        var image = '';
        if (anonymous) {
            name = "匿名";
            image = '/image/niming.png'
        }
        else {
            name = "{{$user["name"]}}";
            image="{{$user["avatar"]}}";
        }

        $.post('/api/channel/message', {
            channel_id: channel_id,
            message: content,
            openid: "{{$user["id"]}}",
            name: name,
            image: image
        }, function (res) {
            $("#comment-text").val('');
        });
    }
    var emoji = '😄😃😀😊😉😍😘😚😗😙😜😝😛😳😁😔😌😒😞😣😢😂😭😪😥😰😅😓😩😫😨😱😠😡😤😖😆😋😷😎😴😵😲😟😧😈👿😮😬😐😕😯😶😇😏😑😺😸😻😽😼🙀😿😹😾';

    for (var i = 0; i < emoji.length; i = i + 2) {
        $('#emoji-box').append("<span onclick='insert(this)'>" + emoji[i] + emoji[i + 1] + "</span>");
    }
    var emojiShow = false;
    function showEmoji() {
        if (!emojiShow) {
            $("#emoji-box").css('left', 0);
            emojiShow = true;
        } else {
            $("#emoji-box").css('left', -1200);
            emojiShow = false;
        }
    }
    function insert(_this) {
        $('#comment-text').val($('#comment-text').val() + $(_this).html());
    }
    function hideEmoji() {
        $("#emoji-box").css('left', -1200 + 'px');
    }

</script>

</body>
</html>
