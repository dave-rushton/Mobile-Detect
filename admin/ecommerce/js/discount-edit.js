var discountForm;

$(function(){
	
	discountForm = $('#discountForm');
	
	$('#updateDiscountBtn').click(function(e){
		e.preventDefault();
		discountForm.submit();
	});
	
	
	$('[name="begdat"]', discountForm).datepicker({ format: 'yyyy-mm-dd', weekStart: 1 });
	$('#clearBegDateBtn').click(function(e){
		e.preventDefault();
		$('[name="begdat"]', discountForm).val('');
	});
	
	$('[name="enddat"]', discountForm).datepicker({ format: 'yyyy-mm-dd', weekStart: 1 });
	$('#clearEndDateBtn').click(function(e){
		e.preventDefault();
		$('[name="enddat"]', discountForm).val('');
	});

    $('[name="prd_idselect"]', discountForm).each(function(){
        var $el = $(this);
        var search = ($el.attr("data-nosearch") === "true") ? true : false,
            opt = {};
        if(search) opt.disable_search_threshold = 9999999;
        $el.chosen(opt);

        resize_chosen();

    });

	discountForm.submit(function(e){
	
		e.preventDefault();
		
		if (discountForm.valid()) {

            alert( $('[name="prd_idselect"]', discountForm).val() );

            $('[name="prd_id"]', discountForm).val( $('[name="prd_idselect"]', discountForm).val() );
		
			$.ajax({
				url: discountForm.attr("action"),
				data: 'action=update&ajax=true&' + discountForm.serialize(),
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
					
						$('#id', discountForm).val( result.id );
						
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
		
		}
		
	
	});
	
	$('#deleteDiscountBtn').click(function (e) {
		e.preventDefault();
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Discount'
			, text: 'Are you sure you wish to permanently remove this discount from the database?'
			, callback: function () {
				
				$.ajax({
					url: discountForm.attr("action"),
					data: 'action=delete&ajax=true&' + discountForm.serialize(),
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						window.location = discountForm.data("returnurl");
						
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
