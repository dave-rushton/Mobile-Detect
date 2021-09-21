var attrGroupForm, attrLabelForm;

$(function(){
	
	attrGroupForm = $('#attrGroupForm');
	attrLabelForm = $('#attrLabelForm');
	
	$('[name="atrnam"]', attrGroupForm).on("keyup", function() {
		$('[name="seourl"]', attrGroupForm ).val( seoURL( $('[name="tbl_id"] option:selected ', attrGroupForm ).text() + ' ' + $('[name="atrnam"]', attrGroupForm).val() ) );
	});

    $('[name="tbl_id"]', attrGroupForm).on("change", function() {
        $('[name="seourl"]', attrGroupForm ).val( seoURL( $('[name="tbl_id"] option:selected ', attrGroupForm ).text() + ' ' + $('[name="atrnam"]', attrGroupForm).val() ) );
    });
	
	//
	// basic screen functionality
	//
	
	$('#createAttrLabelBtn').click(function(e){
		e.preventDefault();
		
		// clear form
		$('[name="atllbl"]',attrLabelForm ).val( '' );
		$('[name="atldsc"]',attrLabelForm ).val( '' );
		$('[name="atl_id"]',attrLabelForm ).val( 0 );
		$('#AtrLst_UL',attrLabelForm).html('');
		$('[name="atltyp"]', attrLabelForm).val('text');
		$('[name="atltyp"]', attrLabelForm).change();
		$('#attrLabelTableBox').hide();
		$('#attrLabelBox').show();
	});
	
	$('#cancelAttrLabelBtn').click(function(e){
		e.preventDefault();
		$('#attrLabelTableBox').show();
		$('#attrLabelBox').hide();
	});

    $('[name="atrtagselect"]', attrGroupForm).each(function(){
        var $el = $(this);
        var search = ($el.attr("data-nosearch") === "true") ? true : false,
            opt = {};
        if(search) opt.disable_search_threshold = 9999999;
        $el.chosen(opt);

        resize_chosen();

    });

	
	//
	// form functionality
	//
	
//	attrGroupForm.validate({
//        rules: {
//            atrnam: {
//                minlength: 2,
//                required: true
//            },
//            sta_id: {
//                required: true
//            }
//        }
//    });

    attrGroupForm.submit(function(e){

		e.preventDefault();

        $('[name="atrtag"]', attrGroupForm).val( $('[name="atrtagselect"]', attrGroupForm).val() );
		
		if (attrGroupForm.valid()) {
			
			$.ajax({
				url: attrGroupForm.attr("action"),
				data: 'action=update&ajax=true&' + attrGroupForm.serialize(),
				type: 'POST',
				async: false,
				success: function( data ) {
					
					var result = JSON.parse(data);
					
					$.msgGrowl ({
						type: result.type
						, title: result.title
						, text: result.description
					});
					
					if (result.type == 'success') {
						$('#createAttrLabelBtn').show();
						$('#deleteAttrGroupBtn').removeClass('hide');
						$('#id', attrGroupForm ).val( result.id );
						$('#atrId', attrLabelForm ).val( result.id );
					}
					
				},
				error: function (x, e) {
					throwAjaxError(x, e);
				}
			});

			$('#attrGroupBox').unblock();
		}
		else {
			$.msgGrowl ({
				type: 'error'
				, title: 'Invalid Form'
				, text: 'There is an error in the form'
			});
		}
		
	});
	
	$('#updateAttrGroupBtn').click(function(e){
		e.preventDefault();
		attrGroupForm.submit();
	});
	
	$('#deleteAttrGroupBtn').click(function (e) {
		e.preventDefault();
		
		var atrId = $('[name="atr_id"]', attrGroupForm).val();
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Group'
			, text: 'Are you sure you wish to permanently remove this group from the database?'
			, callback: function () {
				
				$.ajax({
					url: 'attributes/attrgroup_script.php',
					data: 'action=delete&ajax=true&atr_id=' + atrId,
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						window.location = attrGroupForm.data('returnurl');
		
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
				
			}
		});
		
	});
	
//	attrLabelForm.validate({
//        rules: {
//            atllbl: {
//                minlength: 2,
//                required: true
//            }
//        }
//    });
	
	attrLabelForm.submit(function(e){
		
		e.preventDefault();
		
		var AtlLst = '';
		$('.AtlLstVal').each(function(){
			AtlLst += ( AtlLst == '' ) ? $(this).val() : ',' + $(this).val();
		});
		
		$('[name="atllst"]',attrLabelForm ).val( AtlLst );
		
		if (attrLabelForm.valid()) {
			
			$.ajax({
				url: attrLabelForm.attr("action"),
				data: 'action=update&ajax=true&' + attrLabelForm.serialize(),
				type: 'POST',
				async: false,
				success: function( data ) {
					
					var result = JSON.parse(data);
					
					$.msgGrowl ({
						type: result.type
						, title: result.title
						, text: result.description
					});
					
					if (result.type == 'success') {					
						$('#deleteAttrLabelBtn').removeClass('hide');
						$('#id', attrLabelForm ).val( result.id );
					}
					
					getAttrLabels();
					
					$('#attrLabelTableBox').show();
					$('#attrLabelBox').hide();
					
				},
				error: function (x, e) {
					throwAjaxError(x, e);
				}
			});
			
		
		}
			
	});
	
	$('#updateAttrLabelBtn').click(function(e){
		e.preventDefault();
		attrLabelForm.submit();
	});
	
	//
	// attribute type functionality
	//
	
	$('[name="atltyp"]', attrLabelForm).change(function(){
		
		if ( $(this).val() == 'select' || $(this).val() == 'radio' ) {
			$('#AtlLstDiv').removeClass('hide');
		} else {
			$('#AtlLstDiv').addClass('hide');
		}
		
	});
	
	$('#addAltLst').click(function(e){
		
		e.preventDefault();
		
		if ( $('#AddAltLst').val() != '' ) {
			
			var resultHTML  = '<li style="list-style: none; padding: 0; margin: 0 0 3px 0;">';
				resultHTML += '<div class="input-append">';
				resultHTML += '<input type="text" class="input-medium AtlLstVal" value="'+$('#AddAltLst').val()+'" />';
				resultHTML += '<div class="btn-group">';
				resultHTML += '<button class="btn" type="button" rel="tooltip" title="Remove From List"><i class="icon icon-remove removeAltLst"></i></button>';
				resultHTML += '<a href="#" class="btn" type="button" rel="tooltip" title="Drag To Reorder List"><i class="icon icon-reorder moveLI"></i></a>';
				resultHTML += '</div>';
				resultHTML += '</div>';
				resultHTML += '</li>';
			
			$('#AtrLst_UL').append( resultHTML );
			
			$('#AddAltLst').val('');
			
		}
		
	});
	
	$('#AtrLst_UL').on('click', '.removeAltLst', function (e) {
		e.preventDefault();
		$(this).closest('li').remove();
	});
	
	$('#attrLabelBody').on('click', '.editAttrLabel', function (e) {
		e.preventDefault();
		
		$.ajax({
			url: 'attributes/attrlabel_script.php',
			data: 'action=select&atl_id=' + $(this).data("atl_id"),
			type: 'GET',
			async: true,
			success: function( data ) {
				
				try {
					
					var attrLabel = JSON.parse(data);
					
					$('[name="atllbl"]',attrLabelForm ).val( attrLabel[0].atllbl );
					$('[name="atldsc"]',attrLabelForm ).val( attrLabel[0].atldsc );
					$('[name="atl_id"]',attrLabelForm ).val( attrLabel[0].atl_id );
					$('[name="atltyp"]',attrLabelForm ).val( attrLabel[0].atltyp );
					$('[name="srctyp"]',attrLabelForm ).val( attrLabel[0].srctyp );
					$('[name="srtord"]',attrLabelForm ).val( attrLabel[0].srtord );
					
					$('[name="atlreq"]',attrLabelForm ).prop('checked', (attrLabel[0].atlreq == 1) ? true : false );
					$('[name="atlspc"]',attrLabelForm ).prop('checked', (attrLabel[0].atlspc == 1) ? true : false );
					$('[name="srcabl"]',attrLabelForm ).prop('checked', (attrLabel[0].srcabl == 1) ? true : false );
					
					$('[name="atltyp"]', attrLabelForm).change();
					
					var atlArr = attrLabel[0].atllst.split(",");
					var resultHTML = '';
					
					if (attrLabel[0].atllst.length > 0) {
					
						for (i=0;i<atlArr.length;i++) {
							
							resultHTML += '<li style="list-style: none; padding: 0; margin: 0 0 3px 0;">';
							resultHTML += '<div class="input-append">';
							resultHTML += '<input type="text" class="input-medium AtlLstVal" value="'+atlArr[i]+'" />';
							resultHTML += '<div class="btn-group">';
							resultHTML += '<button class="btn removeAltLst" type="button" rel="tooltip" title="Remove From List"><i class="icon icon-remove"></i></button>';
							resultHTML += '<a href="#" class="btn" type="button" rel="tooltip" title="Drag To Reorder List"><i class="icon icon-reorder moveLI"></i></a>';
							resultHTML += '</div>';
							resultHTML += '</div>';
							resultHTML += '</li>';
							
						}
					
					}
					
					$('#AtrLst_UL').html( resultHTML );
					
					$('#attrLabelTableBox').hide();
					$('#attrLabelBox').show();
					
					
				} catch (ex) {
					
					alert(ex);
					
				}
				
				
			},
			error: function (x, e) {
				throwAjaxError(x, e);
			}
		});
		
	});
	
	$('#attrLabelBody').on('click', '.deleteAttrLabelBtn', function (e) {
		e.preventDefault();
		
		var atlId = $(this).data('atl_id');
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Label'
			, text: 'Are you sure you wish to permanently remove this label from the database?'
			, callback: function () {
				
				$.ajax({
					url: 'attributes/attrlabel_script.php',
					data: 'action=delete&ajax=true&atl_id=' + atlId,
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						getAttrLabels();
		
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
				
			}
		});
		
	});
	
	$('#AtrLst_UL').sortable({ handle: '.moveLI' });
	
	$('#attrLabelBody').sortable({ 
		handle: '.attrLabelSort',
		stop: function( event, ui ) {
			
			var atlLst = '';
			
			$('.editAttrLabel', $('#attrLabelBody')).each(function(){
			
				atlLst += (atlLst == '') ? $(this).data('atl_id') : ',' + $(this).data('atl_id');
				
			});
			
			$.ajax({
				url: 'attributes/attrlabel_script.php',
				data: 'action=resort&ajax=true&atl_id=' + atlLst,
				type: 'POST',
				async: false,
				success: function( data ) {
					
					var result = JSON.parse(data);
					
					$.msgGrowl ({
						type: result.type
						, title: result.title
						, text: result.description
					});
	
				},
				error: function (x, e) {
					throwAjaxError(x, e);
				}
			});
			
		}
	});
	
	if ( $('[name="atr_id"]', attrGroupForm).val() != '0' ) {
		plupLoad( $('#plupload') );	
		getAttrLabels();
		getGallery();
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

            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });

        $('#imageModal').modal('show');
    });


    $('.gallery-dynamic').on('click', '.deleteUpload', function (e) {
        e.preventDefault();

        var upl_id = $(this).data('upl_id');

        $.msgAlert ({
            type: 'warning'
            , title: 'Delete This Image'
            , text: 'Are you sure you wish to permanently remove this image from the database?'
            , callback: function () {

                $.ajax({
                    url: 'gallery/uploads_script.php',
                    data: 'action=delete&ajax=true&upl_id=' + upl_id,
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

            data: 'tblnam=PRDGRP&tbl_id='+$('[name="atr_id"]', attrGroupForm).val() + '&filnam=' + imageFiles,

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
	
	
});

function getAttrLabels() {

	$.ajax({
		url: 'attributes/attrlabels_table.php',
		data: 'atr_id=' + $('[name="atr_id"]',attrGroupForm).val(),
		type: 'GET',
		async: true,
		success: function( data ) {
			
			$('#attrLabelBody').html( data );
			
		},
		error: function (x, e) {
			throwAjaxError(x, e);
		}
	});
	
	$('[rel="tooltip"]', $('#attrLabelBody')).tooltip();
	
}


function getGallery() {

    $.ajax({
        url: 'gallery/uploads.gallery.global.php',
        data: 'tblnam=GLOBAL&tbl_id=0',
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

    $('imageselect').removeClass('active');

    $.ajax({
        url: 'gallery/uploads.gallery.php',
        data: 'tblnam=PRDGRP&tbl_id='+$('[name="atr_id"]', attrGroupForm).val(),
        type: 'GET',
        async: false,
        success: function( data ) {

            $('#galleryImages').html(data);

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

    // GLOBAL IMAGES

    $(".colorbox-image").colorbox({
        maxWidth: "90%",
        maxHeight: "90%",
        rel: $(this).attr("rel")
    });
	
}

function plupLoad(iElement) {

    var $el = iElement;
    $el.pluploadQueue({
        runtimes : 'html5,gears,flash,silverlight,browserplus',
        url : '../js/plugins/plupload/upload.php?resize='+ $el.data('resize') +'&tblnam=GLOBAL&tbl_id=0',
        max_file_size : '10mb',
        chunk_size : '6mb',
        unique_names : true,
        multiple_queues : true,
        //resize : {width : 320, height : 240, quality : 90},
        filters : [
            {title : "Image files", extensions : "jpg,gif,png"}
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