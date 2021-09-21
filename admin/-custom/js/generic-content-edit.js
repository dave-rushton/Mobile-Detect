var contentForm;

$(function(){
	
	contentForm = $('#contentForm');
	
	contentForm.submit(function(e){
		
		e.preventDefault();
		
		contentForm.block({ 
			message: '<h4>Updating</h4>', 
			centerY: 0,
			centerX: 0,
			css: { top: '10px', left: '', right: '10px', border: '2px solid #a00' } 
		});
		
		if ($(this).valid()) {


            if( $('#logofile')[0].files[0] ) {

                var data;
            
                data = new FormData();
                data.append('logofile', $('#logofile')[0].files[0]);
                data.append('newname', $('[name="pgcttl"]', contentForm).val());

                $.ajax({
                    url: 'custom/upload_service_image.php',
                    data: data,
                    processData: false,
                    type: 'POST',
                    contentType: false,
                    aSync: false,
                    success: function (data) {
            
                        $('[name="pgcimg"]', contentForm).val(data);

                        var fields = $(".customfield", contentForm).serializeArray();
                        var elementVariables = JSON.stringify(fields);
                        var postData = encodeURIComponent(elementVariables);

                        // After Upload - Update

                        $.ajax({
                            url: contentForm.attr("action"),
                            data: 'action=update&ajax=true&' + contentForm.serialize() + '&pgcobj=' + postData,
                            type: 'POST',
                            async: false,
                            success: function( data ) {

                                $('#id', contentForm ).val( data );

                            },
                            error: function (x, e) {
                                throwAjaxError(x, e);
                            }
                        });
                        
            
                    },
                    error: function (x, e) {
            
                        throwAjaxError(x, e);
            
                    }
                });
            
            } else {

                var fields = $(".customfield", contentForm).serializeArray();
                var elementVariables = JSON.stringify(fields);
                var postData = encodeURIComponent(elementVariables);

                $.ajax({
                    url: contentForm.attr("action"),
                    data: 'action=update&ajax=true&' + contentForm.serialize() + '&pgcobj=' + postData,
                    type: 'POST',
                    async: false,
                    success: function( data ) {

                        $('#id', contentForm ).val( data );

                    },
                    error: function (x, e) {
                        throwAjaxError(x, e);
                    }
                });
            
            }
			
		}
		else {
			$.msgGrowl ({
				type: 'error'
				, title: 'Invalid Form'
				, text: 'There is an error in the form'
			});
		}
					
		contentForm.unblock();
		
	});
	
	$('#deleteContentBtn').click(function (e) {
		e.preventDefault();
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Content'
			, text: 'Are you sure you wish to permanently remove this content from the database?'
			, callback: function () {
				
				contentForm.block({ 
					message: '<h4>Deleting</h4>', 
					centerY: 0,
					centerX: 0,
					css: { top: '10px', left: '', right: '10px', border: '2px solid #a00' } 
				});
				
				$.ajax({
					url: contentForm.attr("action"),
					data: 'action=delete&ajax=true&' + contentForm.serialize(),
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						window.location = contentForm.data("returnurl");
						
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
				
			}
		});
		return false;
	});

	
	$('#updateContentBtn').click(function(e){
		e.preventDefault();

		tinyMCE.triggerSave('pgctxt');

		//CKEDITOR.instances['pgctxt'].updateElement();
		
//		var editor = CKEDITOR.instances.arttxt;
//		var html = editor.getData();
//		html.replace('', '');
//		editor.setData(html);
//		formSubmitted = true;
//		$(this).trigger('click');
		
		contentForm.submit();
		
	});
	
	//CKEDITOR.replace("pgctxt", {
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

			toolbar1: "bold italic underline | alignleft aligncenter alignright | image ",
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
			content_css: $('#webRoot').val() + "pages/css/styles.css"

		}];

	tinyMCE.settings = tinymceConfigs[1];
	tinyMCE.execCommand('mceAddEditor', false, 'pgctxt');

	
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