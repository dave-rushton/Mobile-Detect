var searchForm,searchInput,searchDelay;

var tenderForm;

var basketBody;

var addForm;

$(function(){

    searchForm = $('#searchForm');
    searchInput = $('[name="srcnam"]', searchInput);
    tenderForm = $('#tenderForm');
    basketBody = $('#basketBody');
    addForm = $('#addForm');

    //
    // SEARCH FUNCTIONALITY
    //

    searchInput.bind('keyup paste', function(){

        clearTimeout(searchDelay);
        searchDelay = setTimeout(searchProducts, 300);

    });

    searchForm.submit(function(e){

        e.preventDefault();

    });

    //
    // TENDER FORM
    //

    $('[name="taken"]', tenderForm).bind('keyup paste', function(){

        var change = 0;

        taken = parseFloat($('[name="taken"]', tenderForm).val()).toFixed(2);
        total = parseFloat($('#totalPrice').val()).toFixed(2);
        change = parseFloat(taken - total).toFixed(2);

        $('[name="change"]', tenderForm).val( change );

        if ( change < 0 ) {
            $('#buyButton').attr("disabled", "disabled");
        } else {
            $('#buyButton').attr("disabled", false);
        }

    }).focus(function(){

        $(this).select();

    });

    //
    // TABLE CONTROLS
    //

    basketTable();

    basketBody.on('click','a.removeRow',function(e){

        e.preventDefault();

        $.ajax({
            url: "ecommerce/ajax/pos_control.php",
            data: 'action=removerow&rownum=' + $(this).data('itemrow'),
            type: "POST",
            async: true,
            success: function (data) {

                basketTable();

            },
            error: function (x, e) {

                alert('error');

            }
        });

    }).on('keyup','input',function(e){

        e.preventDefault();

        $.ajax({
            url: "ecommerce/ajax/pos_control.php",
            data: 'action=updaterowqty&rownum=' + $(this).data('itemrow') + '&qty=' + $(this).val(),
            type: "POST",
            async: true,
            success: function (data) {

                basketTable();

            },
            error: function (x, e) {

                alert('error');

            }
        });

    }).on('focus','input',function(e){

        $(this).select();

    });


    //
    // QUICK ADD FORM
    //

    addForm.submit(function(e){

        e.preventDefault();

        $.ajax({
            url: "ecommerce/ajax/pos_control.php",
            data: 'action=manualadd&' + addForm.serialize(),
            type: "POST",
            async: true,
            success: function (data) {

                basketTable();

            },
            error: function (x, e) {

                alert('error');

            }
        });

    });

    addForm.find('input').bind('focus', function(){

        $(this).select();

        //var url = 'http://google.com';
        //
        //window.open(url,'_blank');
        //window.open(url);

    })


    $('#buyButton').click(function(e){

        e.preventDefault();

        $.msgAlert ({
            type: 'warning'
            , title: 'Confirm Purchase'
            , text: 'Confirm purchase of shop bought goods'
            , callback: function () {

                $.ajax({
                    url: "ecommerce/ajax/pos_control.php",
                    data: 'action=shoppayment',
                    type: "POST",
                    async: true,
                    success: function (data) {

                        basketTable();

                    },
                    error: function (x, e) {

                    }
                });

            }
        });

    });


    $('#printButton').click(function(e){

        e.preventDefault();

        //$("basketTableWrapper").printElement();

        PrintElem('printWrapper');

    })

});

function searchProducts(){

    if (searchInput.val().length > 0 ) {

        $.ajax({
            url: "ecommerce/ajax/pos_control.php",
            data: 'action=search&searchterm=' + searchInput.val(),
            type: "POST",
            async: true,
            success: function (data) {

                basketTable();

            },
            error: function (x, e) {

                alert('error');

            }
        });

    }

}


function basketTable(){

    $.ajax({
        url: "ecommerce/ajax/pos_control.php",
        data: 'action=table',
        type: "POST",
        async: true,
        success: function(data) {

            $('#basketBody').html( data );

            var printBasketHTML = '';

            $('#basketBody tr').each(function(){

                if ( $(this).find('td:nth-child(5)').length > 0 ) {

                    printBasketHTML += $(this).find('td:nth-child(2)').html() + '<br><strong>'+$(this).find('td:nth-child(5)').html()+'</strong><br>';

                }

            });

            $('#printBasket').html( printBasketHTML );

            if ( parseFloat($('#totalPrice').val()) <= 0 ) {
                $('#buyButton').attr("disabled", "disabled");
            } else {
                $('#buyButton').attr("disabled", false);
            }

            $('#printPrice').html( '&pound;' + $('#totalPrice').val() );

            searchInput.val('').focus();

        },
        error: function (x, e) {


            alert('error');

        }
    });

}

function PrintElem(elem)
{
    var mywindow = window.open('', 'PRINT', 'height=600,width=300');

    mywindow.document.write('<html><head><title>' + document.title  + '</title>');
    mywindow.document.write('</head><body >');
    //mywindow.document.write('<h1>' + document.title  + '</h1>');
    mywindow.document.write(document.getElementById(elem).innerHTML);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();
    mywindow.close();

    return true;
}