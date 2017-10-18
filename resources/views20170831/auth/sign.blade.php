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
        @include('framework.error')
        <div class="alert alert-danger" style="display: none">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong id="message_error"></strong>
        </div>
        <form action="/sign" method="post">
            <h4 class="tac"><b>注册</b></h4>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon" style="background:#fff;"><i class="fa fa-user"></i></div>
                    <input type="email" onblur="check_email(this)" name="email" class="form-control" id="" placeholder="请输入邮箱地址">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon" style="background:#fff;"><i class="fa fa-mobile"></i></div>
                    <input id="mobile" onblur="check_mobile(this)" type="num" class="form-control" placeholder="请输入手机号码" name="name">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon" style="background:#fff;"><i class="fa fa-lock"></i></div>
                    <input name="captcha" type="text" class="form-control v-code" placeholder="验证码">
                    <img src="{{ URL('api/captcha/1') }}" width="100px" height="34px" alt=""
                         id="c2c98f0de5a04167a9e427d883690ff6" onclick="re_captcha()">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon" style="background:#fff;"><i class="fa fa-lock"></i></div>
                    <input type="text" class="form-control v-code" placeholder="动态密码" name="verifyCode">
                    <button type="button" class="btn" style="background:#2ba6ee;color:#fff" id="getVerifyCode">获取验证码
                    </button>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon" style="background:#fff;"><i class="fa fa-lock"></i></div>
                    <input type="password" name="password" class="form-control" id="" placeholder="设置密码(6-30个数字)">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon" style="background:#fff;"><i class="fa fa-lock"></i></div>
                    <input type="password" name="password_confirmation" class="form-control" id=""
                           placeholder="确认密码(6-30个数字)">
                </div>
            </div>

            <div class="checkbox">
                <label style="font-family:'微软雅黑';">
                    <input id="server_license" type="checkbox"> 我已经阅读并同意相关<a href="" data-toggle="modal" data-target="#law">服务条款</a>
                </label>
            </div>
            <button id="register_now" type="submit" disabled class="btn login-submit center-block">立即注册</button>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="law" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">服务条款</h4>
                </div>
                <div class="modal-body">
                    <iframe class="iframe-law" src="/law" frameborder="0"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="/dist/jquery/jquery.min.js"></script>
<script src="/dist/bootstrap/js/bootstrap.min.js"></script>

<script src="/dist/jquery/jquery.js"></script>
<script>

    $(function () {
        $height = $(document).height();
        $('html').height($height);
    });
    var countTime = 60, timeOut;
    function startCount() {
        timeOut = setInterval(countDowm, 1000);
    }
    function endCount() {
        clearInterval(timeOut);
    }
    function countDowm() {
        if (countTime > 0) {
            countTime--;
            $("#getVerifyCode").html('剩余' + countTime +'秒').attr('disabled', true);
        } else {
            clearInterval(timeOut);
            $("#getVerifyCode").html('获取验证码').attr('disabled', false);
        }
        console.log(countTime);
    };

    function re_captcha() {
        $url = "{{ URL('api/captcha') }}";
        $url = $url + "/" + Math.random();
        document.getElementById('c2c98f0de5a04167a9e427d883690ff6').src = $url;
    }
    function check_email(obj){
        var value = $(obj).val();
            $.get('http://api.hongshiyun.net/rest?action=accountExist&account='+value,function(res){
                if(res.code===0){
                    console.log(res.code);
                }else if(res.code ===10006){
                    $('#message_error').html('邮箱已经被注册，请输入新的邮箱！');
                    $('#message_error').parent().show();
                }
            })
    }
    function check_mobile(obj){
        var value = $(obj).val();
        $.get('http://api.hongshiyun.net/rest?action=checkCellphone&cellphone='+value,function(res){
            if(res.code===0){
                console.log(res.code);
            }else if(res.code ===10042){
                $('#message_error').html('手机号码已经注册！');
                $('#message_error').parent().show();
            }
        })
    }

    $('#getVerifyCode').click(function () {
        var mobile = $('#mobile').val();

        if (mobile.length != 11) {
            $('#message_error').html('请输入正确的手机号码！');
            $('#message_error').parent().show();
        }

        $.post('/api/verify_code', {
            'mobile': mobile
        }, function (res) {

            if (res.code == 0) {
                $('#message_error').html('验证码获取成功！');
                $('#message_error').parent().show();

                startCount()
            }
            else {

                $('#message_error').html(res.message);
                $('#message_error').parent().show();
            }
        })

    });

    $("#server_license").bind('click',function(){
        if($(this).prop('checked')){

            $('#register_now').attr('disabled',false);

        }else{
            $('#register_now').attr('disabled',true);


        }
    })
</script>
</html>