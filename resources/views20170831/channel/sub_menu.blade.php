<div class="main-body-left">
    <ul>
        <li @if(strpos(Request::getRequestUri(),'control')) class="active" @endif>
            <a href="/channel/{{$channel_id}}/control">直播间管理</a>
        <li @if(strpos(Request::getRequestUri(),'guide')) class="active" @endif>
            <a href="/channel/{{$channel_id}}/guide">启动引导页面</a>

        </li>
        <li @if(strpos(Request::getRequestUri(),'background')) class="active" @endif>
            <a href="/channel/{{$channel_id}}/background">直播窗口背景图</a>

        </li>
        <li @if(strpos(Request::getRequestUri(),'live_logo')) class="active" @endif>
            <a href="/channel/{{$channel_id}}/live_logo">视频LOGO/水印</a>

        </li>
        <li @if(strpos(Request::getRequestUri(),'/logo')) class="active" @endif>
            <a href="/channel/{{$channel_id}}/logo">频道图标</a>

        </li>
        <li @if(strpos(Request::getRequestUri(),'countdown')) class="active" @endif>
            <a href="/channel/{{$channel_id}}/countdown">倒计时设置</a>
        </li>
    </ul>
</div>