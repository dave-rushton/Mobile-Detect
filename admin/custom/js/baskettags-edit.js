var subCategoriesForm;

$(function(){

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

            toolbar1: "bold italic underline | alignleft aligncenter alignright | image ",
            toolbar2: "link unlink | bullist code fullscreen pasteword",

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
            //content_css: $('#webRoot').val() + "/pages/css/styles.css"

        }];

    tinyMCE.settings = tinymceConfigs[1];
    tinyMCE.execCommand('mceAddEditor', false, 'subtxt');

    subCategoriesForm = $('#subCategoriesForm');

    $('#updateSubCategoryBtn').click(function(e){
        e.preventDefault();
        subCategoriesForm.submit();
    });

    $('[name="subnam"]', subCategoriesForm).on("keyup", function() {
        $('[name="seourl"]', subCategoriesForm ).val( seoURL( $(this).val() ) );
    });

    subCategoriesForm.submit(function(e){

        e.preventDefault();

        uploadBoxImage();

    });

    $('#deleteSubCategoryBtn').click(function (e) {
        e.preventDefault();

        $.msgAlert ({
            type: 'warning'
            , title: 'Delete This Category'
            , text: 'Are you sure you wish to permanently remove this category from the database?'
            , callback: function () {

                $.ajax({
                    url: subCategoriesForm.attr("action"),
                    data: 'action=delete&ajax=true&' + subCategoriesForm.serialize(),
                    type: 'POST',
                    async: false,
                    success: function( data ) {

                        var result = JSON.parse(data);

                        $.msgGrowl ({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });

                        window.location = subCategoriesForm.data("returnurl");

                    },
                    error: function (x, e) {
                        throwAjaxError(x, e);
                    }
                });

            }
        });
        return false;
    });

});


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


function uploadBoxImage() {

    if ($('#logofile2')[0].files[0]) {

        var data;

        data = new FormData();
        data.append('logofile', $('#logofile2')[0].files[0]);

        $.ajax({
            url: 'custom/uploadlogo.php',
            data: data,
            processData: false,
            type: 'POST',
            contentType: false,
            aSync: false,
            success: function (data) {

                var result = JSON.parse(data);

                $('[name="subimg"]', subCategoriesForm).val(result.description);

                uploadHomeImage();

            },
            error: function (x, e) {

                uploadHomeImage();

            }
        });

    } else {
        uploadHomeImage();
    }

}

function uploadHomeImage() {

    if ($('#logofile3')[0].files[0]) {

        var data;

        data = new FormData();
        data.append('logofile', $('#logofile3')[0].files[0]);

        $.ajax({
            url: 'custom/uploadhomeimage.php',
            data: data,
            processData: false,
            type: 'POST',
            contentType: false,
            aSync: false,
            success: function (data) {

                var result = JSON.parse(data);

                $('[name="homimg"]', subCategoriesForm).val(result.description);

                updateRecord();

            },
            error: function (x, e) {

                updateRecord();

            }
        });

    } else {
        updateRecord();
    }

}

function updateRecord() {

    tinyMCE.triggerSave('subtxt');

    var fields = $(".customfield", subCategoriesForm).serializeArray();
    var elementVariables = JSON.stringify(fields);
    var postData = encodeURIComponent(elementVariables);

    $.ajax({
        url: subCategoriesForm.attr("action"),
        data: 'action=update&ajax=true&' + subCategoriesForm.serialize() + '&subtxt=' + postData,
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

                $('#id', subCategoriesForm).val( result.id );

            } catch(Ex) {
                $.msgGrowl ({
                    type: 'error'
                    , title: 'Error'
                    , text: Ex
                });

                //$.growlUI('Error', 'Contact your administrator');
            }

        },
        error: function (x, e) {

            throwAjaxError(x, e);

        }
    });

}