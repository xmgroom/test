<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$channel->name}}</title>
</head>
<style>
    html,body{
        height: 100%;
    }
    body{
        background: url({{$channel->guide_image}}) center/cover no-repeat;
    }
</style>

<body>

</body>

<script src="/dist/jquery/jquery.min.js"></script>

<script>
   $(document).on('click touchend','body',function(){
       location.href = '/channel/{{$channel->id}}';
}); 
</script>
</html>