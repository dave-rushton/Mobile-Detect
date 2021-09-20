
var orderLineRecs = [];
var orderLine, orderForm, orderLineForm, selectCustomerForm, ordTable, orderFilterForm;

$(function(){
	
	orderFilterForm = $('#orderFilterForm');
	orderForm = $('#orderForm');
	orderLineForm = $('#orderLineForm');
	selectCustomerForm = $('#selectCustomerForm');
	
	displayOrders();
	
	$('[name="tmpsta_id"]', orderFilterForm).change(function(){
		displayOrders();
	});
	
	$('#InvDat, #DueDat, #PayDat').datepicker({format: 'dd-mm-yyyy'});
	
	$('#selectCustomerModal').on('shown', function() {
		resize_chosen();
	});
	
	$('#selectCustomerBtn').click(function(e){
		e.preventDefault();
		$('#selectCustomerModal').modal('show');
	});
	
	$('.screenSelect').click(function(e){
		e.preventDefault();
		changeScreen($(this).attr("href"));
	});
	
	$('#createNewOrderBtn').click(function(e){
		e.preventDefault();
		
		orderLineRecs.length = 0;
		displayOrderLines();
		
		// clear form
		
		var today = new Date();
		
		$('#ordInvDat').val( switchDate(getMysqlDate(js2mysql(today))) );
		$('#ordOrd_ID').html( 'New Order' );
		
		$('#customerAddressDiv').html( '' );
		
		$('[name="ord_id"]', orderForm).val( 0 );
		$('[name="tbl_id"]', orderForm).val( 0 );
		$('[name="adr1"]', orderForm).val( '' );
		$('[name="adr2"]', orderForm).val( '' );
		$('[name="adr3"]', orderForm).val( '' );
		$('[name="adr4"]', orderForm).val( '' );
		$('[name="pstcod"]', orderForm).val( '' );
		$('[name="payadr1"]', orderForm).val( '' );
		$('[name="payadr2"]', orderForm).val( '' );
		$('[name="payadr3"]', orderForm).val( '' );
		$('[name="payadr4"]', orderForm).val( '' );
		$('[name="paypstcod"]', orderForm).val( '' );
		
		
		$('[name="invdat"]').val( switchDate(getMysqlDate(js2mysql(today))) );
		$('[name="duedat"]').val( switchDate(getMysqlDate(js2mysql(today))) );
		$('[name="paydat"]').val( switchDate(getMysqlDate(js2mysql(today))) );
		
		$('#printOrderBtn').hide();
		
		changeScreen('#orderEditDiv'); 
		
	});
	
	$('#ordersBody').on('click', '.editOrderLnk', function(e){
		e.preventDefault();
		
		/* POPULATE ORDER FORM */
		
		var ordId = $(this).data('ord_id');
		
		$.ajax({
			url: 'ecommerce/order_edit_script.php',
			data: 'action=select&ajax=true&ord_id=' + ordId,
			type: 'POST',
			async: false,
			success: function( data ) {
				
				try {
				
					var jsonArray = JSON.parse(data);
					var orderArray = jsonArray.order;
					var orderLinesArray = jsonArray.orderlines;
					
					$('[name="ord_id"]', orderForm).val( orderArray[0].ord_id );
					$('[name="ordtyp"]', orderForm).val( orderArray[0].ordtyp );
					$('[name="invdat"]', orderForm).val( switchDate(getMysqlDate(orderArray[0].invdat)) );
					$('[name="duedat"]', orderForm).val( switchDate(getMysqlDate(orderArray[0].duedat)) );
					$('[name="paydat"]', orderForm).val( switchDate(getMysqlDate(orderArray[0].paydat)) );
					
					$('[name="tblnam"]', orderForm).val( orderArray[0].tblnam );
					$('[name="tbl_id"]', orderForm).val( orderArray[0].tbl_id );
					
					$('[name="cusnam"]', orderForm).val( orderArray[0].cusnam );
					$('[name="adr1"]', orderForm).val( orderArray[0].adr1 );
					$('[name="adr2"]', orderForm).val( orderArray[0].adr2 );
					$('[name="adr3"]', orderForm).val( orderArray[0].adr3 );
					$('[name="adr4"]', orderForm).val( orderArray[0].adr4 );
					$('[name="pstcod"]', orderForm).val( orderArray[0].pstcod );
					
					$('[name="payadr1"]', orderForm).val( orderArray[0].payadr1 );
					$('[name="payadr2"]', orderForm).val( orderArray[0].payadr2 );
					$('[name="payadr3"]', orderForm).val( orderArray[0].payadr3 );
					$('[name="payadr4"]', orderForm).val( orderArray[0].payadr4 );
					$('[name="paypstcod"]', orderForm).val( orderArray[0].paypstcod );
					
					$('[name="paytrm"]', orderForm).val( orderArray[0].paytrm );
					
					$('[name="vatrat"]', orderForm).val( orderArray[0].vatrat );
					$('[name="sta_id"]', orderForm).val( orderArray[0].sta_id );
					
					var adrHTML = '';
					
						if (orderArray[0].comnam) {
							adrHTML += '<strong>'+orderArray[0].comnam+'</strong>';
						}
						
						adrHTML += '<address>';
						adrHTML += orderArray[0].cusnam + '<br>';
						adrHTML += $('[name="adr1"]', orderForm ).val() + '<br>';
						adrHTML += $('[name="adr2"]', orderForm ).val() + '<br>';
						adrHTML += $('[name="adr3"]', orderForm ).val() + '<br>';
						adrHTML += $('[name="adr4"]', orderForm ).val() + '<br>';
						adrHTML += $('[name="pstcod"]', orderForm ).val() + '<br>';
						adrHTML += '</address>';
					
					$('#customerAddressDiv').html( adrHTML );
					
					
					// Set up order lines
					
					orderLineRecs = orderLinesArray;
					
					displayOrderLines();
					
					$('#printOrderBtn').show().attr("href", 'ecommerce/print_order.php?output=screen&ord_id=' + orderArray[0].ord_id);
					
					changeScreen('#orderEditDiv');
				
				} catch(ex) {
					alert(ex);
				}
				
			}
		});
		
	});
	
	$('#updateOrderBtn').click(function(e){
		e.preventDefault();
		orderForm.submit();
	});
	
	$('#updateOrderLineBtn').click(function(e){
		e.preventDefault();
		orderLineForm.submit();
	});
	
	orderForm.validate();
	
	orderForm.submit(function(e){
		
		e.preventDefault();
		
		createOrder();
				
	});
	
	selectCustomerForm
        .validate({
        rules: {
            planam: {
                minlength: 2,
                required: true
            },
			adr1: {
                minlength: 2,
                required: true
            },
			pstcod: {
                minlength: 2,
                required: true
            }
        },
        focusCleanup: false,
        submitHandler: function (form) {

        }
    });

    selectCustomerForm.submit(function () {

        if ($(this).valid()) {

			if ($('[name="pla_id"]', selectCustomerForm).val() == 0) {
			
				selectCustomerForm
					.block({
					message: 'Updating'
				});
				
				$.ajax({
					url: selectCustomerForm.attr("action"),
					data: 'action=update&ajax=true&' + selectCustomerForm.serialize(),
					type: 'POST',
					async: false,
					success: function (data) {
						var result = JSON.parse(data);
	
						$.msgGrowl({
							type: result.type,
							title: result.title,
							text: result.description
						});
	
						if (result.type = 'success') {
	
							$('#cancelBookingLink')
								.click();
	
						}
	
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
	
				selectCustomerForm.unblock();
				
			}
			
			var adrHTML = '';
				
				if ( $('[name="comnam"]', selectCustomerForm ).val() != '') {
					adrHTML += '<strong>'+$('[name="comnam"]', selectCustomerForm ).val()+'</strong>';
				}
				
				adrHTML += '<address>';
				adrHTML += $('[name="planam"]', selectCustomerForm ).val() + '<br>';
				adrHTML += $('[name="cusnam"]', selectCustomerForm ).val() + '<br>';
				adrHTML += $('[name="adr1"]', selectCustomerForm ).val() + '<br>';
				adrHTML += $('[name="adr2"]', selectCustomerForm ).val() + '<br>';
				adrHTML += $('[name="adr3"]', selectCustomerForm ).val() + '<br>';
				adrHTML += $('[name="adr4"]', selectCustomerForm ).val() + '<br>';
				adrHTML += $('[name="pstcod"]', selectCustomerForm ).val() + '<br>';
				adrHTML += '</address>';
			
			$('#customerAddressDiv').html( adrHTML );
			
			$('[name="tbl_id"]', orderForm).val( $('[name="pla_id"]', selectCustomerForm ).val() );
			$('[name="cusnam"]', orderForm).val( $('[name="cusnam"]', selectCustomerForm ).val() );
			$('[name="adr1"]', orderForm).val( $('[name="adr1"]', selectCustomerForm ).val() );
			$('[name="adr2"]', orderForm).val( $('[name="adr2"]', selectCustomerForm ).val() );
			$('[name="adr3"]', orderForm).val( $('[name="adr3"]', selectCustomerForm ).val() );
			$('[name="adr4"]', orderForm).val( $('[name="adr4"]', selectCustomerForm ).val() );
			$('[name="pstcod"]', orderForm).val( $('[name="pstcod"]', selectCustomerForm ).val() );
			$('[name="payadr1"]', orderForm).val( $('[name="adr1"]', selectCustomerForm ).val() );
			$('[name="payadr2"]', orderForm).val( $('[name="adr2"]', selectCustomerForm ).val() );
			$('[name="payadr3"]', orderForm).val( $('[name="adr3"]', selectCustomerForm ).val() );
			$('[name="payadr4"]', orderForm).val( $('[name="adr4"]', selectCustomerForm ).val() );
			$('[name="paypstcod"]', orderForm).val( $('[name="pstcod"]', selectCustomerForm ).val() );
			
			$('#cancelSelectCustomerLink').click();
			
        } else {
            $.msgGrowl({
                type: 'error',
                title: 'Invalid Form',
                text: 'There is an error in the form'
            });
        }
        return false;
    });
	
	orderLineForm.validate({
        rules: {
            prd_id: {
                required: true,
				min: 0
            },
			numuni: {
                number: true,
                required: true,
				min: 0
            },
			unipri: {
                number: true,
                required: true,
				//min: 0.01
            }
        },
        focusCleanup: false,
        submitHandler: function (form) {

        }
    });

    orderLineForm.submit(function (e) {
		
		e.preventDefault();
		
        if ($(this).valid()) {
			
			var l = $('[name="newoln"]', orderLineForm).val();
			
			if ( l == -1 ) {
				createOrderLine();
			} else {

				orderLineRecs[l].prd_id = $('[name="prd_id"]', orderLineForm ).val();
				orderLineRecs[l].prdnam = $('[name="prd_id"] option:selected', orderLineForm ).text(),
				orderLineRecs[l].numuni = $('[name="numuni"]', orderLineForm ).val();
				orderLineRecs[l].unipri = $('[name="unipri"]', orderLineForm ).val();
				orderLineRecs[l].olndsc = $('[name="olndsc"]', orderLineForm ).val();
				
			}
			
			displayOrderLines();
			
			changeScreen('#orderEditDiv');
			
		} else {
			$.msgGrowl({
                type: 'error',
                title: 'Invalid Form',
                text: 'There is an error in the form'
            });
		}
			
    });
	
	$('[name="pla_id"]', selectCustomerForm).chosen();
	$('[name="pla_id"]', selectCustomerForm).change(function(){
			
		if ( $(this).val() != 0) {
			
			$.ajax({
				url: './system/json/places.json.php',
				data: 'action=select&ajax=true&pla_id='+$(this).val(),
				type: 'GET',
				async: false,
				success: function( data ) {
					
					try {
						
						var customerRec = JSON.parse(data);
						
						$('[name="comnam"]', selectCustomerForm ).val( customerRec[0].comnam ).prop('disabled', true);
						$('[name="planam"]', selectCustomerForm ).val( customerRec[0].planam ).prop('disabled', true);
						$('[name="cusnam"]', selectCustomerForm ).val( customerRec[0].planam ).prop('disabled', true);
						$('[name="adr1"]', selectCustomerForm ).val( customerRec[0].adr1 ).prop('disabled', true);
						$('[name="adr2"]', selectCustomerForm ).val( customerRec[0].adr2 ).prop('disabled', true);
						$('[name="adr3"]', selectCustomerForm ).val( customerRec[0].adr3 ).prop('disabled', true);
						$('[name="adr4"]', selectCustomerForm ).val( customerRec[0].adr4 ).prop('disabled', true);
						$('[name="pstcod"]', selectCustomerForm ).val( customerRec[0].pstcod ).prop('disabled', true);
						
					} catch (ex) {
						
						$.msgGrowl ({
							type: 'fail'
							, title: 'Fail'
							, text: 'Could not find customer'
						});
						
					}
				},
				error: function (x, e) {
					throwAjaxError(x, e);
				}
			});
			
		} else {
			
			$('[name="comnam"]', selectCustomerForm ).val( '' ).prop('disabled', false);
			$('[name="planam"]', selectCustomerForm ).val( '' ).prop('disabled', false);
			$('[name="cusnam"]', selectCustomerForm ).val( '' ).prop('disabled', false);
			$('[name="adr1"]', selectCustomerForm ).val( '' ).prop('disabled', false);
			$('[name="adr2"]', selectCustomerForm ).val( '' ).prop('disabled', false);
			$('[name="adr3"]', selectCustomerForm ).val( '' ).prop('disabled', false);
			$('[name="adr4"]', selectCustomerForm ).val( '' ).prop('disabled', false);
			$('[name="pstcod"]', selectCustomerForm ).val( '' ).prop('disabled', false);
			
		}
		
	});
	
	
	$('#orderLineTable').on('click', '.editOrderLineLink', function(e){
		
		e.preventDefault();
		
		var l = $(this).data('lin_id');
		
		$('[name="newoln"]', orderLineForm ).val( l );
		
		$('[name="numuni"]', orderLineForm ).val( orderLineRecs[l].numuni );
		$('[name="unipri"]', orderLineForm ).val( orderLineRecs[l].unipri );
		$('[name="olndsc"]', orderLineForm ).val( orderLineRecs[l].olndsc );
		
		$('[name="prd_id"]', orderLineForm).removeClass("chzn-done").data("chosen", null).next().remove();
		$('[name="prd_id"]', orderLineForm).val( orderLineRecs[l].prd_id );
		$('[name="prd_id"]', orderLineForm).chosen();
		
		changeScreen('#orderLineEditDiv');
		
	});
	
	$('#orderLineTable').on('click', '.deleteOlnBtn', function(e){
		
		e.preventDefault();
		
		var l = $(this).data('lin_id');
		
		orderLineRecs.splice(l, 1);
		
		var orderLineRow = $(this).parent().parent();
		
		orderLineRow.fadeOut(500, function(){
			orderLineRow.remove();
		});
		
		displayOrderLines();
		
	});
	
	$('#newOrderLine').click(function(e){
		
		e.preventDefault();
		
		$('[name="newoln"]', orderLineForm ).val( -1 );
		
		//$('[name="prd_id"]', orderLineForm).removeClass("chzn-done").data("chosen", null).next().remove();
		//$('[name="prd_id"]', orderLineForm ).val( '' );
		$('[name="prd_id"]', orderLineForm).change();
		//$('[name="prd_id"]', orderLineForm).chosen();
		
		$('[name="numuni"]', orderLineForm ).val( 1 );
		//$('[name="unipri"]', orderLineForm ).val( 0 );
		//$('[name="olndsc"]', orderLineForm ).val( '' );
		
		changeScreen('#orderLineEditDiv');
		
	});
	
	$('[name="prd_id"]', orderLineForm).chosen();
	$('[name="prd_id"]', orderLineForm).change(function(){
			
		if ( $(this).val() != 0) {
			
			$.ajax({
				url: './ecommerce/products_script.php',
				data: 'action=select&ajax=true&prd_id='+$(this).val(),
				type: 'GET',
				async: false,
				success: function( data ) {
					
					try {
						
						var productRec = JSON.parse(data);
						
						$('[name="unipri"]', orderLineForm ).val( productRec[0].unipri );
						$('[name="olndsc"]', orderLineForm ).val( productRec[0].prddsc );
						
					} catch (ex) {
						
						$.msgGrowl ({
							type: 'fail'
							, title: 'Fail'
							, text: 'Could not find product'
						});
						
					}
				},
				error: function (x, e) {
					throwAjaxError(x, e);
				}
			});
			
		} else {
			
		}
		
	});
	
	$('[name="vatrat"]', orderForm).change(function(){
		
		$('#vatTotal').html( ((parseFloat($('#subTotal').html() / 100)) * parseFloat($(this).val())).toFixed(2) );
		
		$('#netTotal').html( (parseFloat($('#subTotal').html()) + parseFloat($('#vatTotal').html())).toFixed(2) );
		
	});
	
//	$('#deleteOrderBtn').live ('click', function (e) {
//		e.preventDefault();
//		$.msgAlert ({
//			type: $(this).attr ('data-type')
//			, title: 'Delete This Order'
//			, text: 'Are you sure you wish to permanently remove this order from the database?'
//			, callback: function () {
//				
//				$.ajax({
//					url: $('#orderForm').attr("action"),
//					data: 'action=delete&ajax=true&' + $('#orderForm').serialize(),
//					type: 'POST',
//					async: false,
//					success: function( data ) {
//						
//						var result = JSON.parse(data);
//						
//						$.msgGrowl ({
//							type: result.type
//							, title: result.title
//							, text: result.description
//						});
//						
//					},
//					error: function (x, e) {
//						throwAjaxError(x, e);
//					}
//				});
//				
//			}
//		});
//		return false;
//	});
	
	
});

function changeScreen(screenID) {
	
	$('.orderScreen').fadeOut();
	setTimeout( function() { $(screenID).fadeIn(200, function(){ resize_chosen();}); } , 400);
			
}

function createOrder() {

	var orderRec = {
		"ord_id": $('[name="ord_id"]', orderForm).val(),
		"ordtyp": "SALE",
		"invdat": switchDate($('[name="invdat"]', orderForm).val()),
		"duedat": switchDate($('[name="duedat"]', orderForm).val()),
		"paydat": switchDate($('[name="paydat"]', orderForm).val()),
		"cusnam": $('[name="cusnam"]', orderForm).val(),
		"adr1": $('[name="adr1"]', orderForm).val(),
		"adr2": $('[name="adr2"]', orderForm).val(),
		"adr3": $('[name="adr3"]', orderForm).val(),
		"adr4": $('[name="adr4"]', orderForm).val(),
		"pstcod": $('[name="pstcod"]', orderForm).val(),
		"payadr1": $('[name="payadr1"]', orderForm).val(),
		"payadr2": $('[name="payadr2"]', orderForm).val(),
		"payadr3": $('[name="payadr3"]', orderForm).val(),
		"payadr4": $('[name="payadr4"]', orderForm).val(),
		"paypstcod": $('[name="paypstcod"]', orderForm).val(),
		"paytrm": $('[name="paytrm"]', orderForm).val(),
		"vatrat": $('[name="vatrat"]', orderForm).val(),
		"tblnam": "CUS",
		"tbl_id": $('[name="tbl_id"]', orderForm).val(),
		"sta_id": $('[name="sta_id"]', orderForm).val(),
		"orderlines": orderLineRecs
	}
	
	//alert( JSON.stringify(orderRec) );
	
	$.ajax({
		url: 'ecommerce/order_script.php',
		data: { action: "update", ajax: true, jsonobj: JSON.stringify(orderRec) },
		type: 'POST',
		async: false,
		success: function( data ) {
			
			$('#returnHTML').html( data );
			
			try {
				
				var result = JSON.parse(data);
				
				$.msgGrowl ({
					type: result.type
					, title: result.title
					, text: result.description
				});
				
				$('#printOrderBtn').show().attr("href", 'ecommerce/print_order.php?output=screen&ord_id=' + result.id );
				
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
	
	displayOrders();
	
}

function createOrderLine() {

	orderLineRec = 
	{
	
		oln_id: $('[name="oln_id"]', orderLineForm ).val(),
		ord_id: $('[name="ord_id"]', orderForm ).val(),
		prd_id: $('[name="prd_id"]', orderLineForm ).val(),
		prdnam: $('[name="prd_id"] option:selected', orderLineForm ).text(),
		numuni: $('[name="numuni"]', orderLineForm ).val(),
		unipri: $('[name="unipri"]', orderLineForm ).val(),
		vatrat: $('[name="vatrat"]', orderForm ).val(),
		olndsc: $('[name="olndsc"]', orderLineForm ).val(),
		tblnam: 'SALE',
		tbl_id: 0,
		sta_id: 0
	
	}
	
	orderLineRecs.push(orderLineRec);
	
}

function displayOrderLines() {
	
	var resultHTML = '';
	
	for (l=0;l<orderLineRecs.length;l++) {
					
		var lineTotal = (parseFloat( orderLineRecs[l].unipri ) * parseFloat( orderLineRecs[l].numuni )).toFixed(2);

		resultHTML += '<tr>';
		resultHTML += '<td>'+orderLineRecs[l].numuni+'</td>';
		resultHTML += '<td>Hours</td>';
		resultHTML += '<td><a href="#" data-lin_id="'+l+'" class="editOrderLineLink">'+ orderLineRecs[l].prdnam +'</a></td>';
		resultHTML += '<td>'+ orderLineRecs[l].olndsc +'</td>';
		resultHTML += '<td class="price">&pound;'+orderLineRecs[l].unipri+'</td>';
		resultHTML += '<td class="total">&pound;<span class="orderLineTotal">'+lineTotal+'</span></td>';
		resultHTML += '<td><a href="#" data-lin_id="'+l+'" class="btn btn-mini btn-danger deleteOlnBtn"><i class="icon-trash"></i></a></td>';
		resultHTML += '</tr>';
		
	}
	
	$('#orderLineTable').html( resultHTML );
	
	var orderTotal = 0;
		
	$('.orderLineTotal').each(function(){
		orderTotal += parseFloat( $(this).html() );
	});
	
	$('#subTotal').html( orderTotal.toFixed(2) );
	
	$('[name="vatrat"]', orderForm).change();
	
}

function displayOrders() {
	
	var ordersTotal =0;
	var orderCount = 0;
	
	var staID = '';
	$('[name="tmpsta_id"]:checked', orderFilterForm).each(function(){
		staID += (staID == '') ? $(this).val() : ',' + $(this).val();
	});
	
	//alert(staID);
	
	$('[name="sta_id"]', orderFilterForm).val(staID);
	
	//alert(orderFilterForm.serialize());
	
	$.ajax({
		url: 'ecommerce/orders_table.php',
		data: orderFilterForm.serialize(),
		type: 'GET',
		async: false,
		success: function( data ) {
			
			//alert(data);
			
			try { ordTable.fnDestroy(); } catch (ex) { }
			
			$('#ordersBody').html( data );
			
			ordTable = $("table#ordersTable").dataTable({
							"bDestroy": true,
							"aoColumns": [
                              {"bSortable": true},
                              {"bSortable": true},
                              {"iDataSort": 3},
                              {"bVisible": false},
                              {"iDataSort": 5},
                              {"bVisible": false},
                              {"bSortable": true},
                              {"bSortable": true}
                             ]
						});
			
		}
		
	});
	
	$('.orderTotalCalc').each(function(){
		ordersTotal += parseFloat($(this).html());
		orderCount++;
	});
	
	$('#ordersTotal').html( '&pound;' + ordersTotal.toFixed(2) );
	$('#ordersCount').html( orderCount );
	
}