$(function(){
	
	
	$('#formsBody').on('click', '.deleteFormBtn', function (e) {
		e.preventDefault();
		
		var atrId = $(this).data('atr_id');
		
		var row = $(this).parent().parent();
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Form'
			, text: 'Are you sure you wish to permanently remove this form from the database?'
			, callback: function () {
				
				
				
				$.ajax({
					url: 'attributes/attrgroup_script.php',
					data: 'action=delete&ajax=true&atr_id=' + atrId,
					type: 'POST',
					async: false,
					success: function( data ) {
						
						row.fadeOut();
						
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
				
			}
		});
		
	});
	
	
	
});