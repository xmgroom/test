<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{$channel->name}}</title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0 minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="/dist/aui/aui.css"/>
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/wechat/live_ver.css">

</head>

<body>

<div id="videoBox" style="width: 100%">
    <button id="playBtn" style="height: 100%;"></button>
    <img id="poster" src="{{$channel->live_image}}" alt="">
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
    <video id="videoLive" preload="auto"  x5-video-player-type="h5" x5-video-player-fullscreen="false"
           webkit-playsinline="true" x-webkit-airplay="true" playsinline="true">
        <source src="{{$video_src}}" type="application/x-mpegURL">
    </video>
</div>
<footer class="aui-bar aui-bar-tab aui-row-padded " style="z-index:999;" id="footer">
    <div onclick="showEmoji()" class="aui-col-xs-2">
        <i class="fa fa-smile-o fa-2x"></i>
    </div>
    @if($channel->chatroom_any == 1)
        <div class="aui-col-xs-2" style="text-align: center;">
            <img src="/image/niming-off.png" style="width: 1.8em;    height: 1.8em;    vertical-align: middle;    margin-top: 2px;display: inline-block;" alt="" onclick="openAnonymous(this)">
        </div>
    @endif
    <div class="aui-col-xs-6">
        <input class="footer-input" id="comment-text" onfocus="hideEmoji()" type="text">
    </div>
    <div class="aui-col-xs-2">
        @if($channel->chatroom)
            <button type="button" onclick="sendMessage()"
                    class="send-btn">发送
            </button>
        @else
            <button type="button" style="background:grey;"
                    class="send-btn">发送
            </button>
        @endif        </div>
</footer>
<div class="mask">
    <img src="{{$channel->logo}}" width="50px" height="50px" alt="">
    <span class="top-name">&nbsp;&nbsp;{{$channel->name}}<span>
            <i class="fa fa-user-o"></i>&nbsp;
            <label id="count_user"></label>
        </span>
    </span>
</div>
<div class="comment" id="comment_box">
    <ul id="comment" v-for="message in messages">
        <li class='fadeIn'><span class='comment-name' v-text="message.name"></span><span
                    class='comment-text' v-text="message.message"></span>
        </li>
    </ul>
</div>
<div class="emoji" id="emoji-box">

</div>
<script src="/dist/jquery/jquery.js"></script>
<script src="/dist/jquery.cookie/jquery.cookie.js"></script>
<script src="/dist/socket.io/socket.io.js"></script>
<script src="/js/jquery.danmu.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
<script src="/dist/vue/vue.js" ></script>

<script type="text/javascript" charset="utf-8">

    var channel_id = '{{$channel->id}}';
    var openid = '{{$user["id"]}}';
    var msg_count = 0;
    var anonymous = false;

    $.cookie('channel', channel_id, {path: "/"});
    $.cookie('openid', openid, {path: "/"});

    $(document).ready(function () {
        var top = (window.innerHeight - 45) /3;
        $('#playBtn').css('top',top);
        $('#poster').height(window.innerHeight - 45);
    });

    $('#playBtn').click(function () {
        $('#videoLive').height(window.innerHeight - 45);
        document.getElementById('videoLive').play();
        $('#playBtn').hide();
        $('#poster').hide();

    });


    var comment = new Vue({
        el: '#comment_box',
        data: {
            messages: []
        },
        methods: {}
    });

    var socket = io(':6001');

    socket.on('connect', function () {

        socket.emit('login', {channel: channel_id, openid: openid});

    });

    socket.on(channel_id + ':OnPushMessage', function (res) {

        comment.messages.unshift(res);

        $("#comment_box").scrollTop(0);

        msg_count += 1;

        $('#count_msg').html(msg_count);

    });

    socket.on(channel_id + ':UserChange', function (res) {

        $('#count_user').html(res);

    });

    socket.on(channel_id + ':removeMessage', function (res) {
        comment.messages.find(function (value, index, arr) {
            if (value.id == res.id) arr.splice(index, 1);
        });

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
    //发送消息请求
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
            openid: "{{$user["id"]}}",
            message: content,
            name: name,
            image: image
        }, function (res) {
            console.log(res);
            $("#comment-text").val('');

        })
    }
    function openAnonymous(obj) {

        if (!anonymous) {
            $(obj).attr('src', '/image/niming-on.png');
            anonymous = true;
        } else {
            $(obj).attr('src', '/image/niming-off.png');
            anonymous = false;
        }

    }

    //选择图标
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