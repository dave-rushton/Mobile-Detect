
var basketForm, productSearchForm, addGroupForm, modalProductSearchForm;
var articleForm = $('#articleForm');

$(function(){


    if($('[name="bsk_id"]').val() == "0"){
        $('#previewBtn').hide();
    }

    $('#productModal').on('shown.bs.modal', function () {
        $('#batchProducts').html('');
        $('[name="prdnam"]', modalProductSearchForm).focus();
    })

    $('#addBatchBtn').click(function(e){

        e.preventDefault();
        $('#batchModal').modal('show');


    })

    $('#productListBody').on('click','.alternateBtn', function(e){

        e.preventDefault();
        $('#productModal').modal('show');

    });

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
        data: 'action=selectlight&prd_id=0',
        type: "GET",
        async: true,
        success: function(data) {

            console.log(data);

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
                    //$('[name="prdimg"]', productSearchForm).val(map[item].prdimg);
                    //
                    //$('[name="prd_id"]', modalProductSearchForm).val(map[item].prd_id);
                    //$('[name="unipri"]', modalProductSearchForm).val(map[item].unipri);

                    var resultHTML = '<li>';
                        resultHTML += '<input type="hidden" name="prd_id[]" value="'+map[item].prd_id+'">';
                        resultHTML += '<input type="hidden" name="unipri[]" value="'+map[item].unipri+'">';
                        resultHTML += map[item].prdnam;
                        resultHTML += '<a href="#" class="removeBatchProduct pull-right"><i class="icon-remove"></i> </a> </li>';

                    $('#batchProducts').append(resultHTML);

                    setTimeout(function(){ $('[name="prdnam"]').val('').focus() }, 200);

                    return item;
                }
            });

            //$('[name="prdnam"]', $('#modalProductSearchForm')).bind('typeahead:selected', function(obj, datum, name) {
            //    alert(JSON.stringify(obj)); // object
            //    // outputs, e.g., {"type":"typeahead:selected","timeStamp":1371822938628,"jQuery19105037956037711017":true,"isTrigger":true,"namespace":"","namespace_re":null,"target":{"jQuery19105037956037711017":46},"delegateTarget":{"jQuery19105037956037711017":46},"currentTarget":
            //    alert(JSON.stringify(datum)); // contains datum value, tokens and custom fields
            //    // outputs, e.g., {"redirect_url":"http://localhost/test/topic/test_topic","image_url":"http://localhost/test/upload/images/t_FWnYhhqd.jpg","description":"A test description","value":"A test value","tokens":["A","test","value"]}
            //    // in this case I created custom fields called 'redirect_url', 'image_url', 'description'
            //
            //    alert(JSON.stringify(name)); // contains dataset name
            //    // outputs, e.g., "my_dataset"
            //
            //});

            $('#relatedProductBox').unblock();

        }
    });

    $('#batchProducts').on('click', '.removeBatchProduct', function(e){

        e.preventDefault();
        $(this).parent().fadeOut().remove();

    })

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


    $('#productListTable').on('click','.updateGroupBtn', function(e){

        e.preventDefault();
        var thisLink = $(this);
        var bpgttl = thisLink.parent().parent().find('[name="bpgttl"]').val();
        var bpgmin = thisLink.parent().parent().find('[name="bpgmin"]').val();
        var bpgmax = thisLink.parent().parent().find('[name="bpgmax"]').val();

        var bpgId = thisLink.data('bpg_id');

        $.ajax({
            url: 'custom/basket_script.php?action=updategroup&bpg_id=' + bpgId + '&bpgttl=' + bpgttl + '&bpgmin=' + bpgmin + '&bpgmax=' + bpgmax,
            type: 'POST',
            async: false,
            success: function( data ) {

                var result = JSON.parse(data);

                $.msgGrowl ({
                    type: result.type
                    , title: result.title
                    , text: result.description
                });

            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });

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


    $('#productListTable').on('click','.updateProductBtn', function(e){

        e.preventDefault();
        var thisLink = $(this);
        var extpri = thisLink.parent().parent().find('[name="extpri"]').val();
        var bprqty = thisLink.parent().parent().find('[name="bprqty"]').val();

        var bprId = thisLink.data('bpr_id');

        //alert( 'custom/basket_script.php?action=updateproduct&bpr_id=' + bprId + '&extpri=' + extpri + '&bprqty=' + bprqty );

        $.ajax({
            url: 'custom/basket_script.php?action=updateproduct&bpr_id=' + bprId + '&extpri=' + extpri + '&bprqty=' + bprqty,
            type: 'POST',
            async: false,
            success: function( data ) {

                var result = JSON.parse(data);

                $.msgGrowl ({
                    type: result.type
                    , title: result.title
                    , text: result.description
                });

            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });

    });



    $('#extrasListBody').on('click','.deleteExtraBtn', function(e){

        e.preventDefault();

        var thisLink = $(this);

        var bexId = thisLink.data('bex_id');

        $.msgAlert ({
            type: 'warning'
            , title: 'Delete This Extra From Basket'
            , text: 'Are you sure you wish to permanently remove this extra from this basket?'
            , callback: function () {

                $.ajax({
                    url: 'custom/basket_script.php?action=deleteextra&bex_id=' + bexId,
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

    });

    $('#addExtraBtn').click(function(e){

        e.preventDefault();

        var thisRow = $(this).parent().parent();

        $.ajax({
            url: 'custom/basket_script.php?action=addextra&bsk_id=' + $('[name="bsk_id"]', basketForm).val() + '&bexttl=' + thisRow.find('[name="bexttl"]').val() + '&bextxt=' + thisRow.find('[name="bextxt"]').val() + '&bexdef=' + thisRow.find('[name="bexdef"]').is(':checked') + '&bexman=' + thisRow.find('[name="bexman"]').is(':checked') + '&bexpri=' + thisRow.find('[name="bexpri"]').val(),
            type: 'POST',
            async: false,
            success: function( data ) {

                var result = JSON.parse(data);

                $.msgGrowl ({
                    type: result.type
                    , title: result.title
                    , text: result.description
                });

                getBasketExtras();

            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });

    });

    $('[name="mrk_up"]').on('keyup paste', function(){
        calcBasketBasePrice();
    });

    //$('[name="unipri"]').on('keyup paste', function(){
    //    calcMarkupPrice();
    //});

    $('#calcBasePriceBtn').click(function(e){
        calcBasketBasePrice();
        $('[name="unipri"]').val( $('#recommendPrice').html() );
    });

    $('#calcMarkupBtn').click(function(e){
        calcMarkupPrice();
        calcBasketBasePrice();
        $('[name="unipri"]').val( $('#recommendPrice').html() );
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

    if ( $('[name="bsk_id"]', basketForm).val() != 0 ) {
        $('#deleteBasketBtn').show();
    }

    if ( $('[name="bsk_id"]', basketForm).val() != '0' ) {
        plupLoad( $('#plupload') );
    } else {
        $('#addImageBtn').hide();
    }



    $('#deleteBasketBtn').click(function(e){

        e.preventDefault();

        $.msgAlert ({
            type: 'warning'
            , title: 'Delete This Label'
            , text: 'Are you sure you wish to permanently remove this label from the database?'
            , callback: function () {

                $.ajax({
                    url: 'custom/basket_script.php?action=delete&bsk_id=' + $('[name="bsk_id"]', basketForm).val(),
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

                            // Redirect
                            window.location = basketForm.data("returnurl");

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
        });

    });

    basketForm.submit(function(e){

        e.preventDefault();

        //
        // Check Name
        //

        $.ajax({
            url: 'custom/basket_script.php?action=checkname&bsk_id='+$('[name="bsk_id"]', basketForm).val()+ "" +'&bskttl='+$('[name="bskttl"]', basketForm).val()+'&seourl='+$('[name="seourl"]', basketForm).val(),
            processData: false,
            type: 'POST',
            contentType: false,
            aSync: false,
            success: function (data) {
              
                try {




                    var result = JSON.parse(data);
                    plupLoad( $('#plupload') );
                    if ( $('[name="bsk_id"]', basketForm).val() == 0 ) {

                        $.msgGrowl({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });

                        if (result.type == 'error') return false;

                    }

                    updateBasket();

                } catch(Ex) {

                    $.msgGrowl ({
                        type: 'error'
                        , title: 'Error'
                        , text: Ex
                    });

                    if (result.type == 'error') return false;

                }

            }
        });

    });

    function updateBasket() {
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

                    $('[name="bskimg"]', basketForm).val(data);
                    var basketval = 0;
                    if($('[name="bypass_min_order"]', basketForm).prop('checked')){
                        basketval = 1;
                    }
                    basketObj = {
                        customtext : $('[name="customtext"]', basketForm).val(),
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
                        weight : $('[name="weight"]', basketForm).val(),
                        vatrat : $('[name="vatrat"]', basketForm).val(),
                        riblbl : $('[name="riblbl"]', basketForm).val(),
                        ribcol : $('[name="ribcol"]', basketForm).val(),
                        minord : $('[name="minord"]', basketForm).val(),
                        bypass_min_order :basketval,
                        //products : productArray,
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

                                $('#deleteBasketBtn').show();

                                $('#addGroupsDiv').show();



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

            atr_id_string = "";
            count = 0;
            $('.art_id').each(function(){
                if($(this).prop("checked") && count == 0){
                    atr_id_string += $(this).val();
                    count ++;
                }
                else if($(this).prop("checked") && count > 0){
                    atr_id_string += ","+$(this).val();
                    count ++;
                }
            })
            var basketval = 0;
            if($('[name="bypass_min_order"]', basketForm).prop('checked')){
                basketval = 1;
            }

            basketObj = {
                customtext : $('[name="customtext"]', basketForm).val(),
                bsk_id : $('[name="bsk_id"]', basketForm).val(),
                bskttl : $('[name="bskttl"]', basketForm).val(),
                bskdsc : $('[name="bskdsc"]', basketForm).val(),
                unipri : $('[name="unipri"]', basketForm).val(),
                mrk_up : $('[name="mrk_up"]', basketForm).val(),
                bskimg : $('[name="bskimg"]', basketForm).val(),
                custom : ($('[name="custom"]', basketForm).prop('checked')) ? 1 : 0,
                sta_id : 0,
                srtord : 0,
                atr_id : atr_id_string,
                bsktxt : postData,
                seourl : $('[name="seourl"]', basketForm).val(),
                keywrd : $('[name="keywrd"]', basketForm).val(),
                keydsc : $('[name="keydsc"]', basketForm).val(),
                //atr_id : $('[name="atr_id"]', basketForm).val(),
                bsktag : $('[name="bsktag"]', basketForm).val(),
                weight : $('[name="weight"]', basketForm).val(),
                vatrat : $('[name="vatrat"]', basketForm).val(),
                riblbl : $('[name="riblbl"]', basketForm).val(),
                ribcol : $('[name="ribcol"]', basketForm).val(),
                minord : $('[name="minord"]', basketForm).val(),
                bypass_min_order : basketval,
                //products : productArray,
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

                        $('#deleteBasketBtn').show();

                        $('#addGroupsDiv').show();



                        //UPDATE PREVIEW
                        id = $('[name="bsk_id"]').val();
                        seourl = $('[name="seourl"]').val();
                        $('#previewBtn').show();
                        root = $('#previewBtn').data('root');
                        $('#previewBtn').attr('href',root+"baskets/basket/"+id+"/"+seourl);

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
    }

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


    //$('.sortproducts').each(function(){
    //
    //    alert($(this).html());
    //
    //    $(this).sortable({
    //        handle: '.sortproductsbtn' ,
    //        stop: function( event, ui ) {
    //
    //        }
    //    });
    //
    //});


    $('#productListTable').sortable({
        handle: '.sortProductBtn' ,
        stop: function( event, ui ) {

            var sortList = '';

            $('.sortProductBtn').each(function(){
                sortList += (sortList.length == 0) ? $(this).data('bpg_id') : ',' + $(this).data('bpg_id');
            });

            $.ajax({
                url: 'custom/basket_script.php?action=resortgroup&bpg_id=' + sortList,
                type: 'POST',
                async: false,
                success: function( data ) {

                },
                error: function (x, e) {
                    throwAjaxError(x, e);
                }
            });

        }
    });

    $('#productListTable').on('change','.multipleOption', function(){

        var bpgId = $(this).val();
        var bpgmul = ($(this).is(':checked')) ? 1 : 0;


        $.ajax({
            url: 'custom/basket_script.php?action=updategroupmulti&bpg_id=' + bpgId + '&mulsel=' + bpgmul,
            type: 'POST',
            async: false,
            success: function( data ) {

            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });

    })

    $('#productListTable').on('change','.mandatoryProduct', function(){

        $(this).closest('table').find('[name="bpgmin"]').val( $(this).closest('table').find('.mandatoryProduct:checked').length );
        if ( $(this).closest('table').find('[name="bpgmin"]').val() > $(this).closest('table').find('[name="bpgmax"]').val() ) {
            $(this).closest('table').find('[name="bpgmax"]').val( $(this).closest('table').find('[name="bpgmin"]').val() );
            $(this).closest('table').find('.updateGroupBtn').click();
        }


        if ( $(this).closest('table').find('.mandatoryProduct:checked').length > 1 ) {
            $(this).closest('table').find('.multipleOption').prop('checked', true).change();
        } else {
            //$(this).closest('table').find('.multipleOption').prop('checked', false);
        }

        var bprId = $(this).val();
        var bprman = ($(this).is(':checked')) ? 1 : 0;

        $(this).parent().next().find('.defaultProduct').prop('checked', bprman);

        $.ajax({
            url: 'custom/basket_script.php?action=updateproductmandatory&bpr_id=' + bprId + '&bprman=' + bprman,
            type: 'POST',
            async: false,
            success: function( data ) {
            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });

        if (bprman == 1) {
            $.ajax({
                url: 'custom/basket_script.php?action=updateproductdefault&bpr_id=' + bprId + '&defsel=' + bprman,
                type: 'POST',
                async: false,
                success: function (data) {
                },
                error: function (x, e) {
                    throwAjaxError(x, e);
                }
            });
        }

    })

    $('#productListTable').on('change','.defaultProduct', function(){

        var bprId = $(this).val();
        var defsel = ($(this).is(':checked')) ? 1 : 0;

        calcBasketBasePrice();

        $.ajax({
            url: 'custom/basket_script.php?action=updateproductdefault&bpr_id=' + bprId + '&defsel=' + defsel,
            type: 'POST',
            async: false,
            success: function( data ) {
            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });

    });

    $('#productListTable').on('keyup paste','[name="bprqty"]', function(){

        calcBasketBasePrice();

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

                        $.msgGrowl ({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });

                        getBasketProducts();
                        calcBasketBasePrice();


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
        $('[name="prdnam"]', modalProductSearchForm).val('').focus();
        $('#productModal').modal('show');
    });


    modalProductSearchForm.submit(function(e){

        //return false;

        //alert('custom/basket_script.php?action=addproduct&bsk_id=' + $('[name="bsk_id"]', basketForm).val() + '&bpg_id=' + $('[name="bpg_id"]', modalProductSearchForm).val() + '&prd_id=' + $('[name="prd_id"]', modalProductSearchForm).val());

        e.preventDefault();

        $.ajax({
            url: 'custom/basket_script.php?action=addproduct&bsk_id=' + $('[name="bsk_id"]', basketForm).val() + '&' + modalProductSearchForm.serialize(),
            type: 'POST',
            async: false,
            success: function( data ) {

                var result = JSON.parse(data);

                $.msgGrowl ({
                    type: result.type
                    , title: result.title
                    , text: result.description
                });

                getBasketProducts();
                $('#productModal').modal('hide');
                $('[name="prdnam"]', modalProductSearchForm).val();

            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });

    });

    $('#addBatchProduct').click(function(e){

        e.preventDefault();

        var resultHTML = '';
            resultHTML = '<li>Product Name<a href="#" class="removeRelated pull-right" data-rel_id="Rel_ID"><i class="icon-remove"></i> </a> </li>';

        $('#batchProducts').append(resultHTML);

    })


    if ( $('#id', basketForm).val() > 0 ) {
        $('#addGroupsDiv').show();
    }



    $('.selectGroup').click(function(e){
        e.preventDefault();

        $.ajax({
            url: 'custom/basket_script.php?action=copygroup&bsk_id=' + $('[name="bsk_id"]', basketForm).val() + '&bpg_id=' + $(this).data('bpg_id'),
            type: 'POST',
            async: false,
            success: function( data ) {

                var result = JSON.parse(data);

                $.msgGrowl ({
                    type: result.type
                    , title: result.title
                    , text: result.description
                });

                getBasketProducts();
                getBasketExtras();
                calcBasketBasePrice();

                $('#batchModal').modal('hide');

            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });

    });

    getBasketProducts();
    getBasketExtras();
    calcBasketBasePrice();




    //GALLERY CODE
    $('.gallery-dynamic').on('click', '.editUpload', function (e) {
        e.preventDefault();

        var uplId = $(this).data('upl_id');

        $.ajax({
            url: 'gallery/uploads_script.php',
            data: 'action=select&upl_id=' + uplId,
            type: 'GET',
            async: false,
            success: function( data ) {

                var imgArray = JSON.parse(data);

                $('[name="upl_id"]', $('#imageForm')).val( imgArray[0].upl_id );
                $('[name="uplttl"]', $('#imageForm')).val( imgArray[0].uplttl );
                $('[name="upldsc"]', $('#imageForm')).val( imgArray[0].upldsc );
                $('[name="urllnk"]', $('#imageForm')).val( imgArray[0].urllnk );

            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });

        $('#imageModal').modal('show');
    });

    $('.gallery-dynamic').on('click', '.moveUpload', function (e) {
        e.preventDefault();
    });

    $('#imageForm').submit(function(e){
        e.preventDefault();

        $.ajax({
            url: 'gallery/uploads_script.php',
            data: 'action=update&' + $('#imageForm').serialize(),
            type: 'GET',
            async: false,
            success: function( data ) {

                var result = JSON.parse(data);

                $.msgGrowl ({
                    type: result.type
                    , title: result.title
                    , text: result.description
                });

                if ( result.type != 'Error' ) {
                    $('#imageModal').modal('hide');
                }

            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });

    });


    $('.gallery-dynamic').on('click', '.deleteUpload', function (e) {
        e.preventDefault();

        var upl_id = $(this).data('upl_id');

        var masterimage = ($(this).hasClass('masterimage')) ? '&masterimage=true' : '';

        $.msgAlert ({
            type: 'warning'
            , title: 'Delete This Image'
            , text: 'Are you sure you wish to permanently remove this image from the database?'
            , callback: function () {

                $.ajax({
                    url: 'gallery/uploads_script.php',
                    data: 'action=delete&ajax=true&upl_id=' + upl_id + masterimage,
                    type: 'POST',
                    async: false,
                    success: function( data ) {

                        var result = JSON.parse(data);

                        $.msgGrowl ({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });

                        getGallery();

                    },
                    error: function (x, e) {
                        throwAjaxError(x, e);
                    }
                });

            }
        });
        return false;
    });

    getGallery();


    $('#addImageBtn').click(function(e){
        e.preventDefault();
        $('#galleryImagesDiv').hide();
        $('#uploadImagesDiv').show();
    });

    $('#cancelGalleryImagesBtn').click(function(e){
        e.preventDefault();
        $('#galleryImagesDiv').show();
        $('#uploadImagesDiv').hide();
    });


    $('#updateGalleryImagesBtn').click(function(e){

        e.preventDefault();

        //
        // UPDATE GALLERY
        //

        var imageFiles = '';

        $('.imageselect.active').each(function(){

            imageFiles += (imageFiles == '') ? $(this).data('upl_id') : ',' + $(this).data('upl_id');

        });

        $.ajax({
            url: 'gallery/galleryimagecontrol.php',
            //data: 'action=update&ajax=true&' + galleryForm.serialize(),

            data: 'tblnam=BASKET&tbl_id='+$('[name="bsk_id"]', basketForm).val() + '&filnam=' + imageFiles,

            type: 'POST',
            async: false,
            success: function( data ) {

                try {

                    getGallery();

                }
                catch (ex) {
                    $.msgGrowl ({
                        type: 'error'
                        , title: 'Error'
                        , text: ex
                    });
                }
            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });


        $('#galleryImagesDiv').show();
        $('#uploadImagesDiv').hide();

    });



    $('#imagelisting').on('click','.selectUpload',function(e){
        e.preventDefault();
        $(this).parent().parent().toggleClass('active').prev('.imageselect').toggleClass('active');
    });


    // if($(".gallery-dynamic").length > 0){
    //     $(".gallery-dynamic").imagesLoaded(function(){
    //         $(".gallery-dynamic").masonry({
    //             itemSelector: 'li',
    //             columnWidth: 201,
    //             isAnimated: true
    //         });
    //     });
    // }

    $('#galleryImages').sortable({
        handle: ".moveUpload",
        stop: function(){

            var images = $('#galleryImages').find('.moveUpload');

            var SrtOrd = '';

            images.each(function(){
                SrtOrd += (SrtOrd == '') ? $(this).data('upl_id') : ','+$(this).data('upl_id');
            });

            //alert('uploader/gallery.resort.php?srtord=' + SrtOrd);

            $.ajax({
                url: 'gallery/uploads_script.php',
                data: 'action=resort&srtord=' + SrtOrd,
                type: 'GET',
                async: false,
                success: function( data ) {

//					alert(data);

                    $.msgGrowl ({
                        type: 'success'
                        , title: 'Gallery Re-Ordered'
                        , text: 'Gallery Re-Ordered'
                    });

                },
                error: function (x, e) {
                    throwAjaxError(x, e);
                }
            });

        }
    });
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

function getBasketExtras() {

    $.ajax({
        url: 'custom/basketextras_table.php?bsk_id=' + $('[name="bsk_id"]', basketForm).val(),
        type: 'POST',
        async: false,
        success: function( data ) {

            $('#extrasListBody').html(data);

        },
        error: function (x, e) {
            throwAjaxError(x, e);
        }
    });

}


function calcBasketBasePrice() {

    var basketPrice = 0;
    $('#productListTable').find('.selectedPrice').each(function(){
        if ( $(this).parent().next().next().find('input:checked').length > 0 ) {

            var linePrice = parseFloat($(this).html()) * $(this).parent().next().next().next().next().next().find('input').val();

            basketPrice += linePrice; //parseFloat($(this).html());
        }
    })

    $('#basketBasePrice').html(basketPrice.toFixed(2));

    basketPrice = basketPrice + ((basketPrice/100) * parseFloat( $('[name="mrk_up"]').val() ) );

    basketPrice = Math.round(basketPrice + 0.01) - 0.01;

    //basketPrice = Math.round(basketPrice*2)/2 - 0.01

    $('#recommendPrice').html(basketPrice.toFixed(2));

}


function calcMarkupPrice() {

    var basketPrice = 0;
    $('#productListTable').find('.selectedPrice').each(function(){
        if ( $(this).parent().next().next().find('input:checked').length > 0 ) basketPrice += parseFloat($(this).html());
    })

    $('#basketBasePrice').html(basketPrice.toFixed(2));

    setBasketPrice = $('[name="unipri"]').val();

    var markUp = (setBasketPrice-basketPrice)/basketPrice * 100;

    $('[name="mrk_up"]').val( Math.round(markUp) );

}
function getGallery() {

    $.ajax({
        url: 'gallery/uploads.gallery.global.php',
        data: 'tblnam=GLOBAL&tbl_id=0',
        type: 'GET',
        async: false,
        success: function( data ) {

            $('#imagelisting').html(data);

            $.msgGrowl ({
                type: 'success'
                , title: 'Gallery Retrieved'
                , text: 'Gallery Retrieved'
            });

        },
        error: function (x, e) {
            throwAjaxError(x, e);
        }
    });

    $('imageselect').removeClass('active');

    $.ajax({
        url: '/admin/gallery/uploads.gallery.php',
        //todo
        data: 'tblnam=BASKET&tbl_id='+$('[name="bsk_id"]', basketForm).val(),
        type: 'GET',
        async: false,
        success: function( data ) {

            $('#galleryImages').html(data);

            //
            // preselect global gallery images based on current gallery
            //

            //var currentimages = $('#galleryImages').find('img');
            //
            //for (i=0;i<currentimages.length;i++) {
            //
            //    $("[data-imgfil='" + $(currentimages[i]).attr('src') +"']").addClass('active');
            //
            //}

            $.msgGrowl ({
                type: 'success'
                , title: 'Gallery Retrieved'
                , text: 'Gallery Retrieved'
            });

        },
        error: function (x, e) {
            throwAjaxError(x, e);
        }
    });

    // GLOBAL IMAGES

    // $(".colorbox-image").colorbox({
    //     maxWidth: "90%",
    //     maxHeight: "90%",
    //     rel: $(this).attr("rel")
    // });

    //$.ajax({
    //	url: 'gallery/uploads.gallery.php',
    //	data: 'tblnam=ARTICLE&tbl_id='+$('[name="art_id"]', articleForm).val(),
    //	type: 'GET',
    //	async: false,
    //	success: function( data ) {
    //
    //		$('#galleryImages').html(data);
    //
    //		$.msgGrowl ({
    //			type: 'success'
    //			, title: 'Gallery Retrieved'
    //			, text: 'Gallery Retrieved'
    //		});
    //
    //	},
    //	error: function (x, e) {
    //		throwAjaxError(x, e);
    //	}
    //});
    //
    //$(".colorbox-image").colorbox({
    //	maxWidth: "90%",
    //	maxHeight: "90%",
    //	rel: $(this).attr("rel")
    //});

}

function plupLoad(iElement) {

    var $el = iElement;
    $el.pluploadQueue({
        runtimes : 'html5,gears,flash,silverlight,browserplus',
        url : '../js/plugins/plupload/upload.php?resize='+ $el.data('resize') +'&tblnam=GLOBAL&tbl_id=0',
        max_file_size : '10mb',
        chunk_size : '6mb',
        unique_names : true,
        multiple_queues : true,
        //resize : {width : 320, height : 240, quality : 90},
        filters : [
            {title : "Image files", extensions : "jpg,gif,png"},
            {title : "Zip files", extensions : "zip"}
        ],
        flash_swf_url : 'js/plupload/plupload.flash.swf',
        silverlight_xap_url : 'js/plupload/plupload.silverlight.xap'
    });
    $(".plupload_header").remove();
    var upload = $el.pluploadQueue();
    if($el.hasClass("pl-sidebar")){
        $(".plupload_filelist_header,.plupload_progress_bar,.plupload_start").remove();
        $(".plupload_droptext").html("<span>Drop files to upload</span>");
        $(".plupload_progress").remove();
        $(".plupload_add").text("Or click here...");
        upload.bind('FilesAdded', function(up, files) {
            setTimeout(function () {
                up.start();
            }, 500);
        });
        upload.bind("QueueChanged", function(up){
            $(".plupload_droptext").html("<span>Drop files to upload</span>");
        });
        upload.bind("StateChanged", function(up){
            $(".plupload_upload_status").remove();
            $(".plupload_buttons").show();
        });

    } else {
        $(".plupload_progress_container").addClass("progress").addClass('progress-striped');
        $(".plupload_progress_bar").addClass("bar");
        $(".plupload_button").each(function(){
            if($(this).hasClass("plupload_add")){
                $(this).attr("class", 'btn pl_add btn-primary').html("<i class='icon-plus-sign'></i> "+$(this).html());
            } else {
                $(this).attr("class", 'btn pl_start btn-success').html("<i class='icon-cloud-upload'></i> "+$(this).html());
            }
        });
    }

    upload.bind("UploadComplete", function(up) {
        if (up.files.length == (up.total.uploaded + up.total.failed)) {
            getGallery();
        }
    });

}
