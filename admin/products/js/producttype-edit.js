

var relatedForm;
var productEntryForm;
var productForm;
var attlEntryForm;
var attrLabelForm;

$(function(){

    function change_machine_type_tab(id){
        $('.custom-tab').hide();
        $('#tab-'+id).show();
    }

    tab_val = $("[name=machine_type] :selected").data('screen');
    change_machine_type_tab(tab_val)

    $("[name=machine_type]").change(function(){
        // tab_val =$(this).val();
        tab_val = $("[name=machine_type] :selected").data('screen');
        change_machine_type_tab(tab_val)
    })

    $('.tab').click(function () {
        $('.tab').removeClass('active');
        $(this).addClass('active');
        $("[name=machine_type]").val($(this).data('select'))
        // tab_val = $("[name=machine_type]").val();
        tab_val = $("[name=machine_type] :selected").data('screen');
        change_machine_type_tab(tab_val)
    });

    relatedForm = $('#relatedForm');

    tinymceConfigs = [
        {
            mode: "none",
            theme: "simple"
        },
        {
            relative_urls: false,
            remove_script_host: false,
            document_base_url: $('#webRoot').val(), //"http://demo.patchworkscms.com/",
            convert_urls: false,
            //mode: "none",
            plugins: "hr,link,image,autolink,lists,layer,table,preview,media,searchreplace,contextmenu,directionality,fullscreen,noneditable,visualchars,nonbreaking,template,advlist,code,paste",

            image_advtab: true,

            toolbar1: "bold italic underline | alignleft aligncenter alignright ",
            toolbar2: "link unlink | bullist code fullscreen pasteword",

            templates:[
                {title: 'Table', description: 'Product Table', url: '../admin/tinymcetemplates/table.html'},
            ],
            paste_as_text: true,
            paste_word_valid_elements: "b,strong,i,em,h1,h2",
            inline_styles : false,
            paste_retain_style_properties: "",

            removeformat : [
                {selector : 'b,strong,em,i,font,u,strike', remove : 'all', split : true, expand : false, block_expand : true, deep : true},
                {selector : 'span', attributes : ['style', 'class'], remove : 'empty', split : true, expand : false, deep : true},
                {selector : '*', attributes : ['style', 'class'], split : false, expand : false, deep : true}
            ],

            cleanup_on_startup : true,
            fix_list_elements : false,
            fix_nesting : false,
            fix_table_elements : false,
            paste_use_dialog : true,
            paste_auto_cleanup_on_paste : true,

            file_browser_callback : elFinderBrowser,

            menubar: "edit insert view format table tools",

            image_dimensions: false,

            // Example content CSS (should be your site CSS)
            content_css: $('#webRoot').val() + "pages/css/style.css",

            //theme_advanced_blockformats: "p,h1,h2,h3,h4,blockquote"

        }];

    tinyMCE.settings = tinymceConfigs[1];
    tinyMCE.execCommand('mceAddEditor', false, 'overview');
    tinyMCE.execCommand('mceAddEditor', false, 'maindsc');
    tinyMCE.execCommand('mceAddEditor', false, 'technical_features');
    tinyMCE.execCommand('mceAddEditor', false, 'optional_features');
    tinyMCE.execCommand('mceAddEditor', false, 'quote');
    tinyMCE.execCommand('mceAddEditor', false, 'detailed');
    tinyMCE.execCommand('mceAddEditor', false, 'prtdsc');
    tinyMCE.execCommand('mceAddEditor', false, 'prtspc');

    $('.changelanguagelink').click(function(e){
        var tabID = $(this).attr('href');

        $(tabID).find('.tinymce').each(function(){

            var mceID = $(this).attr('id');
            tinyMCE.execCommand('mceAddEditor', false, mceID);

        });

    });
    $('.changelanguagelink')[0].click();

    productEntryForm = $('#productEntryForm');
    productForm = $('#productForm');
    attlEntryForm = $('#attlEntryForm');
    attrLabelForm = $('#attrLabelForm');

    //$('[name="atr_id"]', productEntryForm).chosen();
    //resize_chosen();

    $('[name="atr_id"]', productEntryForm).change(function(){

        $('[name="atr_id"]').val( $(this).val() );

    });


    $('[name="atr_id"]', productEntryForm).change(function(){

        $.ajax({
            url: 'attributes/ajax/attribute_form.php',
            data: 'atr_id=' + $(this).val() + '&atvtblnam=PRODUCT&atvtbl_id=' + $('[name="prd_id"]', productForm).val(),
            type: 'GET',
            async: true,
            success: function( data ) {

                //alert(data);

                $('#attlEntryForm').html(data);

            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });

    });

    $('[name="atr_id"]', productEntryForm).change();

    productEntryForm.submit(function(e){

        e.preventDefault();

        //alert(productEntryForm.serialize());

        $('[name="prttag"]', productEntryForm).val( $('[name="prttagselect"]', productEntryForm).val() );
        $('[name="seourl"]', productEntryForm ).val( seoURL( $('[name="seourl"]', productEntryForm).val() ) );

        if (productEntryForm.valid()) {

            //console.log(productEntryForm.serialize());

            var fields = $(".customfield", productEntryForm).serializeArray();
            fields.push( $(".customfield", $('#languageForm')).serializeArray() );

            //fields = $(".customfield", $('#languageForm')).serializeArray();

            var elementVariables = JSON.stringify(fields);
            var postData = encodeURIComponent(elementVariables);

            let categories = ""
            $('.filters_type').each(function(){
                if($(this).prop('checked') == true){
                    categories += $(this).val() + ",";
                }
            });


            $.ajax({
                url: productEntryForm.attr("action"),
                data: 'action=update&ajax=true&' + productEntryForm.serialize() + '&prtobj=' + postData+ '&filters=,' + categories,
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

                        $('#id', productEntryForm ).val( result.id );
                        $('[name="tbl_id"]', productForm ).val( result.id );
                        $('[name="prt_id"]', productForm ).val( result.id );

                        var strID = '';

                        $('[name="str_id[]"]:checked').each(function(){

                            strID += (strID == '') ? $(this).val() : ',' + $(this).val();

                        });

                        //alert(data);

                        $.ajax({
                            url: 'system/related_script.php',
                            data: 'action=relate&ajax=true&tblnam=PRODUCT&tbl_id=' + $('[name="prt_id"]', productEntryForm).val() + '&refnam=STRUCTURE&ref_id=' + strID,
                            type: 'POST',
                            async: false,
                            success: function( data ) {

                            }
                        });

                        getProductsTable();

                        $('#createVariantBtn').show();
                        $('#deleteProductType').show();
                        plupLoad( $('#plupload') );
                        fileplupLoad( $('#fileplupload') );

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
    $('#imagelisting').on('click','.selectUpload',function(e){
        e.preventDefault();
        $(this).parent().parent().toggleClass('active').prev('.imageselect').toggleClass('active');
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

    eventForm = $('#productEntryForm');
    $('#addImageBtn').click(function(e){
        e.preventDefault();
        $('#productimagepicker').show();
        $('#addImageBtn').hide();
        $('#updateGalleryImagesBtn').show();
        $('#cancelGalleryImagesBtn').show();

    });
    $('#cancelGalleryImagesBtn').click(function(e){
        e.preventDefault();
        $('#productimagepicker').hide();
        $('#addImageBtn').show();
        $('#updateGalleryImagesBtn').hide();
        $('#cancelGalleryImagesBtn').hide();
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
            data: 'tblnam=PRDTYPE' + '&tbl_id='+$('[name="prt_id"]', eventForm).val() + '&filnam=' + imageFiles,
            // data: 'tblnam=' + $('[name="tblnam"]').val() + '&tbl_id='+$('[name="prt_id"]').val() + '&filnam=' + imageFiles,
            type: 'POST',
            async: false,
            success: function( data ) {
                // alert(data)
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
        $('#addImageBtn').show();
        $('#updateGalleryImagesBtn').hide();
        $('#cancelGalleryImagesBtn').hide();
        $('#productimagepicker').hide();
    });




    $('#action').on('change',function(){
        $('.gal-options').hide();
        var val = $(this).val();
        $("#"+val+'-select').show();
    });

    gallerySearchForm = $('#gallerySearchForm');
    gallerySearchForm.submit(function(e){
        e.preventDefault();
        var val = $('#action').val();
        var Tbl_ID = $('[name="gal_id1"]', $("#"+val+'-select')).val();

        var KeyWrd = $('#keywrd1').val();

        var Tblnam;

        if (val == 'gallery') {
            Tblnam = (Tbl_ID == 0) ? 'GLOBAL' : 'WEBGALLERY';
        } else {
            Tblnam = val.toUpperCase();
        }

        // alert('tblnam='+Tblnam+'&tbl_id='+Tbl_ID+'&keywrd=' + $('[name="keywrd"]', gallerySearchForm).val())
        $.ajax({
            url: 'gallery/uploads.gallery.global.php',
            data: 'tblnam='+Tblnam+'&tbl_id='+Tbl_ID+'&keywrd='+ $('#keywrd1').val(),
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
    })
    $('#deleteProductType').click(function (e) {
        e.preventDefault();

        $.msgAlert ({
            type: 'warning'
            , title: 'Delete This Product Type'
            , text: 'Are you sure you wish to permanently remove this product type from the database?'
            , callback: function () {

                $.ajax({
                    url: productEntryForm.attr("action"),
                    data: 'action=delete&ajax=true&' + productEntryForm.serialize(),
                    type: 'POST',
                    async: false,
                    success: function( data ) {

                        var result = JSON.parse(data);

                        $.msgGrowl ({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });

                        if (result.type == 'success') window.location = productEntryForm.data("returnurl");

                    },
                    error: function (x, e) {
                        throwAjaxError(x, e);
                    }
                });

            }
        });
        return false;
    });


    $('.updateProduct').click(function(e){
        e.preventDefault();

        //CKEDITOR.instances['prtdsc'].updateElement();

        tinyMCE.triggerSave('overview');
        tinyMCE.triggerSave('maindsc');
        tinyMCE.triggerSave('detailed');
        tinyMCE.triggerSave('technical_features');
        tinyMCE.triggerSave('optional_features');
        tinyMCE.triggerSave('quote');
        tinyMCE.triggerSave('prtdsc');
        tinyMCE.triggerSave('prtspc');

        productEntryForm.submit();
    });

    $('#createVariantBtn').click(function(e){
        e.preventDefault();
        clearProductForm();
        $('#variantBox').show();
        $('#variantTableBox').hide();
    });


    attlEntryForm.submit(function(e){
        e.preventDefault();

        $(':radio:checked', attlEntryForm).each(function(){
            $(this).parent().parent().find(':hidden').val( $(this).val() );
        });

        $(':checkbox', attlEntryForm).each(function(){
            $(this).parent().next(':hidden').val( ($(this).is(':checked')) ? 1 : 0 );
        });

        if ($(this).valid()) {

            $.ajax({
                url: attlEntryForm.attr("action"),
                data: 'action=update&ajax=true&' + attlEntryForm.serialize(),
                type: 'POST',
                async: false,
                success: function( data ) {

                    var result = JSON.parse(data);

                    $.msgGrowl ({
                        type: result.type
                        , title: result.title
                        , text: result.description
                    });

                    getProductsTable();

                    //$('#id', $('#attrLabelForm') ).val( result.id );

                },
                error: function (x, e) {
                    throwAjaxError(x, e);
                }
            });

            $('#variantBox').hide();
            $('#variantTableBox').show();

        }
        else {
            $.msgGrowl ({
                type: 'error'
                , title: 'Invalid Form'
                , text: 'There is an error in the form'
            });
        }

    });

    productForm.submit(function(e){
        e.preventDefault();

        if (productForm.valid()) {

            $('[name="atr_id"]', productForm).val( $('[name="atr_id"]', productEntryForm).val() );

            //
            // Custom Fields
            //

            var fields = $(".customfield", productForm).serializeArray();
            var elementVariables = JSON.stringify(fields);
            var postData = encodeURIComponent(elementVariables);

            $.ajax({
                url: productForm.attr("action"),
                data: 'action=update&ajax=true&' + productForm.serialize() + '&prdobj=' + postData,
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

                        $('#id', productForm ).val( result.id );

                        $('#AtvTbl_ID', attlEntryForm ).val( result.id );

                        //alert( $('[name="atr_id"]', productForm).val() );

                        if ( $('[name="atr_id"]', productForm).val() != 0 ) {

                            //alert('#');

                            attlEntryForm.submit();

                            //alert('######');

                        }

                        plupLoad( $('#pluploadproducts'), 'PRODUCT', result.id );
                        fileplupLoad( $('#pluploadproductpdfs'), 'PRODUCTFILE', result.id );

                        getProductsTable();

                        $('#variantBox').hide();
                        $('#variantTableBox').show();

                    } catch (Ex) {
                        // $.msgGrowl ({
                        //     type: 'error'
                        //     , title: 'Update Failure'
                        //     , text: 'The server replied with an error'
                        // });

                    }

                },
                error: function (x, e) {
                    // throwAjaxError(x, e);
                }
            });

            //attlEntryForm.submit();

        } else {
            $.msgGrowl ({
                type: 'error'
                , title: 'Invalid Form'
                , text: 'There is an error in the form'
            });
        }

    });



    $('#updateSalePriceBtn').click(function(e){

        e.preventDefault();

        $.msgAlert ({
            type: 'warning'
            , title: 'Update Variant Prices'
            , text: 'Are you sure you wish to update the sale price of all variants?'
            , callback: function () {

                $('#updateProduct').click();

                $.ajax({
                    url: 'products/product_types_script.php',
                    data: 'action=reprice&ajax=true&prt_id=' + $('[name="prt_id"]', productEntryForm).val() + '&unipri=' + $('[name="unipri"]', productEntryForm).val(),
                    type: 'POST',
                    async: false,
                    success: function( data ) {

                        try {

                            getProductsTable();

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

    });




    attrLabelForm.submit(function(e){

        //alert('[][][][][');

        e.preventDefault();

        var AtlLst = '';
        $('.AtlLstVal').each(function(){
            AtlLst += ( AtlLst == '' ) ? $(this).val() : ',' + $(this).val();
        });

        $('[name="atllst"]',attrLabelForm ).val( AtlLst );

        //alert('@');

        if (attrLabelForm.valid()) {

            //alert('~');

            $.ajax({
                url: attrLabelForm.attr("action"),
                data: 'action=update&ajax=true&' + attrLabelForm.serialize(),
                type: 'POST',
                async: false,
                success: function( data ) {

                    //alert(data);

                    var result = JSON.parse(data);

                    $.msgGrowl ({
                        type: result.type
                        , title: result.title
                        , text: result.description
                    });

                    if (result.type == 'success') {
                        $('#deleteAttrLabelBtn').removeClass('hide');
                        $('#id', attrLabelForm ).val( result.id );
                    }

                    getAttrLabels();

                    $('#attrLabelTableBox').show();
                    $('#attrLabelBox').hide();

                },
                error: function (x, e) {
                    //throwAjaxError(x, e);
                }
            });


        }

    });

    $('#updateAttrLabelBtn').click(function(e){
        e.preventDefault();
        attrLabelForm.submit();
    });

    //
    // attribute type functionality
    //

    $('[name="atltyp"]', attrLabelForm).change(function(){

        if ( $(this).val() == 'select' || $(this).val() == 'radio' ) {
            $('#AtlLstDiv').removeClass('hide');
        } else {
            $('#AtlLstDiv').addClass('hide');
        }

    });

    $('#addAltLst').click(function(e){

        e.preventDefault();

        if ( $('#AddAltLst').val() != '' ) {

            var resultHTML  = '<li style="list-style: none; padding: 0; margin: 0 0 3px 0;">';
            resultHTML += '<div class="input-append">';
            resultHTML += '<input type="text" class="input-medium AtlLstVal" value="'+$('#AddAltLst').val()+'" />';
            resultHTML += '<div class="btn-group">';
            resultHTML += '<button class="btn" type="button" rel="tooltip" title="Remove From List"><i class="icon icon-remove removeAltLst"></i></button>';
            resultHTML += '<a href="#" class="btn" type="button" rel="tooltip" title="Drag To Reorder List"><i class="icon icon-reorder moveLI"></i></a>';
            resultHTML += '</div>';
            resultHTML += '</div>';
            resultHTML += '</li>';

            $('#AtrLst_UL').append( resultHTML );

            $('#AddAltLst').val('');

        }

    });

    $('#AtrLst_UL').on('click', '.removeAltLst', function (e) {
        e.preventDefault();
        $(this).closest('li').remove();
    });

    $('#attrLabelBody').on('click', '.editAttrLabel', function (e) {
        e.preventDefault();

        $.ajax({
            url: 'attributes/attrlabel_script.php',
            data: 'action=select&atl_id=' + $(this).data("atl_id"),
            type: 'GET',
            async: true,
            success: function( data ) {

                try {

                    var attrLabel = JSON.parse(data);

                    $('[name="atllbl"]',attrLabelForm ).val( attrLabel[0].atllbl );
                    $('[name="atldsc"]',attrLabelForm ).val( attrLabel[0].atldsc );
                    $('[name="atl_id"]',attrLabelForm ).val( attrLabel[0].atl_id );
                    $('[name="atltyp"]',attrLabelForm ).val( attrLabel[0].atltyp );
                    $('[name="srctyp"]',attrLabelForm ).val( attrLabel[0].srctyp );
                    $('[name="srtord"]',attrLabelForm ).val( attrLabel[0].srtord );

                    $('[name="atlreq"]',attrLabelForm ).prop('checked', (attrLabel[0].atlreq == 1) ? true : false );
                    $('[name="atlspc"]',attrLabelForm ).prop('checked', (attrLabel[0].atlspc == 1) ? true : false );
                    $('[name="srcabl"]',attrLabelForm ).prop('checked', (attrLabel[0].srcabl == 1) ? true : false );

                    $('[name="atltyp"]', attrLabelForm).change();

                    var atlArr = attrLabel[0].atllst.split(",");
                    var resultHTML = '';

                    if (attrLabel[0].atllst.length > 0) {

                        for (i=0;i<atlArr.length;i++) {

                            resultHTML += '<li style="list-style: none; padding: 0; margin: 0 0 3px 0;">';
                            resultHTML += '<div class="input-append">';
                            resultHTML += '<input type="text" class="input-medium AtlLstVal" value="'+atlArr[i]+'" />';
                            resultHTML += '<div class="btn-group">';
                            resultHTML += '<button class="btn removeAltLst" type="button" rel="tooltip" title="Remove From List"><i class="icon icon-remove"></i></button>';
                            resultHTML += '<a href="#" class="btn" type="button" rel="tooltip" title="Drag To Reorder List"><i class="icon icon-reorder moveLI"></i></a>';
                            resultHTML += '</div>';
                            resultHTML += '</div>';
                            resultHTML += '</li>';

                        }

                    }

                    $('#AtrLst_UL').html( resultHTML );

                    $('#attrLabelTableBox').hide();
                    $('#attrLabelBox').show();


                } catch (ex) {

                    alert(ex);

                }


            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });

    });

    $('#attrLabelBody').on('click', '.deleteAttrLabelBtn', function (e) {
        e.preventDefault();

        var atlId = $(this).data('atl_id');

        $.msgAlert ({
            type: 'warning'
            , title: 'Delete This Label'
            , text: 'Are you sure you wish to permanently remove this label from the database?'
            , callback: function () {

                $.ajax({
                    url: 'attributes/attrlabel_script.php',
                    data: 'action=delete&ajax=true&atl_id=' + atlId,
                    type: 'POST',
                    async: false,
                    success: function( data ) {

                        var result = JSON.parse(data);

                        $.msgGrowl ({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });

                        getAttrLabels();

                    },
                    error: function (x, e) {
                        throwAjaxError(x, e);
                    }
                });

            }
        });

    });

    $('#AtrLst_UL').sortable({ handle: '.moveLI' });

    $('#attrLabelBody').sortable({
        handle: '.attrLabelSort',
        stop: function( event, ui ) {

            var atlLst = '';

            $('.editAttrLabel', $('#attrLabelBody')).each(function(){

                atlLst += (atlLst == '') ? $(this).data('atl_id') : ',' + $(this).data('atl_id');

            });

            $.ajax({
                url: 'attributes/attrlabel_script.php',
                data: 'action=resort&ajax=true&atl_id=' + atlLst,
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

        }
    });



    //$('#updateAttrLabelBtn').click(function(e){
    //    e.preventDefault();
    //    attrLabelForm.submit();
    //});



    $('#updateVariantBtn').click(function(e){
        e.preventDefault();

        productForm.submit();

    });

    $('#cancelVariantBtn').click(function(e){
        e.preventDefault();
        $('#variantBox').hide();
        $('#variantTableBox').show();
    });


    $('#productBody').on('click', '.editProduct', function (e) {
        e.preventDefault();

        var prdId = $(this).data('prd_id');


        //getGallery(prdId);
        //getProductGallery($('[name="prd_id"]', productForm).val());


        $.ajax({
            url: 'products/products_script.php',
            data: 'action=select&ajax=true&prd_id=' + prdId,
            type: 'POST',
            async: false,
            success: function( data ) {

                var productRec = JSON.parse(data);

                $('[name="atr_id"]', productForm).val( productRec[0].atr_id );
                $('[name="prd_id"]', productForm).val( productRec[0].prd_id );
                $('[name="prdnam"]', productForm).val( productRec[0].prdnam );
                $('[name="altref"]', productForm).val( productRec[0].altref );
                $('[name="seourl"]', productForm).val( productRec[0].seourl );
                $('[name="unipri"]', productForm).val( productRec[0].unipri );
                $('[name="buypri"]', productForm).val( productRec[0].buypri );
                $('[name="delpri"]', productForm).val( productRec[0].delpri );
                $('[name="usestk"]', productForm).val( productRec[0].usestk );
                $('[name="in_stk"]', productForm).val( productRec[0].in_stk );
                $('[name="on_ord"]', productForm).val( productRec[0].on_ord );
                $('[name="on_del"]', productForm).val( productRec[0].on_del );
                $('[name="weight"]', productForm).val( productRec[0].weight );

                $('[name="supdat"]', productForm).val( getJSONvariable('supdat', productRec[0].prdobj ) );

                //plupLoad( $('#plupload') );
                //fileplupLoad( $('#fileplupload') );

                //getProductGallery(productRec[0].prd_id);
                //getLibrary( '#productFileDownloads', 'PRDFILE', productRec[0].prd_id );

                //plupLoad( $('#pluploadproducts'), 'PRODUCT', productRec[0].prd_id );
                //fileplupLoad( $('#pluploadproductpdfs'), 'PRDFILE', productRec[0].prd_id );

                //alert( 'atr_id=' + $('[name="atr_id"]', productEntryForm).val() + '&atvtblnam=PRODUCT&atvtbl_id=' + $('[name="prd_id"]', productForm).val() );

                $.ajax({
                    url: 'attributes/ajax/attribute_form.php',
                    data: 'atr_id=' + $('[name="atr_id"]', productEntryForm).val() + '&atvtblnam=PRODUCT&atvtbl_id=' + $('[name="prd_id"]', productForm).val(),
                    type: 'GET',
                    async: true,
                    success: function( data ) {

                        $('#attlEntryForm').html(data);

                    },
                    error: function (x, e) {
                        throwAjaxError(x, e);
                    }
                });

                $('#variantBox').show();
                $('#variantTableBox').hide();

            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });
    });

    $('#productBody').on('click', '.deleteProduct', function (e) {
        e.preventDefault();

        var prdId = $(this).data('prd_id');

        $.msgAlert ({
            type: 'warning'
            , title: 'Delete This Product Variant'
            , text: 'Are you sure you wish to permanently remove this product variant from the database?'
            , callback: function () {

                $.ajax({
                    url: 'products/products_script.php',
                    data: 'action=delete&ajax=true&prd_id=' + prdId,
                    type: 'POST',
                    async: false,
                    success: function( data ) {

                        var result = JSON.parse(data);

                        $.msgGrowl ({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });

                        getProductsTable();

                    },
                    error: function (x, e) {
                        throwAjaxError(x, e);
                    }
                });

            }
        });

    });

    getProductsTable();

    $('#productBody').sortable({
        handle: '.sortProduct',
        stop: function( event, ui ) {

            var prdLst = '';

            $('.sortProduct', $('#productBody')).each(function(){
                prdLst += (prdLst == '') ? $(this).data('prd_id') : ',' + $(this).data('prd_id');
            });

            $.ajax({
                url: 'products/products_script.php',
                data: 'action=resort&ajax=true&prd_id=' + prdLst,
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

        }
    });

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
                $('[name="alttxt"]', $('#imageForm')).val( imgArray[0].alttxt );
                $('[name="urllnk"]', $('#imageForm')).val( imgArray[0].urllnk );

            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });

        $('#imageModal').modal('show');

    });

    $('.gallery-dynamic').on('click', '.deleteUpload', function (e) {
        e.preventDefault();

        var upl_id = $(this).data('upl_id');

        $.msgAlert ({
            type: 'warning'
            , title: 'Delete This Image'
            , text: 'Are you sure you wish to permanently remove this image from the database?'
            , callback: function () {

                $.ajax({
                    url: 'gallery/uploads_script.php',
                    data: 'action=delete&ajax=true&upl_id=' + upl_id,
                    type: 'POST',
                    async: false,
                    success: function( data ) {

                        var result = JSON.parse(data);

                        $.msgGrowl ({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });

                        getGallery($('[name="prd_id"]', productForm).val());
                        getProductGallery($('[name="prd_id"]', productForm).val());

                    },
                    error: function (x, e) {
                        throwAjaxError(x, e);
                    }
                });

            }
        });
        return false;
    });

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

                    getLibrary('#productDownloads', 'PRTFILE', $('[name="prt_id"]', productEntryForm).val() );

                    //getLibrary( '#productFileDownloads', 'PRDFILE', $('[name="prd_id"]', $('#productForm')).val() );

                    $('#imageModal').modal('hide');
                }

            },
            error: function (x, e) {
                throwAjaxError(x, e);
            }
        });

    });

    $('[name="prtnam"]', productEntryForm).on("keyup", function() {
        $('[name="seourl"]', productEntryForm ).val( seoURL( $(this).val() ) );
    });

    $('[name="prdnam"]', productForm).on("keyup", function() {
        $('[name="seourl"]', productForm ).val( seoURL( $(this).val() ) );
    });

    $('[name="prttagselect"]', productEntryForm).each(function(){
        var $el = $(this);
        var search = ($el.attr("data-nosearch") === "true") ? true : false,
            opt = {};
        if(search) opt.disable_search_threshold = 9999999;
        $el.chosen(opt);

        resize_chosen();

    });

    $('#createAttrLabelBtn').click(function(e){
        e.preventDefault();

        // clear form
        $('[name="atllbl"]',attrLabelForm ).val( '' );
        $('[name="atldsc"]',attrLabelForm ).val( '' );
        $('[name="atl_id"]',attrLabelForm ).val( 0 );
        $('#AtrLst_UL',attrLabelForm).html('');
        $('[name="atltyp"]', attrLabelForm).val('text');
        $('[name="atltyp"]', attrLabelForm).change();
        $('#attrLabelTableBox').hide();
        $('#attrLabelBox').show();
    });

    $('#cancelAttrLabelBtn').click(function(e){
        e.preventDefault();
        $('#attrLabelTableBox').show();
        $('#attrLabelBox').hide();
    });

    getGallery();
    getAttrLabels();

    $('#buildStructure').find('a').each(function(){

        var btnHTML = '<input type="checkbox" name="str_id[]" class="relatedCb" value="'+$(this).data('str_id')+'" />';

        $(this).prepend(btnHTML);
    });

    $('#buildStructure').on('change', '.relatedCb', function(){


    });

    //function buildCBs (callback) {
    //
    //    $('li', '#libList').each(function (index) {
    //
    //        var strId = $(this).find('a').data('str_id');
    //        //$(this).prepend('<input type="checkbox" value="' + strId + '" name="str_id[]">');
    //        $(this).prepend('<input type="checkbox" name="str_id[]" class="relatedCb" value="'+strId+'" />');
    //
    //    }).promise().done( function(){
    //        callback();
    //    });
    //
    //    //
    //    return true;
    //
    //}
    //
    //function getRelated () {
    //
    //    $.ajax({
    //        url: 'system/related_script.php',
    //        data: 'action=getrelated&ajax=true&tblnam=PRODUCT&tbl_id=' + $('[name="prt_id"]', productEntryForm).val() + '&refnam=STRUCTURE',
    //        type: 'POST',
    //        async: false,
    //        success: function (data) {
    //
    //            var jsonArray = JSON.parse(data);
    //
    //            for (i = 0; i < jsonArray.length; i++) {
    //
    //                $('[name="str_id[]"][value="' + jsonArray[i].ref_id + '"]').prop('checked', true);
    //
    //            }
    //
    //        }
    //    });
    //
    //}
    //
    //buildCBs(function () {
    //    getRelated();
    //});

    $.ajax({
        url: 'system/related_script.php',
        data: 'action=getrelated&ajax=true&tblnam=PRODUCT&tbl_id=' + $('[name="prt_id"]', productEntryForm).val() + '&refnam=STRUCTURE',
        type: 'POST',
        async: false,
        success: function( data ) {

            var jsonArray = JSON.parse(data);

            for (i=0;i<jsonArray.length;i++) {

                $('[name="str_id[]"][value="'+jsonArray[i].ref_id+'"]').prop('checked', true);

            }

        }
    });

    $('#buildStructure').on('click', '.selectStructureBtn', function(e){

        e.preventDefault();

        var strID = $(this).data('str_id');

        var cbchecked = $(this).find('input:checkbox').prop('checked')
        cbchecked = (cbchecked) ? false : true;

        $(this).find('input:checkbox').prop('checked', cbchecked);

        //$(this).parents().each(function(){
        //    $(this).find('input:checkbox').prop('checked', cbchecked);
        //});

    });


    //
    // BUILD RELATED LIST
    //

    var prddata;

    $('#relatedProductBox').block({ message: 'Retrieving Products' });

    $.ajax({
        url: "products/product_types_script.php",
        data: 'action=select&prt_id=0',
        type: "GET",
        async: true,
        success: function(data) {

            //console.log(data);

            prddata = JSON.parse( data );

            $(':input.autocomplete').typeahead({
                source: function(query, process) {
                    objects = [];
                    map = {};
                    var data = prddata; // Or get your JSON dynamically and load it into this variable
                    $.each(data, function(i, object) {

                        map[object.prtnam + ' (' + object.prtnam + ')'] = object;
                        objects.push(object.prtnam + ' (' + object.prtnam + ')');
                    });

                    return objects;

                    //process(objects);
                },
                updater: function(item) {

                    $('#relatedName', relatedForm).val(item);

                    $('[name="ref_id"]', relatedForm).val(map[item].prt_id);
                    return item;
                }
            });

            $('#relatedProductBox').unblock();

        }
    });


    relatedForm.submit(function(e){

        e.preventDefault();

        $.ajax({
            url: 'system/related_script.php',
            data: 'action=create&ajax=true&tblnam=PRDTYPE&tbl_id=' + $('[name="tbl_id"]', relatedForm).val() + '&refnam=PRDTYPE&ref_id=' + $('[name="ref_id"]', relatedForm).val(),
            type: 'POST',
            async: false,
            success: function( data ) {

                getRelatedProducts();

                $('[name="refnam"]', relatedForm).val('');
                $('[name="ref_id"]', relatedForm).val(0);

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

    //
    // PRODUCT DOWNLOADS
    //

    $('#productDownloads').on('click', '.editUpload', function (e) {
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

    $('#productDownloads').on('click', '.deleteUpload', function (e) {
        e.preventDefault();

        var upl_id = $(this).data('upl_id');

        $.msgAlert ({
            type: 'warning'
            , title: 'Delete This File'
            , text: 'Are you sure you wish to permanently remove this file from the database?'
            , callback: function () {

                $.ajax({
                    url: 'gallery/uploads_script.php',
                    data: 'action=delete&ajax=true&masterimage=true&upl_id=' + upl_id,
                    type: 'POST',
                    async: false,
                    success: function( data ) {

                        var result = JSON.parse(data);

                        $.msgGrowl ({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });

                        getLibrary('#productDownloads', 'PRTFILE', $('[name="prt_id"]', productEntryForm).val() );

                    },
                    error: function (x, e) {
                        throwAjaxError(x, e);
                    }
                });

            }
        });
        return false;
    });



    $('#productFileDownloads').on('click', '.editUpload', function (e) {
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

    $('#productFileDownloads').on('click', '.deleteUpload', function (e) {
        e.preventDefault();

        var upl_id = $(this).data('upl_id');

        $.msgAlert ({
            type: 'warning'
            , title: 'Delete This File'
            , text: 'Are you sure you wish to permanently remove this file from the database?'
            , callback: function () {

                $.ajax({
                    url: 'gallery/uploads_script.php',
                    data: 'action=delete&ajax=true&masterimage=true&upl_id=' + upl_id,
                    type: 'POST',
                    async: false,
                    success: function( data ) {

                        var result = JSON.parse(data);

                        $.msgGrowl ({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });

                        getLibrary('#productDownloads', 'PRTFILE', $('[name="prt_id"]', productEntryForm).val() );
                        //getLibrary( '#productFileDownloads', 'PRDFILE', $('[name="prd_id"]', $('#productForm')).val() );

                    },
                    error: function (x, e) {
                        throwAjaxError(x, e);
                    }
                });

            }
        });
        return false;
    });



    if ( $('[name="prt_id"]', productEntryForm).val() != '0' ) {

        plupLoad($('#plupload'));
        fileplupLoad( $('#fileplupload'), 'PRTFILE', $('[name="prt_id"]', productEntryForm).val() );
        $('#createVariantBtn').show();
        $('#deleteProductType').show();
        getProductsTable();
        getRelatedProducts();
        getLibrary('#productDownloads', 'PRTFILE', $('[name="prt_id"]', productEntryForm).val() );

    } else {
        $('#createVariantBtn').hide();
        $('#deleteProductType').hide();
    }

});


function getAttrLabels() {

    $.ajax({
        url: 'attributes/attrlabels_table.php',
        data: 'tblnam=PRODUCTTYPE&tbl_id=' + $('[name="prt_id"]', productEntryForm).val(),
        type: 'GET',
        async: true,
        success: function( data ) {

            $('#attrLabelBody').html( data );

        },
        error: function (x, e) {
            throwAjaxError(x, e);
        }
    });

    $('[rel="tooltip"]', $('#attrLabelBody')).tooltip();

}


function getProductsTable() {

    $.ajax({
        url: 'products/type_products_table.php',
        data: 'tblnam=PRODUCT&prt_id=' + $('[name="prt_id"]', productEntryForm).val(), // + productEntryForm.serialize(),
        type: 'GET',
        async: false,
        success: function( data ) {

            $('#productBody').html(data);

        }
    });

}

function clearProductForm() {

    $('[name="prd_id"]', productForm).val(0);

    $('[name="prt_id"]', productForm).val( $('[name="prt_id"]', productEntryForm).val() );
    $('[name="prdnam"]', productForm).val( $('[name="prtnam"]', productEntryForm).val() );
    $('[name="altref"]', productForm).val( '' );

    $('[name="unipri"]', productForm).val( $('[name="unipri"]', productEntryForm).val() );
    $('[name="buypri"]', productForm).val( $('[name="buypri"]', productEntryForm).val() );
    $('[name="delpri"]', productForm).val( $('[name="delpri"]', productEntryForm).val() );

    $('[name="seourl"]', productForm).val( '' );

    $('[name="usestk"]', productForm).val(1);

    $('[name="in_stk"]', productForm).val(0);
    $('[name="on_ord"]', productForm).val(0);
    $('[name="on_del"]', productForm).val(0);

    $('[name="weight"]', productForm).val(0);

    $.ajax({
        url: 'attributes/ajax/attribute_form.php',
        data: 'atr_id=' + $('[name="atr_id"]', productEntryForm).val() + '&atvtblnam=PRODUCT&atvtbl_id=' + $('[name="prd_id"]', productForm).val(),
        type: 'GET',
        async: true,
        success: function( data ) {

            $('#attlEntryForm').html(data);

        },
        error: function (x, e) {
            throwAjaxError(x, e);
        }
    });

}


function getGallery() {

    $.ajax({
        url: 'products/producttypes.gallery.php',
        data: 'tblnam=PRDTYPE&tbl_id='+$('[name="prt_id"]', productEntryForm).val(),
        type: 'GET',
        async: false,
        success: function( data ) {

            $('#galleryImages').html(data);

            //$.msgGrowl ({
            //    type: 'success'
            //    , title: 'Gallery Retrieved'
            //    , text: 'Gallery Retrieved'
            //});

        },
        error: function (x, e) {
            throwAjaxError(x, e);
        }
    });

    $(".colorbox-image").colorbox({
        maxWidth: "90%",
        maxHeight: "90%",
        rel: $(this).attr("rel")
    });

}



function getProductGallery(iPrdId) {

    $.ajax({
        url: 'products/producttypes.gallery.php',
        data: 'tblnam=PRODUCT&tbl_id='+iPrdId,
        type: 'GET',
        async: false,
        success: function( data ) {

            $('#productgalleryImages').html(data);

            //$.msgGrowl ({
            //    type: 'success'
            //    , title: 'Gallery Retrieved'
            //    , text: 'Gallery Retrieved'
            //});

        },
        error: function (x, e) {
            throwAjaxError(x, e);
        }
    });

    $(".colorbox-image").colorbox({
        maxWidth: "90%",
        maxHeight: "90%",
        rel: $(this).attr("rel")
    });

}

function getProductLibrary(iPrdId) {

    $.ajax({
        url: 'products/producttypes.gallery.php',
        data: 'tblnam=PRODUCT&tbl_id='+iPrdId,
        type: 'GET',
        async: false,
        success: function( data ) {

            $('#productgalleryImages').html(data);

            //$.msgGrowl ({
            //    type: 'success'
            //    , title: 'Gallery Retrieved'
            //    , text: 'Gallery Retrieved'
            //});

        },
        error: function (x, e) {
            throwAjaxError(x, e);
        }
    });

    $(".colorbox-image").colorbox({
        maxWidth: "90%",
        maxHeight: "90%",
        rel: $(this).attr("rel")
    });

}


function plupLoad(iElement, tblnam, tbl_id) {

    tblnam = typeof tblnam !== 'undefined' ? tblnam : 'PRDTYPE';
    tbl_id = typeof tbl_id !== 'undefined' ? tbl_id : $('[name="prt_id"]', productEntryForm).val();

    var $el = iElement;
    $el.pluploadQueue({
        runtimes : 'html5,gears,flash,silverlight,browserplus',
        url : 'upload.php?resize='+$el.data('imgsiz')+'&tblnam='+tblnam+'&tbl_id=' + tbl_id,
        max_file_size : '10mb',
        chunk_size : '10mb',
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

            getGallery($('[name="prt_id"]', productEntryForm).val());
            getProductGallery($('[name="prd_id"]', productForm).val());

        }
    });

}


function elFinderBrowser (field_name, url, type, win) {
    tinymce.activeEditor.windowManager.open({
        file: 'system/elfindertiny.php',
        title: 'File Browser',
        width: 900,
        height: 600,
        resizable: 'yes'
    }, {
        setUrl: function (url) {
            win.document.getElementById(field_name).value = url;
        }
    });
    return false;
}


function getRelatedProducts() {

    $.ajax({
        url: 'system/related_script.php',
        data: 'action=relatedproducttypes&ajax=true&rel_id=0&tblnam=PRDTYPE&tbl_id=' + $('[name="tbl_id"]', relatedForm).val() + '&refnam=PRDTYPE',
        type: 'POST',
        async: true,
        success: function( data ) {

            var jsonArray = JSON.parse(data);

            var resultHTML = '';

            for (i=0;i<jsonArray.length;i++) {
                resultHTML = resultHTML + '<li>'+jsonArray[i].prtnam+'<a href="#" class="removeRelated pull-right" data-rel_id="'+jsonArray[i].rel_id+'"><i class="icon-remove"></i> </a> </li>';
            }

            $('#relatedProductList').html(resultHTML);

        }
    });

}


function getLibrary(iElement, tblnam, tbl_id) {

    tblnam = typeof tblnam !== 'undefined' ? tblnam : 'PRTFILE';
    tbl_id = typeof tbl_id !== 'undefined' ? tbl_id : $('[name="prt_id"]', productEntryForm).val();

    //$('[name="prt_id"]', productEntryForm).val()
    //$('#productDownloads')

    $.ajax({
        url: 'downloads/uploads.library.php',
        data: 'tblnam='+tblnam+'&tbl_id='+tbl_id,
        type: 'GET',
        async: false,
        success: function( data ) {

            $(iElement).html(data);

            //$.msgGrowl ({
            //    type: 'success'
            //    , title: 'Library Retrieved'
            //    , text: 'Library Retrieved'
            //});

        },
        error: function (x, e) {
            throwAjaxError(x, e);
        }
    });

    $(".colorbox-image").colorbox({
        maxWidth: "90%",
        maxHeight: "90%",
        rel: $(this).attr("rel")
    });

}

function fileplupLoad(iElement, tblnam, tbl_id) {

    tblnam = typeof tblnam !== 'undefined' ? tblnam : 'PRTFILE';
    tbl_id = typeof tbl_id !== 'undefined' ? tbl_id : $('[name="prt_id"]', productEntryForm).val();

    //alert( '../js/plugins/plupload/uploadfile.php?tblnam=' + $('[name="tblnam"]', libraryForm ).val() + '&tbl_id=' + $('[name="gal_id"]', libraryForm ).val() );

    var $el = iElement;
    $el.pluploadQueue({
        runtimes : 'html5,browserplus,silverlight,flash,gears,html4',
        url : '../js/plugins/plupload/uploadfile.php?tblnam='+tblnam+'&tbl_id=' + tbl_id,
        //max_file_size : '20mb',
        //chunk_size : '20mb',
        unique_names : true,
        multiple_queues : true,
        //resize : {width : 320, height : 240, quality : 90},
        filters : [
            {title : "PDF", extensions : "pdf"},
            {title : "WORD Docs", extensions : "doc,docx,txt"},
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

    upload.bind("UploadComplete", function(up, files) {
        if (up.files.length == (up.total.uploaded + up.total.failed)) {
            getLibrary( '#productDownloads', tblnam, tbl_id);
            //getLibrary( '#productFileDownloads', 'PRDFILE', $('[name="prd_id"]', $('#productForm')).val() );
        }
    });

    upload.bind("Error", function(up, args) {
        console.log(args);
    });


}