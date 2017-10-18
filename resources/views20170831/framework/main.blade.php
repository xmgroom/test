<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>虹直播</title>
    <link rel="stylesheet" href="/dist/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/dist/fontAwesome/font-awesome.min.css">
    <link rel="stylesheet" href="/css/temp.css">
    <link rel="stylesheet" href="/dist/iziToast/iziToast.min.css">

</head>

<body>
<div class="container-fluid no-padding fs12" style="background:#ebf1f4;">
    <div class="topbar">
        <img src="/image/logo.png" width="140px" height=30px" alt="">

        <div>
        <span class="top-info">
                <img src="{{Session::get('company:logo')}}" width="34px" height="34px" alt="">
                <span>{{Session::get('company:name')}}</span>
            <a class="btn top-btn" style="color:white" href="/company">切换组织</a>
        </span>
        <span class="top-text" id="account">
                <i class="fa fa-user-o"></i>
                <span>{{Auth::user()->name}}</span>
            <span class="caret"></span>
              <a href="/logout">
                  <div class="logout not-select">退出</div>
              </a>
            <a href="/change_password">
                  <div class="change-password not-select">修改密码</div>
              </a>
            </span>
        </div>
    </div>
    <div class="left-menu not-select">
        @include('framework.menu')

    </div>

    <div class="box-container">
        {{--  @if (count($errors) > 0)
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
        @endif  --}}

        @yield('content')
        <?php print_r(1223)?>
        <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background:#ebf1f4;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                        <h4 class="modal-title" id="myModalLabel">提示</h4>
                    </div>
                    <div class="modal-body" style="padding:20px;">
                        <p style="font-size:16px;">您好，</p>
                        <p style="font-size:16px;">您还未进行账户绑定，请联系您的代理商进行账户绑定后再来。</p>
                        <p style="font-size:12px;color:#ccc;">如果您是新用户，未有归属代理商，请联系我们</p>
                        <p style="font-size:12px;color:#ccc;">联系电话：400-007-7100</p>
                        <p style="font-size:12px;color:#ccc;">联系邮箱：support-ACOC@acrvideo.com</p>
                    </div>
                </div>
            </div>
    </div>
</div>

<script src="/dist/jquery/jquery.min.js"></script>
<script src="/dist/daterangepicker/moment.js"></script>
<script src="/dist/bootstrap/js/bootstrap.min.js"></script>
<script src="/dist/iziToast/iziToast.min.js"></script>
<script src="/js/resize.js"></script>

<script>
    @if (count($errors) > 0)
                   @if(is_object($errors))
                       @foreach($errors->all() as $error)
                         iziToast.warning({
        message: "{{$error}}",
        ballon: true,
        timeout: 3000,
        position: 'topRight'
    });
    @endforeach
@elseif(is_array($errors))
    @foreach($errors as $error)
         iziToast.warning({
        message: "{{$error}}",
        ballon: true,
        timeout: 3000,
        position: 'topRight'
    });
    @endforeach
@else
        iziToast.warning({
        message: "{{$error}}",
        ballon: true,
        timeout: 3000,
        position: 'topRight'
    });

    @endif
@endif

$("#account").bind('mouseover', function () {
        $(".logout").css('display', 'block');
        $(".change-password").css('display', 'block');
    })
    $("#account").bind('mouseout', function () {
        $(".logout").css('display', 'none');
        $(".change-password").css('display', 'none');

    })
</script>
@yield('script')


</body>

</html>

<script type="text/javascript">

      @if(Auth::user()->type!=2)
        $('#alertModal').modal();
    @endif
</script>