
var basketForm, productSearchForm, addGroupForm, modalProductSearchForm;

$(function(){

    $('#productListBody').on('click','.alternateBtn', function(e){

        e.preventDefault();
        $('#productModal').modal('show');

    })

    //
    // BUILD PRODUCT LIST
    //

    productSearchForm = $('#productSearchForm');
    basketForm = $('#basketForm');
    addGroupForm = $('#addGroupForm');
    modalProductSearchForm = $('#modalProductSearchForm');

    var prddata;

    $('#relatedProductBox').block({ message: 'Retrieving Products' });

    $.ajax({
        url: "products/products_script.php",
        data: 'action=select&prd_id=0',
        type: "GET",
        async: true,
        success: function(data) {

            prddata = JSON.parse( data );

            $(':input.autocomplete').typeahead({
                limit: 30,
                items: 50,
                source: function(query, process) {
                    objects = [];
                    map = {};
                    var data = prddata;
                    $.each(data, function(i, object) {
                        map[object.atrnam + ' :<br> ' + object.prdnam + ' (' + object.unipri + ')'] = object;
                        objects.push(object.atrnam + ' :<br> ' + object.prdnam + ' (' + object.unipri + ')');
                    });

                    return objects;

                },
                updater: function(item) {

                    //$('#selectedProduct', productSearchForm).val(map[item].prdnam);
                    $('[name="prd_id"]', modalProductSearchForm).val(map[item].prd_id);
                    $('[name="unipri"]', modalProductSearchForm).val(map[item].unipri);
                    //$('[name="prdimg"]', productSearchForm).val(map[item].prdimg);

                    return item;
                }
            });

            $('#relatedProductBox').unblock();

        }
    });


    //
    // Build Tag List
    //


    $('[name="bsktagselect"]', basketForm).each(function(){
        var $el = $(this);
        var search = ($el.attr("data-nosearch") === "true") ? true : false,
            opt = {};
        if(search) opt.disable_search_threshold = 9999999;
        $el.chosen(opt);

        resize_chosen();

    });


    $('#addProductToGroupBtn').click(function(e){
        e.preventDefault();

        addGroup();

    });

    $('[name="prdnam"]', productSearchForm).keypress(function(e){

        if(e.which == 13) {

            addGroup();

        }


    });

    //function addGroup() {
    //
    //    if ( $('[name="prd_id"]', productSearchForm).val().length == 0 ) return false;
    //
    //    productHTML = '<li data-prd_id="'+ $('[name="prd_id"]', productSearchForm).val() +'">'+ $('[name="prdnam"]', productSearchForm).val() +'</li>';
    //    $('#alternateProducts').append(productHTML);
    //
    //    var productHTML = '';
    //
    //    productHTML += '<tr>';
    //    productHTML += '<td>'+ $('#selectedProduct', productSearchForm).val() +'</td>';
    //    productHTML += '<td style="text-align: right">&pound;<span class="selectedPrice">'+ $('[name="unipri"]', productSearchForm).val() +'</span></td>';
    //
    //    if ( $('#productListBody').html().length == 0 ) {
    //        productHTML += '<td style="text-align: center"><input type="radio" name="defsel" value="1" checked> </td>';
    //    } else {
    //        productHTML += '<td style="text-align: center"><input type="radio" name="defsel" value="1"> </td>';
    //    }
    //
    //    //productHTML += '<td style="text-align: center"><input type="checkbox" name="bprman" value="1"> </td>';
    //
    //    //productHTML += '<td><button class="btn btn-primary alternateBtn"><i class="icon icon-search"></i></button></td>';
    //
    //    productHTML += '<td><input type="text" name="exttxt" value=""></td>';
    //    productHTML += '<td><input style="width: 40px; text-align: right" type="text" name="bprman" value="0.00"></td>';
    //    productHTML += '<td><a href="#" class="btn btn-danger deleteProductBtn"><i class="icon icon-trash"></i></a></td>';
    //    productHTML += '<td><a href="#" class="btn btn-success sortProductBtn" data-prd_id="'+ $('[name="prd_id"]', productSearchForm).val() +'"><i class="icon icon-sort"></i></a></td>';
    //    productHTML += '</tr>';
    //
    //    $('#productListBody').append(productHTML);
    //
    //    $('#selectedProduct', productSearchForm).val('');
    //    $('[name="prd_id"]', productSearchForm).val('');
    //    $('[name="unipri"]', productSearchForm).val('');
    //    $('[name="prdimg"]', productSearchForm).val('');
    //    $('[name="prdnam"]', productSearchForm).val('').focus();
    //
    //}

    productSearchForm.submit(function(e){
        e.preventDefault();
    //
    //    if ( $('[name="prd_id"]', productSearchForm).val().length == 0 ) return false;
    //
    //    productHTML = '<li data-prd_id="'+ $('[name="prd_id"]', productSearchForm).val() +'">'+ $('#selectedProduct', productSearchForm).val() +'</li>';
    //    $('#alternateProducts').append(productHTML);
    //
    //
    //    //
    //    // Create Basket Product
    //    //
    //
    //    var productHTML = '';
    //
    //    productHTML += '<tr>';
    //    productHTML += '<td><img src="../uploads/images/169-130/'+ $('[name="prdimg"]', productSearchForm).val() +'"/></td>';
    //    productHTML += '<td>'+ $('#selectedProduct', productSearchForm).val() +'</td>';
    //    productHTML += '<td style="text-align: right">&pound;<span class="selectedPrice">'+ $('[name="unipri"]', productSearchForm).val() +'</span></td>';
    //    productHTML += '<td style="text-align: center"><input type="checkbox" name="defsel" value="1" checked> </td>';
    //    productHTML += '<td style="text-align: center"><input type="checkbox" name="bprext" value="1"> </td>';
    //    productHTML += '<td style="text-align: center"><input type="checkbox" name="bprman" value="1"> </td>';
    //
    //    productHTML += '<td><button class="btn btn-primary alternateBtn"><i class="icon icon-search"></i></button></td>';
    //
    //    //productHTML += '<td><input type="text" name="exttxt" value=""></td>';
    //    productHTML += '<td><input style="width: 40px; text-align: right" type="text" name="bprman" value="0.00"></td>';
    //    productHTML += '<td><a href="#" class="btn btn-danger deleteProductBtn"><i class="icon icon-trash"></i></a></td>';
    //    productHTML += '<td><a href="#" class="btn btn-success sortProductBtn" data-prd_id="'+ $('[name="prd_id"]', productSearchForm).val() +'"><i class="icon icon-sort"></i></a></td>';
    //    productHTML += '</tr>';
    //
    //    $('#productListBody').append(productHTML);
    //
    //    $('#selectedProduct', productSearchForm).val('');
    //    $('[name="prd_id"]', productSearchForm).val('');
    //    $('[name="unipri"]', productSearchForm).val('');
    //    $('[name="prdimg"]', productSearchForm).val('');
    //    $('[name="prdnam"]', productSearchForm).val('').focus();
    //
    //    calcBasketBasePrice();

    });


    $('#productListTable').on('click','.deleteProductBtn', function(e){
        e.preventDefault();

        var thisLink = $(this);

        var bprId = thisLink.data('bpr_id');

        $.msgAlert ({
            type: 'warning'
            , title: 'Delete This Product From Group'
            , text: 'Are you sure you wish to permanently remove this product from this group?'
            , callback: function () {

                $.ajax({
                    url: 'custom/basket_script.php?action=deleteproduct&bpr_id=' + bprId,
                    type: 'POST',
                    async: false,
                    success: function( data ) {

                        thisLink.parent().parent().fadeOut().remove();

                    },
                    error: function (x, e) {
                        throwAjaxError(x, e);
                    }
                });

            }
        });

        return false;

    });

    $('#extrasListBody').on('click','.deleteExtraBtn', function(e){
        e.preventDefault();
        $(this).parent().parent().fadeOut().remove();
    });

    //$('#addExtraBtn').click(function(e){
    //
    //    e.preventDefault();
    //
    //    var extRow = $(this).parent().parent();
    //    var bexTtl = extRow.find('[name="bexttl"]').val();
    //    var bexTxt = extRow.find('[name="bextxt"]').val();
    //    var bexDef = (extRow.find('[name="bexdef"]').is(':checked')) ? 'checked' : '';
    //    var bexMan = (extRow.find('[name="bexman"]').is(':checked')) ? 'checked' : '';
    //    var bexPri = extRow.find('[name="bexpri"]').val();
    //
    //    var extrasHTML = '';
    //    extrasHTML += '<tr>';
    //    extrasHTML += '<td><input type="text" name="bexttl" value="'+bexTtl+'"></td>';
    //    extrasHTML += '<td><input type="text" name="bextxt" value="'+bexTxt+'"> </td>';
    //    extrasHTML += '<td style="text-align: center;"><input type="checkbox" name="bexdef" value="1" '+bexDef+'> </td>';
    //    extrasHTML += '<td style="text-align: center;"><input type="checkbox" name="bexman" value="1" '+bexMan+'> </td>';
    //    extrasHTML += '<td><input type="text" name="bexpri" value="'+bexPri+'"> </td>';
    //    extrasHTML += '<td><a href="#" class="btn btn-danger deleteExtraBtn"><i class="icon icon-trash"></i></a></td>';
    //    extrasHTML += '<td><a href="#" class="btn btn-success sortExtraBtn""><i class="icon icon-sort"></i></a></td>';
    //    extrasHTML += '</tr>';
    //
    //    $('#extrasListBody').append(extrasHTML);
    //
    //    extRow.find('[name="bextxt"]').val('');
    //    extRow.find('[name="bexdef"]').prop('checked', false);
    //    extRow.find('[name="bexman"]').prop('checked', false);
    //    extRow.find('[name="bexpri"]').val('');
    //    extRow.find('[name="bexttl"]').val('').focus();
    //
    //})


    $('[name="mrk_up"]').on('keyup paste', function(){
        calcBasketBasePrice();
    });

    $('[name="defsel"]').change(function(){
        calcBasketBasePrice();
    })


    calcBasketBasePrice();


    $('[name="bskttl"]', basketForm).on("keyup paste", function() {
        $('[name="seourl"]', basketForm ).val( seoURL( $('[name="bskttl"]', basketForm).val() ) );
    });


    $('#updateBasketBtn').click(function(e){
        e.preventDefault();
        basketForm.submit();
    })

    basketForm.submit(function(e){

        e.preventDefault();

        $('[name="bsktag"]', basketForm).val( $('[name="bsktagselect"]', basketForm).val() );

        var fields = $(".customfield", basketForm).serializeArray();
        var elementVariables = JSON.stringify(fields);
        var postData = encodeURIComponent(elementVariables);


        var limits = [];
        $('.atr_id', $('#productGroupBody')).each(function(){

            var minUni = $(this).find('[name="minuni"]').val();
            var maxUni = $(this).find('[name="maxuni"]').val();

            var limitRec = {
                atr_id : $(this).data('atr_id'),
                minuni : minUni,
                maxuni : maxUni
            }

            limits.push(limitRec);

        });

        var postData = JSON.stringify(limits);


        //
        // Products
        //

        //var srtOrd = 0;
        //var productArray = [];
        //$('.sortProductBtn', $('#productListBody')).each(function(){
        //
        //    var defSel = $(this).parent().prev().prev().prev().prev().prev().prev().find('input').is(':checked');
        //    var defExt = $(this).parent().prev().prev().prev().prev().prev().find('input').is(':checked');
        //    var defMan = $(this).parent().prev().prev().prev().prev().find('input').is(':checked');
        //    var extPri = $(this).parent().prev().prev().find('input').val();
        //    var extTxt = $(this).parent().prev().prev().prev().find('input').val();
        //
        //    var basketProduct = {
        //        prd_id : $(this).data('prd_id'),
        //        srtord : srtOrd,
        //        defsel : defSel,
        //        bprext : defExt,
        //        bprman : defMan,
        //        extpri : extPri,
        //        exttxt : extTxt
        //    }
        //
        //    productArray.push(basketProduct);
        //
        //    srtOrd++;
        //
        //});

        //
        // Extras
        //

        var srtOrd = 0;
        var extraArray = [];
        $('.sortExtraBtn', $('#extrasListBody')).each(function(){

            var extRow = $(this).parent().parent();
            var bexTtl = extRow.find('[name="bexttl"]').val();
            var bexTxt = extRow.find('[name="bextxt"]').val();
            var bexDef = extRow.find('[name="bexdef"]').is(':checked');
            var bexMan = extRow.find('[name="bexman"]').is(':checked');
            var bexPri = extRow.find('[name="bexpri"]').val();

            var basketExtra = {
                bexttl : bexTtl,
                bextxt : bexTxt,
                bexpri : bexPri,
                bexdef : bexDef,
                bexman : bexMan,
                srtord : srtOrd
            }

            extraArray.push(basketExtra);

            srtOrd++;

        });


        if( $('#logofile')[0].files[0] ) {

            var data;

            data = new FormData();
            data.append('logofile', $('#logofile')[0].files[0]);
            data.append('newname', $('[name="seourl"]', basketForm).val());

            $.ajax({
                url: 'custom/uploadbasketimage.php',
                data: data,
                processData: false,
                type: 'POST',
                contentType: false,
                aSync: false,
                success: function(data) {

                    //alert(data);

                    $('[name="bskimg"]', basketForm).val(data);

                    basketObj = {
                        bsk_id : $('[name="bsk_id"]', basketForm).val(),
                        bskttl : $('[name="bskttl"]', basketForm).val(),
                        bskdsc : $('[name="bskdsc"]', basketForm).val(),
                        unipri : $('[name="unipri"]', basketForm).val(),
                        mrk_up : $('[name="mrk_up"]', basketForm).val(),
                        bskimg : $('[name="bskimg"]', basketForm).val(),
                        custom : ($('[name="custom"]', basketForm).prop('checked')) ? 1 : 0,
                        sta_id : 0,
                        srtord : 0,
                        bsktxt : postData,
                        seourl : $('[name="seourl"]', basketForm).val(),
                        keywrd : $('[name="keywrd"]', basketForm).val(),
                        keydsc : $('[name="keydsc"]', basketForm).val(),
                        atr_id : $('[name="atr_id"]', basketForm).val(),
                        bsktag : $('[name="bsktag"]', basketForm).val(),
                        products : productArray,
                        extras : extraArray
                    }

                    console.log(basketObj);

                    $.ajax({
                        url: 'custom/basket_script.php',
                        data: { action: "update", ajax: true, jsonobj: JSON.stringify(basketObj) },
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

                                $('#id', basketForm).val( result.id );
                                $('[name="bsk_id"]', productSearchForm).val( result.id );

                                $('#addProductsDiv').show();

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

                },
                error: function (x, e) {

                    throwAjaxError(x, e);

                }
            });

        } else {

            basketObj = {
                bsk_id : $('[name="bsk_id"]', basketForm).val(),
                bskttl : $('[name="bskttl"]', basketForm).val(),
                bskdsc : $('[name="bskdsc"]', basketForm).val(),
                unipri : $('[name="unipri"]', basketForm).val(),
                mrk_up : $('[name="mrk_up"]', basketForm).val(),
                bskimg : $('[name="bskimg"]', basketForm).val(),
                custom : ($('[name="custom"]', basketForm).prop('checked')) ? 1 : 0,
                sta_id : 0,
                srtord : 0,
                bsktxt : postData,
                seourl : $('[name="seourl"]', basketForm).val(),
                keywrd : $('[name="keywrd"]', basketForm).val(),
                keydsc : $('[name="keydsc"]', basketForm).val(),
                atr_id : $('[name="atr_id"]', basketForm).val(),
                bsktag : $('[name="bsktag"]', basketForm).val(),
                products : productArray,
                extras : extraArray
            }

            console.log(basketObj);

            $.ajax({
                url: 'custom/basket_script.php',
                data: { action: "update", ajax: true, jsonobj: JSON.stringify(basketObj) },
                type: 'POST',
                async: false,
                success: function( data ) {

                    console.log(data);

                    try {

                        var result = JSON.parse(data);

                        $.msgGrowl ({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });

                        $('#id', basketForm).val( result.id );
                        $('[name="bsk_id"]', productSearchForm).val( result.id );

                        $('#addProductsDiv').show();

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

        }

    })

    $('#productListBody').sortable({
        handle: '.sortProductBtn' ,
        stop: function( event, ui ) {

            var prdLst = '';

            $('.sortProductBtn', $('#productListBody')).each(function(){
                prdLst += (prdLst == '') ? $(this).data('prd_id') : ',' + $(this).data('prd_id');
            });

        }
    });

    $('#extrasListBody').sortable({
        handle: '.sortExtraBtn' ,
        stop: function( event, ui ) {

        }
    });




    //
    // PRODUCT GROUPS
    //

    addGroupForm.submit(function(e){

        e.preventDefault();

        //alert('action=addgroup&bsk_id=' + $('[name="bsk_id"]', basketForm).val() + '&bpgttl=' + $('[name="bpgttl"]', addGroupForm).val());

        $.ajax({
            url: 'custom/basket_script.php?action=addgroup&bsk_id=' + $('[name="bsk_id"]', basketForm).val() + '&bpgttl=' + $('[name="bpgttl"]', addGroupForm).val(),
            processData: false,
            type: 'POST',
            contentType: false,
            aSync: false,
            success: function(data) {

                //alert(data);

                $('[name="bpgttl"]', addGroupForm).val('');

                getBasketProducts();

            },
            error: function (x, e) {

                throwAjaxError(x, e);

            }
        });


    });

    $('#addGroupsDiv').on('click','.deleteGroupBtn', function(e){

        e.preventDefault();

        var bpgId = $(this).data("bpg_id");

        $.msgAlert ({
            type: 'warning'
            , title: 'Delete This Group and Products'
            , text: 'Are you sure you wish to permanently remove this group from the database?'
            , callback: function () {

                $.ajax({
                    url: 'custom/basket_script.php?action=deletegroup&bpg_id=' + bpgId,
                    type: 'POST',
                    async: false,
                    success: function( data ) {

                        var result = JSON.parse(data);

                        //$.msgGrowl ({
                        //    type: result.type
                        //    , title: result.title
                        //    , text: result.description
                        //});

                    },
                    error: function (x, e) {
                        throwAjaxError(x, e);
                    }
                });

            }
        });
        return false;

    });

    $('#addGroupsDiv').on('click','.addProductBtn', function(e){
        e.preventDefault();
        $('[name="bpg_id"]', modalProductSearchForm).val( $(this).data('bpg_id') );
        $('[name="prdnam"]', modalProductSearchForm).val();
        $('#productModal').modal('show');
    });


    modalProductSearchForm.submit(function(e){
        e.preventDefault();

        $.ajax({
            url: 'custom/basket_script.php?action=addproduct&bsk_id=' + $('[name="bsk_id"]', basketForm).val() + '&bpg_id=' + $('[name="bpg_id"]', modalProductSearchForm).val() + '&prd_id=' + $('[name="prd_id"]', modalProductSearchForm).val(),
            type: 'POST',
            async: false,
            success: function( data ) {

                getBasketProducts();
                $('#productModal').modal('hide');
                $('[name="prdnam"]', modalProductSearchForm).val();

            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });

    });


    //if ( $('#id', basketForm).val() != 0 ) {
    //    $('#addProductsDiv').show();
    //}

    getBasketProducts();

});

function getBasketProducts() {

    $.ajax({
        url: 'custom/basketproducts_table.php?bsk_id=' + $('[name="bsk_id"]', basketForm).val(),
        type: 'POST',
        async: false,
        success: function( data ) {


            $('#productListTable').html(data);

        },
        error: function (x, e) {
            throwAjaxError(x, e);
        }
    });

}

function calcBasketBasePrice() {

    var basketPrice = 0;
    $('#productListBody').find('.selectedPrice').each(function(){
        if ( $(this).parent().next().find('input:checked').length > 0 ) basketPrice += parseFloat($(this).html());
    })

    $('#basketBasePrice').html(basketPrice.toFixed(2));

    basketPrice = basketPrice + ((basketPrice/100) * parseFloat( $('[name="mrk_up"]').val() ) );

    $('[name="unipri"]', basketForm).val(basketPrice.toFixed(2));

}