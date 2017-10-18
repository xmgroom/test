@extends('framework.main')

@section('content')
    <link rel="stylesheet" href="/css/temp.css">
    <link rel="stylesheet" href="/css/guide.css">
    <form id="form" action="/channel/{{$channel->id}}/guide" method="post" enctype="multipart/form-data">

        <input id="guide_image" name="guide_image" type="hidden" value="{{$channel->guide_image}}">
        <input id="guide" name="guide" type="hidden" value="{{$channel->guide}}">

        <div class="container-fluid no-padding no-margin main-box">
            <div class="main-header">
                <ol class="breadcrumb" style="background:transparent;margin-bottom: 5px;">
                    <li><a href="/">首页</a></li>
                    <li class="active">启动引导页面</li>
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
                        <div class="main-body-right-left-header">
                            引导图
                            @if($channel->guide)
                                <img style="cursor:pointer;margin-left: 10px;" id="guide-img" src="/image/switch2_on.png" alt="">
                            @else
                                <img style="cursor:pointer;margin-left: 10px;" id="guide-img" src="/image/switch2_off.png" alt="">
                            @endif
                            <span class="left-header-control" style="margin-left:40px;color:#888;">
                                   此图片显示在进入直播页面之前，用于展示直播相关介绍内容，观众点击图片进入频道
                                </span>
                        </div>
                        <div class="main-body-right-left-body">
                            <div class="body-content">

                                <p>上传背景图 <span style="margin-left:40px;color:#888;">建议选择背景底色简洁大方的背景图，您也可以从以下背景图选择:</span></p>
                                <div class="img-group" id="preview">
                                    <img class="guide-images @if($channel->guide_image=='/image/guide1.png') select-img @endif"
                                         onclick="clickImage(this,'/image/guide1.png')" src="/image/guide1.png"  alt="">
                                         
                                    @foreach($images as $image)
                                    <div class="image-container" style="width:200px;height:390px;margin:1.3333%;display:inline-block;">
                                        <img onload="resize()" style="margin:0;    box-sizing: border-box;" class="guide-images add-images @if($channel->guide_image==$image->url) select-img @endif"
                                           onclick="clickImage(this)" src="{{$image->url}}" alt="">
                                        <img onclick="removeImg({{$image->id}},{{$channel->id}},'guide')" width="20px" height="20px" class="delete hide" src="/image/close.png" alt="">
                                    </div>
                                    @endforeach
                                </div>
                                <div class="upload-group">
                                    <p class="gray">图片大小4M以内</p>
                                    <p class="gray">图片分辨率1080*1930像素</p>
                                    <p class="gray">支持PNG、JPG、JPEG格式</p>
                                    <label for="uploadImg" class="btn" id="btn_upload"
                                           style="background:#15ae8f;width:100px;color:#fff;">上传</label>
                                    <input type="file" id="uploadImg" class="hide" name="guide_image_file"
                                           onchange="preview(this)">
                                </div>

                            </div>
                            <div class="line center-block"></div>
                            <div style="clear:both"></div>

                            @include('channel.qr_code',['url'=>Config::get('app.url') ."/live/" . $channel->id])
                            <button type="submit" class="btn center-block"
                                    style="background:#15ae8f;color:#fff;width:100px;margin-bottom: 40px;">提交
                            </button>

                        </div>

                    </div>
                    <div class="main-body-right-right">

                        <div class="body-right-body">
                            <p>预览效果</p>
                            <div class="mobile" style="background:url(/image/mobile.png) center/100% no-repeat">
                                <img id="previewImg" src="/image/guide1.png" width="100%" height="100%" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="/dist/jquery/jquery.min.js"></script>
    <script src="/js/resize.js"></script>
    <script src="/js/control/guide.js"></script>

@endsection