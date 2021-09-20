var priceBandForm;

$(function(){

    priceBandForm = $('#priceBandForm');

    var oTable = $('#productTypeTable').dataTable({
        "bServerSide": true,
        "sServerMethod": "GET",
        "sAjaxSource": "products/producttypes_table.php",
        "sAjaxDataProp": "aaData",
        "iDisplayLength": 999,

        "sPaginationType": "full_numbers",
        "oLanguage":{
            "sSearch": "<span>Search:</span> ",
            "sInfo": "Showing <span>_START_</span> to <span>_END_</span> of <span>_TOTAL_</span> entries",
            "sLengthMenu": "_MENU_ <span>entries per page</span>"
        },
        "fnInitComplete": function(settings, json) {

            $('.selectPrtLink')[0].click();

        },
        "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
            return iStart +" to "+ iEnd;
        },
        "aoColumnDefs": [
            { "bVisible": false, "aTargets": [ 0 ] },
            { "bVisible": false, "aTargets": [ 1 ] },
            { "bVisible": true, "aTargets": [ 2 ] },
            { "bVisible": true, "aTargets": [ 3 ] },
            { "bVisible": false, "aTargets": [ 4 ] }
        ],
        "fnRowCallback": function( nRow, aData, iDisplayIndex ) {

            $('td:eq(0)', nRow).html('<a href="#" class="selectPrtLink" data-prt_id="' + aData[1] + '">' + aData[2] + '</a>');

            return nRow;
        }
    });


    $(".dataTables_length select").wrap("<div class='input-mini'></div>").chosen({
        disable_search_threshold: 9999999
    });

    $( 'input', $('#productTypeTable_wrapper') ).garlic();
    $( 'input', $('#productTypeTable_wrapper') ).keyup();



    $('#bndsiz').on('keyup paste', function(){

        var bndArray = [];

        bndArray = $(this).val().split(",");

        var resultHTML = '';

        if ( bndArray.length > 0 ) {

            for (i=0;i<bndArray.length;i++) {

                resultHTML += '<div class="row-fluid">';
                resultHTML += '<div class="span4">';
                resultHTML += '<div class="control-group">';
                resultHTML += '<label class="control-label">Discount from x units</label>';
                resultHTML += '<div class="controls">';
                resultHTML += '<input type="number" class="input-small" name="numuni[]" value="'+bndArray[i]+'" required>';
                resultHTML += '</div>';
                resultHTML += '</div>';
                resultHTML += '</div>';
                resultHTML += '<div class="span4">';
                resultHTML += '<div class="control-group">';
                resultHTML += '<label class="control-label">Unit Price<small></small></label>';
                resultHTML += '<div class="controls">';
                resultHTML += '<input type="text" class="input-small" name="unipri[]" value="" required>';
                resultHTML += '</div>';
                resultHTML += '</div>';
                resultHTML += '</div>';
                resultHTML += '<div class="span4">';
                resultHTML += '<p style="padding: 10px; margin: 0; text-align: right;">';
                resultHTML += '<button type="submit" class="btn btn-primary"><i class="icon-save"></i> Update</button>';
                resultHTML += '</p>';
                resultHTML += '</div>';
                resultHTML += '</div>';

            }
        }

        $('#priceBandConfig').html(resultHTML);

    })

    //
    // SELECT PRODUCT TYPE
    //

    $('#productTypeTable').on('click','.selectPrtLink', function(e){

        e.preventDefault();

        $('[name="prt_id"]', priceBandForm).val( $(this).data('prt_id') );

        changeProductType();
        getVariants();

    });

    //
    // SELECT PRODUCT VARIANT
    //

    $('#productTable').on('click','.editProduct', function(e){

        e.preventDefault();

        //$('[name="prd_id"]', priceBandForm).val( $(this).data('prd_id') );

        $.ajax({
            url: priceBandForm.attr("action"),
            data: 'action=search&ajax=true&' + priceBandForm.serialize(),
            type: 'POST',
            async: false,
            success: function( data ) {


                try {

                    console.log(data);

                    var result = JSON.parse(data);

                    var resultHTML = '';

                    for (i=0; i < result.length; i++) {

                        resultHTML += '<tr>';
                        resultHTML += '<td>'+result[i].numuni+'</td>';
                        resultHTML += '<td>'+result[i].unipri+'</td>';
                        resultHTML += '</tr>';

                    }

                    $('#priceBandBody').html( resultHTML );


                } catch (Ex) {

                    $.msgGrowl ({
                        type: 'error'
                        , title: 'Update Failure'
                        , text: 'The server replied with an error'
                    });

                }

            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });

    });

    $('[name="pla_id"]', $('#customerSelectForm')).on('change', function(){

        $('[name="cus_id"]', priceBandForm).val( $(this).val() );

    })

    $('[name="pla_id"]', $('#customerSelectForm')).chosen();


    priceBandForm.submit(function(e){

        e.preventDefault();

        if (priceBandForm.valid()) {

            $.ajax({
                url: priceBandForm.attr("action"),
                data: 'action=update&ajax=true&' + priceBandForm.serialize(),
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

                        //$('#id', priceBandForm ).val( result.id );

                        changeProductType();

                    } catch (Ex) {

                        $.msgGrowl ({
                            type: 'error'
                            , title: 'Update Failure'
                            , text: 'The server replied with an error'
                        });

                    }

                },
                error: function (x, e) {
                    throwAjaxError(x, e);
                }
            });

        }


    });


    $('#priceBandBody').on('click', '.deleteBandLink', function(e){

        e.preventDefault();

        var prbId = $(this).data('prb_id');

        var thisRow = $(this).parent().parent();

        $.msgAlert ({
            type: 'warning'
            , title: 'Delete This Price band'
            , text: 'Are you sure you wish to permanently remove this price band from the database?'
            , callback: function () {

                $.ajax({
                    url: priceBandForm.attr("action"),
                    data: 'action=delete&ajax=true&prb_id=' + prbId,
                    type: 'POST',
                    async: false,
                    success: function( data ) {

                        var result = JSON.parse(data);

                        $.msgGrowl ({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });

                        thisRow.slideUp();

                    },
                    error: function (x, e) {
                        throwAjaxError(x, e);
                    }
                });

            }
        });

    });


    $('#productWrapper').slimScroll({
        height: '542px'
    });


});

function changeProductType () {

    $.ajax({
        url: priceBandForm.attr("action"),
        data: 'action=search&ajax=true&prb_id=0&prt_id=' + $('[name="prt_id"]', priceBandForm).val(),
        type: 'POST',
        async: false,
        success: function( data ) {

            try {

                var result = JSON.parse(data);

                var resultHTML = '';

                for (i=0; i < result.length; i++) {

                    resultHTML += '<tr>';
                    resultHTML += '<td>'+result[i].prdnam+'</td>';
                    resultHTML += '<td>'+result[i].planam+'</td>';
                    resultHTML += '<td>'+result[i].numuni+'</td>';
                    resultHTML += '<td>'+result[i].unipri+'</td>';
                    resultHTML += '<td><a href="#" class="btn btn-danger btn-mini deleteBandLink" data-prb_id="'+result[i].prb_id+'"><i class="icon icon-remove"></i></a></td>';
                    resultHTML += '</tr>';

                }

                $('#priceBandBody').html( resultHTML );


            } catch (Ex) {

                $.msgGrowl ({
                    type: 'error'
                    , title: 'Update Failure'
                    , text: 'The server replied with an error'
                });

            }

        },
        error: function (x, e) {
            throwAjaxError(x, e);
        }
    });

}

function getVariants() {

    $.ajax({
        url: 'products/products_script.php',
        data: 'action=select&prd_id=0&prt_id=' + $('[name="prt_id"]', priceBandForm).val(),
        type: 'GET',
        async: false,
        success: function( data ) {

            var result = JSON.parse(data);

            var resultHTML = '';

            resultHTML += '<option value="0">All Variants</option>';

            for (i=0; i < result.length; i++) {

                resultHTML += '<option value="'+result[i].prd_id+'">'+result[i].prdnam+'</option>';

            }

            //$('[name="prd_id"]', priceBandForm).chosen('destroy');
            $('[name="prd_id"]', priceBandForm).html(resultHTML);
            $('#productSelect').html(resultHTML);
            //$('[name="prd_id"]', priceBandForm).chosen();

        }
    });

}