var vatForm;


$(function(){
	vatForm = $('#vatForm');
	
	$('#updateVatBtn').click(function(e){
		e.preventDefault();
		vatForm.submit();
	});
	
	$('[name="begdat"]', vatForm).datepicker({dateFormat: 'yy-mm-dd'});
	
	vatForm.submit(function(e){
	
		e.preventDefault();
		
		//alert( vatForm.serialize() );
		
		$.ajax({
			url: vatForm.attr("action"),
			data: 'action=update&ajax=true&' + vatForm.serialize(),
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
				
					$('#id', vatForm).val( result.id );
					
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
	
	$('#deleteVatBtn').click(function (e) {
		e.preventDefault();
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Vat'
			, text: 'Are you sure you wish to permanently remove this vat from the database?'
			, callback: function () {
				
				$.ajax({
					url: vatForm.attr("action"),
					data: 'action=delete&ajax=true&' + vatForm.serialize(),
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						window.location = vatForm.data("returnurl");
						
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