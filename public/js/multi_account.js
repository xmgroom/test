var user_id = 0;
var ids = "";
var channelLogo="'123'",channelName="123";
$("#addChannel").click(function () {
    ids = $("#channel_id").selectivity('value');
    $.post("/multi_account/api/add",

        {user_id: user_id,
            ids: ids
        }, function (res) {
        if (res.code == 0) {
            $("#addAlert").removeClass('hide').text(res.message)
        } else {
            $("#addAlert").removeClass('hide').text(res.message).removeClass('alert-info').addClass('alert-warning')
        }
    }, "json")
})
$("#getChannel").click(function () {
    $.post("/multi_account/api/get",
        {
            id: user_id
        },
        function (res) {
            console.log(res);
           for(var i=0;i<res.length;i++){
               channelName=res[i].name;
               channelLogo=res[i].logo;
               var li = "<li><div class='modal-search'><img width='100px' height='100px' src=" +channelLogo+ " class='search-img'> <p class='search-name'>"+channelName+"</p> </div> </li>"
               $(".search-list").append(li);
           }
        }
    )
})