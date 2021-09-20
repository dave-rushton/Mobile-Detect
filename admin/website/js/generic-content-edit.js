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
			
			$.ajax({
				url: contentForm.attr("action"),
				data: 'action=update&ajax=true&' + contentForm.serialize(),
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

		contentForm.submit();
		
	});



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
			toolbar1: "bold italic underline | alignleft aligncenter alignright alignjustify ",
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
			style_formats: [
				{title: "Headers", items: [
					{title: "Header 1", format: "h1"},
					{title: "Header 2", format: "h2"},
					{title: "Header 3", format: "h3"},
					{title: "Header 4", format: "h4"}
				]},
				{title: "Text", items: [
					//{title: "Green Text", icon: "bold", inline: "span", classes: "text-green"},
					{title: "Heading 1", icon: "bold", inline: "span", classes: "h1"},
					{title: "Heading 2", icon: "bold", inline: "span", classes: "h2"},
					{title: "Heading 3", icon: "bold", inline: "span", classes: "h3"},
				]}
			],
			link_class_list: [
				{title: 'None', value: ''},
				{title: 'Call To Action', value: 'cta'}
			]
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