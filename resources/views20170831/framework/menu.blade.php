<ul>
    <a href="/">
        <li @if(Request::getRequestUri() == '/' || strpos(Request::getRequestUri() ,'type')) class="left-active" @endif><img src="/image/home.png" alt="">首页</li>
    </a>
    <a href="/material">
        <li @if(strpos(Request::getRequestUri() ,'material')) class="left-active" @endif><img src="/image/material.png"
                                                                                         alt="">素材管理
        </li>
    </a>
    <a href="/list">
        <li @if(strpos(Request::getRequestUri() ,'list')) class="left-active" @endif><img src="/image/list.png" alt="">列表管理
        </li>
    </a>

    @if(Session::get('channel:id'))
        <a href="/channel/{{Session::get('channel:id')}}/count">
            <li @if(strpos(Request::getRequestUri() ,'count') && ! strpos(Request::getRequestUri() ,'countdown')) class="left-active" @endif>
                <img src="/image/count.png" alt="">统计
            </li>
        </a>
    @endif
</ul>
