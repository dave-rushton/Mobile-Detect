
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
            url: 'website/article_script.php',
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
                    displayArticles();
                }

            }

        });


    });

    searchForm.submit(function(e){

        e.preventDefault();
        var rtnHTML = displayArticles();

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
        displayArticles();
    });

    $('#createReportBtn').click(function (e) {
        e.preventDefault();

        $.msgAlert ({
            type: 'warning'
            , title: 'Create Report'
            , text: 'Are you sure you want to create this report? (It may overwrite any existing reports)'
            , callback: function () {
                $.ajax({
                    url: 'ecommerce/articles_csv_script.php',
                    data: searchForm.serialize(),
                    type: 'GET',
                    async: false,
                    success: function( data ) {

                        console.log(data);

                        var result = JSON.parse(data);

                        $.msgGrowl({
                            type: result.type,
                            title: result.title,
                            text: result.description
                        });

                    }
                });
            }
        });
    });

    displayArticles();
});

function changeScreen(screenID) {

    $('.orderScreen').fadeOut();
    setTimeout( function() { $(screenID).fadeIn(200, function(){ resize_chosen();}); } , 400);

}


function displayArticles() {

    var articlesTotal =0;
    var orderCount = 0;

    var staID = '';
    $('[name="tmpsta_id[]"]:checked', searchForm).each(function(){
        staID += (staID == '') ? $(this).val() : ',' + $(this).val();
    });

    $('[name="sta_id"]', searchForm).val(staID);

    var returnHTML = '';

    console.log("???");

    console.log("website/articles_table.php?" + searchForm.serialize());

    $.ajax({
        url: 'website/articles_table.php',
        data: searchForm.serialize(),
        type: 'GET',
        async: false,
        success: function( data ) {

            try { artTable.fnDestroy(); } catch (ex) { }


            returnHTML = data;

            $('#articlesBody').html( data );


            artTable = $("table#articlesTable").dataTable({
                "iDisplayLength": 10,
                "bDestroy": true,
                "aoColumns": [
                    {"bVisible": false},
                    {"iDataSort": 0},
                    {"bSortable": true},
                    {"bVisible": true},
                    {"bSortable": false},
                    {"bSortable": false},

                ]

            });

            artTable.fnSort( [ [0,'desc'] ] );

        }

    });

    return returnHTML;

    //$('.orderTotalCalc').each(function(){
    //	articlesTotal += parseFloat($(this).html());
    //	orderCount++;
    //});
    //
    //$('#articlesTotal').html( '&pound;' + articlesTotal.toFixed(2) );
    //$('#articlesCount').html( orderCount );

}