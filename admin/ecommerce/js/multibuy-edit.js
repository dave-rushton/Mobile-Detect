var multibuyForm, relatedProductTypeForm, relatedProductForm;


$(function(){

	multibuyForm = $('#multibuyForm');
    relatedProductTypeForm = $('#relatedProductTypeForm');
    relatedProductForm = $('#relatedProductForm');

    $('[name="begdat"]', multibuyForm).datepicker({ format: 'yyyy-mm-dd', weekStart: 1 });
    $('#clearBegDateBtn').click(function(e){
        e.preventDefault();
        $('[name="begdat"]', multibuyForm).val('');
    });

    $('[name="enddat"]', multibuyForm).datepicker({ format: 'yyyy-mm-dd', weekStart: 1 });
    $('#clearEndDateBtn').click(function(e){
        e.preventDefault();
        $('[name="enddat"]', multibuyForm).val('');
    });

    //$('[name="prd_idselect"]', multibuyForm).each(function(){
    //    var $el = $(this);
    //    var search = ($el.attr("data-nosearch") === "true") ? true : false,
    //        opt = {};
    //    if(search) opt.disable_search_threshold = 9999999;
    //    $el.chosen(opt);
    //
    //    resize_chosen();
    //
    //});


	$('#updateMultibuyBtn').click(function(e){
		e.preventDefault();
		multibuyForm.submit();
	});
	
	multibuyForm.submit(function(e){
	
		e.preventDefault();

        //$('[name="prd_id"]', multibuyForm).val( $('[name="prd_idselect"]', multibuyForm).val() );

		//alert( multibuyForm.serialize() );
		
		$.ajax({
			url: multibuyForm.attr("action"),
			data: 'action=update&ajax=true&' + multibuyForm.serialize(),
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
				
					$('#id', multibuyForm).val( result.id );
					
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
	
	$('#deleteMultibuyBtn').click(function (e) {
		e.preventDefault();
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Multibuy'
			, text: 'Are you sure you wish to permanently remove this multibuy promotion from the database?'
			, callback: function () {
				
				$.ajax({
					url: multibuyForm.attr("action"),
					data: 'action=delete&ajax=true&' + multibuyForm.serialize(),
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						window.location = multibuyForm.data("returnurl");
						
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
				
			}
		});
		return false;
	});


    //
    // BUILD RELATED LIST
    //

    var prtdata;

    $('#relatedProductTypeBox').block({ message: 'Retrieving Product Types' });

    $.ajax({
        url: "products/product_types_script.php",
        data: 'action=select&prt_id=0',
        type: "GET",
        async: true,
        success: function(data) {

            prtdata = JSON.parse( data );

            $(':input.autocomplete', relatedProductTypeForm).typeahead({
                source: function(query, process) {
                    objects = [];
                    map = {};
                    var data = prtdata; // Or get your JSON dynamically and load it into this variable
                    $.each(data, function(i, object) {

                        map[object.prtnam + ' (' + object.prtnam + ')'] = object;
                        objects.push(object.prtnam + ' (' + object.prtnam + ')');
                    });

                    return objects;

                    //process(objects);
                },
                updater: function(item) {

                    $('#relatedProductTypeName', relatedProductTypeForm).val(item);

                    $('[name="ref_id"]', relatedProductTypeForm).val(map[item].prt_id);
                    return item;
                }
            });

            $('#relatedProductTypeBox').unblock();

        }
    });


    relatedProductTypeForm.submit(function(e){

        e.preventDefault();

        $.ajax({
            url: 'system/related_script.php',
            data: 'action=create&ajax=true&tblnam=MULTIBUY&tbl_id=' + $('[name="tbl_id"]', relatedProductTypeForm).val() + '&refnam=PRDTYPE&ref_id=' + $('[name="ref_id"]', relatedProductTypeForm).val(),
            type: 'POST',
            async: false,
            success: function( data ) {

                getRelatedProductTypes();

                $('[name="refnam"]', relatedProductTypeForm).val('');
                $('[name="ref_id"]', relatedProductTypeForm).val(0);

            }
        });

    });

    $('#relatedProductTypeList').on('click', '.removeRelated', function(e){

        $el = $(this);

        e.preventDefault();

        $.ajax({
            url: 'system/related_script.php',
            data: 'action=delete&ajax=true&rel_id=' + $el.data('rel_id'),
            type: 'POST',
            async: false,
            success: function( data ) {

                var result = JSON.parse(data);

                $.msgGrowl ({
                    type: result.type
                    , title: result.title
                    , text: result.description
                });

                $el.parent().fadeOut();

            }
        });

    });

    getRelatedProductTypes();




    var prddata;

    $('#relatedProductBox').block({ message: 'Retrieving Products' });

    $.ajax({
        url: "products/products_script.php",
        data: 'action=select&prd_id=0',
        type: "GET",
        async: true,
        success: function(data) {

            prddata = JSON.parse( data );

            $('[name="refnam"]', relatedProductForm).typeahead({
                source: function(query, process) {
                    objects = [];
                    map = {};
                    var data = prddata; // Or get your JSON dynamically and load it into this variable
                    $.each(data, function(i, object) {

                        map[object.prdnam + ' (' + object.prtnam + ')'] = object;
                        objects.push(object.prdnam + ' (' + object.prtnam + ')');
                    });

                    return objects;

                    //process(objects);
                },
                updater: function(item) {

                    $('#relatedName', relatedProductForm).val(item);

                    $('[name="ref_id"]', relatedProductForm).val(map[item].prd_id);
                    return item;
                }
            });

            $('#relatedProductBox').unblock();

        }
    });


    relatedProductForm.submit(function(e){

        e.preventDefault();

        $.ajax({
            url: 'system/related_script.php',
            data: 'action=create&ajax=true&tblnam=MULTIBUY&tbl_id=' + $('[name="tbl_id"]', relatedProductForm).val() + '&refnam=PRODUCT&ref_id=' + $('[name="ref_id"]', relatedProductForm).val(),
            type: 'POST',
            async: false,
            success: function( data ) {

                getRelatedProducts();

                $('[name="refnam"]', relatedProductForm).val('');
                $('[name="ref_id"]', relatedProductForm).val(0);

            }
        });

    });

    $('#relatedProductList').on('click', '.removeRelated', function(e){

        $el = $(this);

        e.preventDefault();

        $.ajax({
            url: 'system/related_script.php',
            data: 'action=delete&ajax=true&rel_id=' + $el.data('rel_id'),
            type: 'POST',
            async: false,
            success: function( data ) {

                var result = JSON.parse(data);

                $.msgGrowl ({
                    type: result.type
                    , title: result.title
                    , text: result.description
                });

                $el.parent().fadeOut();

            }
        });

    });

    getRelatedProducts();


});


function getRelatedProductTypes() {

    $.ajax({
        url: 'system/related_script.php',
        data: 'action=relatedmultibuy&ajax=true&tblnam=MULTIBUY&tbl_id=' + $('[name="tbl_id"]', relatedProductTypeForm).val() + '&refnam=PRDTYPE',
        type: 'POST',
        async: true,
        success: function( data ) {

            var jsonArray = JSON.parse(data);

            var resultHTML = '';

            for (i=0;i<jsonArray.length;i++) {
                resultHTML = resultHTML + '<li><a href="products/producttype-edit.php?prt_id='+jsonArray[i].prt_id+'" target="_blank">'+jsonArray[i].prtnam+'</a><a href="#" class="removeRelated pull-right" data-rel_id="'+jsonArray[i].rel_id+'"><i class="icon-remove"></i> </a> </li>';
            }

            $('#relatedProductTypeList').html(resultHTML);

        }
    });

}


function getRelatedProducts() {

    $.ajax({
        url: 'system/related_script.php',
        data: 'action=relatedmultibuyproducts&ajax=true&tblnam=MULTIBUY&tbl_id=' + $('[name="tbl_id"]', relatedProductTypeForm).val() + '&refnam=PRODUCT',
        type: 'POST',
        async: true,
        success: function( data ) {

            var jsonArray = JSON.parse(data);

            var resultHTML = '';

            for (i=0;i<jsonArray.length;i++) {
                resultHTML = resultHTML + '<li><a href="products/producttype-edit.php?prt_id='+jsonArray[i].prd_id+'" target="_blank">'+jsonArray[i].prdnam+' ('+jsonArray[i].prtnam+')</a><a href="#" class="removeRelated pull-right" data-rel_id="'+jsonArray[i].rel_id+'"><i class="icon-remove"></i> </a> </li>';
            }

            $('#relatedProductList').html(resultHTML);

        }
    });

}