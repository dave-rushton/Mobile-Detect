$(function () {
	
	var userForm = $('#userForm');
	
	userForm.validate({
		errorElement:'span',
		errorClass: 'help-inline error',
		errorPlacement:function(error, element){
			element.parents('.controls').append(error);
		},
		highlight: function(label) {
			$(label).closest('.control-group').removeClass('error success').addClass('error');
		},
		success: function(label) {
			label.addClass('valid').closest('.control-group').removeClass('error success').addClass('success');
		}
    });
	
    userForm.submit(function(){
		
		$('#userFormBox').block({ 
			message: '<h4>Updating</h4>', 
			centerY: 0,
			centerX: 0,
			css: { top: '10px', left: '', right: '10px', border: '2px solid #a00' } 
		});
		
		if ($(this).valid()) {
			
			var UsrAcc = '';

			$('.subModuleCB:checked', $(this)).each(function(){					
				UsrAcc += ( UsrAcc != '' ) ? ',' + $(this).val() : $(this).val();
			});
			
			$('#UsrAcc').val( UsrAcc );
			
			//alert( userForm.attr("action")+'?ajax=true&' + userForm.serialize() );
			
			$.ajax({
				url: userForm.attr("action"),
				data: 'action=update&ajax=true&' + userForm.serialize(),
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
						
					
						$('#id', userForm).val( result.id );
					
						//$.growlUI(result.title, result.description); 
						$('#userFormBox').unblock();
						
						//
						// Update Related
						//
						
						var refID = '';
						
						$('[name="emp_id[]"]:checked', userForm).each(function(){
							
							refID += (refID == '') ? $(this).val() : ',' + $(this).val();
								
						});
						
						$.ajax({
							url: 'system/related_script.php',
							data: 'action=relate&ajax=true&tblnam=USR&tbl_id=' + $('[name="usr_id"]', userForm).val() + '&refnam=EMP&ref_id=' + refID,
							type: 'POST',
							async: false,
							success: function( data ) {

							}
						});
						
						
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

			$('#userFormBox').unblock();
		}
		else {
			$.msgGrowl ({
				type: 'error'
				, title: 'Error'
				, text: 'There is an error in the form'
			});
			
			//$.growlUI('Error', 'There is an error in the form'); 
			$('#userFormBox').unblock();
		}
		return false;
	});
	
	$('#updateUserBtn').click(function(e){ 
		e.preventDefault();
		userForm.submit(); 
	});
	
	$('#deleteUserBtn').click(function (e) {
		e.preventDefault();
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This User'
			, text: 'Are you sure you wish to permanently remove this user from the database?'
			, callback: function () {
				
				$.ajax({
					url: userForm.attr("action"),
					data: 'action=delete&ajax=true&' + userForm.serialize(),
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						window.location = userForm.data("returnurl");
						
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
				
			}
		});
		return false;
	});
	
	$.ajax({
		url: 'system/related_script.php',
		data: 'action=getrelated&ajax=true&tblnam=USR&tbl_id=' + $('[name="usr_id"]', userForm).val() + '&refnam=EMP',
		type: 'POST',
		async: false,
		success: function( data ) {
			
			var jsonArray = JSON.parse(data);
			
			for (i=0;i<jsonArray.length;i++) {
				
				$('[name="emp_id[]"][value="'+jsonArray[i].ref_id+'"]', userForm).prop('checked', true);
					
			}
			
		}
	});
	
	$('.form').eq(0).find('input').eq(0).focus();

});