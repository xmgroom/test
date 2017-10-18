@extends('framework.main')

@section('content')
    <link rel="stylesheet" href="/css/temp.css">
    <link rel="stylesheet" href="/css/window.css">
    <form action="/channel/{{$channel->id}}/background" enctype="multipart/form-data" method="post">
        <input id="live_image" name="live_image" type="hidden" value="{{$channel->live_image}}">

        <div class="container-fluid no-padding no-margin main-box">
            <div class="main-header">
                <ol class="breadcrumb" style="background:transparent;margin-bottom: 5px;">
                    <li><a href="/">首页</a></li>
                    <li class="active">直播窗口背景图</li>
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
                           上传背景图                                                   
                        </div>
                        <div class="main-body-right-left-body">
                            <div class="body-content">

                                <div class="img-group" id="preview">
                                    @if($channel->screen ==1)
                                        <img id="upload_bg_image" style="width:46%"
                                             class="guide-images @if($channel->live_image=='/image/h.png') select-img @endif"
                                             onclick="clickImage(this,'/image/h.png')" src="/image/h.png"
                                             alt="">

                                        @foreach($images as $image)
                                            <div class="image-container"
                                                 style="width:46%;height:200px;margin:1.3333% 0;display:inline-block;">
                                                <img onload="resize()" style="margin:0;height:100%;width: 100%;"
                                                     class="guide-images add-images @if($channel->live_image==$image->url) select-img @endif"
                                                     onclick="clickImage(this)" src="{{$image->url}}" alt="">
                                                <img onclick="removeImg({{$image->id}},{{$channel->id}},'background')"
                                                     width="20px" height="20px" class="delete hide"
                                                     src="/image/close.png" alt="">
                                            </div>
                                        @endforeach
                                    @endif
                                    @if($channel->screen == 2)
                                        <img class="guide-images @if($channel->live_image=='/image/background_bg1.png') select-img @endif"
                                             onclick="clickImage(this,'/image/background_bg1.png')" src="/image/background_bg1.png"
                                             alt="">

                                        @foreach($images as $image)
                                            <div class="image-container"
                                                 style="width:29%;margin:1.3333%;display:inline-block;">
                                                <img onload="resize()" style="margin:0;"
                                                     class="guide-images add-images @if($channel->live_image==$image->url) select-img @endif"
                                                     onclick="clickImage(this)" src="{{$image->url}}" alt="">
                                                <img onclick="removeImg({{$image->id}},{{$channel->id}},'background')"
                                                     width="20px" height="20px" class="delete hide"
                                                     src="/image/close.png" alt="">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="upload-group">
                                    <p class="gray">图片大小4M以内</p>
                                    <p class="gray">@if($channel->screen==1)图片分辨率424*238像素@endif
                                                    @if($channel->screen==2)图片分辨率1080*1920像素@endif</p>
                                    <p class="gray">支持PNG、JPG、JPEG格式</p>
                                    <label for="uploadImg" class="btn" id="btn_upload"
                                           style="background:#15ae8f;width:100px;color:#fff;">上传</label>
                                    <input type="file" id="uploadImg" class="hide" name="live_image_file"
                                           onchange="preview(this)">
                                </div>

                            </div>
                            <div class="line center-block"></div>

                            <div style="clear:both"></div>

                            @include('channel.qr_code',['url'=>Config::get('app.url') ."/live/" . $channel->id])
                            <button type="submit" class="btn center-block"
                                    style="background:#15ae8f;color:#fff;width:100px;margin-bottom:40px;">提交
                            </button>

                        </div>
                    </div>
                    <div class="main-body-right-right">

                        <div class="body-right-body">
                            <p>预览效果</p>
                            <div class="mobile" style="background:url(/image/mobile.png) center/100% no-repeat">
                                @if($channel->screen ==1)  <img id="previewImg" src="/image/background_bg.png"
                                                                width="100%" height="37%" alt="">
                                <img id="previewImg" src="/image/y.png"
                                     width="100%" height="63%" alt="">@endif
                                @if($channel->screen ==2)  <img id="previewImg" src="/image/background_bg.png"
                                                                width="100%" height="100%" alt=""> @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="/dist/jquery/jquery.min.js"></script>
    <script src="/js/control/background.js"></script>
    <script>
        function preview(file) {
            var prevDiv = document.getElementById('preview');
            if (file.files && file.files[0]) {
                var reader = new FileReader();
                reader.onload = function (evt) {
                            @if($channel->screen == 1)
                    var child = '<img id="pre_upload_img" class="guide-images" style="height:'+$("#upload_bg_image").height()+'px;box-sizing: border-box;width: 46%;box-sizing:border-box" onclick="clickImage(this)" src="' + evt.target.result + '" alt="">';
                            @endif
                            @if($channel->screen == 2)
                    var child = '<img class="guide-images" onclick="clickImage(this)" src="' + evt.target.result + '" alt="">';

                    @endif


                    $(child).appendTo(prevDiv);
            
                    
                }
                reader.readAsDataURL(file.files[0]);
                $('#btn_upload').css('display', 'none');
            }
            else {
                alert("上传失败,请重试");
            }
        }
        $(".image-container").each(function(){
            $(this).height($("#upload_bg_image").height())
        })

    </script>
@endsection