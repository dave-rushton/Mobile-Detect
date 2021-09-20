
var orderLineRecs = [];
var orderLine, orderForm, orderLineForm, selectCustomerForm, trackingForm;

$(function(){

    orderForm = $('#orderForm');
    orderLineForm = $('#orderLineForm');
    selectCustomerForm = $('#selectCustomerForm');
    trackingForm = $('#trackingForm');

    $('#InvDat, #DueDat, #PayDat').datepicker({format: 'dd-mm-yyyy'});

    $('[name="sta_id"]', orderForm).change(function(){

        if ($(this).val() == 20) {
            $('#payDatDiv').show();
        } else {
            $('#payDatDiv').hide();
        }

    });

    $('#sendTrackingEmailBtn').click(function(e){

        e.preventDefault();

        $.ajax({
            url: 'ecommerce/order-despatched-email.php',
            data: 'ord_id=' + $('[name="ord_id"]', orderForm).val() + '&' + trackingForm.serialize(),
            type: 'POST',
            async: false,
            success: function (data) {

                //$('#emailModal').modal('hide');

                $.msgGrowl({
                    type: 'success',
                    title: 'Email Sent',
                    text: 'Order email sent'
                });


            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });


    });

    $('#emailOrderBtn').click(function(e){
        e.preventDefault();

        $('[name="emaadr"]', $('#emailForm')).val( $('[name="emaadr"]', orderForm).val() );

        $('#emailModal').modal('show');
    });

    $('#sendOrderEmailBtn').click(function(e){

        e.preventDefault();

        $.ajax({
            url: 'ecommerce/enquiry-email.php',
            // url: 'ecommerce/order-email.php',
            data: 'ord_id=' + $('[name="ord_id"]', $('#emailForm')).val() + '&emaadr=' + $('[name="emaadr"]', $('#emailForm')).val(),
            type: 'POST',
            async: false,
            success: function (data) {
                
                $('#emailModal').modal('hide');

                var result = JSON.parse(data);

                $.msgGrowl({
                    type: result.type,
                    title: result.title,
                    text: result.description
                });

            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });


    })



    $('#selectCustomerModal').on('shown', function() {
        resize_chosen();
    });

    $('#selectCustomerBtn').click(function(e){
        e.preventDefault();
        $('#selectCustomerModal').modal('show');
    });

    $('#editDeliveryAddress').click(function(e){
        e.preventDefault();
        $('#viewDeliveryAddressDiv').hide();
        $('#editDeliveryAddressDiv').show();
    });
    $('#updateDeliveryAddress').click(function(e){
        e.preventDefault();

        var adrHTML = '';

        adrHTML += '<address>';
        //adrHTML += $('[name="planam"]', orderForm ).val() + '<br>';
        adrHTML += $('[name="cusnam"]', orderForm ).val() + '<br>';
        adrHTML += $('[name="adr1"]', orderForm ).val() + '<br>';
        adrHTML += $('[name="adr2"]', orderForm ).val() + '<br>';
        adrHTML += $('[name="adr3"]', orderForm ).val() + '<br>';
        adrHTML += $('[name="adr4"]', orderForm ).val() + '<br>';
        adrHTML += $('[name="pstcod"]', orderForm ).val() + '<br>';
        adrHTML += '</address>';

        $('#customerAddressDiv').html( adrHTML );

        $('#viewDeliveryAddressDiv').show();
        $('#editDeliveryAddressDiv').hide();
    });

    $('#editInvoiceAddress').click(function(e){
        e.preventDefault();
        $('#viewInvoiceAddressDiv').hide();
        $('#editInvoiceAddressDiv').show();
    });
    $('#updateInvoiceAddress').click(function(e){
        e.preventDefault();

        var adrHTML = '';

        adrHTML += '<address>';
        adrHTML += $('[name="payadr1"]', orderForm ).val() + '<br>';
        adrHTML += $('[name="payadr2"]', orderForm ).val() + '<br>';
        adrHTML += $('[name="payadr3"]', orderForm ).val() + '<br>';
        adrHTML += $('[name="payadr4"]', orderForm ).val() + '<br>';
        adrHTML += $('[name="paypstcod"]', orderForm ).val() + '<br>';
        adrHTML += '</address>';

        $('#invoiceAddressDiv').html( adrHTML );

        $('#viewInvoiceAddressDiv').show();
        $('#editInvoiceAddressDiv').hide();
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

            $('#customerAddressDiv, #invoiceAddressDiv').html( adrHTML );

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
                required: true
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
                orderLineRecs[l].vatrat = $('[name="vatrat"]', orderLineForm ).val();

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

    $('#cancelOrderLineBtn').click(function(e){
        e.preventDefault();
        changeScreen('#orderEditDiv');
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


    // Populate Screen

    var ordId = $('[name="ord_id"]', orderForm).val();

    if ( ordId > 0 ) {

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

                    // Set up order lines

                    orderLineRecs = orderLinesArray;

                    displayOrderLines();

                    $('#printOrderBtn').show().attr("href", 'ecommerce/print_order.php?output=screen&ord_id=' + orderArray[0].ord_id);

                } catch(ex) {
                    alert(ex);
                }

            }
        });

    }

    $('#vatrat').change();

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
        "altref": $('[name="altref"]', orderForm).val(),
        "altnam": $('[name="altnam"]', orderForm).val(),
        "del_id": $('[name="del_id"]', orderForm).val(),
        "discod": $('[name="discod"]', orderForm).val(),
        "emaadr": $('[name="emaadr"]', orderForm).val(),
        "orderlines": orderLineRecs
    }

    console.log( JSON.stringify(orderRec) );
    //alert( JSON.stringify(orderRec) );

    $.ajax({
        url: 'ecommerce/order_script.php',
        data: { action: "update", ajax: true, jsonobj: JSON.stringify(orderRec) },
        type: 'POST',
        async: false,
        success: function( data ) {

            alert(data);

            $('#returnHTML').html( data );

            try {

                var result = JSON.parse(data);

                $.msgGrowl ({
                    type: result.type
                    , title: result.title
                    , text: result.description
                });

                $('#id', orderForm).val( result.id );

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

    //displayOrders();

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
        vatrat: $('[name="vatrat"]', orderLineForm ).val(),
        olndsc: $('[name="olndsc"]', orderLineForm ).val(),
        tblnam: 'SALE',
        tbl_id: 0,
        sta_id: 0,
        altref: '',
        altnam: ''
        //vatrat: $('[name="vatrat"] option:selected', orderLineForm ).text()

    }

    orderLineRecs.push(orderLineRec);

}

function displayOrderLines() {

    var resultHTML = '';

    for (l=0;l<orderLineRecs.length;l++) {

        var lineTotal = (parseFloat( orderLineRecs[l].unipri ) * parseFloat( orderLineRecs[l].numuni )).toFixed(2);

        resultHTML += '<tr>';
        resultHTML += '<td>'+orderLineRecs[l].numuni+'</td>';
        resultHTML += '<td><a href="#" data-lin_id="'+l+'" class="editOrderLineLink">'+ orderLineRecs[l].prdnam +'</a></td>';
        resultHTML += '<td>'+ orderLineRecs[l].olndsc +'</td>';
        resultHTML += '<td class="price">&pound;'+orderLineRecs[l].unipri+'</td>';

        var lineAmount = orderLineRecs[l].unipri * orderLineRecs[l].numuni;
        var vatAmount = 100 + parseFloat(orderLineRecs[l].vatrat);
        vatAmount = vatAmount / 100;
        vatAmount = lineAmount - ((lineAmount) / vatAmount);

        resultHTML += '<td class="price">&pound;'+vatAmount.toFixed(2)+'</td>';

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

}

function displayOrders() {

    var ordersTotal =0;
    var orderCount = 0;

    var staID = '';
    //$('[name="tmpsta_id"]:checked', orderFilterForm).each(function(){
    //	staID += (staID == '') ? $(this).val() : ',' + $(this).val();
    //});

    //alert(staID);

    //$('[name="sta_id"]', orderFilterForm).val(staID);

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