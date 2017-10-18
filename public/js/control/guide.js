$(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $(".guide-images").each(function () {
        if ($(this).hasClass('select-img')) $("#previewImg").attr('src', $(this).attr('src'));
    })
});

$("#guide-img").bind('click', function () {
    if ($(this).attr('src') == '/image/switch2_on.png') {
        $(this).attr('src', '/image/switch2_off.png');
        $("#guide").val(0);
    } else {
        $(this).attr('src', '/image/switch2_on.png');
        $("#guide").val(1);
    }
});

$(".image-container").each(function () {
    $(this).bind('mouseover', function () {
        $(this).find('.delete').removeClass('hide')
    });
    $(this).bind('mouseout', function () {
        $(this).find('.delete').addClass('hide')
    })
})
function showToast(text){
    iziToast.warning({
        message: text,
        ballon: true,
        timeout: 3000,
        position: 'topRight'
    });
}

function removeImg(id,channel_id,type) {

    $.post('/api/rm', {
        id: id,
        channel_id:channel_id,
        type:type
    }, function (res) {
        if (res.code === 0) {
            showToast('删除成功')
            setTimeout(function () {
                location.reload();
            }, 1000)
        } else {
            showToast(res.message);
        }
    })
}

function clickImage(obj, url = null) {

    $(".guide-images").each(function () {
        $(this).removeClass('select-img');
    })
    $(obj).addClass("select-img");
    $("#previewImg").attr('src', $(obj).attr('src'));
    if (url) {
        $("#guide_image").val(url);

    } else {
        $("#guide_image").val($(obj).attr('src'));
    }
}

function preview(file) {
    var prevDiv = document.getElementById('preview');
    if (file.files && file.files[0]) {
        var reader = new FileReader();
        reader.onload = function (evt) {
            var child = '<img class="guide-images" onclick="clickImage(this)" src="' + evt.target.result + '" alt="">';
            $(child).appendTo(prevDiv);
        }
        reader.readAsDataURL(file.files[0]);
        $('#btn_upload').css('display', 'none');
        resize();
    } else {
        alert("上传失败,请重试");
    }
}