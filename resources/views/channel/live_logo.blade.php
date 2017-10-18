@extends('framework.main')

@section('content')
    <link rel="stylesheet" href="/css/temp.css">
    <link rel="stylesheet" href="/css/logo.css">
    <form action="/channel/{{$channel->id}}/live_logo" enctype="multipart/form-data" method="post">
        <input id="live_logo" name="live_logo" type="hidden" value="{{$channel->live_logo}}">
        <input id="live_logo_open" name="live_logo_open" type="hidden" value="{{$channel->live_logo_open}}">

        <div class="container-fluid no-padding no-margin main-box">
            <div class="main-header">
                <ol class="breadcrumb" style="background:transparent;margin-bottom: 5px;">
                    <li><a href="/">首页</a></li>
                    <li class="active">视频LOGO/水印</li>
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
                            视频LOGO
                            @if($channel->live_logo_open)
                                <img style="cursor:pointer;" id="guide-img" src="/image/switch2_on.png" alt="">
                            @else
                                <img style="cursor:pointer;" id="guide-img" src="/image/switch2_off.png" alt="">
                            @endif

                        </div>
                        <div class="main-body-right-left-body">
                            <div class="body-content">

                                <p>上传LOGO
                                    <input id="left-top" style="margin-left:20px;" type="radio" value="1" name="live_logo_position" @if($channel->live_logo_position ==1) checked @endif > 左上
                                    <input id="left-bottom" style="margin-left:10px;" type="radio" value="2" name="live_logo_position" @if($channel->live_logo_position ==2) checked @endif > 左下
                                    <input id="right-top" style="margin-left:10px;"  type="radio" value="3" name="live_logo_position" @if($channel->live_logo_position ==3) checked @endif> 右上
                                    <input id="right-bottom" style="margin-left:10px;" type="radio" value="4" name="live_logo_position" @if($channel->live_logo_position ==4) checked @endif> 右下
                                </p>

                                <div class="img-group" style="background:url(/image/guide1.png) center/cover no-repeat">
                                    <img id="logo" style="max-width:100px;max-height:30px;" @if($channel->live_logo_position ==1)class="left-top" @elseif($channel->live_logo_position ==2) class="left-bottom"
                                         @elseif($channel->live_logo_position ==3) class="right-top" @else class="right-bottom" @endif
                                         src="{{$channel->live_logo}}" alt="">
                                </div>
                                <div class="upload-group">
                                    <p class="gray">图片大小4M以内</p>
                                    <p class="gray">图片分辨率200*200像素</p>
                                    <p class="gray">支持PNG、JPG、JPEG格式</p>
                                    <label for="uploadImg" class="btn" id="btn_upload"
                                           style="background:#15ae8f;width:100px;color:#fff;">上传</label>
                                    <input type="file" id="uploadImg" class="hide" name="live_logo_file" onchange="preview(this)">
                                </div>

                            </div>
                                                                                    <div class="line center-block"></div>

                            <div style="clear:both"></div>
                            @include('channel.qr_code',['url'=>Config::get('app.url') ."/live/" . $channel->id])
                            <button type="submit" class="btn center-block"
                                    style="background:#15ae8f;color:#fff;width:100px;    margin-bottom: 40px;">提交
                            </button>
                        </div>
                    </div>
                    <div class="main-body-right-right">


                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="/dist/jquery/jquery.min.js"></script>
    <script src="/js/control/live_logo.js"></script>

@endsection