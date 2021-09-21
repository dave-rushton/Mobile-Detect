$(function(){
	
	CKEDITOR.replace("prtdsc", {
		filebrowserBrowseUrl : 'system/elfinder.php',
		toolbarGroups: [
				{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },			// Group's name will be used to create voice label.
				'/',																// Line break - next group will be placed in new line.
				{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
				{ name: 'links' },
				{ items: [ 'Image', 'Table' ] },
				{ items: [ 'Format', 'Maximize' ] }
			]
		}
	);
	
	var productTypeForm = $('#productTypeForm');
	
//	var productEntryForm = $('#productEntryForm');
//	var productForm = $('#productForm');
//	var attlEntryForm = $('#attlEntryForm');
//	
//	$('[name="atr_id"]', productEntryForm).chosen();
//	resize_chosen();
//	
//	$('[name="atr_id"]', productEntryForm).change(function(){
//		
//		$('[name="atr_id"]').val( $(this).val() );
//		
//	});
//	
//	$('[name="atr_id"]', productEntryForm).change();
//	
//	productEntryForm.submit(function(e){
//		
//		e.preventDefault();
//		
//		//alert(productEntryForm.serialize());
//		
//		if (productEntryForm.valid()) {
//			
//			$.ajax({
//				url: productEntryForm.attr("action"),
//				data: 'action=update&ajax=true&' + productEntryForm.serialize(),
//				type: 'POST',
//				async: false,
//				success: function( data ) {
//					
//					//alert( data );
//					
//					try {
//					
//						var result = JSON.parse(data);
//						
//						$.msgGrowl ({
//							type: result.type
//							, title: result.title
//							, text: result.description
//						});
//						
//						$('#id', productEntryForm ).val( result.id );
//						$('[name="tbl_id"]', productForm ).val( result.id );
//						$('[name="prt_id"]', productForm ).val( result.id );
//					
//					} catch (Ex) {
//						
//						$.msgGrowl ({
//							type: 'error'
//							, title: 'Update Failure'
//							, text: 'The server replied with an error'
//						});
//						
//					}
//					
//				},
//				error: function (x, e) {
//					throwAjaxError(x, e);
//				}
//			});
//		
//		}
//		
//	});
	
	$('#updateProductType').click(function(e){
		e.preventDefault();
		
		CKEDITOR.instances['prtdsc'].updateElement();
		
		productTypeForm.submit();
	});
	
//	$('#createVariantBtn').click(function(e){
//		e.preventDefault();
//		clearProductForm();
//		$('#variantBox').show();
//		$('#variantTableBox').hide();
//	});
//	
	productTypeForm.submit(function(e){
		e.preventDefault();
		
		if (productTypeForm.valid()) {
			
			$.ajax({
				url: productTypeForm.attr("action"),
				data: 'action=update&ajax=true&' + productTypeForm.serialize(),
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
						
						$('#id', productTypeForm ).val( result.id );
						
						plupLoad( $('#plupload') );	
					
					} catch (Ex) {
						
						$.msgGrowl ({
							type: 'error'
							, title: 'Update Failure'
							, text: 'The server replied with an error'
						});
						
					}
					
				},
				error: function (x, e) {
					throwAjaxError(x, e);
				}
			});
			
			//attlEntryForm.submit();
				
		} else {
			$.msgGrowl ({
				type: 'error'
				, title: 'Invalid Form'
				, text: 'There is an error in the form'
			});
		}
		
	});
//	
//	attlEntryForm.submit(function(e){
//		e.preventDefault();
//		
//		$(':radio:checked', attlEntryForm).each(function(){
//			$(this).parent().parent().find(':hidden').val( $(this).val() );
//		});
//		
//		$(':checkbox', attlEntryForm).each(function(){
//			$(this).parent().next(':hidden').val( ($(this).is(':checked')) ? 1 : 0 );
//		});
//		
//		if ($(this).valid()) {
//			
//			$.ajax({
//				url: attlEntryForm.attr("action"),
//				data: 'action=update&ajax=true&' + attlEntryForm.serialize(),
//				type: 'POST',
//				async: false,
//				success: function( data ) {
//					
//					//alert(data);
//					
//					var result = JSON.parse(data);
//					
//					$.msgGrowl ({
//						type: result.type
//						, title: result.title
//						, text: result.description
//					});
//					
//					getProductsTable();
//					
//					//$('#id', $('#attrLabelForm') ).val( result.id );
//
//				},
//				error: function (x, e) {
//					throwAjaxError(x, e);
//				}
//			});
//			
//			$('#variantBox').hide();
//			$('#variantTableBox').show();
//			
//		}
//		else {
//			$.msgGrowl ({
//				type: 'error'
//				, title: 'Invalid Form'
//				, text: 'There is an error in the form'
//			});
//		}
//		
//	});
//	
//	$('#updateVariantBtn').click(function(e){
//		e.preventDefault();
//		
//		productForm.submit();
//		
//	});
//	
//	$('#cancelVariantBtn').click(function(e){
//		e.preventDefault();
//		$('#variantBox').hide();
//		$('#variantTableBox').show();
//	});
//
//
//	$('#productBody').on('click', '.editProduct', function (e) {
//		e.preventDefault();
//		
//		var prdId = $(this).data('prd_id');
//		
//		$.ajax({
//			url: 'ecommerce/products_script.php',
//			data: 'action=select&ajax=true&prd_id=' + prdId,
//			type: 'POST',
//			async: false,
//			success: function( data ) {
//				
//				var productRec = JSON.parse(data);
//				
//				$('[name="atr_id"]', productForm).val( productRec[0].atr_id );
//				$('[name="prd_id"]', productForm).val( productRec[0].prd_id );
//				$('[name="prdnam"]', productForm).val( productRec[0].prdnam );
//				$('[name="unipri"]', productForm).val( productRec[0].unipri );
//				$('[name="buypri"]', productForm).val( productRec[0].buypri );
//				$('[name="delpri"]', productForm).val( productRec[0].delpri );
//				$('[name="usestk"]', productForm).val( productRec[0].usestk );
//				$('[name="in_stk"]', productForm).val( productRec[0].in_stk );
//				$('[name="on_ord"]', productForm).val( productRec[0].on_ord );
//				$('[name="on_del"]', productForm).val( productRec[0].on_del );
//				
//				$.ajax({
//					url: 'attributes/ajax/attribute_form.php',
//					data: 'atr_id=' + $('[name="atr_id"]', productForm).val() + '&atvtblnam=PRODUCT&atvtbl_id=' + $('[name="prd_id"]', productForm).val(),
//					type: 'GET',
//					async: true,
//					success: function( data ) {
//						
//						$('#attlEntryForm').html(data);
//		
//					},
//					error: function (x, e) {
//						throwAjaxError(x, e);
//					}
//				});
//				
//				$('#variantBox').show();
//				$('#variantTableBox').hide();
//
//			},
//			error: function (x, e) {
//				throwAjaxError(x, e);
//			}
//		});
//	});
//	
//	$('#productBody').on('click', '.deleteProduct', function (e) {
//		e.preventDefault();
//		
//		var prdId = $(this).data('prd_id');
//		
//		$.msgAlert ({
//			type: 'warning'
//			, title: 'Delete This Product Variant'
//			, text: 'Are you sure you wish to permanently remove this product variant from the database?'
//			, callback: function () {
//				
//				$.ajax({
//					url: 'ecommerce/products_script.php',
//					data: 'action=delete&ajax=true&prd_id=' + prdId,
//					type: 'POST',
//					async: false,
//					success: function( data ) {
//						
//						var result = JSON.parse(data);
//						
//						$.msgGrowl ({
//							type: result.type
//							, title: result.title
//							, text: result.description
//						});
//						
//						getProductsTable();
//		
//					},
//					error: function (x, e) {
//						throwAjaxError(x, e);
//					}
//				});
//				
//			}
//		});
//		
//	});
//	
//	getProductsTable();
//	
//	if ( $('[name="prt_id"]', productEntryForm).val() != '0' ) {
//		plupLoad( $('#plupload') );	
//	}
//	
//	$('.gallery-dynamic').on('click', '.deleteUpload', function (e) {
//		e.preventDefault();
//		
//		var upl_id = $(this).data('upl_id');
//		
//		$.msgAlert ({
//			type: 'warning'
//			, title: 'Delete This Image'
//			, text: 'Are you sure you wish to permanently remove this image from the database?'
//			, callback: function () {
//				
//				$.ajax({
//					url: 'gallery/uploads_script.php',
//					data: 'action=delete&ajax=true&upl_id=' + upl_id,
//					type: 'POST',
//					async: false,
//					success: function( data ) {
//						
//						var result = JSON.parse(data);
//						
//						$.msgGrowl ({
//							type: result.type
//							, title: result.title
//							, text: result.description
//						});
//						
//						getGallery();
//						
//					},
//					error: function (x, e) {
//						throwAjaxError(x, e);
//					}
//				});
//				
//			}
//		});
//		return false;
//    }); 
	
	$('[name="prtnam"]', productTypeForm).on("keyup", function() {
		$('[name="seourl"]', productTypeForm ).val( seoURL( $(this).val() ) );
	});
	
	getGallery();
	
});

//function getProductsTable() {
//	
//	//alert( 'ecommerce/products_table.php?tblnam=PRODUCT&tbl_id=' + $('[name="prt_id"]', productEntryForm).val() );
//	
//	$.ajax({
//		url: 'ecommerce/type_products_table.php',
//		data: 'tblnam=PRODUCT&prt_id=' + $('[name="prt_id"]', productEntryForm).val(), // + productEntryForm.serialize(),
//		type: 'GET',
//		async: false,
//		success: function( data ) {
//			
//			$('#productBody').html(data);
//			
//		}
//	});
//	
//}
//
//function clearProductForm() {
//
//	$('[name="prd_id"]', productForm).val(0);
//	
//	$('[name="prt_id"]', productForm).val( $('[name="prt_id"]', productEntryForm).val() );
//	$('[name="prdnam"]', productForm).val( $('[name="prtnam"]', productEntryForm).val() );
//	
//	$('[name="unipri"]', productForm).val( $('[name="unipri"]', productEntryForm).val() );
//	$('[name="buypri"]', productForm).val( $('[name="buypri"]', productEntryForm).val() );
//	$('[name="delpri"]', productForm).val( $('[name="delpri"]', productEntryForm).val() );
//	$('[name="usestk"]', productForm).val(1);
//	
//	$('[name="in_stk"]', productForm).val(0);
//	$('[name="on_ord"]', productForm).val(0);
//	$('[name="on_del"]', productForm).val(0);
//	
//	$.ajax({
//		url: 'attributes/ajax/attribute_form.php',
//		data: 'atr_id=' + $('[name="atr_id"]', productForm).val() + '&atvtblnam=PRODUCT&atvtbl_id=' + $('[name="prd_id"]', productForm).val(),
//		type: 'GET',
//		async: true,
//		success: function( data ) {
//			
//			$('#attlEntryForm').html(data);
//
//		},
//		error: function (x, e) {
//			throwAjaxError(x, e);
//		}
//	});
//	
//}
//

function getGallery() {
	
	$.ajax({
		url: 'gallery/uploads.gallery.php',
		data: 'tblnam=PRDTYPE&tbl_id='+$('[name="prt_id"]', productTypeForm).val(),
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
	
}

function plupLoad(iElement) {

	var $el = iElement;
	$el.pluploadQueue({
		runtimes : 'html5,gears,flash,silverlight,browserplus',
		url : '../js/plugins/plupload/upload.php?resize=169-130,620-414,960-400,2000-350&tblnam=PRDTYPE&tbl_id=' + $('[name="prt_id"]', productTypeForm ).val(),
		max_file_size : '10mb',
		chunk_size : '1mb',
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