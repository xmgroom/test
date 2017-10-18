$("#left-top").click(function () {
    $("#logo").removeClass().addClass('left-top');
})
$("#left-bottom").click(function () {
    $("#logo").removeClass().addClass('left-bottom');
})
$("#right-top").click(function () {
    $("#logo").removeClass().addClass('right-top');
})
$("#right-bottom").click(function () {
    $("#logo").removeClass().addClass('right-bottom');
})

$("#guide-img").bind('click', function () {
    if ($(this).attr('src') == '/image/switch2_on.png') {
        $(this).attr('src', '/image/switch2_off.png');
        $("#live_logo_open").val(0);
    } else {
        $(this).attr('src', '/image/switch2_on.png');
        $("#live_logo_open").val(1);
    }
});

function preview(file) {
    if (file.files && file.files[0]) {
        var reader = new FileReader();
        reader.onload = function (evt) {
            $('#logo').attr('src', evt.target.result);
        };
        reader.readAsDataURL(file.files[0]);
        $('#btn_upload').css('display', 'none');
    }
    else {
        alert("上传失败,请重试");
    }
}