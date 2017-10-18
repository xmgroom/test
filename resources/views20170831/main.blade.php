<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>苍狼直播</title>
    <link rel="stylesheet" href="/css/mincss/index.css">
    <link rel="stylesheet" href="/dist/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/mincss/header-footer.css">
    <link rel="stylesheet" href="/dist/datetimepicker/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="/dist/font-awesome/css/font-awesome.min.css">
</head>

<body>
<div class="wrap">
    <div class="header clearfix">
        <div class="top">
            <a href="#" class="logo-wrap">
                    <span class="symbol">
                    <img src="{{Session::get('company:logo')}}" alt="">
                    </span>
            </a>
            <div class="mid">
                <a href="/" class="nav first @if(Request::getRequestUri() == '/') active @endif">首页</a>
                <a href="/video" class="nav @if(Request::getRequestUri() == '/video') active @endif">回放</a>
                <a href="/account" class="nav @if(Request::getRequestUri() == '/account') active @endif">账号</a>
            </div>
            <div class="right-wrap">
                <div class="user-info">
                    <span href="#" id="username-link" title="账号设置">
                        {{ Auth::user()->name }}
                    </span>
                </div>
                <a href="/logout" class="logout">退出</a>
            </div>
        </div>

        @if(isset($header))
            <div class="link clearfix">
                <div id="rm-channel" class="left">
                    <div class="chooseChannel">
                        <strong title="Test">
                            <i class="icon-file" style="margin-top: -2px;"></i>
                            {{Session::get('channel:name')}}
                        </strong>
                        <ul id="cChannel" class="hide">

                            @if($channels = Session::get('channels'))
                                @foreach($channels as $key => $value)
                                    <li>
                                        <a class="rm-add-channel" style="color: #000" href="/channel/{{$key}}/edit" >{{$value}}</a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>


                    <div id="rm-error" class="rm-error hide" style="left:272px;top:246px;width:100%;"></div>
                    <div id="rm-mask" class="mask hide"></div>
                    <div id="add-block" class="add-block hide" style="left:530px;top:244px;">
                        <p class="title">新建直播频道<i id="close" class="close"></i></p>
                        <p class="info">苍狼直播严禁上传包括反动、暴力、色情、违法、及侵权内容的文件。目睹直播有义务配合有关部门将上传违规文件的用户信息保存，并保留因配合调查及冻结账号的权利。</p>
                        <div class="content">
                            <p class="warn">注意：以下内容将显示在观看页面上，请认真填写。</p>
                            <p class="input-info">
                                <input id="title-input" type="text" maxlength="20" placeholder="频道名称"
                                       class="input-shadow">
                                <span class="count">
            <strong id="title-count" class="used-count">0</strong>
            <strong class="total-count">/20</strong>
          </span>
                            </p>
                        </div>
                        <p class="box-footer">
                            <span id="rm-box-confirm">确定</span>
                            <span id="rm-box-cancel" class="rm-close">取消</span>
                        </p>
                    </div>

                </div>

                @if(Session::get('channel:id'))

                    <ul class="right">
                        <a href="/channel/{{Session::get('channel:id')}}/control" class="first-child brdcast
                  @if(Request::getRequestUri() == "/channel/" . Session::get('channel:id') . "/control") active @endif">
                            <li>
                                <i class="icon-play-circle" style="margin-top: 3px;"></i><span>直播控制</span>
                            </li>
                        </a>
                        <a href="/channel/{{Session::get('channel:id')}}/edit" class="cnlmana
 @if(Request::getRequestUri() == "/channel/" . Session::get('channel:id') . "/edit") active @endif">
                            <li>
                                <i class="icon-edit" style="margin-top: 3px;"></i><span>频道管理</span>
                            </li>
                        </a>
                        <a href="/channel/{{Session::get('channel:id')}}/count" class="last-child anycal
@if(Request::getRequestUri() == "/channel/" . Session::get('channel:id') . "/count") active @endif">
                            <li>
                                <i class="icon-external-link"></i><span>数据统计</span>
                            </li>
                        </a>
                    </ul>

                @endif
            </div>
        @endif

    </div>
    <div class="main clearfix">
        @if (count($errors) > 0)
            <div class="alert alert-warning">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                @if(is_object($errors))
                    @foreach($errors->all() as $error)
                        <strong>{{ $error }}!</strong>
                    @endforeach
                @elseif(is_array($errors))
                    @foreach($errors as $error)
                        <strong>{{ $error }}!</strong>
                    @endforeach
                @else
                    <strong>{{ $errors }}!</strong>
                @endif
            </div>
        @endif


        @yield('content')
    </div>
    <div class="footer clearfix">
        <p style="margin-top:40px">Copyright © 2017 苍狼直播. All rights reserved. </p>
    </div>

</div>

<script src="/dist/jquery/jquery.min.js"></script>
<script src="/dist/bootstrap/js/bootstrap.min.js"></script>
<script>
    $(function () {
        var userType = $('#rm-header').attr('data-type');
        var cChannel = $('#rm-header').attr('data-account');
        $('#add-channel').on('click', function () {
            $('#rm-mask').css({
                'opacity': '0.65',
                'background-color': '#050505'
            })
            $('#rm-mask').removeClass('hide');
            if (userType === '1' && cChannel >= 1) {
                $('#free-error').removeClass('hide');
            } else {
                $('#add-block').removeClass('hide');
            }
        });

        $('.cancel').on('click', function () {
            $('#free-error').addClass('hide');
            $('#rm-mask').addClass('hide');
        });

        var coInputLimit = function (id, countId, max) {
            var inputLimit = function () {
                var num = $(id).val().substr(0, max);
                $(countId).text($(id).val().length);
            };
            $(id).on('keyup', function () {
                $('#rm-error').addClass('hide');
                inputLimit();
            });
        };

        coInputLimit('#title-input', '#title-count', 10);

        var $addBtnClicked = false;
        $('#rm-box-confirm').on('click', function () {
            if ($addBtnClicked == true) {
                return true;
            }
            $addBtnClicked = true;
            if ($('#title-input').val().length === 0) {
                if ($('#title-input').val().length === 0) {
                    error_text = '频道名称不能为空!';
                }

                $('#rm-error').text(error_text);
                $('#rm-error').removeClass('hide');
                $addBtnClicked = false;
                return;
            } else {
                var postTitle = $('#title-input').val();
                var postStart = $('#start').val();
                var postEnd = $('#end').val();

                $.ajax({
                    type: 'post',
                    url: '/console/?a=add&c=index',
                    data: {
                        name: postTitle,
                        start_time: postStart,
                        end_time: postEnd
                    },
                })
                        .done(function (data) {

                            if (JSON.parse(data).status) {
                                location.reload();
                            } else {
                                $addBtnClicked = false;
                            }
                        })
                        .fail(function () {
                            $addBtnClicked = false;
                        })
                        .always(function () {
                        });
            }
        });

        $('.rm-close').on('click', function () {
            $('#add-block').addClass('hide');
            $('#rm-mask').addClass('hide');
            $('#rm-error').addClass('hide');
        });
    });
    $("#rm-channel").hover(function () {
        $("#cChannel").removeClass('hide');
    })
    $("#rm-channel").mouseleave(function () {
        $("#cChannel").addClass('hide');
    })
</script>
@yield('script')

</body>

</html>