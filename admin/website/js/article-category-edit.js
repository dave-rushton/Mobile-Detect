var subCategoriesForm;



$(function(){



    $('[name="subnam"]', subCategoriesForm).on("keyup paste", function() {

        $('[name="seourl"]', subCategoriesForm ).val( seoURL( $('[name="subnam"]', subCategoriesForm).val() ) );

    });



	subCategoriesForm = $('#subCategoriesForm');

	

	$('#updateSubCategoryBtn').click(function(e){

		e.preventDefault();

		subCategoriesForm.submit();

	});

	

	subCategoriesForm.submit(function(e){

	

		e.preventDefault();



		if (subCategoriesForm.valid()) {

			$("#categoryBox").block({

				message: '<h4>Updating</h4>',

				centerY: 0,

				centerX: 0,

				css: { top: '10px', left: '', right: '10px', border: '2px solid #a00'}

			});



			//alert( subCategoriesForm.serialize() );



			$.ajax({

				url: subCategoriesForm.attr("action"),

				data: 'action=update&ajax=true&' + subCategoriesForm.serialize(),

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

		}

		else {

			$.msgGrowl ({

				type: 'error'

				, title: 'Invalid Form'

				, text: 'There is an error in the form'

			});

		}







		$("#categoryBox").unblock();

		

	

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

	

});