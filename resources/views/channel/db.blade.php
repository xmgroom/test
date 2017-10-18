<!--     <!DOCTYPE html>  
    <html xmlns="http://www.w3.org/1999/xhtml">  
    <head>  
        <title>HTML5 GetUserMedia Demo</title>  
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />  
    </head>  
    <body>  
    <img src="http://192.168.1.137/yzm">
        <input type="button" title="开启摄像头" value="开启摄像头" onclick="getMedia();" /><br />  
        <video height="120px" autoplay="autoplay"></video><hr />  
        <input type="button" title="拍照" value="拍照" onclick="getPhoto();" /><br />  
        <canvas id="canvas1" height="120px" ></canvas><hr />  
        <input type="button" title="视频" value="视频" onclick="getVedio();" /><br />  
        <canvas id="canvas2" height="120px"></canvas>  
      
        <script type="text/javascript">  
            var video = document.querySelector('video');  
            var audio, audioType;  
      
            var canvas1 = document.getElementById('canvas1');  
            var context1 = canvas1.getContext('2d');  
      
            var canvas2 = document.getElementById('canvas2');  
            var context2 = canvas2.getContext('2d');  
      
            navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;  
            window.URL = window.URL || window.webkitURL || window.mozURL || window.msURL;  
      
            var exArray = []; //存储设备源ID  
            MediaStreamTrack.getSources(function (sourceInfos) {  
                for (var i = 0; i != sourceInfos.length; ++i) {  
                    var sourceInfo = sourceInfos[i];  
                    //这里会遍历audio,video，所以要加以区分  
                    if (sourceInfo.kind === 'video') {  
                        exArray.push(sourceInfo.id);  
                    }  
                }  
            });  
      
            function getMedia() {  
                if (navigator.getUserMedia) {  
                    navigator.getUserMedia({  
                        'video': {  
                            'optional': [{  
                                'sourceId': exArray[1] //0为前置摄像头，1为后置  
                            }]  
                        },  
                        'audio':true  
                    }, successFunc, errorFunc);    //success是获取成功的回调函数  
                }  
                else {  
                    alert('Native device media streaming (getUserMedia) not supported in this browser.');  
                }  
            }  
      
            function successFunc(stream) {  
                //alert('Succeed to get media!');  
                if (video.mozSrcObject !== undefined) {  
                    //Firefox中，video.mozSrcObject最初为null，而不是未定义的，我们可以靠这个来检测Firefox的支持  
                    video.mozSrcObject = stream;  
                }  
                else {  
                    video.src = window.URL && window.URL.createObjectURL(stream) || stream;  
                }  
      
                //video.play();  
      
                // 音频  
                audio = new Audio();  
                audioType = getAudioType(audio);  
                if (audioType) {  
                    audio.src = 'polaroid.' + audioType;  
                    audio.play();  
                }  
            }  
            function errorFunc(e) {  
                alert('Error！'+e);  
            }  
      
              
            // 将视频帧绘制到Canvas对象上,Canvas每60ms切换帧，形成肉眼视频效果  
            function drawVideoAtCanvas(video,context) {  
                window.setInterval(function () {  
                    context.drawImage(video, 0, 0,90,120);  
                }, 60);  
            }  
      
            //获取音频格式  
            function getAudioType(element) {  
                if (element.canPlayType) {  
                    if (element.canPlayType('audio/mp4; codecs="mp4a.40.5"') !== '') {  
                        return ('aac');  
                    } else if (element.canPlayType('audio/ogg; codecs="vorbis"') !== '') {  
                        return ("ogg");  
                    }  
                }  
                return false;  
            }  
      
            // vedio播放时触发，绘制vedio帧图像到canvas  
    //        video.addEventListener('play', function () {  
    //            drawVideoAtCanvas(video, context2);  
    //        }, false);  
      
            //拍照  
            function getPhoto() {  
                context1.drawImage(video, 0, 0,90,120); //将video对象内指定的区域捕捉绘制到画布上指定的区域，实现拍照。  
            }  
      
            //视频  
            function getVedio() {  
                drawVideoAtCanvas(video, context2);  
            }  
      
        </script>  
    </body>  
    </html>  

 <!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>GetUserMedia实例</title>
</head>
<body>
    <video style="width: 100%" id="video" autoplay><ideo>
</body>
<script type="text/javascript">
    var getUserMedia = (navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);
    getUserMedia.call(navigator, {
        video: true,
        audio: true
    }, function(localMediaStream) {
        var video = document.getElementById('video');
        video.src = window.URL.createObjectURL(localMediaStream);
        video.onloadedmetadata = function(e) {
            console.log("Label: " + localMediaStream.label);
            console.log("AudioTracks" , localMediaStream.getAudioTracks());
            console.log("VideoTracks" , localMediaStream.getVideoTracks());
        };
    }, function(e) {
        console.log('Reeeejected!', e);
    });
</script>
<html> -->
<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <form action="http://192.168.1.137/postchannelcreate/17/1/93ee9ebc-a01/JMiS6lrX0MB07i9A7UTu/ce3b4db366f23145e7c41e7c18f03941" method="post" enctype="multipart/form-data">
    姓名：<input type="file" name="logo_file">
    h:<input type="text" name="height">
    w:<input type="text" name="width">
    x:<input type="text" name="x">
    y:<input type="text" name="y">
     name:<input type="text" name="name">
     guanliname:<input type="text" name="manager_id">
      screen:<input type="text" name="screen">
   
   <!--  验证码：<input type="text" name="captcha"> -->
    <button type="submit" >登录</button>
    </form>
</body>
</html>
<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
// window.onload=function(){

// $.ajax({
//         url: "http://192.168.1.137/apilogin",              //请求地址
//          // url: "http://192.168.1.137/yzm",  
//         type: "post",                       //请求方式
//         data: { name: "lincy444@126.com", password: 'arcvideo'},        //请求参数
//         dataType: "json",
//         success: function (msg) {
//             // 此处放成功后执行的代码
//             console.log(msg);
//         },
//         fail: function (status) {
//             alert('111');
//             // 此处放失败后执行的代码
//         }
//     });
// }
 

</script>
