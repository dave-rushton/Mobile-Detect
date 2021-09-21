var productForm, attlEntryForm, relatedForm;

$(function(){
	
	productForm = $('#productForm');
	attlEntryForm = $('#attlEntryForm');
    relatedForm = $('#relatedForm');

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
            content_css: $('#webRoot').val() + "pages/css/styles.css",

            //theme_advanced_blockformats: "p,h1,h2,h3,h4,blockquote"

        }];

    tinyMCE.settings = tinymceConfigs[1];
    tinyMCE.execCommand('mceAddEditor', false, 'prddsc');
    tinyMCE.execCommand('mceAddEditor', false, 'prdspc');
	
	$('[name="prdtagselect"]', productForm).each(function(){
		var $el = $(this);
		var search = ($el.attr("data-nosearch") === "true") ? true : false,
		opt = {};
		if(search) opt.disable_search_threshold = 9999999;
		$el.chosen(opt);
		
		resize_chosen();
		
	});
	
	
	$('[name="atr_id"]', productForm).change(function(){
		
		$.ajax({
			url: 'attributes/ajax/attribute_form.php',
			data: 'atr_id=' + $(this).val() + '&atvtblnam=PRODUCT&atvtbl_id=' + $('[name="prd_id"]', productForm).val(),
			type: 'GET',
			async: true,
			success: function( data ) {
				
				$('#attlEntryForm').html(data);
	
			},
			error: function (x, e) {
				throwAjaxError(x, e);
			}
		});
	
	});
	$('[name="atr_id"]', productForm).change();
	
	
	$('[name="prdnam"], [name="altref"]', productForm).on("keyup paste", function() {

        $('[name="seourl"]', productForm ).val( seoURL( $('[name="atr_id"] option:selected', productForm).text() + ' ' + $('[name="prdnam"]', productForm).val() ) );

	});

    $('[name="atr_id"]', productForm).on("change", function() {

        $('[name="seourl"]', productForm ).val( seoURL( $('[name="atr_id"] option:selected', productForm).text() + ' ' + $('[name="prdnam"]', productForm).val() ) );

    });

	productForm.submit(function(e){
		e.preventDefault();

        tinyMCE.triggerSave('prddsc');
        tinyMCE.triggerSave('prdspc');
		
		if (productForm.valid()) {
			
			$('[name="prdtag"]', productForm).val( $('[name="prdtagselect"]', productForm).val() );
			
			$.ajax({
				url: productForm.attr("action"),
				data: 'action=update&ajax=true&' + productForm.serialize(),
				type: 'POST',
				async: false,
				success: function( data ) {

                    // alert(data);
					
					try {
					
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						$('#id', productForm ).val( result.id );
						$('#AtvTbl_ID', attlEntryForm ).val( result.id );
						
						attlEntryForm.submit();
						
						plupLoad( $('#plupload') );
						
						getGallery();
					
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
				
		} else {
			$.msgGrowl ({
				type: 'error'
				, title: 'Invalid Form'
				, text: 'There is an error in the form'
			});
		}
		
		
	});	
	
	attlEntryForm.submit(function(e){
		e.preventDefault();
		
		$(':radio:checked', attlEntryForm).each(function(){
			$(this).parent().parent().find(':hidden').val( $(this).val() );
		});
		
		$(':checkbox', attlEntryForm).each(function(){
			$(this).parent().next(':hidden').val( ($(this).is(':checked')) ? 1 : 0 );
		});
		
		if ($(this).valid()) {
			
			$.ajax({
				url: attlEntryForm.attr("action"),
				data: 'action=update&ajax=true&' + attlEntryForm.serialize(),
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
					
					/*getProductsTable();*/
					
					//$('#id', $('#attrLabelForm') ).val( result.id );

				},
				error: function (x, e) {
					//throwAjaxError(x, e);
				}
			});
			/*
			$('#variantBox').hide();
			$('#variantTableBox').show();
			*/
		}
		else {
			$.msgGrowl ({
				type: 'error'
				, title: 'Invalid Form'
				, text: 'There is an error in the form'
			});
		}
		
	});

    //
    // BUILD RELATED LIST
    //

    var prddata;

    $('#relatedProductBox').block({ message: 'Retrieving Products' });

    $.ajax({
        url: "products/products_script.php",
        data: 'action=select&prd_id=0',
        type: "GET",
        async: true,
        success: function(data) {

            prddata = JSON.parse( data );

            $(':input.autocomplete').typeahead({
                source: function(query, process) {
                    objects = [];
                    map = {};
                    var data = prddata; // Or get your JSON dynamically and load it into this variable
                    $.each(data, function(i, object) {

                        map[object.prdnam + ' (' + object.atrnam + ')'] = object;
                        objects.push(object.prdnam + ' (' + object.atrnam + ')');
                    });

                    return objects;

                    //process(objects);
                },
                updater: function(item) {

                    $('#relatedName', relatedForm).val(item);

                    $('[name="ref_id"]', relatedForm).val(map[item].prd_id);
                    return item;
                }
            });

            $('#relatedProductBox').unblock();

        }
    });


    relatedForm.submit(function(e){

        e.preventDefault();

        $.ajax({
            url: 'system/related_script.php',
            data: 'action=create&ajax=true&tblnam=PRODUCT&tbl_id=' + $('[name="tbl_id"]', relatedForm).val() + '&refnam=PRODUCT&ref_id=' + $('[name="ref_id"]', relatedForm).val(),
            type: 'POST',
            async: false,
            success: function( data ) {

                getRelatedProducts();

                $('[name="refnam"]', relatedForm).val('');
                $('[name="ref_id"]', relatedForm).val(0);

            }
        });

    });

    $('#relatedProductList').on('click', '.removeRelated', function(e){

        $el = $(this);

        e.preventDefault();

        $.ajax({
            url: 'system/related_script.php',
            data: 'action=delete&ajax=true&rel_id=' + $el.data('rel_id'),
            type: 'POST',
            async: false,
            success: function( data ) {


                var result = JSON.parse(data);

                $.msgGrowl ({
                    type: result.type
                    , title: result.title
                    , text: result.description
                });

                $el.parent().fadeOut();

            }
        });

    });

    getRelatedProducts();

	$('#updateProductBtn').click(function(e){
		e.preventDefault();	
		productForm.submit();
	});

    $('#deleteProductBtn').click(function (e) {
        e.preventDefault();

        //
        // Check if product is in basket
        //



        $.msgAlert ({
            type: 'warning'
            , title: 'Delete This Product'
            , text: 'Are you sure you wish to permanently remove this product from the database?'
            , callback: function () {

                $.ajax({
                    url: productForm.attr("action"),
                    data: 'action=delete&ajax=true&' + productForm.serialize(),
                    type: 'POST',
                    async: false,
                    success: function( data ) {

                        alert(data);

                        var result = JSON.parse(data);

                        $.msgGrowl ({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });

                        if (result.type == 'success') window.location = productForm.data("returnurl");

                    },
                    error: function (x, e) {
                        throwAjaxError(x, e);
                    }
                });

            }
        });
        return false;
    });


    $('#galleryImages').on('click', '.editUpload', function (e) {
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


    $('#galleryImages').on('click', '.deleteUpload', function (e) {
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


    if ( $('[name="prd_id"]', productForm).val() != '0' ) {
		plupLoad( $('#plupload') );
        plupLoadFile( $('#pdfplupload') );
		getGallery();
	}



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

            data: 'tblnam=PRODUCT&tbl_id='+$('[name="prd_id"]', productForm).val() + '&filnam=' + imageFiles,

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

	$.ajax({
		url: 'gallery/uploads.gallery.php',
		data: 'tblnam=PRODUCT&tbl_id='+$('[name="prd_id"]', productForm).val(),
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

    $.ajax({
        url: 'gallery/uploads.files.php',
        data: 'tblnam=PRODUCTFIL&tbl_id='+$('[name="prd_id"]', productForm).val(),
        type: 'GET',
        async: false,
        success: function( data ) {

            $('#galleryPDFs').html(data);

            $.msgGrowl ({
                type: 'success'
                , title: 'Documents Retrieved'
                , text: 'Documents Retrieved'
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
		url : '../js/plugins/plupload/upload.php?resize=169-130,620-414,960-400,2000-350&tblnam=PRODUCT&tbl_id=' + $('[name="prd_id"]', productForm ).val(),
		max_file_size : '8mb',
		chunk_size : '8mb',
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
		
		if (up.files.length == (up.total.uploaded + up.total.failed)) {
			getGallery();
		}
	});
	
}


function plupLoadFile(iElement) {

    var $el = iElement;
    $el.pluploadQueue({
        runtimes : 'html5,gears,flash,silverlight,browserplus',
        url : '../js/plugins/plupload/uploadfile.php?tblnam=PRODUCTFIL&tbl_id=' + $('[name="prd_id"]', productForm ).val(),
        max_file_size : '10mb',
        chunk_size : '2mb',
        unique_names : false,
        multiple_queues : true,
        //resize : {width : 320, height : 240, quality : 90},
        filters : [
            {title : "Documents", extensions : "pdf,doc,dox"}
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

        if (up.files.length == (up.total.uploaded + up.total.failed)) {
            getGallery();
        }
    });

}


function getRelatedProducts() {

    $.ajax({
        url: 'system/related_script.php',
        data: 'action=relatedproducts&ajax=true&tblnam=PRODUCT&tbl_id=' + $('[name="tbl_id"]', relatedForm).val() + '&refnam=PRODUCT',
        type: 'POST',
        async: false,
        success: function( data ) {

            var jsonArray = JSON.parse(data);

            var resultHTML = '';

            for (i=0;i<jsonArray.length;i++) {
                resultHTML = resultHTML + '<li>'+jsonArray[i].prdnam+'<a href="#" class="removeRelated pull-right" data-rel_id="'+jsonArray[i].rel_id+'"><i class="icon-remove"></i> </a> </li>';
            }

            $('#relatedProductList').html(resultHTML);

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