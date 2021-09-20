var articleForm;
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



	articleForm = $('#articleForm');
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
            toolbar1: "bold italic underline | alignleft aligncenter alignright ",
            toolbar2: "link unlink | bullist code fullscreen pasteword",
            paste_as_text: true,
            paste_word_valid_elements: "b,strong,i,em,h1,h2",
            inline_styles : false,
            paste_retain_style_properties: "",
            templates:[
                {title: 'Clear', description: 'Clears Page (Start new Row)', url: '../admin/custom/templates/clear.html'},
                {title: 'Image Left', description: 'Image Floated Left', url: '../admin/templates/image-left.html'},
                {title: 'Image Right', description: 'Image Floated Left', url: '../admin/templates/image-right.html'},
                {title: 'Two Image', description: 'Two Image', url: '../admin/templates/two-image.html'},
            ],
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
            content_css: $('#webRoot').val() + "pages/css/style.css?v=2,"+$('#webRoot').val() + "pages/css/critical.css?v=3",
            //theme_advanced_blockformats: "p,h1,h2,h3,h4,blockquote"
        }];

	$('[name="artdat"]', articleForm).datepicker({ format: 'yyyy-mm-dd', weekStart: 1 });
	
	articleForm.validate({
        rules: {
            artttl: {
                minlength: 2,
                required: true
            },
			artdat: {
                date: true,
                required: true
            },
            seourl: {
				minlength: 2,
//				url: true,
                required: true
            }
        }
		,
        focusCleanup: false,
        highlight: function (label) {
            $(label).closest('.control-group').removeClass('success').addClass('error');
        },
        success: function (label) {
            label.text('OK!').addClass('valid').closest('.control-group').addClass('success');
        },
        errorPlacement: function (error, element) {
            error.appendTo(element.parents('.controls'));
        },
		submitHandler: function(form) {
			
		}
    });
    articleForm.eq(0).find('input').eq(0).focus();
	articleForm.submit(function(e){
		
		e.preventDefault();
		
		$("#articleBox, #articleBoxContent").block({
			message: '<h4>Updating</h4>', 
			centerY: 0,
			centerX: 0,
			css: { top: '10px', left: '', right: '10px', border: '2px solid #a00'}
		});

        if ($(this).valid()) {
			
			uploadImage();
		}
		else {
			$.msgGrowl ({
				type: 'error'
				, title: 'Invalid Form'
				, text: 'There is an error in the form'
			});
		}
        $("#articleBox, #articleBoxContent").unblock();
		
	});
	
	$('#deleteArticleBtn').click(function (e) {
		e.preventDefault();
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Article'
			, text: 'Are you sure you wish to permanently remove this article from the database?'
			, callback: function () {
				
				articleForm.block({ 
					message: '<h4>Deleting</h4>', 
					centerY: 0,
					centerX: 0,
					css: { top: '10px', left: '', right: '10px', border: '2px solid #a00' } 
				});
				
				$.ajax({
					url: articleForm.attr("action"),
					data: 'action=delete&ajax=true&' + articleForm.serialize(),
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						window.location = articleForm.data("returnurl");
						
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
				
			}
		});
		return false;
	});
	$('[name="artttl"]', articleForm ).blur(function(){
		$('[name="seourl"]', articleForm ).val( seoURL( $(this).val() ) );
	});
	
	$('#updateArticleBtn').click(function(e){
		e.preventDefault();
		
		//CKEDITOR.instances['arttxt'].updateElement();
        tinyMCE.triggerSave('arttxt');
		articleForm.submit();
		
	});
    tinyMCE.settings = tinymceConfigs[1];
    tinyMCE.execCommand('mceAddEditor', false, 'arttxt');
	//CKEDITOR.replace("arttxt", {
	//	height: 470,
	//	filebrowserBrowseUrl : 'system/elfinder.php',
	//	toolbarGroups: [
	//			{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },			// Group's name will be used to create voice label.
	//			'/',																// Line break - next group will be placed in new line.
	//			{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
	//			{ name: 'links' },
	//			{ items: [ 'Image', 'Table' ] },
	//			{ items: [ 'Format', 'Maximize', 'Source' ] }
	//		]
	//	}
	//);
	
	if ( $('[name="art_id"]', articleForm).val() != '0' ) {
		plupLoad( $('#plupload') );
        $('#addImageBtn').show();
	} else {
        $('#addImageBtn').hide();
    }
    //
    // GALLERY CODE
    //

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
            data: 'tblnam=ARTICLE&tbl_id='+$('[name="art_id"]', articleForm).val() + '&filnam=' + imageFiles,
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
        data: 'tblnam=GLOBAL&tbl_id=0',
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
    $.ajax({
        url: 'gallery/uploads.gallery.php',
        data: 'tblnam=ARTICLE&tbl_id='+$('[name="art_id"]', articleForm).val(),
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
    // GLOBAL IMAGES
    $(".colorbox-image").colorbox({
        maxWidth: "90%",
        maxHeight: "90%",
        rel: $(this).attr("rel")
    });
	//$.ajax({
	//	url: 'gallery/uploads.gallery.php',
	//	data: 'tblnam=ARTICLE&tbl_id='+$('[name="art_id"]', articleForm).val(),
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
        url : '../js/plugins/plupload/upload.php?resize='+ $el.data('resize') +'&tblnam=ARTICLE&tbl_id=' + $('[name="art_id"]', articleForm).val(),
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

function uploadImage() {
    if( $('#logofile')[0].files[0] ) {
        var data;
        data = new FormData();
        data.append('logofile', $('#logofile')[0].files[0]);
        data.append('newname', $('[name="seourl"]', articleForm).val());
        $.ajax({
            url: 'website/uploadlogo.php',
            data: data,
            processData: false,
            type: 'POST',
            contentType: false,
            aSync: false,
            success: function (data) {
                $('[name="cuslogo"]', articleForm).val(data);
                updateRecord();
            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });
    } else {
        updateRecord();
    }
}
function updateRecord() {
    var csTags = '';
    $('.caseStudyCB:checked').each(function(){
        csTags += (csTags.length > 0) ? ',' + $(this).val() : $(this).val();
    });
    $('[name="cs_tag"]', articleForm).val(csTags);

    var ArtTyp = '';
    $('.articleTypeCheckboxCB:checked').each(function(){
        ArtTyp += '|'+$(this).val()+'|';
    });
    $('[name="arttyp"]', articleForm).val(ArtTyp);
    var fields = $(".customfield", articleForm).serializeArray();
    var elementVariables = JSON.stringify(fields);
    var postData = encodeURIComponent(elementVariables);
    //alert(postData);
    $.ajax({
        url: articleForm.attr("action"),
        data: 'action=update&ajax=true&' + articleForm.serialize() + '&artobj=' + postData,
        type: 'POST',
        async: false,
        success: function( data ) {
//					alert(data);
            var result = JSON.parse(data);
            $.msgGrowl ({
                type: result.type
                , title: result.title
                , text: result.description
            });
            $('#id', articleForm ).val( result.id );
            $('#addImageBtn').show();
            plupLoad( $('#plupload') );
            // FACEBOOK LINK
            $('#facebookFrame').attr('src','website/getfacebooklink.php?seourl=' + $('[name="seourl"]', articleForm).val());
            $('#twitterFrame').attr('src', 'website/gettwitterlink.php?seourl=' + $('[name="seourl"]', articleForm).val());
            //$('#fbModal').modal('show');
            //$.ajax({
            //    url: 'website/getfacebooklink.php',
            //    data: 'seourl=' + $('[name="seourl"]', articleForm).val(),
            //    type: 'POST',
            //    async: false,
            //    success: function( data ) {
            //
            //        if (data != '') {
            //
            //            $('#facebookLink').attr('href', data);
            //            $('#fbModal').modal('show');
            //
            //        }
            //
            //    },
            //    error: function (x, e) {
            //        throwAjaxError(x, e);
            //    }
            //});
        },
        error: function (x, e) {
            throwAjaxError(x, e);
        }
    });
//			$('#customerFormRow').unblock();
}