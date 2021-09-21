var subCategoriesForm;

$(function(){
	
	subCategoriesForm = $('#subCategoriesForm');
	
	$('#updateSubCategoryBtn').click(function(e){
		e.preventDefault();
		subCategoriesForm.submit();
	});
	
	subCategoriesForm.submit(function(e){
	
		e.preventDefault();
		
		//alert( subCategoriesForm.serialize() );
		
		$.ajax({
			url: subCategoriesForm.attr("action"),
			data: 'action=update&ajax=true&' + subCategoriesForm.serialize(),
			type: 'POST',
			async: false,
			success: function( data ) {
				
				try {
					
					//alert( data );
					
					var result = JSON.parse(data);
					
					$.msgGrowl ({
						type: result.type
						, title: result.title
						, text: result.description
					});
				
					$('#id', subCategoriesForm).val( result.id );
					
					plupLoad( $('#plupload') );
						
					getGallery();
					
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
	
	if ( $('[name="sub_id"]', subCategoriesForm).val() != '0' ) {
		plupLoad( $('#plupload') );
		getGallery();
	}
	
});


function getGallery() {
	
	$.ajax({
		url: 'gallery/uploads.gallery.php',
		data: 'tblnam=PRDCAT&tbl_id='+$('[name="sub_id"]', subCategoriesForm).val(),
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
		url : '../js/plugins/plupload/upload.php?resize=169-130,620-414,960-400,2000-350&tblnam=PRDCAT&tbl_id=' + $('[name="sub_id"]', subCategoriesForm ).val(),
		max_file_size : '10mb',
		chunk_size : '2mb',
		unique_names : true,
		multiple_queues : true,
		//resize : {width : 320, height : 240, quality : 90},
		filters : [
		{title : "Image files", extensions : "jpg,gif,png"}
		],
		flash_swf_url : '../js/plugins/js/plupload/plupload.flash.swf',
		silverlight_xap_url : '../js/plugins/js/plupload/plupload.silverlight.xap'
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
		
		console.log(up);
		
		if (up.files.length == (up.total.uploaded + up.total.failed)) {
			getGallery();
		}
	});
	
}