var subCategoriesForm;

$(function(){



    gallerySearchForm = $('#gallerySearchForm');
    gallerySearchForm.submit(function(e){
        e.preventDefault();

        var Tbl_ID = $('[name="gal_id1"]').val();
        var Tblnam = (Tbl_ID == 0) ? 'GLOBAL' : 'WEBGALLERY';

        // alert('tblnam='+Tblnam+'&tbl_id='+Tbl_ID+'&keywrd=' + $('[name="keywrd"]', gallerySearchForm).val())
        $.ajax({
            url: 'gallery/uploads.gallery.global.php',
            data: 'tblnam='+Tblnam+'&tbl_id='+Tbl_ID+'&keywrd='+ $('#keywrd1').val(),
            type: 'GET',
            async: false,
            success: function( data ) {
                $('#imagelisting').html(data);
                $.msgGrowl ({
                    type: 'success'
                    , title: 'Gallery Retrieved'
                    , text: 'Gallery Retrieved'
                });
            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });
    })


    $('.reset').click(function(){
        $('input[name="cuslogo"]').val("");
        $('.fileupload-new img').attr('src',"http://www.placehold.it/1920x800/EFEFEF/AAAAAA&text=no+image");
    })






    tinymceConfigs = [
        {
            mode: "none",
            theme: "simple"
        },
        {
            relative_urls: false,
            remove_script_host: false,
            document_base_url: $('#webRoot').val(), //"http://demo.patchworkscms.com/",
            convert_urls: false,
            //mode: "none",
            plugins: "hr,link,image,autolink,lists,layer,table,preview,media,searchreplace,contextmenu,directionality,fullscreen,noneditable,visualchars,nonbreaking,template,advlist,code,paste",

            image_advtab: true,

            toolbar1: "bold italic underline | alignleft aligncenter alignright | image ",
            toolbar2: "link unlink | bullist code fullscreen pasteword",

            paste_as_text: true,
            paste_word_valid_elements: "b,strong,i,em,h1,h2",
            inline_styles : false,
            paste_retain_style_properties: "",

            removeformat : [
                {selector : 'b,strong,em,i,font,u,strike', remove : 'all', split : true, expand : false, block_expand : true, deep : true},
                {selector : 'span', attributes : ['style', 'class'], remove : 'empty', split : true, expand : false, deep : true},
                {selector : '*', attributes : ['style', 'class'], split : false, expand : false, deep : true}
            ],

            cleanup_on_startup : true,
            fix_list_elements : false,
            fix_nesting : false,
            fix_table_elements : false,
            paste_use_dialog : true,
            paste_auto_cleanup_on_paste : true,

            file_browser_callback : elFinderBrowser,

            menubar: "edit insert view format table tools",

            image_dimensions: false,

            // Example content CSS (should be your site CSS)
            //content_css: $('#webRoot').val() + "/pages/css/styles.css"

        }];

    tinyMCE.settings = tinymceConfigs[1];
    tinyMCE.execCommand('mceAddEditor', false, 'subtxt');

    subCategoriesForm = $('#subCategoriesForm');

    $('#updateSubCategoryBtn').click(function(e){
        e.preventDefault();
        subCategoriesForm.submit();

    });

    $('[name="subnam"]', subCategoriesForm).on("keyup", function() {
        $('[name="seourl"]', subCategoriesForm ).val( seoURL( $(this).val() ) );
    });

    subCategoriesForm.submit(function(e){

        e.preventDefault();


            $('#addImageBtn').css('display','block')

        uploadBoxImage();

    });

    $('#deleteSubCategoryBtn').click(function (e) {
        e.preventDefault();

        $.msgAlert ({
            type: 'warning'
            , title: 'Delete This Category'
            , text: 'Are you sure you wish to permanently remove this category from the database?'
            , callback: function () {

                $.ajax({
                    url: subCategoriesForm.attr("action"),
                    data: 'action=delete&ajax=true&' + subCategoriesForm.serialize(),
                    type: 'POST',
                    async: false,
                    success: function( data ) {

                        var result = JSON.parse(data);

                        $.msgGrowl ({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });

                        window.location = subCategoriesForm.data("returnurl");

                    },
                    error: function (x, e) {
                        throwAjaxError(x, e);
                    }
                });

            }
        });
        return false;
    });

    function elFinderBrowser (field_name, url, type, win) {
        tinymce.activeEditor.windowManager.open({
            file: 'system/elfindertiny.php',
            title: 'File Browser',
            width: 900,
            height: 600,
            resizable: 'yes'
        }, {
            setUrl: function (url) {
                win.document.getElementById(field_name).value = url;
            }
        });
        return false;
    }


    function uploadBoxImage() {

        if ($('#logofile2')[0].files[0]) {

            var data;

            data = new FormData();
            data.append('logofile', $('#logofile2')[0].files[0]);

            $.ajax({
                url: 'custom/uploadlogo.php',
                data: data,
                processData: false,
                type: 'POST',
                contentType: false,
                aSync: false,
                success: function (data) {

                    var result = JSON.parse(data);

                    $('[name="subimg"]', subCategoriesForm).val(result.description);

                    uploadHomeImage();

                },
                error: function (x, e) {

                    uploadHomeImage();

                }
            });

        } else {
            uploadHomeImage();
        }

    }

    function uploadHomeImage() {

        if ($('#logofile3')[0].files[0]) {

            var data;

            data = new FormData();
            data.append('logofile', $('#logofile3')[0].files[0]);

            $.ajax({
                url: 'custom/uploadhomeimage.php',
                data: data,
                processData: false,
                type: 'POST',
                contentType: false,
                aSync: false,
                success: function (data) {

                    var result = JSON.parse(data);

                    $('[name="homimg"]', subCategoriesForm).val(result.description);

                    updateRecord();

                },
                error: function (x, e) {

                    updateRecord();

                }
            });

        } else {
            updateRecord();
        }

    }

    function updateRecord() {

        tinyMCE.triggerSave('subtxt');

        var fields = $(".customfield", subCategoriesForm).serializeArray();
        var elementVariables = JSON.stringify(fields);
        var postData = encodeURIComponent(elementVariables);

        $.ajax({
            url: subCategoriesForm.attr("action"),
            data: 'action=update&ajax=true&' + subCategoriesForm.serialize() + '&subtxt=' + postData,
            type: 'POST',
            async: false,
            success: function( data ) {

                try {

                    var result = JSON.parse(data);

                    $.msgGrowl ({
                        type: result.type
                        , title: result.title
                        , text: result.description
                    });

                    $('#id', subCategoriesForm).val( result.id );

                } catch(Ex) {
                    $.msgGrowl ({
                        type: 'error'
                        , title: 'Error'
                        , text: Ex
                    });

                    //$.growlUI('Error', 'Contact your administrator');
                }

            },
            error: function (x, e) {

                throwAjaxError(x, e);

            }
        });

    }

    $('.gallery-dynamic').on('click', '.editUpload', function (e) {
        e.preventDefault();
        var uplId = $(this).data('upl_id');
        $.ajax({
            url: 'gallery/uploads_script.php',
            data: 'action=select&upl_id=' + uplId,
            type: 'GET',
            async: false,
            success: function( data ) {
                var imgArray = JSON.parse(data);
                $('[name="upl_id"]', $('#imageForm')).val( imgArray[0].upl_id );
                $('[name="uplttl"]', $('#imageForm')).val( imgArray[0].uplttl );
                $('[name="upldsc"]', $('#imageForm')).val( imgArray[0].upldsc );
                $('[name="urllnk"]', $('#imageForm')).val( imgArray[0].urllnk );
                $('[name="alttxt"]', $('#imageForm')).val( imgArray[0].alttxt );
            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });
        $('#imageModal').modal('show');
    });
    $('.gallery-dynamic').on('click', '.moveUpload', function (e) {
        e.preventDefault();
    });
    $('#imageForm').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: 'gallery/uploads_script.php',
            data: 'action=update&' + $('#imageForm').serialize(),
            type: 'GET',
            async: false,
            success: function( data ) {
                var result = JSON.parse(data);
                $.msgGrowl ({
                    type: result.type
                    , title: result.title
                    , text: result.description
                });
                if ( result.type != 'Error' ) {
                    $('#imageModal').modal('hide');
                }
            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });
    });

    $('.gallery-dynamic').on('click', '.deleteUpload', function (e) {
        e.preventDefault();
        var upl_id = $(this).data('upl_id');
        var masterimage = ($(this).hasClass('masterimage')) ? '&masterimage=true' : '';
        $.msgAlert ({
            type: 'warning'
            , title: 'Delete This Image'
            , text: 'Are you sure you wish to permanently remove this image from the database?'
            , callback: function () {
                $.ajax({
                    url: 'gallery/uploads_script.php',
                    data: 'action=delete&ajax=true&upl_id=' + upl_id + masterimage,
                    type: 'POST',
                    async: false,
                    success: function( data ) {
                        var result = JSON.parse(data);
                        $.msgGrowl ({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });
                        getGallery();
                    },
                    error: function (x, e) {
                        throwAjaxError(x, e);
                    }
                });
            }
        });
        return false;
    });
    getGallery();
    $('#addImageBtn').click(function(e){
        e.preventDefault();
        $('#galleryImagesDiv').hide();
        $('#uploadImagesDiv').show();
    });
    $('#cancelGalleryImagesBtn').click(function(e){
        e.preventDefault();
        $('#galleryImagesDiv').show();
        $('#uploadImagesDiv').hide();
    });

    $('#updateGalleryImagesBtn').click(function(e){
        e.preventDefault();
        //
        // UPDATE GALLERY
        //
        var imageFiles = '';
        $('.imageselect.active').each(function(){
            imageFiles += (imageFiles == '') ? $(this).data('upl_id') : ',' + $(this).data('upl_id');
        });
        $.ajax({
            url: 'gallery/galleryimagecontrol.php',
            //data: 'action=update&ajax=true&' + galleryForm.serialize(),
            data: 'tblnam=BASKETTAG&tbl_id='+$('[name="sub_id"]', subCategoriesForm).val() + '&filnam=' + imageFiles,
            type: 'POST',
            async: false,
            success: function( data ) {
                try {
                    getGallery();
                }
                catch (ex) {
                    $.msgGrowl ({
                        type: 'error'
                        , title: 'Error'
                        , text: ex
                    });
                }
            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });

        $('#galleryImagesDiv').show();
        $('#uploadImagesDiv').hide();
    });

    $('#imagelisting').on('click','.selectUpload',function(e){
        e.preventDefault();
        $(this).parent().parent().toggleClass('active').prev('.imageselect').toggleClass('active');
    });
    if($(".gallery-dynamic").length > 0){
        $(".gallery-dynamic").imagesLoaded(function(){
            $(".gallery-dynamic").masonry({
                itemSelector: 'li',
                columnWidth: 201,
                isAnimated: true
            });
        });
    }
    $('#galleryImages').sortable({
        handle: ".moveUpload",
        stop: function(){
            var images = $('#galleryImages').find('.moveUpload');
            var SrtOrd = '';
            images.each(function(){
                SrtOrd += (SrtOrd == '') ? $(this).data('upl_id') : ','+$(this).data('upl_id');
            });
            //alert('uploader/gallery.resort.php?srtord=' + SrtOrd);
            $.ajax({
                url: 'gallery/uploads_script.php',
                data: 'action=resort&srtord=' + SrtOrd,
                type: 'GET',
                async: false,
                success: function( data ) {
//					alert(data);
                    $.msgGrowl ({
                        type: 'success'
                        , title: 'Gallery Re-Ordered'
                        , text: 'Gallery Re-Ordered'
                    });
                },
                error: function (x, e) {
                    throwAjaxError(x, e);
                }
            });
        }
    });
});


function getGallery() {
    $.ajax({
        url: 'gallery/uploads.gallery.global.php',
        data: 'tblnam=BASKETTAG&tbl_id=2',
        type: 'GET',
        async: false,
        success: function( data ) {
            $('#imagelisting').html(data);
            //$.msgGrowl ({
            //    type: 'success'
            //    , title: 'Gallery Retrieved'
            //    , text: 'Gallery Retrieved'
            //});
        },
        error: function (x, e) {
            throwAjaxError(x, e);
        }
    });
    $('imageselect').removeClass('active');

    if($('[name="sub_id"]', subCategoriesForm).val() > 0){
        $('#addImageBtn').css('display','block')
    }
    $.ajax({
        url: 'gallery/uploads.gallery.php',
        data: 'tblnam=BASKETTAG&tbl_id='+$('[name="sub_id"]', subCategoriesForm).val(),
        type: 'GET',
        async: false,
        success: function( data ) {

            $('#galleryImages').html(data);
            //
            // preselect global gallery images based on current gallery
            //
            //var currentimages = $('#galleryImages').find('img');
            //
            //for (i=0;i<currentimages.length;i++) {
            //
            //    $("[data-imgfil='" + $(currentimages[i]).attr('src') +"']").addClass('active');
            //
            //}
            //$.msgGrowl ({
            //    type: 'success'
            //    , title: 'Gallery Retrieved'
            //    , text: 'Gallery Retrieved'
            //});
        },
        error: function (x, e) {
            throwAjaxError(x, e);
        }
    });

    //$.ajax({
    //	url: 'gallery/uploads.gallery.php',
    //	data: 'tblnam=ARTICLE&tbl_id='+$('[name="sub_id"]', subCategoriesForm).val(),
    //	type: 'GET',
    //	async: false,
    //	success: function( data ) {
    //
    //		$('#galleryImages').html(data);
    //
    //		$.msgGrowl ({
    //			type: 'success'
    //			, title: 'Gallery Retrieved'
    //			, text: 'Gallery Retrieved'
    //		});
    //
    //	},
    //	error: function (x, e) {
    //		throwAjaxError(x, e);
    //	}
    //});
    //
    //$(".colorbox-image").colorbox({
    //	maxWidth: "90%",
    //	maxHeight: "90%",
    //	rel: $(this).attr("rel")
    //});

}
function plupLoad(iElement) {
    var $el = iElement;
    $el.pluploadQueue({
        runtimes : 'html5,gears,flash,silverlight,browserplus',
        url : '../js/plugins/plupload/upload.php?resize='+ $el.data('resize') +'&tblnam=BASKETTAG&tbl_id=' + $('[name="sub_id"]', subCategoriesForm).val(),
        max_file_size : '10mb',
        chunk_size : '6mb',
        unique_names : true,
        multiple_queues : true,
        //resize : {width : 320, height : 240, quality : 90},
        filters : [
            {title : "Image files", extensions : "jpg,gif,png"},
            {title : "Zip files", extensions : "zip"}
        ],
        flash_swf_url : 'js/plupload/plupload.flash.swf',
        silverlight_xap_url : 'js/plupload/plupload.silverlight.xap'
    });
    $(".plupload_header").remove();
    var upload = $el.pluploadQueue();
    if($el.hasClass("pl-sidebar")){
        $(".plupload_filelist_header,.plupload_progress_bar,.plupload_start").remove();
        $(".plupload_droptext").html("<span>Drop files to upload</span>");
        $(".plupload_progress").remove();
        $(".plupload_add").text("Or click here...");
        upload.bind('FilesAdded', function(up, files) {
            setTimeout(function () {
                up.start();
            }, 500);
        });
        upload.bind("QueueChanged", function(up){
            $(".plupload_droptext").html("<span>Drop files to upload</span>");
        });
        upload.bind("StateChanged", function(up){
            $(".plupload_upload_status").remove();
            $(".plupload_buttons").show();
        });
    } else {
        $(".plupload_progress_container").addClass("progress").addClass('progress-striped');
        $(".plupload_progress_bar").addClass("bar");
        $(".plupload_button").each(function(){
            if($(this).hasClass("plupload_add")){
                $(this).attr("class", 'btn pl_add btn-primary').html("<i class='icon-plus-sign'></i> "+$(this).html());
            } else {
                $(this).attr("class", 'btn pl_start btn-success').html("<i class='icon-cloud-upload'></i> "+$(this).html());
            }
        });
    }
    upload.bind("UploadComplete", function(up) {
        if (up.files.length == (up.total.uploaded + up.total.failed)) {
            getGallery();
        }
    });
}