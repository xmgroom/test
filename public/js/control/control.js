
    $("#close-live").click(function() {
        if ($(this).attr('src') === '/image/switch_off.png') {
            $(this).attr('src', '/image/switch_on.png');
            $("#close-live-text").html('关闭直播');
        } else {
            $(this).attr('src', '/image/switch_off.png');
            $("#close-live-text").html('开启直播');
        }

    })
    $("#show-comment").click(function() {
        if ($(this).attr('src') === '/image/switch_off.png') {
            $(this).attr('src', '/image/switch_on.png');
            $('#chatRoom').removeClass('hide');
        } else {
            $(this).attr('src', '/image/switch_off.png');
            $('#chatRoom').addClass('hide');
        }
    })



    $("#chat-config").bind('mouseover', function () {
                    $("#config-container").removeClass('hide');
        })
                
    $("#config-container").bind('mouseleave', function () {
                    $("#config-container").addClass('hide');
        })

    $("#chat-config").bind('mouseleave', function (e) {

        if(!(
            $('#chat-config').offset().left< e.clientX 
            &&  e.clientX<$('#chat-config').offset().left +$('#chat-config').width() 
            && e.clientY>$('#chat-config').offset().top+$('#chat-config').height())
        )
            $("#config-container").addClass('hide');

    })


    function getDateTime() {

        var date = new Date();
        var year = date.getFullYear();
        var month = date.getMonth()+1;
        var day = date.getDate();
        var hour = date.getHours();
        var minute = date.getMinutes();
        var second = date.getSeconds();
    return year+'-'+month+'-'+day+' '+hour +':'+minute+':'+second ;

    }
