<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="/dist/aui/aui.css"/>
    <link rel="stylesheet" href="/css/font-awesome.min.css">

    <link rel="stylesheet" href="http://static.hongshiyun.net/player/h5/v0.0.5/player.normal.rem.css">
    <link rel="stylesheet" href="/css/wechat/live_hor.css">
</head>

<body>
<div class="container">

    <div class="live-container" style="position: relative;">
        {!! $htmlEmbedCode !!}
    </div>

</div>

<script src="/dist/jquery/jquery.js"></script>


</body>
</html>
