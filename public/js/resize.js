
$(function() {
$('body').css('height',document.documentElement.offsetHeight  +'px');
    $('.left-menu').css('height',$("body").height()+ 100 +'px');

    $('.box-container').css('height',document.body.offsetHeight+'px')

    var leftHeight = $(".main-body-right-left").outerHeight()
    $(".body-comment-box").css('height', leftHeight - 280);
    $(".main-body-right-right").css('height', leftHeight);

    var rightWidth = $('.coment-content').width() - $("#comment-avatar").width() - 20;
    var imgWidth = $("#comment-avatar").width();
    console.log(rightWidth);
    $(".content-item-top-right").css('width', rightWidth + 'px');

});
window.onresize = function() {
    resize()
};
function resize() {

    $('.left-menu').css('height',$("body").height()+ 100 +'px');
    var leftHeight = $(".main-body-right-left").outerHeight()
    $(".body-comment-box").css('height', leftHeight - 280);
    $(".main-body-right-right").css('height', leftHeight);

    var rightWidth = $('.coment-content').width() - $("#comment-avatar").width() - 20;
    var imgWidth = $("#comment-avatar").width();
    console.log(rightWidth);
    $(".content-item-top-right").css('width', rightWidth + 'px');
}

function copyUrl(id) {
    var copyObj = document.getElementById(id);
    console.log(copyObj);
    copyObj.select();
    document.execCommand("Copy"); // 执行浏览器复制命令
    $("#copy-success").show();
    setTimeout(function () {
        $("#copy-success").hide()
    }, 1000)
}