   $previews = $('.preview');
        function preview(file) {
            if (file.files && file.files[0]) {
                var reader = new FileReader();
                reader.onload = function (evt) {
                    $("#edit-logo").attr('src', evt.target.result);
                    $("#prev").attr('src', evt.target.result);
                    $('#edit-logo').cropper({
                        ready: function (e) {

                            var $clone = $(this).clone().removeClass('cropper-hidden');

                            $clone.css({
                                display: 'block',
                                width: '100%',
                                minWidth: 0,
                                minHeight: 0,
                                maxWidth: 'none',
                                maxHeight: 'none'
                            });

                            $previews.css({
                                width: '100%',
                                overflow: 'hidden'
                            }).html($clone);
                        },

                        crop: function (e) {
                            var imageData = $(this).cropper('getImageData');
                            var previewAspectRatio = e.width / e.height;

                            console.log(e);


                            $('#x').val(e.x);
                            $('#y').val(e.y);
                            $('#height').val(e.height);
                            $('#width').val(e.width);


                            $previews.each(function () {
                                var $preview = $(this);
                                var previewWidth = $preview.width();
                                var previewHeight = previewWidth / previewAspectRatio;
                                var imageScaledRatio = e.width / previewWidth;

                                $preview.height(previewHeight).find('img').css({
                                    width: imageData.naturalWidth / imageScaledRatio,
                                    height: imageData.naturalHeight / imageScaledRatio,
                                    marginLeft: -e.x / imageScaledRatio,
                                    marginTop: -e.y / imageScaledRatio
                                });
                            });
                        }
                    });
                }
                reader.readAsDataURL(file.files[0]);
                $('#logo').val(file.files[0]);

            }
            else {
                alert("上传失败,请重试");
            }
        }
