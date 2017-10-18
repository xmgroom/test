$("#read").click(function() {
    if ($('#read').prop('checked')) {
        $('#add').addClass('hide');
        $('#add1').removeClass('hide');
    } else {
        $('#add').removeClass('hide');
        $('#add1').addClass('hide');
    }
})

// for(var i = 0; i < $(".tops").length;i++){
//     console.log($(".tops"));
//     $(".tops")[i].hover(function() {
//         $(".control")[i].removeClass('hide')
//     })
//     $(".tops")[i].mouseleave(function() {
//         $(".control")[i].addClass('hide')
//
//     })
// }

$(".tops").each(function (index) {
      $(this).bind('mouseover',function () {
            $(this).find('.control').removeClass('hide');
      });
    $(this).bind('mouseleave',function () {
        $(this).find('.control').addClass('hide');
    })
})


function startLive() {
    $.post(url, data, function(res) {
        console.log(res);
    }, JSON)
}

function stopLive() {
    $.post(url, data, function(res) {
        console.log(res);
    }, JSON)

};
