<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>虹直播</title>
    <link rel="stylesheet" href="/dist/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/dist/fontAwesome/font-awesome.min.css">
    <link rel="stylesheet" href="/css/register.css">

</head>

<body>
    <div class="container-fluid no-padding fs12" style="background:#ebf1f4;height:100%;">
        <div class="topbar">
            <img src="/image/logo-b.png" width="200px" height="50px" alt="">
            <span class="top-text">已有账号,去<a href="/login">登录</a></span>
        </div>
        <div class="main center-block mt5p p10">
            <form action="/forget_change" method="post">
                @include('framework.error')

                <h4 class="tac"><b>找回密码</b></h4>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon" style="background:#fff;"><i class="fa fa-lock"></i></div>
                        <input type="password" class="form-control" name="password" placeholder="輸入新密码">
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon" style="background:#fff;"><i class="fa fa-lock"></i></div>
                        <input type="password" class="form-control" name="password_confirmation" placeholder="再次输入密码">
                    </div>
                </div>
                <button type="submit" class="btn login-submit center-block">确定</button>
            </form>
        </div>
    </div>
      <script src="/dist/jquery/jquery.min.js"></script>
    <script src="/dist/bootstrap/js/bootstrap.min.js"></script>
    <script>
        $("body").css('height', $('html').height());
    </script>
</body>

</html>