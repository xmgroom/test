$(function () {
    $(".guide-images").each(function () {
        if ($(this).hasClass('select-img')) $("#previewImg").attr('src', $(this).attr('src'));
    })
})
function clickImage(obj, url = null) {
    $(".guide-images").each(function () {
        $(this).removeClass('select-img');
    })
    $(obj).addClass("select-img");
    $("#previewImg").attr('src', $(obj).attr('src'));
    if (url) {
        $("#live_image").val(url);
    } else {
        $("#live_image").val($(obj).attr('src'));
    }
}

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
