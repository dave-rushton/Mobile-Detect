$(function () {
    'use strict';
    var $image = $('#image');
    var canvasData,
        cropBoxData;

    $('.createWidth').click(function(e){
        e.preventDefault();
        var width = $(this).data('width');
        var image = $(this).data('imgfil');
        var dir = $(this).data('directory');
        $.ajax({
            url: 'gallery/createimagewidth.php',
            data: 'filnam='+image+'&imgsiz=' + width + '&dir=' + dir,
            type: 'GET',
            async: false,
            success: function( data ) {
                location.reload(true);
            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });
    });
    $('.changeratio').click(function(e){
        $(this).parent().parent().find('.active').removeClass('active');
        $(this).parent().addClass('active');
        e.preventDefault();
        var setCropArea = 1;
        if ($(this).text() == 'FREE') {
            setCropArea = 0.5;
        }
        var wl = $(this).text().split(' x ');
        $('#imgpreview').width( wl[0] ).height( wl[1] );
        $('[name="avatar_size"]', $('#cropForm')).val( $(this).data('directory') )
        $image.cropper('destroy');
        $image.cropper({
            autoCropArea: setCropArea,
            aspectRatio: $(this).data('ratio'),
            built: function () {
                $image.cropper('setCanvasData', canvasData);
                $image.cropper('setCropBoxData', cropBoxData);
            },
            preview: '.imgpreview',
            crop: function (data) {
                var json = [
                    '{"x":' + data.x,
                    '"y":' + data.y,
                    '"height":' + data.height,
                    '"width":' + data.width,
                    '"rotate":' + data.rotate + '}'
                ].join();
                $('[name="avatar_data"]').val(json);
            }
        });
    });
    $('#rotateBtn').click(function(e) {
        e.preventDefault();
        $image.cropper('rotate', 90)
    });
    $('#saveImage').click(function(e){
        //$image.cropper('crop');
        e.preventDefault();
        var url = 'gallery/crop.php',
        //data = new FormData( $('#cropForm') ),
            _this = this;
        $.ajax(url, {
            type: 'post',
            data: $('#cropForm').serialize(),
            //dataType: 'json',
            //processData: false,
            //contentType: false,
            //beforeSend: function () {
            //    _this.submitStart();
            //},
            //
            success: function (data) {
                //    _this.submitDone(data);
                $.msgGrowl ({
                    type: 'success'
                    , title: 'Image Cropped'
                    , text: 'Image Cropped'
                });
                // location.reload(true);
            },
            //
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('ERROR: ' + errorThrown);
                //    _this.submitFail(textStatus || errorThrown);
            },
            //
            complete: function () {
                //    _this.submitEnd();
            }
        });
    });
    $('.changeratio').first().click();
});