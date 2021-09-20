$(function(){





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





	if($(".gallery-dynamic").length > 0){

        $(".gallery-dynamic").imagesLoaded(function(){

            $(".gallery-dynamic").masonry({

                itemSelector: 'li',

                columnWidth: 201,

                isAnimated: true

            });

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

					data: 'action=delete&ajax=true&masterimage=true&&upl_id=' + upl_id + masterimage,

					type: 'POST',

					async: false,

					success: function( data ) {



                        //alert(data);



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

    plupLoad();



});



function getGallery() {



    $.ajax({

        url: 'gallery/globalgallerydisplay.php',

        data: 'tblnam=GLOBAL&tbl_id=0',

        type: 'GET',

        async: false,

        success: function( data ) {



            $('#globalimagelisting').html(data);



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



    // GLOBAL IMAGES



	$(".colorbox-image").colorbox({

		maxWidth: "90%",

		maxHeight: "90%",

		rel: $(this).attr("rel")

	});



    plupLoad( $('#plupload') );



}



function plupLoad(iElement) {



	var $el = iElement;

	$el.pluploadQueue({

		runtimes : 'html5,gears,flash,silverlight,browserplus',

        url : '../js/plugins/plupload/upload.php?resize=169-130&tblnam=GLOBAL&tbl_id=0',

		max_file_size : '6mb',

		chunk_size : '6mb',

		unique_names : true,

		multiple_queues : true,

		//resize : {width : 320, height : 240, quality : 90},

		filters : [

		{title : "Image files", extensions : "jpg,jpeg,gif,png"},

		{title : "SVG files", extensions : "svg"}

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
