@extends('framework.main')

@section('content')

    <link rel="stylesheet" href="/css/temp.css">
    <link rel="stylesheet" href="/css/list.css">
    <link rel="stylesheet" type="text/css" href="http://demo.htmleaf.com/1411/201411071844/css/colpick.css"/>
    <div class="container-fluid no-padding  main-box">
        <div class="main-title">
            <p>观看地址: <span id="address">{{Config::get('app.url') . '/lists/' . $list->id}}</span>
                <span data-clipboard-action="copy" data-clipboard-target="#address" class="btn btn-default copyaddress">复制</span>
                <span class="btn btn-default" onmouseover="toggleErweima()" onmouseout="hideErweima()">二维码</span></p>
            <div id="erweima" class="title-erweima hide">
                {!! QrCode::size(180)->generate(Config::get('app.url') . '/lists/' . $list->id) !!}
            </div>
        </div>
        <div class="main-body">
            <div class="main-body-left">
                <ul>
                    <li class="add-live">
                        <a class="add" href="/list">新建列表页</a>
                    @foreach($lists as $value)
                        <li style="position:relative">
                            <a href="/list/{{$value->id}}">{{$value->name}}</a>
                            <img onclick="removeList({{$value->id}})"
                                 style="cursor:pointer;position: absolute;right: 10px;top: 30%;" src="/image/remove.png"
                                 alt="" width="20px" height="20px">
                        </li>
                    @endforeach

                </ul>
            </div>
            <div class="main-body-right" id="videoLists">
                <div class="main-body-right-left">
                    <form class="form-horizontal" method="post" action="/list/{{$list->id}}" id="list_form"
                          enctype="multipart/form-data">

                        <input name="lists" id="lists" type="hidden">
                        <input type="hidden" name="item_color" id="item_color" value='{{$list->item_color}}'>
                        <input type="hidden" name="background_color" id="background_color" value='{{$list->background_color}}'>


                        <div class="main-body-right-left-body">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">页面标题</label>
                                <div class="col-sm-10">
                                    <input id="title" type="text" class="form-control" name="name" value="{{$list->name}}">
                                </div>
                            </div>
                                <div class="form-group">
                                <label  class="col-sm-2 control-label">底图颜色</label>
                                <div class="col-sm-10">
                                    <div class="col-sm-6">
                                        <div id="back-color" style="width:80px;height:80px;background:{{$list->background_color}};"></div>
                                    </div>
                                    <div class="col-sm-6">
                                        <span class="col-sm-4" style="font-weight:600">色块颜色</span>
                                        <div class="col-sm-8" id="block-color" style="width:80px;height:80px;background:{{$list->item_color}};"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label">顶部图片</label>
                                <div class="col-sm-10">
                                    <p>建议宽高750*300px</p>
                                    <div class="col-sm-12 posr" style="padding: 0;">
                                        <img id="top-img" src="{{$list->head_img}}" width="100%" height="300px" alt="">
                                        <label for="top-img-upload" class="posa img-mask"></label>
                                        <input onchange="preview(this,'top-img')" name="head_img_file" type="file"
                                               style="display: none;" id="top-img-upload">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label">底部图片</label>
                                <div class="col-sm-10">
                                    <p>建议宽高750*200px</p>
                                    <div class="col-sm-12 posr" style="padding: 0;">
                                        <img id="bottom-img" src="{{$list->foot_img}}" width="100%" height="200px"
                                             alt="">
                                        <label for="bottom-img-upload" class="posa img-mask1"></label>
                                        <input onchange="preview(this,'bottom-img')" name="foot_img_file" type="file"
                                               style="display: none;" id="bottom-img-upload">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label">视频</label>
                                <div class="col-sm-10">
                                    <div class="bottom-img isAdd" v-for="video in videos">
                                        <div class="img-box">
                                            <input class="img-check form-check" type="checkbox"
                                                   v-bind:value="video.resource_id">
                                            <img v-bind:src="video.img" width="100%%" height="100%" alt="">
                                        </div>
                                        <span class="bottom-text">
                                                    <h5>名称:<span>@{{video.name}}</span></h5>
                                                    <p>简介: <span v-html="video.content"></span></p>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10" style="text-align:center;">
                                    <span data-toggle="modal" data-target="#myModal" class="btn btn-default"
                                          style="margin-right:20px;">添加</span>
                                    <span class="btn btn-default" onclick="removeImage()">删除</span>
                                </div>
                            </div>

                        </div>
                        <div class="form-group" style="margin:0;border-top:1px solid #ccc;">

                            <div class="col-sm-10 col-sm-offset-2"
                                 style="text-align:center;margin-top:40px;padding-right:10%;">
                                <button type="button" class="btn center-block" onclick="list_submit()"
                                        style="background:#15ae8f;color:#fff;width:100px;margin-bottom: 40px;">提交
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="main-body-right-right">
                    <div class="body-right-body">
                        <p>预览效果</p>
                        <div class="mobile" style="background:url(/image/mobile.png) left top no-repeat">
                            <div class="preview-box" style="background:{{$list->background_color}}">
                                <img id="top" src="{{$list->head_img}}" width="100%" alt="">
                                <div v-if="videos.length">
                                    <ul id="preview-list" v-for="video in videos">
                                        <li :style="{backgroundColor:itemColor}"><img v-bind:src="video.img" height="48px" width="70px" alt="">
                                            <span><p>名称: <span>@{{video.name}}</span></p>
                                            <p>简介: <span v-html="video.content"></span></p>
                                        </span>
                                            <span class="btn" v-if="video.type==1">视频</span>
                                            <span class="btn" v-if="video.type==2">直播</span>
                                        </li>
                                    </ul>
                                </div>

                                <img id="bottom" src="{{$list->foot_img}}" width="100%" alt="">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">新建素材</h4>
                </div>
                <div class="modal-body">
                    <div>

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs typeTab" role="tablist">
                            <li role="presentation" class="active"><a href="#modalVideoLists"
                                                                      aria-controls="modalVideoLists" role="tab"
                                                                      data-toggle="tab">视频</a></li>
                            <li role="presentation"><a href="#modalChannelLists" aria-controls="modalChannelLists"
                                                       role="tab"
                                                       data-toggle="tab">直播</a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content container-fluid">
                            <div role="tabpanel row" class="tab-pane active" id="modalVideoLists">
                                <div class="col-sm-12" v-for="video in videos">
                                    <div class="bottom-img" style="width:48.8888%;height:90px;" onclick="javascript:$(this).find('.img-check').trigger('click');">
                                        <div class="img-box">
                                            <input class="img-check" type="checkbox" v-model="video.state"
                                                   @click="addToVideoList(video.state,video.id,video.thumbnail,video.title,video.title)">
                                            <img v-bind:src="video.thumbnail" width="100%" height="100%" alt="">
                                        </div>
                                        <span class="bottom-text">
                                                    <h5>名称:<span>@{{ video.title }}</span></h5>
                                                    <p>简介: <span v-html="video.content"></span></p>
                                               </span>
                                    </div>

                                </div>
                            </div>
                            <div role="tabpanel row" class="tab-pane" id="modalChannelLists">
                                <div class="col-sm-12" v-for="channel in channels">
                                    <div class="bottom-img" style="width:48.8888%;height:90px" onclick="javascript:$(this).find('.img-check').trigger('click');">
                                        <div class="img-box">
                                            <input class="img-check" type="checkbox" v-model="channel.state"
                                                   @click="addToVideoList(channel.state,channel.id,channel.logo,channel.name,channel.content)">
                                            <img v-bind:src="channel.logo" width="100%%" height="100%" alt="">

                                        </div>
                                        <span class="bottom-text">
                                                    <h5>名称:<span>@{{channel.name}}</span></h5>
                                                    <p>简介: <span v-html="channel.content"></span></p>
                                               </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                           <span  class="btn btn-default" onclick="allSelect();">全选</span>              
                    <button type="button" class="btn" style="background:rgb(21, 174, 143);color:#fff;" data-dismiss="modal">添加</button>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="/dist/jquery/jquery.min.js"></script>
        <script src="/dist/colorPicker/colpick.js" type="text/javascript"></script>
    <script src="/dist/clipboard/clipboard.min.js"></script>
    <script src="/dist/vue/vue.js"></script>
    <script src="/js/resize.js"></script>

    <script>
        function allSelect(){
            var materialLists1 = $(".typeTab").find('li')[0];
            var materialLists2 = $(".typeTab").find('li')[1];
            if($(materialLists1).hasClass('active')){
                var videoList = $("#modalVideoLists")[0];
                $(videoList).find(".img-check").each(function(){
                      if(!$(this).prop('checked')){
                         $(this).trigger("click");
                    }
                })
            }else if($(materialLists2).hasClass('active')){
                var videoList = $("#modalChannelLists")[0];
                $(videoList).find(".img-check").each(function(){
                    if(!$(this).prop('checked')){
                         $(this).trigger("click");
                    }
                })
            }
        }

        var videoLists = new Vue({
            el: '#videoLists',
            data: {
                videos:{!! $list->lists !!},
                itemColor:"{{$list->item_color}}"
            }
        });

        var modalChannelLists = new Vue({
            el: '#modalChannelLists',
            data: {
                channels: {!! $channels !!}
            },
            methods: {
                addToVideoList: function (state, id, img, name, intro) {

                    if (state) {
                        videoLists.videos.push({
                            "resource_id": id,
                            "img": img,
                            "name": name,
                            "content": intro,
                            "type": 2
                        });
                    } else {
                        videoLists.videos.find(function (value, index, arr) {
                            try{
                             if (value.resource_id == id) arr.splice(index, 1);                                
                            }catch(e){
                                
                            }
                        });
                    }
 event.cancelBubble = true; 

                }
            }
        });

        var modalVideoLists = new Vue({
            el: '#modalVideoLists',
            data: {
                videos: {!! $videos !!}
            },
            methods: {
                addToVideoList: function (state, id, img, name, intro) {

                    if (state) {
                        videoLists.videos.push({
                            "resource_id": id,
                            "img": img,
                            "name": name,
                            "content": intro,
                            "type": 1
                        });
                    } else {

                        videoLists.videos.find(function (value, index, arr) {
                            try{
                                if (value.resource_id == id) arr.splice(index, 1);                            
                            }
                            catch(e){
                                
                            }
                        });
                    }
 event.cancelBubble = true; 


                }
            }
        });

        function toggleErweima() {
            $("#erweima").removeClass('hide');
        }
        function hideErweima() {
            $("#erweima").addClass('hide');
        }
        $(function () {
            setTopBottomImage()
            var boxHeight = $('.mobile').height();
            var bottomHeight = $("#bottom").height();
            var bottomTop = boxHeight - bottomHeight;
            $("#bottom").css('top', bottomTop).css('position', 'absolute');


        })

        function removeImage() {
            $(".form-check").each(function () {
                if ($(this).prop('checked')) {
                    var id = $(this).val();
                    videoLists.videos.find(function (value, index, arr) {

                        try {

                            if (value.resource_id == id) arr.splice(index, 1);

                        } catch (e) {


                        }

                    });
                }
                ;

            });


        }

        function list_submit() {

            if($("#title").val() && videoLists.videos.length){
                $('#lists').val(JSON.stringify(videoLists.videos));

                $('#list_form').submit();
            }else{
                iziToast.error({
                    message:"列表标题和视频不能为空",
                    ballon:true,
                    timeout:3000,
                    position:'topCenter'
                });
            }

        }

        function preview(file, id) {
            if (file.files && file.files[0]) {
                var reader = new FileReader();
                reader.onload = function (evt) {
                    $('#' + id).attr('src', evt.target.result);
                    setTopBottomImage()
                };
                reader.readAsDataURL(file.files[0]);
            }
            else {
                alert("上传失败,请重试");
            }
        }
        function removeList(id) {
            $.ajax({
                url: "/list/" + id,
                data: {
                    id
                },
                type: "DELETE",
                success(res){
                    if (res.code == 0) {
                        iziToast.warning({
                            message: "列表删除成功！",
                            ballon: true,
                            timeout: 3000,
                            position: 'topRight'
                        });
                        location.href = "/list"
                    } else {
                        iziToast.warning({

                            message: "列表删除失败",
                            ballon: true,
                            timeout: 3000,
                            position: 'topRight'
                        });
                    }
                }
            })
        }

        function setTopBottomImage() {
            $("#top").attr('src', $("#top-img").attr("src"))
            $("#top").attr('width', $(".preview-box").width() + 'px').attr('height', $(".preview-box").width() / 2.5 + 'px');
            $("#bottom").attr('width', $(".preview-box").width() + 'px').attr('height', $(".preview-box").width() / 3.8 + 'px');
            $("#bottom").attr('src', $("#bottom-img").attr("src"))

        }

        $(function () {
            var clipboard3 = new Clipboard('.copyaddress');
            clipboard3.on('success', function (e) {
                console.log(e);
            });
            clipboard3.on('error', function (e) {
                console.log(e);
                alert("复制失败！请手动复制")
            });
        });
    </script>
    <script>
         $("#back-color").colpick({
            layout:'rgbhex',
            color:"{{$list->background_color}}",
            colorScheme:'dark',
            onSubmit:function(res){
                var color = '#'+RGBToHex(HSBToRGB(res));
                $("#back-color").css('background',color);
                $("#background_color").val(color);
                $(".preview-box").css('background',color);
            },
            submitText:'确认'
        });
        $("#block-color").colpick({
            layout:'rgbhex',
            color:"{{$list->item_color}}",
            colorScheme:'dark',
            onSubmit:function(res){
                var color = '#'+RGBToHex(HSBToRGB(res));
                $("#block-color").css('background',color);
                $("#item_color").val(color);
                videoLists.itemColor = color;


            },
            submitText:'确认'
        });

          function HSBToRGB (hsb) {
              var rgb = {};
              var h = Math.round(hsb.h);
              var s = Math.round(hsb.s * 255 / 100);
              var v = Math.round(hsb.b * 255 / 100);
              if (s == 0) {
                  rgb.r = rgb.g = rgb.b = v;
              } else {
                  var t1 = v;
                  var t2 = (255 - s) * v / 255;
                  var t3 = (t1 - t2) * (h % 60) / 60;
                  if (h == 360) h = 0;
                  if (h < 60) {
                      rgb.r = t1;
                      rgb.b = t2;
                      rgb.g = t2 + t3
                  }
                  else if (h < 120) {
                      rgb.g = t1;
                      rgb.b = t2;
                      rgb.r = t1 - t3
                  }
                  else if (h < 180) {
                      rgb.g = t1;
                      rgb.r = t2;
                      rgb.b = t2 + t3
                  }
                  else if (h < 240) {
                      rgb.b = t1;
                      rgb.r = t2;
                      rgb.g = t1 - t3
                  }
                  else if (h < 300) {
                      rgb.b = t1;
                      rgb.g = t2;
                      rgb.r = t2 + t3
                  }
                  else if (h < 360) {
                      rgb.r = t1;
                      rgb.g = t2;
                      rgb.b = t1 - t3
                  }
                  else {
                      rgb.r = 0;
                      rgb.g = 0;
                      rgb.b = 0
                  }
              }
              return {r: Math.round(rgb.r), g: Math.round(rgb.g), b: Math.round(rgb.b)};
          }

         function RGBToHex (rgb) {
            var hex = [
                rgb.r.toString(16),
                rgb.g.toString(16),
                rgb.b.toString(16)
            ];
            $.each(hex, function (nr, val) {
                if (val.length == 1) {
                    hex[nr] = '0' + val;
                }
            });
            return hex.join('');
        }
    </script>
@endsection