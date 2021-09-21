
var basketForm, productSearchForm, addGroupForm, modalProductSearchForm;

$(function(){


    $('#productModal').on('shown.bs.modal', function () {
        $('#batchProducts').html('');
        $('[name="prdnam"]', modalProductSearchForm).focus();
    })

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


    $('#addProductToGroupBtn').click(function(e){
        e.preventDefault();
        addGroup();
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


    if ( $('[name="bpg_id"]', basketForm).val() != 0 ) {
        $('#deleteBasketBtn').show();
    }

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

                        window.location = 'custom/basketgroups.php';

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
        getBasketProducts();
    }

});

function getBasketProducts() {

    $.ajax({
        url: 'custom/batchproducts_table.php?bpg_id=' + $('[name="bpg_id"]', basketForm).val(),
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