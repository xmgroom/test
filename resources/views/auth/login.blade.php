<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>白鹿云播</title>
    <link rel="stylesheet" href="/dist/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/dist/fontAwesome/font-awesome.min.css">
    <link rel="stylesheet" href="/css/login.css">

</head>

<body style="position:relative;min-height:768px;">
<div class="container-fluid" style="background:url('/image/bg1.png') center/cover no-repeat;height:100%;">
    <img src="/image/logo.png" class="center-block login-topimage" alt="">
    <form action="/login" method="post">

        <div class="main-container center-block">

            <h5 class="login-form-title">登录您的账户</h5>
            <form action="/login" method="post">
                @include('framework.error')
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon" style="background:#fff;"><i class="fa fa-user"></i></div>
                        <input type="text" class="form-control" id="exampleInputEmail1" name="name"
                               placeholder="账号/邮箱/手机号">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon" style="background:#fff;"><i class="fa fa-lock"></i></div>
                        <input name="password" type="password" class="form-control" id="exampleInputPassword1"
                               placeholder="密码">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" name="captcha" class="form-control v-code" placeholder="验证码">
                        <img id="c2c98f0de5a04167a9e427d883690ff6" src="{{ URL('api/captcha/1') }}    " width="120px"
                             height="34px" alt="">
                        <a href="#" style="margin-left:10px;" onclick="re_captcha()">换一张</a>
                    </div>
                </div>
                <div class="checkbox">
                    <label style="font-family:'微软雅黑';">
                        <input type="checkbox"> 自动登录
                    </label>
                    <label style="float:right;"><a class="forget" href="/forget">忘记密码？</a></label>
                </div>
                <button type="submit" class="btn login-submit center-block">登录</button>
                <p class="tac mt20">还没有账号?去<a href="/sign">注册</a></p>
            </form>
        </div>
    </form>
</div>
<footer class="footer " style="position:absolute;bottom:15px;left:0;right:0;">
    <div class="container">
        <p class="footer tac"><i class="fa fa-globe"></i> 2017 ARCVIDED TECHNOLOGIES CO,LTD. ALL RIGHTS RESERVED</p>
    </div>
</footer>
</div>
<script src="/dist/jquery/jquery.min.js"></script>
<script src="/dist/bootstrap/js/bootstrap.min.js"></script>
<script>
    function re_captcha() {
        $url = "{{ URL('api/captcha') }}";
        $url = $url + "/" + Math.random();
        document.getElementById('c2c98f0de5a04167a9e427d883690ff6').src = $url;
    }

    $(function () {
        $("body").css('height', $('html').height());
    })
    window.onresize = function () {
        $("body").css('height', $('html').height());
    }

</script>
</body>

</html>

