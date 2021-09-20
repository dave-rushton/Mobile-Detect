var deliveryForm;


$(function(){
	deliveryForm = $('#deliveryForm');
	
	$('#updateDeliveryBtn').click(function(e){
		e.preventDefault();
		deliveryForm.submit();
	});

    $('[name="cou_idselect"]', deliveryForm).each(function(){
        var $el = $(this);
        var search = ($el.attr("data-nosearch") === "true") ? true : false,
            opt = {};
        if(search) opt.disable_search_threshold = 9999999;
        $el.chosen(opt);

        resize_chosen();

    });

	deliveryForm.submit(function(e){
	
		e.preventDefault();

        $('[name="delcod"]', deliveryForm).val( $('[name="cou_idselect"]', deliveryForm).val() );
		
		//alert( deliveryForm.serialize() );
		
		$.ajax({
			url: deliveryForm.attr("action"),
			data: 'action=update&ajax=true&' + deliveryForm.serialize(),
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
				
					$('#id', deliveryForm).val( result.id );
					
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
	
	$('#deleteDeliveryBtn').click(function (e) {
		e.preventDefault();
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Delivery'
			, text: 'Are you sure you wish to permanently remove this delivery from the database?'
			, callback: function () {
				
				$.ajax({
					url: deliveryForm.attr("action"),
					data: 'action=delete&ajax=true&' + deliveryForm.serialize(),
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						window.location = deliveryForm.data("returnurl");
						
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