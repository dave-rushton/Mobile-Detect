$(function(){
    $('[name="galnam"]', galleryForm).on("keyup paste", function() {
        $('[name="seourl"]', galleryForm ).val( seoURL( $('[name="galnam"]', galleryForm).val() ) );
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

        var imageFiles = '';
        $('.imageselect.active').each(function(){
            imageFiles += (imageFiles == '') ? $(this).data('upl_id') : ',' + $(this).data('upl_id');
        });
        $.ajax({
            url: 'gallery/galleryimagecontrol.php',
            data: 'tblnam=' + $('[name="tblnam"]', galleryForm ).val() + '&tbl_id='+$('[name="gal_id"]', galleryForm).val() + '&filnam=' + imageFiles,
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
	
	var galleryForm = $('#galleryForm');
	
	galleryForm.submit(function(){
		
		if (galleryForm.valid()) {
			
//			alert( $('#categoriesForm').attr("action")+'?ajax=true&' + $('#categoriesForm').serialize() );
			
			$.ajax({
				url: galleryForm.attr("action"),
				data: 'action=update&ajax=true&' + galleryForm.serialize(),
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
						
						$('#id', galleryForm ).val( result.id );

						plupLoad( $('#plupload') );

                        $('#addImageBtn').show();
						
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
		}
		else {
			$.msgGrowl ({
				type: 'error'
				, title: 'Invalid Form'
				, text: 'There is an error in the form'
			});
		}
		return false;
	});
	
	$('#updateGalleryBtn').click(function(e){ 
		e.preventDefault();
		galleryForm.submit(); 
	});
	
	$('#deleteGalleryBtn').click(function(e) {
		e.preventDefault();
		$.msgAlert ({
			type: $(this).attr ('data-type')
			, title: 'Delete This Gallery'
			, text: 'Are you sure you wish to permanently remove this gallery from the database?'
			, callback: function () {
				
				$.ajax({
					url: galleryForm.attr("action"),
					data: 'action=delete&ajax=true&' + galleryForm.serialize(),
					type: 'POST',
					async: false,
					success: function( data ) {
						
						//alert( data );
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						window.location = galleryForm.data("returnurl");
						
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
				
			}
		});
		return false;
	});	
	
	if ( $('[name="gal_id"]', galleryForm).val() != '0' ) {
        $('#addImageBtn').show();
		plupLoad( $('#plupload') );	
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
        data: 'tblnam=' + $('[name="tblnam"]', galleryForm ).val() + '&tbl_id='+$('[name="gal_id"]', galleryForm).val(),
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
		max_file_size : '6mb',
		chunk_size : '6mb',
		unique_names : true,
		multiple_queues : true,
		//resize : {width : 320, height : 240, quality : 90},
		filters : [
		{title : "Image files", extensions : "jpg,gif,png"},
		{title : "SVG files", extensions : "svg"},
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