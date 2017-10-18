@extends('framework.main')

@section('content')
    <link rel="stylesheet" href="/css/material.css">
    <link rel="stylesheet" href="/dist/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/dist/fileupload/css/jquery.fileupload.css">
    <div class="container-fluid main-box">
        <div class="main-top row">
            <div class="col-xs-3 top-item">
                <img src="/image/combo.png" alt="">
                <span class="main-box-right right">
                            <h2 style="color:#e74b3c">{{$combo}}</h2>
                            <p style="text-align:right">套餐类型</p>
                        </span>
            </div>
            <div class="col-xs-3 top-item">
                <img src="/image/channel.png" alt="">
                <span class="main-box-right right">
                            <h2 style="color:#3fbd8c">{{$channels_count}}</h2>
                            <p style="text-align:right">建立频道数(个)</p>
                        </span>
            </div>
            <div class="col-xs-3 top-item">
                <img src="/image/video.png" alt="">
                <span class="main-box-right right">
                            <h2 style="color:#e166b8">{{$videos_count}}</h2>
                            <p style="text-align:right">视频个数(个)</p>
                        </span>
            </div>
            <div class="col-xs-3 top-item">
                <img src="/image/space.png" alt="">
                <span class="main-box-right right">
                            <h2 id="kongjian" style="color:#6259c6"><span>{{$usedSpace}}</span>/{{$totalSpace}}G</h2>
                        <p style="text-align: right;">已用空间(G)</p>
                        </span>
            </div>
        </div>
        <div class="main-body row">
            <div class="main-body-head">
                        <span>
                           <button class="btn" onclick="javascript:$('#uploadModal').modal()">上传视频</button>
                           <button class="btn" onclick="delete_videos()">删除</button>
                           <button id="selectAll" class="btn" onclick="selectAll()">全选</button>
                           <button id="cancelSelect" class="btn" style="display:none;"
                                   onclick="cancelSelect()">全选</button>
                        </span>
                <span>
                            <p id="file_text" style="display: inline-block; margin-left: 20px;color: #ccc;">单文件小于2GB</p>
                        </span>

                <span class="main-body-head-right form-inline">
                                <form action="/material/search" method="post">

                                <div class="form-group">
                                     <label>排序:</label>
                                       <select class="form-control sort-select" onchange="orderRedirect(this.value)">
                                        <option value="1">默认排序</option>
                                        <option value="2" @if(isset($type)) @if($type==2) selected @endif  @endif)>按大小</option>
                                        </select>
                                </div>
                                <div class="form-group">
                                    @if(isset($type))
                                        @if($type==3)
                                            <a href="/material/4"><img style=" cursor: pointer;" src="/image/sort_n.png"
                                                                       alt=""></a>
                                        @else
                                            <a href="/material/3"><img style=" cursor: pointer;" src="/image/sort_n.png"
                                                                       alt=""></a>
                                        @endif
                                    @endif
                                </div>
                                <div class="form-group" style="margin-left:20px;">
                                    <label>创建时间:</label>
                                    <input class="form-control" style="height:28px;" type="text" id="timer" name="date" value="{{Session::pull('material.date')}}">
                                     <span onclick="location.href='/material';"
                                          style="height:28px;line-height:14px;font-weight: 600;border-radius: 0;"
                                          class="btn btn-default"><i class="fa fa-refresh"></i></span>
                                </div>
                                    <div class="form-group" style="margin-left:30px;">
                                        <input class="form-control" style="height:28px;" type="text" name="name" value="{{Session::pull('material.name')}}">
                                        <button type="submit" style="height:28px;line-height:14px;font-weight: 600;"
                                            class="btn btn-default">搜索</button>
                                    </div>
                                </form>
                    </span>
            </div>
            <div class="main-body-content">
                @foreach($videos as $key=>$video)
                    <div class="content-item" id="{{$video->video_id}}">
                        <div class="content-item-top">
                            <img src="{{$video->thumbnail}}" onerror="changeImage(this)" width="30%" height="80px"
                                 alt="">
                            <div style="display: none">{{$sec = ($video->duration /1000 % 60)}}</div>
                            <span class="content-item-top-right">
                                                        <h4 style="overflow: hidden;height: 33px;font-size: 16px;font-family: '微软雅黑';word-break: break-all;margin: 0px 0 10px 0;">{{$video->title}}</h4>
                                                        <p style="margin-bottom:10px;">上传时间: <span>{{$video->date}}</span></p>
                                                <p>时长:<span> {{(int) ($video->duration / 60000 )}}:{{ $sec> 9 ?$sec: '0' .$sec }}</span>
                        </p>
                        </span>
                        </div>
                        <div class="add-address hide">
                            <a onclick="play('{{$video->id}}')">预览</a>
                            <a onclick="insetAddress({{$key}})">嵌入地址</a>
                            {{--<a  onclick="insetAddress(1,2,3)">嵌入地址</a>--}}
                        </div>

                        <textarea id="htmlEmbedCode-{{$key}}"
                                  style="display: none;">{!! $video->htmlEmbedCode !!}</textarea>
                        <textarea id="flashUrl-{{$key}}"
                                  style="display: none;">{!! $video->flashUrl !!}</textarea>
                        <textarea id="previewUrl-{{$key}}"
                                  style="display: none;">{!! $video->previewUrl !!}</textarea>

                    </div>
                @endforeach

            </div>
        </div>
    </div>

    <div class="modal fade" id="insertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">嵌入地址</h4>
                </div>
                <div class="modal-body">
                    <div>

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#iframe-address"
                                                                      aria-controls="iframe-address" role="tab"
                                                                      data-toggle="tab">iframe嵌入地址</a></li>
                            <li role="presentation"><a href="#flash-address" aria-controls="flash-address" role="tab"
                                                       data-toggle="tab">Flash 地址</a></li>
                            <li role="presentation"><a href="#h5-address" aria-controls="h5-address" role="tab"
                                                       data-toggle="tab">H5 播放地址</a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="iframe-address">
                                <textarea name="" id="iframe-text" cols="70" rows="10"></textarea>
                                <button type="button" onclick="copyText(this)" class="btn btn-default"
                                        style="float:left;">复制
                                </button>

                            </div>
                            <div role="tabpanel" class="tab-pane" id="flash-address">
                                <textarea name="" id="flash-text" cols="70" rows="10"></textarea>
                                <button type="button" onclick="copyText(this)" class="btn btn-default"
                                        style="float:left;">复制
                                </button>

                            </div>
                            <div role="tabpanel" class="tab-pane" id="h5-address">
                                <textarea name="" id="h5-text" cols="70" rows="10"></textarea>
                                <button type="button" onclick="copyText(this)" class="btn btn-default"
                                        style="float:left;">复制
                                </button>

                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer" style="margin-top:40px;">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="playModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="height:400px;width:600px;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            id="playModalClose"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">预览</h4>
                </div>
                <div class="modal-body" style="width:100%;height:85%;">
                    <iframe id="play_src" frameborder='no' marginheight='0' marginwidth='0' allowTransparency='true'
                            width="100%" height="100%"></iframe>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            id="playModalClose"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">上传素材</h4>
                </div>

                <div class="modal-body">
                    <div class="alert alert-info" id="upload_info" style="display: none">

                    </div>
                    <div id="progress" class="progress">
                        <div class="progress-bar progress-bar-success"></div>
                    </div>
                    <div class="form-group 1" id="upload_div">
                        <label for="logo" class="col-sm-2 control-label">视频文件</label>
                        <div class="col-sm-8">
                            <input type="file"  name="file" id="videoFile">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="margin-top:40px;">

                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="javascript:location.href = location.href;">关闭</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModel">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">提示信息</h4>
                </div>
                <div class="modal-body">
                    <p>您确认要删除吗？</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="url"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <a onclick="delete_videos_check()" class="btn btn-danger" data-dismiss="modal">确定</a>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <script src="/dist/jquery/jquery.min.js"></script>
    <script src="/dist/daterangepicker/moment.js"></script>
    <script src="/dist/daterangepicker/daterangepicker.js"></script>
    <script src="/dist/fileupload/js/vendor/jquery.ui.widget.js"></script>
    <script src="/dist/fileupload/js/jquery.iframe-transport.js"></script>
    <script src="/dist/fileupload/js/jquery.fileupload.js"></script>
    <script src="/js/home.js"></script>

    <script type="text/javascript">

        function changeImage(obj) {
            $(obj).attr('src', '/image/logo.png');
        }
        function copyText(obj) {
            var copyObj = $(obj).prev();
            copyObj.select();
            document.execCommand("Copy"); // 执行浏览器复制命令
        }

        function orderRedirect(type) {
            location.href = "/material/" + type;
        }

        function insetAddress(key) {


            $("#iframe-text").val($('#htmlEmbedCode-' + key).val());
            $("#flash-text").val($('#flashUrl-' + key).val());
            $("#h5-text").val($('#previewUrl-' + key).val());

            $("#insertModal").modal();
        }
        $('#timer').daterangepicker({

            maxDate: moment(), //最大时间
            dateLimit: {
                days: 30
            }, //起止时间的最大间隔
            showDropdowns: true,
            showWeekNumbers: false, //是否显示第几周
            timePicker: false, //是否显示小时和分钟
            ranges: {
                //'最近1小时': [moment().subtract('hours',1), moment()],
                '今日': [moment().startOf('day'), moment()],
                '昨日': [moment().subtract('days', 1).startOf('day'), moment().subtract('days', 1).endOf('day')],
                '最近7日': [moment().subtract('days', 6), moment()],
                '最近30日': [moment().subtract('days', 29), moment()]
            },
            opens: 'right', //日期选择框的弹出位置
            buttonClasses: ['btn btn-default'],
            applyClass: 'btn-small btn-primary blue',
            cancelClass: 'btn-small',
            format: 'YYYY-MM-DD ', //控件中from和to 显示的日期格式
            separator: ' to ',
            locale: {
                applyLabel: '确定',
                cancelLabel: '取消',
                fromLabel: '起始时间',
                toLabel: '结束时间',
                customRangeLabel: '自定义',
                daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
                monthNames: ['一月', '二月', '三月', '四月', '五月', '六月',
                    '七月', '八月', '九月', '十月', '十一月', '十二月'
                ],
                firstDay: 1
            }
        }, function (start, end, label) { //格式化日期显示框

            $('#reportrange span').html(start.format('YYYY-MM-DD HH:mm:ss') + ' - ' + end.format('YYYY-MM-DD HH:mm:ss'));
        });

        function play(id) {
            $("#play_src").attr('src', '/api/video/' + id);

            $("#playModal").modal();

        }

        $('#playModalClose').click(function () {
            $("#play_src").attr('src', 'about:blank');

        });
    </script>
    <script>
        var items = [];

        $(".content-item").each(function (index) {
            $(this).bind('mouseover', function () {
                $(this).find('.add-address').removeClass('hide');
            });
            $(this).bind('mouseleave', function () {
                $(this).find('.add-address').addClass('hide');
            })
            $(this).bind('click', function () {
                if ($(this).hasClass('choosed')) {
                    $(this).removeClass('choosed');
                    items.splice(items.indexOf(this.id), 1);
                } else {
                    $(this).addClass('choosed');
                    items.push(this.id);
                }
            })
        });

        function selectAll() {
            $(".content-item").each(function (index) {
                if ($(this).hasClass('choosed')) {
                    $(this).removeClass('choosed');
                    items.splice(items.indexOf(this.id), 1);

                } else {
                    items.push(this.id);
                    $(this).addClass('choosed');
                    $("#selectAll").hide();
                    $("#cancelSelect").show();

                }
            })
        }
        function cancelSelect() {
            $(".content-item").each(function (index) {
                items.splice(items.indexOf(this.id), 1);

                $(this).removeClass('choosed');
                $("#cancelSelect").hide();
                $("#selectAll").show();
            })
        }
        function delete_videos() {
            $('#deleteModel').modal();

        }
        function delete_videos_check() {

            var str = '';

            items.forEach(function (item) {
                str = str + item + ',';
            });

            str = str.substring(0, str.length - 1);

            $.post('/api/video/delete', {'videos': str}, function (res) {
                if (res.code == 0) {

                    items.forEach(function (item) {

                        $('#' + item).remove();

                    });
                     iziToast.warning({
                             
                            message:"视频删除成功",
                            ballon:true,
                            timeout:3000,
                            position:'topRight'
                     });
                }else{
                     iziToast.warning({
                             
                            message:"视频删除失败",
                            ballon:true,
                            timeout:3000,
                            position:'topRight'
                     });
                }
            })
        }

        $('#videoFile').fileupload({
            url: "http://upload.hongshiyun.net/multipart/upload",
            dataType: 'json',
            formData: {
                token: "{{$token}}",
                isEncrypt: "0",
                categoryId: "1",
            },
            start:function () {
                $("#videoFile").attr('disabled','disabled');

            },
            done: function (e, data) {
                if (data.result.code == 0) {
                    $.post('/api/video/upload_info/{{Session::get('company:id')}}/{{Auth::user()->id}}', data.result.result[0], function (res) {
                        $('#upload_info').html(res.message);
                    $("#videoFile").removeAttr('disabled');                                                                    
                        $('#upload_info').show();
                        setTimeout(function(){
                            $("#uploadModal").modal('hide');
                            location.reload();
                        },1500)
                    });

                } else {
                    $('#upload_info').html('上传失败');
                    $('#upload_info').show();
                }
            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
                );
            }
        });

        alphaResize();

        function alphaResize(){
            $("body").css('height',document.documentElement.clientHeight )
            $('.box-container').css('height',document.body.offsetHeight-20+'px')
            var containerHeight = $(".box-container").height();
            $('.left-menu').height($('.box-container').height() );
            var topHeight = $(".main-top").height();
            var headHeight = $(".main-body-head").height();
            $(".main-body-content").height(containerHeight - topHeight - headHeight - 70)
        }
        window.onresize = function(){
            alphaResize();
        }

    </script>
@endsection
