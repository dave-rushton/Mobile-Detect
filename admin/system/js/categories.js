$(function(){
	
	var quickCreateCategory = $('#quickCreateCategory');
	var quickCreateSubCategory = $('#quickCreateSubCategory');
	
	$('#createCategoryBtn', quickCreateCategory).click(function(e){
		e.preventDefault();
		quickCreateCategory.submit();
		
	});
	$('#createSubCategoryBtn').click(function(e){
		e.preventDefault();
		quickCreateSubCategory.submit();
	});
	
	$('#categoriesBody').on('click', '.editCategoryLink', function (e) {
		e.preventDefault();
		displaySubCategories($(this).data('cat_id'));
	});
	
	$('#categoriesBody').on('click', '.deleteCategoryLink', function (e) {
		e.preventDefault();
		
		var catId = $(this).data('cat_id');
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Category'
			, text: 'Are you sure you wish to permanently remove this category and its subcategories from the database?'
			, callback: function () {
				
				$.ajax({
					url: quickCreateCategory.attr("action"),
					data: 'action=delete&ajax=true&cat_id=' + catId,
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						displayCategories();
						
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
				
			}
		});
		
	});
	
	$('#subCategoriesBody').on('click', '.deleteSubCategoryLink', function (e) {
		e.preventDefault();
		
		var subId = $(this).data('sub_id');
		var catId = $(this).data('cat_id');
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Sub Category'
			, text: 'Are you sure you wish to permanently remove this subcategory from the database?'
			, callback: function () {
				
				$.ajax({
					url: quickCreateSubCategory.attr("action"),
					data: 'action=delete&ajax=true&sub_id=' + subId,
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						displaySubCategories(catId);
						
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
				
			}
		});
		
	});
	
	quickCreateCategory.submit(function(e){
		e.preventDefault();
		
		if (quickCreateCategory.valid()) {
			
			$.ajax({
				url: quickCreateCategory.attr("action"),
				data: 'action=update&ajax=true&cat_id=0&' + quickCreateCategory.serialize(),
				type: 'POST',
				async: false,
				success: function( data ) {
					
//					alert(data);
					
					var result = JSON.parse(data);
					
					$.msgGrowl ({
						type: result.type
						, title: result.title
						, text: result.description
					});
					
					//$('#id', $('#categoriesForm') ).val( result.id );
					
					displayCategories();
					displaySubCategories(result.id);
					
					$('[name="catnam"]', quickCreateCategory).val('');
					
				},
				error: function (x, e) {
					throwAjaxError(x, e);
				}
			});
			
		} else {
			$('.help-inline').hide();
		}
		
	});
	
	quickCreateSubCategory.submit(function(e){
		
		e.preventDefault();
		
		if (quickCreateSubCategory.valid()) {
			
			$.ajax({
				url: quickCreateSubCategory.attr("action"),
				data: 'action=update&ajax=true&' + quickCreateSubCategory.serialize(),
				type: 'POST',
				async: false,
				success: function( data ) {
					
//					alert(data);
					
					var result = JSON.parse(data);
					
					$.msgGrowl ({
						type: result.type
						, title: result.title
						, text: result.description
					});
					
					displaySubCategories( $('[name="cat_id"]', quickCreateSubCategory).val() );
//					getSubCategoryRecord(result.id);
					
					$('[name="subnam"]', quickCreateSubCategory).val('');
					
				},
				error: function (x, e) {
					throwAjaxError(x, e);
				}
			});
			
		} else {
			$('.help-inline').hide();
		}
		
	});
	
	displayCategories();

});

function displayCategories() {

	$.ajax({
		url: 'system/categories_table.php',
		type: 'GET',
		async: false,
		success: function( data ) {
			
			$('#categoriesBody').html(data);
			
			if ( $('.editCategoryLink', $('#categoriesBody')).length > 0 ) {
				$('.editCategoryLink:first', $('#categoriesBody')).click();
			} else {
				$('#subCategoriesBody').html('');
			}
			
		},
		error: function (x, e) {
			throwAjaxError(x, e);
		}
	});
		
}

function displaySubCategories(iCat_ID) {

	$('[name="cat_id"]', quickCreateSubCategory).val(iCat_ID);

	$.ajax({
		url: 'system/subcategories_table.php',
		data: 'cat_id=' + iCat_ID,
		type: 'GET',
		async: false,
		success: function( data ) {
			
			$('#subCategoriesBody').html(data);
			
		},
		error: function (x, e) {
			throwAjaxError(x, e);
		}
	});
		
}