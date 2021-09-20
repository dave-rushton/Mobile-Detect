$(function(){
	var libraryForm = $('#libraryForm');

	$('[name="galnam"]', libraryForm).on("keyup paste", function() {
		$('[name="seourl"]', libraryForm ).val( seoURL( $('[name="galnam"]', libraryForm).val()));
	});
	
	libraryForm.submit(function(){

		if (libraryForm.valid()) {
			
			$.ajax({
				url: libraryForm.attr("action"),
				data: 'action=update&ajax=true&' + libraryForm.serialize(),
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
						
						$('#id', libraryForm ).val( result.id );

						plupLoad( $('#plupload') );	
						
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
	
	$('#updateLibraryBtn').click(function(e){ 
		e.preventDefault();
		libraryForm.submit(); 
	});
	
	$('#deleteLibraryBtn').click(function(e) {
		e.preventDefault();
		$.msgAlert ({
			type: $(this).attr ('data-type')
			, title: 'Delete This Library'
			, text: 'Are you sure you wish to permanently remove this library from the database?'
			, callback: function () {
				
				$.ajax({
					url: libraryForm.attr("action"),
					data: 'action=delete&ajax=true&' + libraryForm.serialize(),
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
						
						window.location = libraryForm.data("returnurl");
						
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
				
			}
		});
		return false;
	});	
	
	if ( $('[name="gal_id"]', libraryForm).val() != '0' ) {
		plupLoad( $('#plupload') );	
	}
	
	$('#libraryFiles').on('click', '.editUpload', function (e) {
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

                getLibrary();

			},
			error: function (x, e) {
				throwAjaxError(x, e);
			}
		});

	});
	
	
    $('#libraryFiles').on('click', '.deleteUpload', function (e) {
		e.preventDefault();

		var upl_id = $(this).data('upl_id');

		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This File'
			, text: 'Are you sure you wish to permanently remove this file from the database?'
			, callback: function () {

				$.ajax({
					url: 'gallery/uploads_script.php',
					data: 'action=delete&ajax=true&masterimage=true&upl_id=' + upl_id,
					type: 'POST',
					async: false,
					success: function( data ) {

						var result = JSON.parse(data);

						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});

						getLibrary();

					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});

			}
		});
		return false;
    });
	
	getLibrary();
});

function getLibrary() {

    $.ajax({
		url: 'downloads/uploads.library.php',
		data: 'tblnam=' + $('[name="tblnam"]', libraryForm ).val() + '&tbl_id='+$('[name="gal_id"]', libraryForm).val(),
		type: 'GET',
		async: false,
		success: function( data ) {
			
			$('#libraryFiles').html(data);
			
			$.msgGrowl ({
				type: 'success'
				, title: 'Library Retrieved'
				, text: 'Library Retrieved'
			});
			
		},
		error: function (x, e) {
			throwAjaxError(x, e);
		}
	});
	
	$(".colorbox-image").colorbox({
		maxWidth: "90%",
		maxHeight: "90%",
		rel: $(this).attr("rel")
	});
	
}

function plupLoad(iElement) {

    //alert( '../js/plugins/plupload/uploadfile.php?tblnam=' + $('[name="tblnam"]', libraryForm ).val() + '&tbl_id=' + $('[name="gal_id"]', libraryForm ).val() );

	var $el = iElement;
	$el.pluploadQueue({
		runtimes : 'html5,browserplus,silverlight,flash,gears,html4',
		url : '../js/plugins/plupload/uploadfile.php?tblnam=' + $('[name="tblnam"]', libraryForm ).val() + '&tbl_id=' + $('[name="gal_id"]', libraryForm ).val(),
		max_file_size : '10mb',
		chunk_size : '10mb',
		unique_names : true,
		multiple_queues : true,
		//resize : {width : 320, height : 240, quality : 90},
		filters : [
		{title : "PDF", extensions : "pdf"},
        {title : "WORD Docs", extensions : "doc,docx,txt"},
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
			getLibrary();
		}
	});
	
}