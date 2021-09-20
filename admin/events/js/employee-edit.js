var employeeForm;

$(function(){

	employeeForm = $('#employeeForm');
	
	$('#updateEmployeeBtn').click(function(e){
		e.preventDefault();
		employeeForm.submit();
	});
	
	employeeForm.submit(function(e){
	
		e.preventDefault();
		
		$.ajax({
			url: employeeForm.attr("action"),
			data: 'action=update&ajax=true&' + employeeForm.serialize(),
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
				
					$('#id', employeeForm).val( result.id );
					
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
	
	$('#deleteEmployeeBtn').click(function (e) {
		e.preventDefault();
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Employee'
			, text: 'Are you sure you wish to permanently remove this project from the database?'
			, callback: function () {
				
				$.ajax({
					url: employeeForm.attr("action"),
					data: 'action=delete&ajax=true&' + employeeForm.serialize(),
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						window.location = employeeForm.data("returnurl");
						
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
				
			}
		});
		return false;
	});
	
	
});