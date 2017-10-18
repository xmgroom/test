<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" >
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$lists->name}}</title>
    <link rel="stylesheet" href="/css/wechat/lists.css">
    <style>
        .lists-content{
            width: 90%;
            box-shadow: 0 0 5px #eee;
            background: rgba(0,0,0,.4);
            padding: 6px;
            color:#fff;
        }
    </style>
</head>
<body style="background:{{$lists->background_color}} center/cover no-repeat;"">
<img id="header" src="{{$lists->head_img}}" width="100%" alt="">

<div id="body" style="overflow-y: auto;">
    <ul>

        @foreach($lists->lists as $video)

            @if($video->type==1)
      <li style="background:{{$lists->item_color}}" class="lists-content" id="list-content"  onclick="javascript:location.href='/play/{{$video->resource_id}}'">
               <img src="{{$video->img}}"  alt="">
                <span class="content">
                    <h5 style="">名称:{{$video->name}}</h5>
                    <p>简介:{{$video->content}}</p>
                </span>
                <span class="list-type">视频</span>
            </li>

            @else
                <li style="background:{{$lists->item_color}}" class="lists-content" onclick="javascript:location.href='/live/{{$video->resource_id}}'">
                    <img src="{{$video->img}}" width="90px" height="55px" alt="">
                <span class="content">
                    <h5 style="">名称:{{$video->name}}</h5>
                    <p>简介:{{$video->content}}</p>
                </span>

                    <span class="list-type">直播</span>

                </li>

            @endif
        @endforeach



    </ul>
</div>
<img id="footer" src="{{$lists->foot_img}}" width="100%" alt="">

<script src="/dist/jquery/jquery.min.js"></script>
<script>
    $(function(){
        $("#header").attr('height',$("#header").width()/2.5);
        $("#footer").attr('height',$("#footer").width()/2.5);
        var bodyHeight = $('body').height() - $("#header").height() - $("#footer").height() -40;
        var bodyWidth = $('body').width()-40;
        console.log( $('body').height())
        console.log( $("#header").height())
        console.log( $("#footer").height())
        console.log(bodyHeight);
        $("#body").css('height',bodyHeight);
        $("#body").css('width',bodyWidth);
        $(".content").css('width',$("#list-content").width()-130)
    })
</script>
</body>
</html>