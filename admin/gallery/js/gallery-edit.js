var galleryForm;
$(function(){
	$('#galleryListImages').on('click','.saveGalItem',function(e){
		e.preventDefault();
		var uploadJSON = [];
		listli = $(this).parents('li');

		upl_id = listli.find('input[name="upl_id"]').val();
		uplttl = listli.find('input[name="uplttl"]').val();
		upldsc = listli.find('textarea[name="upldsc"]').val();
		alttxt = listli.find('input[name="alttxt"]').val();
		urllnk = listli.find('input[name="urllnk"]').val();

		uploadJSON.push({
			alttxt:alttxt,
			upldsc:upldsc,
			upl_id:upl_id,
			uplttl:uplttl,
			urllnk:urllnk,
		});

		$.ajax({
			url: 'gallery/uploads_script.php',
			data: 'action=updatejson&json=' + JSON.stringify(uploadJSON),
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
	})




    galleryForm = $('#galleryForm');
    $('[name="galnam"]', galleryForm).on("keyup paste", function() {
        $('[name="seourl"]', galleryForm ).val( seoURL( $('[name="galnam"]', galleryForm).val() ) );
    });
    $('#addImageBtn').click(function(e){
        e.preventDefault();
        $('#galleryImagesDiv').hide();
        $('#uploadImagesDiv').show();
    });
	$('.tabs a').click(function(){
		$('.listbox').css('display','none');
		element = $(this).attr('href');
		$(element).css('display','block');
	})
    $('#cancelGalleryImagesBtn').click(function(e){
        e.preventDefault();
        $('#galleryImagesDiv').show();
        $('#uploadImagesDiv').hide();
    });

	$('#action').on('change',function(){
		$('.gal-options').hide();
		var val = $(this).val();
		$("#"+val+'-select').show();
	});

	gallerySearchForm = $('#gallerySearchForm');
	gallerySearchForm.submit(function(e){
		e.preventDefault();
		var val = $('#action').val();
		var Tbl_ID = $('[name="gal_id1"]', $("#"+val+'-select')).val();

		var KeyWrd = $('#keywrd1').val();

		var Tblnam;

		if (val == 'gallery') {
			Tblnam = (Tbl_ID == 0) ? 'GLOBAL' : 'WEBGALLERY';
		} else {
			Tblnam = val.toUpperCase();
		}

		// alert('tblnam='+Tblnam+'&tbl_id='+Tbl_ID+'&keywrd=' + $('[name="keywrd"]', gallerySearchForm).val())
		$.ajax({
			url: 'gallery/uploads.gallery.global.php',
			data: 'tblnam='+Tblnam+'&tbl_id='+Tbl_ID+'&keywrd='+KeyWrd,
			type: 'GET',
			async: false,
			success: function( data ) {
				$('#imagelisting').html(data);

				$(".colorbox-image").colorbox({
					maxWidth: "90%",
					maxHeight: "90%",
					rel: $(this).attr("rel")
				});

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

	$('input[name="imgsiz"]').blur(function(){
		element = $(this);
		val= element.val().split(' ').join('');
		element.val(val);
	});

    $('#rebuildGalleryBtn').click(function(e){
        e.preventDefault();

		$(this).addClass('btn-primary');

		//$('#imageListing').show();
		//$('#galleryImages').hide();

		$.ajax({
			url: 'gallery/uploads.gallery.global.php',
			data: 'tblnam=WEBGALLERY'+'&tbl_id='+$('[name="gal_id"]').val(),
			type: 'GET',
			async: false,
			success: function( data ) {
				$('#galleryImages').html(data);
				$('#galleryImages').addClass('rebuild');
				$('#rebuildBtnWrapper').show();
				$('#addImageBtn').hide();

				$(".colorbox-image").colorbox({
					maxWidth: "90%",
					maxHeight: "90%",
					rel: $(this).attr("rel")
				});

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
    });

	$('#confirmRebuildBtn').click(function(e) {
		e.preventDefault();
		$.msgAlert ({
		    type: 'warning'
		    , title: 'Rebuild This Gallery'
		    , text: 'Are you sure you wish to rebuild these selected images?'
		    , callback: function () {


				var uplStr = '';

				$('a.active').each(function() {
					console.log($(this));
					uplStr += $(this).data('upl_id') + ',';
				});

				uplStr = uplStr.replace(/,\s*$/, "");

				$.ajax({
					url: 'gallery/rebuildgallery.php',
					data: 'gal_id=' + $('[name="gal_id"]', galleryForm).val() + '&uplstr=' + uplStr,
					type: 'POST',
					async: false,
					success: function( data ) {
						hideRebuildUI();
						getGallery();
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});

		    }
		});
	});

	$('#rebuildAllBtn').click(function(e) {

		e.preventDefault();

		$.msgAlert ({
			type: 'warning'
			, title: 'Rebuild This Gallery'
			, text: 'Are you sure you wish to rebuild the whole gallery?'
			, callback: function () {
				$.ajax({
					url: 'gallery/rebuildgallery.php',
					data: 'gal_id=' + $('[name="gal_id"]', galleryForm).val(),
					type: 'POST',
					async: false,
					success: function( data ) {
						hideRebuildUI();
						getGallery();
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
			}
		});
	});

	$('#cancelRebuildBtn').click(function(e) {
		e.preventDefault();
		hideRebuildUI();
		getGallery();
	});

	function hideRebuildUI() {
		$('#galleryImages').removeClass('rebuild');
		$('#rebuildBtnWrapper').hide();
		$('#rebuildGalleryBtn').removeClass('btn-primary');
		$('#addImageBtn').show();
	}


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


	$('.gallery-dynamic').on('click', '.transfereUpload', function (e) {
		e.preventDefault();
		var uplId = $(this).data('upl_id');
		$('#transfereForm input[name="upl_id"]').val(uplId);
		$('#transfereModal').modal('show');
	});

	$('#transfereForm').submit(function(e){
		e.preventDefault();

		// alert($('#transfereForm').serialize())

		$.ajax({
			url: 'gallery/uploads_script.php',
			data: 'action=transfere&' + $('#transfereForm').serialize(),
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
					$('#transfereModal').modal('hide');
				}

				getGallery();

			},
			error: function (x, e) {
				throwAjaxError(x, e);
			}
		});

	});

    $('#imagelisting, #galleryImages').on('click','.selectUpload',function(e){
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

	galleryForm.submit(function(){

		if (galleryForm.valid()) {
			$(this).block({
				message: '<h4>Updating</h4>',
				centerY: 0,
				centerX: 0,
				css: { top: '10px', left: '', right: '10px', border: '2px solid #a00'}
			});

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
		$(this).unblock();
		return false;
	});

	$('#updateGalleryBtn').click(function(e){
		e.preventDefault();
		hideRebuildUI();
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
                //console.log(data);
                var imgArray = JSON.parse(data);
                $('[name="upl_id"]', $('#imageForm')).val( imgArray[0].upl_id );
                $('[name="uplttl"]', $('#imageForm')).val( imgArray[0].uplttl );
                $('[name="upldsc"]', $('#imageForm')).val( imgArray[0].upldsc );
                $('[name="urllnk"]', $('#imageForm')).val( imgArray[0].urllnk );
                $('[name="alttxt"]', $('#imageForm')).val( imgArray[0].alttxt );
				$('[name="quotefrom"]', $('#imageForm')).val( getJSONvariable('quotefrom',imgArray[0].uplobj) );
				$('[name="quotepos"]', $('#imageForm')).val( getJSONvariable('quotepos',imgArray[0].uplobj) );
            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });
        $('#imageModal').modal('show');
    });



	// $('.saveGalleryItem').click(function(e){
	// 	e.preventDefault();
    //
    //
	// 	alert("")
	// 	listli = $(this).parents('li');
	// 	var uploadJSON = [];
	// 	upl_id = listli.find('input[name="upl_id"]').val();
	// 	uplttl = listli.find('input[name="uplttl"]').val();
	// 	upldsc = listli.find('textarea[name="upldsc"]').val();
	// 	alttxt = listli.find('input[name="alttxt"]').val();
	// 	urllnk = listli.find('input[name="urllnk"]').val();
    //
	//
	// 	uploadJSON.push({
	// 		alttxt:alttxt,
	// 		upldsc:upldsc,
	// 		upl_id:upl_id,
	// 		uplttl:uplttl,
	// 		urllnk:urllnk,
	// 	});
    //
	// 	$.ajax({
	// 		url: 'gallery/uploads_script.php',
	// 		data: 'action=updatejson&json=' + JSON.stringify(uploadJSON),
	// 		type: 'GET',
	// 		async: false,
	// 		success: function( data ) {
	// 			var result = JSON.parse(data);
	// 			$.msgGrowl ({
	// 				type: result.type
	// 				, title: result.title
	// 				, text: result.description
	// 			});
    //
	// 			if ( result.type != 'Error' ) {
	// 				$('#imageModal').modal('hide');
	// 			}
    //
	// 		},
	// 		error: function (x, e) {
	// 			throwAjaxError(x, e);
	// 		}
	// 	});
    //
	// });
	$('#updateList').click(function(e){
		e.preventDefault();
		var uploadJSON = [];

		$('#galleryListImages li').each(function(){
			upl_id = $(this).find('input[name="upl_id"]').val();
			uplttl = $(this).find('input[name="uplttl"]').val();
			upldsc = $(this).find('textarea[name="upldsc"]').val();
			alttxt = $(this).find('input[name="alttxt"]').val();
			urllnk = $(this).find('input[name="urllnk"]').val();
			uploadJSON.push({
				alttxt:alttxt,
				upldsc:upldsc,
				upl_id:upl_id,
				uplttl:uplttl,
				urllnk:urllnk,
			});
		})

		$.ajax({
			url: 'gallery/uploads_script.php',
			data: 'action=updatejson&json=' + JSON.stringify(uploadJSON),
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
    $('.gallery-dynamic').on('click', '.moveUpload', function (e) {
        e.preventDefault();
    });

	$('#imageForm').submit(function(e){
		e.preventDefault();

		var fields = $(".customfield", $(this)).serializeArray();
		var elementVariables = JSON.stringify(fields);
		var postData = encodeURIComponent(elementVariables);

		$.ajax({
			url: 'gallery/uploads_script.php',
			data: 'action=update&' + $('#imageForm').serialize() + '&uplobj=' + postData,
			type: 'GET',
			async: false,
			success: function( data ) {

				var result = JSON.parse(data);
				getGallery();
				 //$.msgGrowl ({
				 //	type: result.type
				 //	, title: result.title
				 //	, text: result.description
				 //});

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

	$('.gallery-dynamic').on('click', '.cropUpload', function (e) {
		var href = $(this).attr("href");
		href += '&imgsiz=' + galleryForm.attr('data-imgsiz') + ',' + $('[name="imgsiz"]', galleryForm ).val();
		$(this).attr("href", href);
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
					getLists();
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
	$('#galleryListImages').sortable({
		handle: ".moveUpload",
		stop: function(){

			var images = $('#galleryListImages').find('.moveUpload');

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

					$.msgGrowl ({
						type: 'success'
						, title: 'Gallery Re-Ordered'
						, text: 'Gallery Re-Ordered'
					});
					getThumbs();

				},
				error: function (x, e) {
					throwAjaxError(x, e);
				}
			});

		}
	});

});
function getThumbs() {
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
			// $.msgGrowl ({
			//     type: 'success'
			//     , title: 'Gallery Retrieved'
			//     , text: 'Gallery Retrieved'
			// });
		},
		error: function (x, e) {
			throwAjaxError(x, e);
		}
	});
	$('imageselect').removeClass('active');
	// GLOBAL IMAGES

	$(".colorbox-image").colorbox({
		maxWidth: "90%",
		maxHeight: "90%",
		rel: $(this).attr("rel")
	});
}
function getLists() {
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
		url: 'gallery/uploads.gallery.list.php',
		data: 'tblnam=' + $('[name="tblnam"]', galleryForm ).val() + '&tbl_id='+$('[name="gal_id"]', galleryForm).val(),
		type: 'GET',
		async: false,
		success: function( data ) {
			$('#galleryListImages').html(data);
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
			// $.msgGrowl ({
			// 	type: 'success'
			// 	, title: 'Gallery Retrieved'
			// 	, text: 'Gallery Retrieved'
			// });
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
		url: 'gallery/uploads.gallery.list.php',
		data: 'tblnam=' + $('[name="tblnam"]', galleryForm ).val() + '&tbl_id='+$('[name="gal_id"]', galleryForm).val(),
		type: 'GET',
		async: false,
		success: function( data ) {
			$('#galleryListImages').html(data);
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
			// $.msgGrowl ({
			// 	type: 'success'
			// 	, title: 'Gallery Retrieved'
			// 	, text: 'Gallery Retrieved'
			// });
		},
		error: function (x, e) {
			throwAjaxError(x, e);
		}
	});
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
            // $.msgGrowl ({
            //     type: 'success'
            //     , title: 'Gallery Retrieved'
            //     , text: 'Gallery Retrieved'
            // });
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
    //$el.data('resize')

	var $el = iElement;
	$el.pluploadQueue({
		runtimes : 'html5,gears,flash,silverlight,browserplus',
		url : '../js/plugins/plupload/upload.php?resize='+ $('[name="imgsiz"]', galleryForm ).val() +',169-130&tblnam=' + $('[name="tblnam"]', galleryForm ).val() + '&tbl_id='+$('[name="gal_id"]', galleryForm).val(),
		max_file_size : '6mb',
		chunk_size : '6mb',
		unique_names : true,
		multiple_queues : true,
		//resize : {width : 320, height : 240, quality : 90},
		filters : [
		{title : "Image files", extensions : "jpg,jpeg,ggif,png"},
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