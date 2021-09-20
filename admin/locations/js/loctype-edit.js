var subCategoriesForm;

$(function(){
	
	subCategoriesForm = $('#subCategoriesForm');

    $('[name="subnam"]', subCategoriesForm).on("change keyup paste", function() {
        $('[name="seourl"]', subCategoriesForm ).val( seoURL( $('[name="subnam"]', subCategoriesForm).val() ) );
    });

	$('#updateSubCategoryBtn').click(function(e){
		e.preventDefault();
		subCategoriesForm.submit();
	});
	
	subCategoriesForm.submit(function(e){

        var fields = $(".customfield", subCategoriesForm).serializeArray();
        var elementVariables = JSON.stringify(fields);
        var postData = encodeURIComponent(elementVariables);
	
		e.preventDefault();
		
		//alert( subCategoriesForm.serialize() );
		
		$.ajax({
			url: subCategoriesForm.attr("action"),
			data: 'action=update&ajax=true&' + subCategoriesForm.serialize() + '&subtxt=' + postData,
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
				
					$('#id', subCategoriesForm).val( result.id );
					
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
	
	$('#deleteSubCategoryBtn').click(function (e) {
		e.preventDefault();
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Category'
			, text: 'Are you sure you wish to permanently remove this category from the database?'
			, callback: function () {
				
				$.ajax({
					url: subCategoriesForm.attr("action"),
					data: 'action=delete&ajax=true&' + subCategoriesForm.serialize(),
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						window.location = subCategoriesForm.data("returnurl");
						
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
				
			}
		});
		return false;
	});

    $('.sortOrder').click(function(e){
        e.preventDefault();
    });

	
});