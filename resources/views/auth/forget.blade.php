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
            <form action="/forget" method="post">
                @include('framework.error')

                <h4 class="tac"><b>找回密码</b></h4>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon" style="background:#fff;"><i class="fa fa-mobile"></i></div>
                        <input type="text" class="form-control" name="name" placeholder="请输入手机号码" id="mobile">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon" style="background:#fff;"><i class="fa fa-lock"></i></div>
                        <input type="text" class="form-control v-code" placeholder="验证码" name="captcha">
                        <img id="c2c98f0de5a04167a9e427d883690ff6" src="{{ URL('api/captcha/1') }}" width="100px" height="34px" onclick="re_captcha()">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon" style="background:#fff;"><i class="fa fa-lock"></i></div>
                        <input type="text" class="form-control v-code" placeholder="动态密码" name="verifyCode">
                        <button type="submit" class="btn" id="getVerifyCode" style="background:#2ba6ee;color:#fff">获取验证码</button>
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
        function re_captcha() {
            $url = "{{ URL('api/captcha') }}";
            $url = $url + "/" + Math.random();
            document.getElementById('c2c98f0de5a04167a9e427d883690ff6').src=$url;
        }

        $('#getVerifyCode').click(function () {
            var mobile = $('#mobile').val();

            if (mobile.length != 11) return alert('请输入正确的手机号码');

            $.post('/api/verify_code', {
                'mobile': mobile
            },function (res) {

                if(res.code==0) alert('验证码获取成功！');

                else alert('验证码获取失败');

            })

        });
    </script>
</body>

</html>