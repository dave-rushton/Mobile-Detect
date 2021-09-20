var locationForm;

var geocoder;
var map;
var marker = [];

$(function(){

    locationForm = $('#locationForm');

    $('[name="comnam"]', locationForm).on("keyup paste", function() {
        $('[name="planam"]', locationForm ).val( $('[name="comnam"]', locationForm).val() );
    });

    $('[name="planam"]', locationForm).on("keyup paste", function() {
        $('[name="seourl"]', locationForm ).val( seoURL( $('[name="planam"]', locationForm).val() ) );
    });

	$('#adrControl').click(function(e){
		e.preventDefault();
		$('#adrInputs').slideToggle(400, function(){
			$('#adrControl i').toggleClass('icon-angle-down').toggleClass('icon-angle-up');
		});
	});

	
	initialize();
	
	if (marker) {
		for (var i = 0; i < marker.length; i++ ) {
			marker[i].setMap(null);
		}
	}
	
	if ( $('#GooLat', locationForm).val() != '' && $('#GooLng', locationForm).val() != '' ) {
		placeMarker( $('#GooLat', locationForm).val() , $('#GooLng', locationForm).val() );
	} else if ($('#GooGeo', locationForm).val() != '') {
		codeAddress();
	}	
	
	$('#geoLocate').click(function(e){
		e.preventDefault();
		codeAddress( $('[name="pstcod"]', locationForm).val() );	
	});
	
	
	$('#updateLocationBtn').click(function(e){
		e.preventDefault();
		locationForm.submit();
	});
	
	locationForm.submit(function(e){

		e.preventDefault();

        tinyMCE.triggerSave('cusfld2');
        $('[name="cusfld2"]', locationForm).val($('#cusfld2', locationForm).val());

        var fields = $(".customfield", locationForm).serializeArray();
        var elementVariables = JSON.stringify(fields);
        var postData = encodeURIComponent(elementVariables);



        //
        // CHECK LOGO (CRAPPY CALL BACK HANDLING)
        //

        if( $('#logofile')[0].files[0] ) {

            var data;

            data = new FormData();
            data.append('logofile', $('#logofile')[0].files[0]);
            data.append('newname', $('[name="seourl"]', locationForm).val());

            $.ajax({
                url: 'locations/uploadlocationimage.php',
                data: data,
                processData: false,
                type: 'POST',
                contentType: false,
                aSync: false,
                success: function (data) {

                    $('[name="plaimg"]', locationForm).val(data);

                    $.ajax({
                        url: locationForm.attr("action"),
                        data: 'action=update&ajax=true&' + locationForm.serialize() + '&platxt=' + postData,
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

                                $('#id', locationForm).val( result.id );

                                //window.location = locationForm.data("returnurl");

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
                            alert(x + ' ' + e);
                            throwAjaxError(x, e);

                        }
                    });


                },
                error: function (x, e) {

                    throwAjaxError(x, e);

                }
            });

        } else {


            $.ajax({
                url: locationForm.attr("action"),
                data: 'action=update&ajax=true&' + locationForm.serialize() + '&platxt=' + postData,
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

                        $('#id', locationForm).val( result.id );

                        //window.location = locationForm.data("returnurl");

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
                    alert(x + ' ' + e);
                    throwAjaxError(x, e);

                }
            });

        }
		
	
	});
	
	$('#deleteLocationBtn').click(function (e) {
		e.preventDefault();
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Location'
			, text: 'Are you sure you wish to permanently remove this location from the database?'
			, callback: function () {
				
				$.ajax({
					url: locationForm.attr("action"),
					data: 'action=delete&ajax=true&' + locationForm.serialize(),
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						window.location = locationForm.data("returnurl");
						
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
				
			}
		});
		return false;
	});
	

    //
    // GALLERY
    //

    $('.gallerylist').on('click', '.editUpload', function (e) {
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


    $('.gallerylist').on('click', '.deleteUpload', function (e) {
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


    if ( $('[name="pla_id"]', locationForm).val() != '0' ) {
        plupLoad( $('#plupload') );
        plupLoadFile( $('#pdfplupload') );
        getGallery();
    }



    //
    // TINYMCE
    //

    //tinymceConfigs = [
    //    {
    //        mode: "none",
    //        theme: "simple"
    //    },
    //    {
    //        relative_urls: false,
    //        remove_script_host: false,
    //        document_base_url: $('#webRoot').val(), //"http://demo.patchworkscms.com/",
    //        convert_urls: false,
    //        mode: "none",
    //        theme: "advanced",
    //        plugins: "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",
    //
    //        // Theme options
    //        theme_advanced_buttons1: "bold,italic,underline,strikethrough,justifyleft,justifycenter,justifyright",
    //        theme_advanced_buttons2: "link,unlink,|,cut,copy,paste,pastetext,pasteword,|,bullist,code,fullscreen,word",
    //        theme_advanced_buttons3: "",
    //        theme_advanced_toolbar_location: "top",
    //        theme_advanced_toolbar_align: "left",
    //        theme_advanced_statusbar_location: "bottom",
    //        theme_advanced_resizing: true,
    //
    //        // Example content CSS (should be your site CSS)
    //        content_css: $('#webRoot').val() + "pages/css/custom.css",
    //
    //        theme_advanced_blockformats: "p,h1,h2,h3,h4,blockquote"
    //
    //    }];


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

            // Example content CSS (should be your site CSS)
            content_css: $('#webRoot').val() + "pages/css/styles.css",

            //theme_advanced_blockformats: "p,h1,h2,h3,h4,blockquote"

        }];

    tinyMCE.settings = tinymceConfigs[1];
    tinyMCE.execCommand('mceAddEditor', false, 'cusfld2');
	
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

function initialize() {

    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng($('#GooLat').val(), $('#GooLng').val());
    var myOptions = {
        zoom: 12,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
}

function codeAddress(iAddress) {

    //var address = document.getElementById("GooGeo").value;

    geocoder.geocode({
        'address': iAddress
    }, function (results, status) {

        if (status == google.maps.GeocoderStatus.OK) {
			
			map.setCenter(results[0].geometry.location);
            
			var markerLength = marker.length;
			
			marker[markerLength] = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location,
                draggable: true
            });

            google.maps.event.addListener(marker[markerLength], "click", function(){marker[markerLength].openInfoWindowHtml('here I am');});
            
			google.maps.event.addListener(marker[markerLength], "dragend", function () {
                map.panTo(new google.maps.LatLng(marker[markerLength].position.lat(), marker[markerLength].position.lng()));
				$('#GooLat').val(marker[markerLength].position.lat());
				$('#GooLng').val(marker[markerLength].position.lng());
                
            });
			
			$('#GooLat').val(marker[markerLength].position.lat());
			$('#GooLng').val(marker[markerLength].position.lng());

        } else {
			
			$.msgGrowl ({
				type: 'warning'
				, title: 'Geocode was not successful'
				, text: status
			});
			
        }
    });

}

function placeMarker (iGooLat, iGooLng) {

	var markerLength = marker.length;
	var GooLatLng = new google.maps.LatLng(iGooLat,iGooLng);
	
	marker[markerLength] = new google.maps.Marker({
		map: map,
		position: GooLatLng,
		draggable: true
	});
	
	google.maps.event.addListener(marker[markerLength], "click", function(){marker[markerLength].openInfoWindowHtml('here I am');});
	
	google.maps.event.addListener(marker[markerLength], "dragend", function () {
		map.panTo(new google.maps.LatLng(marker[markerLength].position.lat(), marker[markerLength].position.lng()));
		$('#GooLat').val(marker[markerLength].position.lat());
		$('#GooLng').val(marker[markerLength].position.lng());
		
	});
	
	$('#GooLat').val(marker[markerLength].position.lat());
	$('#GooLng').val(marker[markerLength].position.lng());
	
	map.panTo(new google.maps.LatLng(marker[markerLength].position.lat(), marker[markerLength].position.lng()));
	
}



function getGallery() {

    $.ajax({
        url: 'gallery/uploads.gallery.php',
        data: 'tblnam='+$('[name="tblnam"]', locationForm).val() + '&tbl_id='+$('[name="pla_id"]', locationForm).val(),
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
        data: 'tblnam=LOCFILE&tbl_id='+$('[name="pla_id"]', locationForm).val(),
        type: 'GET',
        async: false,
        success: function( data ) {

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
        url : '../js/plugins/plupload/upload.php?resize='+ $el.data('resize') +'&tblnam='+$('[name="tblnam"]', locationForm ).val()+'&tbl_id=' + $('[name="pla_id"]', locationForm ).val(),
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


function plupLoadFile(iElement) {

    var $el = iElement;
    $el.pluploadQueue({
        runtimes : 'html5,gears,flash,silverlight,browserplus',
        url : '../js/plugins/plupload/uploadfile.php?tblnam=LOCFILE&tbl_id=' + $('[name="pla_id"]', locationForm ).val(),
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

        console.log(up);

        if (up.files.length == (up.total.uploaded + up.total.failed)) {
            getGallery();
        }
    });

}