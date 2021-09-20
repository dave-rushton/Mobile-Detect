
function makeid()
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 5; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

var MoveElement;
var ReceiveElement;
var DraggerModule;
var DraggerFile;

var newList;
var oldList;

var tinymceConfigs = [];

var tinyMCEs = [];

$(function(){

    $('body').addClass('pagebuilder');


    $('#publishButton').click(function(e){
        e.preventDefault();
        console.log($('#websiteContent').html());
    });


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

            toolbar1: "bold italic underline | alignleft aligncenter alignright alignjustify ",
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
            content_css: $('#webRoot').val() + "pages/css/style.css",

            style_formats: [
                {title: "Headers", items: [
                    {title: "Header 1", format: "h1"},
                    {title: "Header 2", format: "h2"},
                    {title: "Header 3", format: "h3"},
                    {title: "Header 4", format: "h4"}
                ]},
                {title: "Text", items: [
                    //{title: "Green Text", icon: "bold", inline: "span", classes: "text-green"},
                    {title: "Heading 1", icon: "bold", inline: "span", classes: "heading1"},
                    {title: "Heading 2", icon: "bold", inline: "span", classes: "heading2"},
                    {title: "Heading 3", icon: "bold", inline: "span", classes: "heading3"}
                ]}
            ],

            link_class_list: [
                {title: 'None', value: ''},
                {title: 'Call To Action', value: 'readmore'}
            ]

        }];



    //
    // Module Panel Functionality
    //

    $('#modulePanel #showModules').click(function(e){
        e.preventDefault();
        $(this).find('i').toggleClass('fa-chevron-down').toggleClass('fa-chevron-up');
        $(this).parent().next().slideToggle('fast');
    });

    $('#modulePanel #moveModules').click(function(e){

        var modulePanel = $(this).parent().parent();

        e.preventDefault();
        if ( modulePanel.hasClass('topLeft') ) {
            modulePanel.toggleClass('topLeft').toggleClass('topRight');
        } else if ( modulePanel.hasClass('topRight') ) {
            modulePanel.toggleClass('topRight').toggleClass('bottomRight');
        } else if ( modulePanel.hasClass('bottomLeft') ) {
            modulePanel.toggleClass('bottomLeft').toggleClass('topLeft');
        } else if ( modulePanel.hasClass('bottomRight') ) {
            modulePanel.toggleClass('bottomRight').toggleClass('bottomLeft');
        }

    });

    $('#modulePanel .moduleHeader').click(function(e){
        e.preventDefault();

        $('.moduleList').slideUp().prev().removeClass('active');

        $(this).addClass('active').next('.moduleList').slideToggle('fast');
    });

    $('#modulePanel .module').draggable({
        helper: 'clone',
        start: function() {
            DraggerModule = $(this).attr("id");
            DraggerFile = $(this).data("incfil");
        },
        drag: function(e, ui) {
            ui.helper.css("width", "300px");
        }
    });

    //
    // Element Events
    //

    $(".pageElement").on("click", ".editButton", function(e){

        e.preventDefault();

        var editOption = $(this).parent().parent().find('.editOptions');

        if ( editOption.is(':visible') ) {

            // destroy

            var destroyTiny = editOption.find('.tinymce');

            destroyTiny.each(function(){
                tinyMCE.execCommand('mceRemoveControl', false, $(this).attr("id") );
            });

        } else {

            // create

            var createTiny = editOption.find('.tinymce');

            createTiny.each(function(){

                tinyMCE.settings = tinymceConfigs[1];
                tinyMCE.execCommand('mceAddEditor', false, $(this).attr("id"));

            });


            //
            // Multiselect Options
            //
            if ( editOption.find('[name="cb_val"]').length > 0 ) {

                var selectedValues = editOption.find('[name="cb_val"]').val().split(',');

                for (i=0;i<selectedValues.length;i++) {

                    editOption.find(":checkbox[value="+ selectedValues[i] + "]").attr("checked","true");

                }

            }

            //
            // Gallery Code
            //

            if ( editOption.find('[name="gal_id"]').length > 0 ) {

                editOption.find('[name="gal_id"]').change();

            }

            //
            // Image Code
            //
            if ( editOption.find('[name="imgurl"]').length > 0 ) {

                editOption.find('[name="imgurl"]').change();

            }


        }

        editOption.slideToggle(function(){


        });

    });

    $(".pageElement").on("click", ".deleteButton", function(e){
        e.preventDefault();
        $(this).parent().parent().find('.deleteOptions').slideToggle();
    });

    $(".pageElement").on("click", ".draggerHandle", function(e){
        e.preventDefault();
    });

    $(".pageElement").on("submit", "form.deleteForm", function(e){
        e.preventDefault();

        var elementWrapper = $(this).parent().parent();

        var postData = 'action=delete&pel_id='+elementWrapper.data('pel_id');

        //alert(postData);

        $.ajax({
            url: 'admin/website/json/element.json.php',
            data: postData,
            type: 'POST',
            async: false,
            success: function( data ) {

                elementWrapper.slideToggle('slow', function(){
                    elementWrapper.remove();
                });

            },
            error: function (x, e) {
                if (x.status == 0) {
                    alert('You are offline!!\n Please Check Your Network.');
                } else if (x.status == 404) {
                    alert('Requested URL not found.');
                } else if (x.status == 500) {
                    alert('Internel Server Error.');
                } else if (e == 'timeout') {
                    alert('Request Time out.');
                }
            }
        });

    });

    $(".pageElement").on("submit", "form.elementForm", function(e){

        var thisForm = $(this);

        var cbValues = '';
        $('.checkboxoption:checked', thisForm).each(function(){
            cbValues += (cbValues.length == 0) ? $(this).val() : ',' + $(this).val();
        });
        $('[name="cb_val"]', thisForm).val( cbValues );

        tinyMCE.triggerSave();

        e.preventDefault();

        if (thisForm.hasClass('deleteForm')) return false;

        var elementWrapper = thisForm.parent().parent();
        var Pel_ID = elementWrapper.data("pel_id");
        var Pgc_ID = elementWrapper.data("pgc_id");

        //
        // update page content
        //

        if ( $(('[name="pgctxt"]'), thisForm).length > 0 ) {

            tinyMCE.triggerSave( $(('[name="pgctxt"]'), thisForm).attr("id") );

            var postData = 'action=update&pgc_id='+elementWrapper.data("pgc_id")+'&pgctxt='+encodeURIComponent($(('[name="pgctxt"]'), thisForm).val())+'&pgcttl=&sta_id=0';

            $.ajax({
                url: 'admin/website/json/content.json.php',
                data: postData,
                type: 'POST',
                async: false,
                success: function( data ) {

//					alert( data );

                    //$.growlUI("Success", "Content Updated");

                },
                error: function (x, e) {
                    if (x.status == 0) {
                        alert('You are offline!!\n Please Check Your Network.');
                    } else if (x.status == 404) {
                        alert('Requested URL not found.');
                    } else if (x.status == 500) {
                        alert('Internel Server Error.');
                    } else if (e == 'timeout') {
                        alert('Request Time out.');
                    }
                }
            });

        }

        var formArray = thisForm.serializeArray();

        // for lists - check here if listform



        var fields = $(":input", thisForm).not('[name="pgctxt"]').serializeArray();
        var elementVariables = JSON.stringify(fields);
        var postData = 'action=update&pel_id='+Pel_ID+'&elevar='+encodeURIComponent(elementVariables);

        $.ajax({
            url: 'admin/website/json/element.json.php',
            data: postData,
            type: 'POST',
            async: false,
            success: function( data ) {

//				alert(data);

                // request the url so we can refresh
                $.ajax({
                    url: 'admin/website/json/element.json.php',
                    data: 'action=requesturl&pel_id=' + Pel_ID,
                    type: 'POST',
                    async: false,
                    success: function( data ) {
//						alert(data);
                        // once we have the url then refresh
                        $.ajax({
                            url: $('#webRoot').val() + data+'&seourl=' + $('#PagSeoUrl').val()+'&pel_id=' + Pel_ID,
                            type: 'GET',
                            async: false,
                            success: function( data ) {
//								alert(data);
                                $('.contentWrapper', elementWrapper).html( data );
                                thisForm.parent().slideToggle('fast');
                            }
                        });
                    }
                });

                //thisForm.parent().next().remove();

            },
            error: function (x, e) {
                if (x.status == 0) {
                    alert('You are offline!!\n Please Check Your Network.');
                } else if (x.status == 404) {
                    alert('Requested URL not found.');
                } else if (x.status == 500) {
                    alert('Internel Server Error.');
                } else if (e == 'timeout') {
                    alert('Request Time out.');
                }
            }
        });

        //
        // set image size on drop downs so doesnt reuse originally set values in bindElement
        //
        $('[name="imgsiz"]', thisForm).each(function(){
            $(this).parent().parent().prev('.inputContainer').find('[name="imgurl"]').data('imgsiz', $(this).val());
            $(this).parent().parent().prev('.inputContainer').find('[name="gal_id"]').data('imgsiz', $(this).val());
        });

        return false;

    });

    //
    // Drop Event / Create New Element
    //

    $('.pageElement')
        .droppable({
            hoverClass: "hover",
            drop: function( event, ui ) {

                //
                // creation of page element
                //

                // Text Content Handled Seperately

                if (DraggerModule == 'moveElement') return false;

                var pageElement = $(this);

                var newPel_ID = 0;
                var Pgc_ID = 0;

                if (DraggerModule == 'textContent' || DraggerModule == 'textContentExpand') {

                    var pgctxt = '<h1>Edit this text</h1><p>Click the edit button above to amend this text</p>';

                    if ( DraggerModule == 'textContentExpand' ) pgctxt = '<p>Expanded text goes here</p>';

                    var postData = 'action=update&pgc_id=0&pgctxt='+pgctxt+'&pgcttl=&sta_id=0';

                    $.ajax({
                        url: 'admin/website/json/content.json.php',
                        data: postData,
                        type: 'POST',
                        async: false,
                        success: function( data ) {

                            Pgc_ID = data;

                        },
                        error: function (x, e) {
                            if (x.status == 0) {
                                alert('You are offline!!\n Please Check Your Network.');
                            } else if (x.status == 404) {
                                alert('Requested URL not found.');
                            } else if (x.status == 500) {
                                alert('Internel Server Error.');
                            } else if (e == 'timeout') {
                                alert('Request Time out.');
                            }
                        }
                    });

                }

                if (DraggerModule != 'moveElement') { // stop bubbling

                    //
                    // CREATE ELEMENT if element not moved from another list (bubbling)
                    //

                    var pag_id = $('#EdtPag_ID').val();
                    var div_id = $(this).attr("id");
                    var srtord = 99;
                    var eletyp = DraggerModule;
                    var pgc_id = Pgc_ID;
                    var incfil = DraggerFile;
                    var incurl = '';

                    var postData = 'action=update&pel_id=0&pag_id='+pag_id+'&div_id='+div_id+'&srtord='+srtord+'&eletyp='+eletyp+'&pgc_id='+pgc_id+'&incfil='+incfil+'&incurl='+incurl+'&sta_id=0';

                    //alert(postData);

                    $.ajax({
                        url: 'admin/website/json/element.json.php',
                        data: postData,
                        type: 'POST',
                        async: false,
                        success: function( data ) {

                            //
                            // retrieve new element id
                            //

//						alert(data);

                            newPel_ID = data;

                        },
                        error: function (x, e) {
                            if (x.status == 0) {
                                alert('You are offline!!\n Please Check Your Network.');
                            } else if (x.status == 404) {
                                alert('Requested URL not found.');
                            } else if (x.status == 500) {
                                alert('Internel Server Error.');
                            } else if (e == 'timeout') {
                                alert('Request Time out.');
                            }
                        }
                    });

                }

//				alert('admin/website/json/element.json.php?action=requesturl&pel_id=' + newPel_ID);

                $.ajax({
                    url: 'admin/website/json/element.json.php',
                    data: 'action=requesturl&pel_id=' + newPel_ID,
                    type: 'POST',
                    async: false,
                    success: function( data ) {

//						alert(data);

                        $.ajax({
                            url: data+'&seourl=' + $('#SeoUrl').val()+'&pel_id=' + newPel_ID,
                            type: 'GET',
                            async: false,
                            success: function( data ) {

                                //
                                // here we should rebind individual element in function and replace class for each search with this.bindControl()
                                //

                                var buildTmpElement = '<div class="elementModule '+ DraggerModule +'" rel="'+newPel_ID+'" data-type="'+DraggerModule+'" data-element="'+newPel_ID+'" data-pgc="'+Pgc_ID+'">'+data+'</div>';
                                pageElement.append(buildTmpElement);
                                var newElement = $('[data-element="'+newPel_ID+'"]');
                                bindElement(newElement);

                            }
                        });
                    }
                });

            }
        });

    $('.pageElement').sortable({
        items: ".elementWrapper",
        connectWith: ".pageElement",
        handle: ".draggerHandle",
        start: function(event, ui) {
            ReceiveElement = ui.item.parent();
            DraggerModule = 'moveElement';
        },
        receive : function(event, ui){
            ReceiveElement = ui.item.parent();
        },
        change: function(event, ui) {
            ReceiveElement = ui.item.parent();
        },
        stop: function(event, ui) {

            var sortOrder = 0;
            var elementString = '';

            $('.elementWrapper', ReceiveElement).each(function(){
                elementString += (elementString == '') ? $(this).data('pel_id') : ',' + $(this).data('pel_id');
            });

            var postData = 'action=updateorder&div_id='+ReceiveElement.attr("id")+'&elestr='+elementString;

            $.ajax({
                url: 'admin/website/json/element.json.php',
                data: postData,
                type: 'POST',
                success: function( data ) {

                },
                error: function (x, e) {
                    if (x.status == 0) {
                        alert('You are offline!!\n Please Check Your Network.');
                    } else if (x.status == 404) {
                        alert('Requested URL not found.');
                    } else if (x.status == 500) {
                        alert('Internel Server Error.');
                    } else if (e == 'timeout') {
                        alert('Request Time out.');
                    }
                }
            });

        }
    });

    //
    // Bind Controls
    //

    $('.elementModule').each(function(){ bindElement( $(this) ) });

    $('body').addClass('pagebuilder');

    $('[data-texthelp]').each(function () {

        $(this).prepend('<div class="texthelp"><div class="texthelpwrapper"><div class="texthelptext">'+$(this).data('texthelp')+'</div></div></div>');

    });



    //
    // STRUCTURE
    //

    //$('#websiteContent').append('<a href="#" id="newRowBtn">New Row</a>');
    //
    //$('#newRowBtn').click(function(e){
    //
    //    e.preventDefault();
    //
    //});
    //
    //$('.pageElement').each(function(e){
    //    $(this).append('<a href="#" class="editColBtn">Edit Column</a>');
    //});


});

function bindElement(e) {

    // wrap content so that we can update html
    e.wrap('<div class="contentWrapper" />');
    var contentWrapper = e.parent();

    // wrap the content wrapper so we can add UI forms and functionality
    contentWrapper.wrap('<div class="elementWrapper" data-pel_id="'+e.data('element')+'" data-eletyp="'+e.data('type')+'" />');
    var elementWrapper = contentWrapper.parent();

    if (e.data('pgc') > 0) {
        elementWrapper.data("pgc_id", e.data('pgc'));
    }

    // find module element in menu which points to a form ID
    if ( $('#'+e.data('type')).length > 0 ) {
        var module = $('#'+e.data('type'));

        var formMarkup = $('#'+module.data('markup'));

        elementWrapper.prepend( formMarkup.html() );

        var form = $('.elementForm', elementWrapper);

        // populate form
        $.ajax({
            url: 'admin/website/json/element.json.php',
            data: 'action=select&pel_id=' + e.data('element'),
            type: 'POST',
            aSync: false,
            success: function( data ) {

                //alert(data);
                try {
                    var elementVariables = JSON.parse(data);

                    for (v=0;v<elementVariables.length;v++) {

                        if ( $('[name="' + elementVariables[v].name + '"]', form).is(':checkbox')) {
                            // always true as if false variable will not exist in JSON
                            $('[name="' + elementVariables[v].name + '"]', form).prop( 'checked', true );
                        } else if ( $('[name="' + elementVariables[v].name + '"]', form).is(':radio')) {
                            // always true as if false variable will not exist in JSON
                            $('[name="' + elementVariables[v].name + '"][value="' + elementVariables[v].value + '"]', form).prop( 'checked', true );
                        } else {

                            $('[name="' + elementVariables[v].name + '"]', form).val( elementVariables[v].value );

                            if ( $('[name="' + elementVariables[v].name + '"]', form).hasClass('tinymce') ) {

                                var tinyID = $('[name="' + elementVariables[v].name + '"]', form).attr("id");
                            }


                            //
                            // Image Sizes
                            //

                            if (elementVariables[v].name == 'gal_id') {

                                //$('[name="' + elementVariables[v].name + '"]', form).change();

                            }

                            if (elementVariables[v].name == 'imgsiz') {

                                $('[name="gal_id"]', form).data('imgsiz', elementVariables[v].value);
                                $('[name="imgurl"]', form).data('imgsiz', elementVariables[v].value);

                            }


                        }
                    }

                } catch(ex) {

                }

            },
            error: function (x, e) {

                if (x.status == 0) {
                    alert('You are offline!!\n Please Check Your Network.');
                } else if (x.status == 404) {
                    alert('Requested URL not found.');
                } else if (x.status == 500) {
                    alert('Internel Server Error.');
                } else if (e == 'timeout') {
                    alert('Request Time out.');
                }
            }
        });

        // check for tinymce
        if ( $('.tinymce', form).length > 0) {

            var elem = 0;

            $('.tinymce', elementWrapper).each(function(){

                $(this).attr("id", $(this).attr("id") + makeid() );

                var tinyControl = $(this);

                var eleID = tinyControl.attr("id");

                if ( tinyControl.attr("id") == null ) {
                    eleID = 'pgc-' + e.data("element");
                }

                tinyControl.attr("id", eleID);
                tinyControl.attr("rel", eleID);
                tinyControl.val( contentWrapper.find('.pageContentText').html() );

                tinyMCEs.push(eleID);

            });

        }

        // check for menus
        if ( $('ul.menuUL', form).length > 0) {

            console.log(form);

            $('ul.menuUL', elementWrapper).each(function(){

                $('li', $(this)).show();

                // require unique ID based on name and position in page
                var selectID = $(this).parent().data('htmlname') + $('ul.menuUL').index( $(this) );

                $(this).attr("id", selectID );

                selectnav(selectID, {
                    name: $(this).parent().data('htmlname'),
                    label: 'Display Whole Menu',
                    nested: true,
                    indent: '-'
                });

                $('.selectnav', elementWrapper).change();

            });
        }

        $(form).on('change','[name="gal_id"]', function(){

            var imgSizSelect = $(form).find('[name="imgsiz"]');

            var defImgSize = $(this).data('imgsiz');

            $.ajax({
                url: 'admin/gallery/gallery_script.php',
                data: 'action=select&gal_id=' + $(this).val(),
                type: 'POST',
                aSync: false,
                success: function( data ) {

                    var result = JSON.parse(data);

                    var sizes = result[0].imgsiz.split(',');

                    resultHTML = '<option value="">Original</option>';
                    for (i=0;i<sizes.length;i++) {

                        if ( sizes[i].length > 0) resultHTML += '<option value="'+sizes[i]+'">'+sizes[i]+'</option>';

                    }

                    imgSizSelect.html( resultHTML).val(defImgSize);

                },
                error: function (x, e) {

                    if (x.status == 0) {
                        alert('You are offline!!\n Please Check Your Network.');
                    } else if (x.status == 404) {
                        alert('Requested URL not found.');
                    } else if (x.status == 500) {
                        alert('Internel Server Error.');
                    } else if (e == 'timeout') {
                        alert('Request Time out.');
                    }
                }
            });

        })


        $(form).on('change','[name="imgurl"]', function(){

            var imgSizSelect = $(form).find('[name="imgsiz"]');

            var holdSize = $(this).data('imgsiz');

            var sizes = $(this).find(":selected").data('imgsiz').split(',');

            resultHTML = '<option value="">Original</option>';
            for (i=0;i<sizes.length;i++) {

                if ( sizes[i].length > 0) resultHTML += '<option value="'+sizes[i]+'">'+sizes[i]+'</option>';

            }

            imgSizSelect.html( resultHTML).val(holdSize);

        })


    }

    // add buttons and default forms
    elementWrapper.prepend($('#defaultElementMarkUp').html());

    //
    // Add object Box
    //

    elementWrapper.append( '<div class="elementName"><b>NAME</b>: ' + $('#' + elementWrapper.data("eletyp")).html() + '<br><b>FILE</b>: ' + $('#' + elementWrapper.data("eletyp")).data('incfil') + '<br><b>ID</b>: ' + e.data('element') + '</div>' );

}
function elFinderBrowser (field_name, url, type, win) {
    tinymce.activeEditor.windowManager.open({
        file: 'admin/system/elfindertiny.php',
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