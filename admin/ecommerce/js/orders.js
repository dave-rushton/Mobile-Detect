
var changeStatusForm, searchForm;

$(function(){

    changeStatusForm = $('#changeStatusForm');
    searchForm = $('#searchForm');

    $('[name="begdat"],[name="enddat"]', searchForm).datepicker({ format: 'yyyy-mm-dd', weekStart: 1 });
	
	$('.screenSelect').click(function(e){
		e.preventDefault();
		changeScreen($(this).attr("href"));
	});

    changeStatusForm.submit(function(e){

        e.preventDefault();

        ordIDs = '';

        $('.ord_cb:checked').each(function(){

            ordIDs += ( ordIDs == '' ) ? $(this).val() : ',' + $(this).val();

        })

        $('[name="ord_id"]', changeStatusForm).val( ordIDs );

        //
        // UPDATE STATUS
        //

        $.ajax({
            url: 'ecommerce/order_script.php',
            data: changeStatusForm.serialize(),
            type: 'GET',
            async: false,
            success: function( data ) {

                var result = JSON.parse(data);

                $.msgGrowl({
                    type: result.type,
                    title: result.title,
                    text: result.description
                });

                if (result.type = 'success') {
                    displayOrders();
                }

            }

        });


    });

    searchForm.submit(function(e){

        e.preventDefault();
        var rtnHTML = displayOrders();

        if (rtnHTML > 0) {
            $('#searchDisplayBtn').click();
        }

    });


    $('#clearSearchStartDateBtn').click(function(e){
        e.preventDefault();
        $('[name="begdat"]', searchForm).val("");
    });
    $('#clearSearchEndDateBtn').click(function(e){
        e.preventDefault();
        $('[name="enddat"]', searchForm).val("");
    });


    $('#refreshTableBtn').click(function(e){
        e.preventDefault();
        displayOrders();
    })

    displayOrders();

});

function changeScreen(screenID) {
	
	$('.orderScreen').fadeOut();
	setTimeout( function() { $(screenID).fadeIn(200, function(){ resize_chosen();}); } , 400);
			
}


function displayOrders() {
	
	var ordersTotal =0;
	var orderCount = 0;
	
	var staID = '';
	$('[name="tmpsta_id[]"]:checked', searchForm).each(function(){
		staID += (staID == '') ? $(this).val() : ',' + $(this).val();
	});
	
	$('[name="sta_id"]', searchForm).val(staID);

    var returnHTML = '';

	$.ajax({
		url: 'ecommerce/orders_table.php',
		data: searchForm.serialize(),
		type: 'GET',
		async: false,
		success: function( data ) {

			try { ordTable.fnDestroy(); } catch (ex) { }

            returnHTML = data;

			$('#ordersBody').html( data );

            ordTable = $("table#ordersTable").dataTable({
                "iDisplayLength": 50,
                "bDestroy": true});

			ordTable.fnSort( [ [1,'desc'] ] );
			
		}
		
	});

    return returnHTML;

	//$('.orderTotalCalc').each(function(){
	//	ordersTotal += parseFloat($(this).html());
	//	orderCount++;
	//});
	//
	//$('#ordersTotal').html( '&pound;' + ordersTotal.toFixed(2) );
	//$('#ordersCount').html( orderCount );
	
}