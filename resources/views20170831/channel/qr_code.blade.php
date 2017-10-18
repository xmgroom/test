<div class="body-address container-fluid">
    <h3>观看地址</h3>
    <div class=" row">
        <input id="copy1" readonly type="text" class="push-address form-control" value="{{$url}}">
        <button type="button" class="push-copy btn btn-default" onclick="copyUrl('copy1')">复制</button>
    </div>

    <div class="container no-padding no-margin" style="width: 100%;">
        {!! QrCode::size(220)->generate($url) !!}
        <p style="padding-left: 44px;">扫一扫，手机观看</p>
    </div>
</div>


<script>

    function copyUrl(id) {
        var copyObj = document.getElementById(id);
        copyObj.select();
        document.execCommand("Copy"); // 执行浏览器复制命令
        $("#copy-success").show();
        setTimeout(function () {
            $("#copy-success").hide()
        }, 1000)
    }

</script>