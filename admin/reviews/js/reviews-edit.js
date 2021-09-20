var reviewForm;

$(function(){

	reviewForm = $('#reviewForm');
	
	$('#updateReviewBtn').click(function(e){
		e.preventDefault();
		reviewForm.submit();
	});
	
	reviewForm.submit(function(e){
	
		e.preventDefault();
        
        $.ajax({
            url: reviewForm.attr("action"),
            data: 'action=update&ajax=true&' + reviewForm.serialize(),
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

                    $('#id', reviewForm).val( result.id );

                } catch(Ex) {
                    $.msgGrowl ({
                        type: 'error'
                        , title: 'Error'
                        , text: Ex
                    });

                }

            },
            error: function (x, e) {

                throwAjaxError(x, e);

            }
        });
	
	});
	
	$('#deleteReviewBtn').click(function (e) {
		e.preventDefault();
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Review'
			, text: 'Are you sure you wish to permanently remove this review from the database?'
			, callback: function () {
				
				$.ajax({
					url: reviewForm.attr("action"),
					data: 'action=delete&ajax=true&' + reviewForm.serialize(),
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						window.location = reviewForm.data("returnurl");
						
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