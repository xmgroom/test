<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>虹直播</title>
    <link rel="stylesheet" href="/dist/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/dist/fontAwesome/font-awesome.min.css">
    <link rel="stylesheet" href="/css/temp.css">
    <link rel="stylesheet" href="/css/organize.css">

</head>
<body>
<div class="container-fluid no-padding fs12" style="background:#ebf1f4;height:100%;">
    <div class="topbar">
        <img src="/image/logo.png" width="160px" height="40px" alt="">

        <span class="top-info">
                <span class="btn top-btn" onclick="toggleTool(this)">管理</span>
            </span>

    </div>
    <div class="container-fluid no-padding box-container">

        <a onclick="javascript: location.href = (!edit) ? '/company/0' : '#'" style="text-decoration: none;">
            <div class="organizes" style="background: url(http://wx.qlogo.cn/mmopen/hvr6DDkCA6xqT8jMPdGxRVvHybT8CZ08nWj0XQEC8x9OmGjIE6xcuBTyWEKKWwAaicV8sZYMh7liaQpkfAeWbl5Pu5fdE4OcXU/0) no-repeat;background-size:100%">
                <p class="title">虹直播</p>
            </div>
        </a>

        @foreach($companies as $company)
            <a onclick="javascript: location.href = (!edit) ? '/company/{{$company->id}}' : '#'" style="text-decoration: none;">
                <div class="organizes" style="background: url({{$company->logo}}) no-repeat;background-size:100%">
                    <img src="/image/close-o.png" class="organizes-remove hide" data-id="{{$company->id}}" alt="" data-name="{{$company->name}}">
                    <p class="title">{{$company->name}}</p>
                    <div id="editOrg" onclick="editOrganize(this)" class="organizes-mask hide" data-id="{{$company->id}}" data-logo="{{$company->logo}}" data-name="{{$company->name}}"></div>
                </div>
            </a>

        @endforeach

        <a href="{{$url}}" class="add-organize"></a>


    </div>

</div>


<!-- Modal -->
<div class="modal fade" id="removeOrganize" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <form action="/company/delete" method="post">
            <input id="delete_id" name="id" type="hidden">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">提示</h4>
                </div>
                <div class="modal-body tac" style="padding: 40px;">
                    确定要解绑<span id="delete_name"></span>？
                </div>
                <div class="modal-footer tac">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-danger">确认</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="editOriganize" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="/company/update" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit-id">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">编辑组织</h4>
                </div>
                <div class="modal-body" style="padding: 20px 40px;">
                <span class="edit-img-container">
                    <img class="edit-img" src="" alt="" width="100px" height="100px" id="edit-image">
                    <label for="edit-img" class="edit-img-mask hide"></label>
                    <input type="file" id="edit-img" class="hide" onchange="preview(this)" name="logo">
                </span>
                    <span class="edit-input-container">
                     <input type="text" class="form-control" placeholder="名称" name="name" id="edit-name">
                </span>
                </div>
                <div class="modal-footer tac">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-default">确认</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="/dist/jquery/jquery.min.js"></script>
<script src="/dist/bootstrap/js/bootstrap.min.js"></script>
<script>

    var edit = false;

    $(".organizes-remove").each(function () {
        $(this).bind('click', function () {

            $('#delete_name').html($(this).attr('data-name'));
            $('#delete_id').val($(this).attr('data-id'));
            $('#removeOrganize').modal()
            return false;
        })
    })

    $(".edit-img-container").each(function () {
        $(this).bind('mouseover', function () {
            $(this).find('.edit-img-mask').removeClass('hide');
        })
        $(this).bind('mouseout', function () {
            $(this).find('.edit-img-mask').addClass('hide');
        })
    })
    function toggleTool(obj) {
        var obj = $(obj);
        if (obj.html() == "管理") {
            edit = true;
            obj.html('切换组织');
            $(".organizes-remove").each(function () {
                $(this).removeClass('hide');
            })
            $(".organizes").each(function () {
                $(this).bind('mouseover', function () {
                    $(this).find('.organizes-mask').removeClass('hide');
                })
                $(this).bind('mouseout', function () {
                    $(this).find('.organizes-mask').addClass('hide');
                })
            })
        } else {
            edit = false;
            obj.html('管理');
            $(".organizes-remove").each(function () {
                $(this).addClass('hide');
            })
            $(".organizes").each(function () {
                $(this).bind('mouseover', function () {
                    $(this).find('.organizes-mask').addClass('hide');
                })

            })
        }
    }
    function editOrganize(obj) {
        $('#edit-image').attr('src',$(obj).attr('data-logo'));
        $('#edit-name').val($(obj).attr('data-name'));
        $('#edit-id').val($(obj).attr('data-id'));

       $('#editOriganize').modal();
    }

    function preview(file) {
        if (file.files && file.files[0]) {
            var reader = new FileReader();
            reader.onload = function (evt) {
                $("#edit-image").attr('src',evt.target.result);
            };
            reader.readAsDataURL(file.files[0]);
        }
        else {
            alert("上传失败,请重试");
        }
    }
</script>
</body>

</html>