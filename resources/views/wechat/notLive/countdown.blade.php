<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$channel->name}}</title>
</head>
<style>
    html{
        height: 100%;
    }
    body{
        text-align:center;
        background-size:100%;
        background: url(/image/bg_cd.jpg) center/cover no-repeat;
        height: 100%;
    }
    .control-label{
        margin-top: 60%;
        display:inline-block;;
        padding:20px;
        width: 85%;
        background:rgba(8,17,53,.4);
        box-shadow:0 0 20px -2px #2b4675,1px 0 20px -2px #2b4675,-1px 0 20px -2px #2b4675,0 1px 20px -2px #2b4675,0 -1px 20px -2px #2b4675;
        color:#fff;
    }
</style>
<body>

<label class="control-label" id="endTime"></label>


<script src="/dist/jquery/jquery.min.js"></script>
<script>


    window.setInterval(getCountTime, 1000);

    function getCountTime() {

        var date= '{{$channel->countdown_time}}';
        var text = '{{$channel->countdown_text}}';
        var date1=new Date();  //开始时间
        var date2 = new Date(date);
        date2 = date2.getFullYear() > 0 ? date2 : new Date(Date.parse(date.replace(/-/g, "/")));
        console.log(date2);
        var date3=date2.getTime()-date1.getTime(); //时间差的毫秒数
        var leave1=date3%(24*3600*1000);   //计算天数后剩余的毫秒数
        var hours=Math.floor(leave1/(3600*1000));
        var days=Math.floor(date3/(24*3600*1000));
        //计算相差分钟数
        var leave2=leave1%(3600*1000)   ;     //计算小时数后剩余的毫秒数
        var minutes=Math.floor(leave2/(60*1000));
        //计算相差秒数
        var leave3=leave2%(60*1000)   ;   //计算分钟数后剩余的毫秒数
        var seconds=Math.round(leave3/1000);

        var string = '';

        if(days){

            string+=days+"天 ";

        }


        $('#endTime').html(text+": "+string+hours+" 小时 "+minutes+" 分钟 "+seconds+" 秒 ");

    }

</script>
</body>
</html>