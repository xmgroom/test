@extends('framework.main')

@section('content')
    <link rel="stylesheet" href="/css/temp.css">
    <link rel="stylesheet" href="/css/icon.css">
    <link rel="stylesheet" href="/dist/cropper/cropper.css">
    <form action="/channel/{{$channel->id}}/logo" enctype="multipart/form-data" method="post">

        <input id="logo" name="logo" type="hidden" value="{{$channel->logo}}">
        <input id="x" name="x" type="hidden">
        <input id="y" name="y" type="hidden">
        <input id="width" name="width" type="hidden">
        <input id="height" name="height" type="hidden">

        <div class="container-fluid no-padding no-margin main-box">
            <div class="main-header">
                <ol class="breadcrumb" style="background:transparent;margin-bottom: 5px;">
                    <li><a href="/">首页</a></li>
                    <li class="active">频道图标</li>
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
                            编辑
                        </div>
                        <div class="main-body-right-left-body">
                            <div class="body-content">
                                <div class="img-group" style="background:#ccc">
                                    <img id="edit-logo" width="100%" height="100%" src="{{$channel->logo}}" alt="">
                                </div>
                                <div class="upload-group">
                                    <div id="prev" class="preview" style="width: 120px;height: 120px"></div>
                                    <p class="gray">图片大小2M以内</p>
                                    <p class="gray">图片分辨率200*200像素</p>
                                    <p class="gray">支持PNG、JPG、JPEG格式</p>
                                    <label for="uploadImg" class="btn" id="btn_upload"
                                           style="background:#15ae8f;width:100px;color:#fff;">上传</label>
                                    <input type="file" id="uploadImg" class="hide" name="logo_file"
                                           onchange="preview(this)">
                                </div>
                            <div style="clear:both"></div>
                            <div class="line center-block"></div>

                            </div>


                            @include('channel.qr_code',['url'=>Config::get('app.url') ."/live/" . $channel->id])

                            <button type="submit" class="btn center-block"
                                    style="background:#15ae8f;color:#fff;width:100px;margin-bottom:40px">提交
                            </button>


                        </div>
                    </div>
                    <div class="main-body-right-right">


                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@section('script')

    <script src="/dist/cropper/cropper.js"></script>
    <script src="/js/control/logo.js"></script>

@endsection