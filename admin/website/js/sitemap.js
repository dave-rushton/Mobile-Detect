var pwRoot;

$(function(){

    $('.submit-form').click(function(e){
        e.preventDefault();
        $(this).closest('form').submit();
    });

    pwRoot = $('#pwRoot').val();

    $('#pageForm').validate({
        rules: {
            pagttl: {
                minlength: 2,
                required: true
            },
            seourl: {
                minlength: 2,
                required: true
            }
        },
        errorElement:'span',
        errorClass: 'help-inline error',
        errorPlacement:function(error, element){
            element.parents('.controls').append(error);
        },
        highlight: function(label) {
            $(label).closest('.control-group').removeClass('error success').addClass('error');
        },
        success: function(label) {
            label.addClass('valid').closest('.control-group').removeClass('error success').addClass('success');
        }
    });

    $('#pageForm').submit(function(e){

        e.preventDefault();

        if ($(this).valid()) {

            $('#pageForm').block({ message: 'Updating' });

//			alert( $('#pageForm').attr("action")+'?ajax=true&' + $('#pageForm').serialize() );


            var fields = $(".customfield", $('#pageForm')).serializeArray();
            var elementVariables = JSON.stringify(fields);
            var postData = encodeURIComponent(elementVariables);

            $.ajax({
                url: $('#pageForm').attr("action"),
                data: 'action=update&ajax=true&' + $('#pageForm').serialize() + '&pagobj=' + postData,
                type: 'POST',
                async: false,
                success: function( data ) {

                    var result = JSON.parse(data);

                    $.msgGrowl ({
                        type: 'success' //result.type
                        , title: 'Page Updated' //result.title
                        , text: 'Page Updated' //result.description
                    });

                },
                error: function (x, e) {
                    throwAjaxError(e,x);
                }
            });

            $('#pageForm').unblock();
        }
        else {
            $.msgGrowl ({
                type: 'error'
                , title: 'Invalid Form'
                , text: 'There is an error in the form'
            });
        }
        return false;
    });

    $('#cmsPropForm').submit(function(e){

        e.preventDefault();

        if ($(this).valid()) {

            $('#cmsPropForm').block({ message: 'Updating' });

//			alert( $('#cmsPropForm').attr("action")+'?ajax=true&' + $('#cmsPropForm').serialize() );

            var fields = $(".customfield", $('#cmsPropForm')).serializeArray();
            var elementVariables = JSON.stringify(fields);
            var postData = encodeURIComponent(elementVariables);

            $.ajax({
                url: $('#cmsPropForm').attr("action"),
                data: 'action=update&ajax=true&' + $('#cmsPropForm').serialize()  + '&cmsobj=' + postData,
                type: 'POST',
                async: false,
                success: function( data ) {

                    //logConsole(data);

                    var result = JSON.parse(data);

                    $.msgGrowl ({
                        type: 'success' //result.type
                        , title: 'Properties Updated' //result.title
                        , text: 'Properties Updated' //result.description
                    });

                },
                error: function (e, x) {
                    throwAjaxError(e,x);
                }
            });

            $('#cmsPropForm').unblock();
        }
        else {
            $.msgGrowl ({
                type: 'error'
                , title: 'Invalid Form'
                , text: 'There is an error in the form'
            });
        }
        return false;
    });



    $('[name="sta_id"]', $('#pageForm')).change(function(){

        var fields = $(".customfield", $('#pageForm')).serializeArray();
        var elementVariables = JSON.stringify(fields);
        var postData = encodeURIComponent(elementVariables);

        $.ajax({
            url: $('#pageForm').attr("action"),
            data: 'action=update&ajax=true&' + $('#pageForm').serialize() + '&pagobj=' + postData,
            type: 'POST',
            async: false,
            success: function( data ) {

                $.msgGrowl ({
                    type: 'success' //result.type
                    , title: 'Change of status' //result.title
                    , text: 'Change of status' //result.description
                });


            },
            error: function (x, e) {
                throwAjaxError(e,x);
            }
        });

    });

});

function displayPage(iPag_ID) {

    $('#pageForm').block({ message: 'Retrieving' });

    $('.customfield').each(function(){

        if ( $(this).is(':checkbox')) {
            $(this).prop( 'checked', false );
        } else if ( $(this).is(':radio')) {
            //$('[name="' + elementVariables[v].name + '"][value="' + elementVariables[v].value + '"]').prop( 'checked', true );
        } else {
            $(this).val( '' );
        }

    });

    //alert(iPag_ID);

    if (iPag_ID == 2) {
        $('#cmsPropForm').show();
        $('#pageForm').hide();
        $('#renameSitemapPage, #deleteSitemapPage').hide();


        $.ajax({
            url: pwRoot + 'website/json/cmsprop.json.php',
            type: 'GET',
            success: function( data ) {
                var result = JSON.parse( data );

                //
                // CUSTOM FIELDS
                //

                //fix empty ... variables

                var elementVariables = result[0]['cmsobj'];

                if (elementVariables != null) {

                    for (v = 0; v < elementVariables.length; v++) {

                        if ($('[name="' + elementVariables[v].name + '"]').is(':checkbox')) {
                            // always true as if false variable will not exist in JSON
                            $('[name="' + elementVariables[v].name + '"]').prop('checked', true);
                        } else if ($('[name="' + elementVariables[v].name + '"]').is(':radio')) {
                            // always true as if false variable will not exist in JSON
                            $('[name="' + elementVariables[v].name + '"][value="' + elementVariables[v].value + '"]').prop('checked', true);
                        } else {
                            $('[name="' + elementVariables[v].name + '"]').val(elementVariables[v].value);
                        }

                    }
                }

            },
            error: function(e,x) {
                throwAjaxError(e,x);
            }
        });

        return false;
    } else {
        $('#cmsPropForm').hide();
        $('#pageForm').show();
        $('#renameSitemapPage, #deleteSitemapPage').show();
    }

    $('[name="pag_id"]').val( iPag_ID );

    $.ajax({
        url: pwRoot + 'website/json/page.json.php',
        data: 'pag_id=' + iPag_ID,
        type: 'GET',
        success: function( data ) {

            var result = JSON.parse( data );

            $('#Pag_ID').val( result[0]['pag_id'] );
            $('#PagTtl').val( result[0]['pagttl'] );
            $('#TmpLte').val( result[0]['tmplte'] );
            $('#SeoUrl').val( result[0]['seourl'] );
            $('#KeyWrd').val( result[0]['keywrd'] );
            $('#PagDsc').val( result[0]['pagdsc'] );
            $('#LnkTyp').val( result[0]['lnktyp'] );
            //$('#Sta_ID').val( result[0]['sta_id'] );
            $('#PagImg').val( result[0]['pagimg'] );
            $('#GoogEx').attr("checked", (result[0]['googex'] == 1) ? true : false );
            $('[name="defpag"]', $('#pageForm') ).prop("checked", (result[0]['defpag'] == 1) ? true : false );
            $('[name="sta_id"]', $('#pageForm') ).prop("checked", (result[0]['sta_id'] == 1) ? true : false );

            $('[name="lnkcol"]').val( getJSONvariable('lnkcol', result[0]['pagobj']) );

            if ( getJSONvariable('incsub', result[0]['pagobj']) == 1) {
                $('[name="incsub"]').prop('checked',true);
            } else {
                $('[name="incsub"]').prop('checked',false);
            }

            $('#pageBuilderLink').attr("href", 'website/pagebuilder.php?seourl=' + result[0]['seourl'] );
            $('#pagePreviewLink').attr("href", '../' + result[0]['seourl'] );

            //
            // CUSTOM FIELDS
            //

            var elementVariables = JSON.parse(result[0]['pagobj']);

            if (elementVariables != null) {

                for (v = 0; v < elementVariables.length; v++) {

                    if ($('[name="' + elementVariables[v].name + '"]').is(':checkbox')) {
                        // always true as if false variable will not exist in JSON
                        $('[name="' + elementVariables[v].name + '"]').prop('checked', true);
                    } else if ($('[name="' + elementVariables[v].name + '"]').is(':radio')) {
                        // always true as if false variable will not exist in JSON
                        $('[name="' + elementVariables[v].name + '"][value="' + elementVariables[v].value + '"]').prop('checked', true);
                    } else {
                        $('[name="' + elementVariables[v].name + '"]').val(elementVariables[v].value);
                    }

                }
            }


            $('#pageForm').unblock();

        },
        error: function(e,x) {
            throwAjaxError(e,x);
        }
    });
}

$(function(){

    $("#siteMap")
        .bind("before.jstree", function (e, data) {
            $("#alog").append(data.func + "<br />");
        })
        .jstree({
            // List of active plugins
            "plugins" : [
                "themeroller","json_data","ui","crrm","cookies","contextmenu","dnd","search"
            ],

            // I usually configure the plugin that handles the data first
            // This example uses JSON as it is most common
            "json_data" : {
                // This tree is ajax enabled - as this is most common, and maybe a bit more complex
                // All the options are almost the same as jQuery's AJAX (read the docs)
                "ajax" : {
                    // the URL to fetch the data
                    "url" : pwRoot + "website/server.php",
                    // the `data` function is executed in the instance's scope
                    // the parameter is the node being loaded
                    // (may be -1, 0, or undefined when loading the root nodes)
                    "data" : function (n) {
                        // the result is fed to the AJAX request `data` option
                        return {
                            "operation" : "get_children",
                            "id" : n.attr ? n.attr("id").replace("node_","") : 1
                        };
                    }
                }
            },
            // the UI plugin - it handles selecting/deselecting/hovering nodes
            "ui" : {
                // this makes the node with ID node_4 selected onload
                "initially_select" : 2 //[ "node_2" ]
            },
            // the core plugin - not many options here
            "core" : {
                // just open those two nodes up
                // as this is an AJAX enabled tree, both will be downloaded from the server
                "initially_open" : [ "node_2" , "node_3" ]
            }
        })
        .bind("create.jstree", function (e, data) {

            $.post(
                pwRoot + "website/server.php",
                {
                    "operation" : "create_node",
                    "id" : data.rslt.parent.attr("id").replace("node_",""),
                    "position" : data.rslt.position,
                    "title" : data.rslt.name,
                    "type" : data.rslt.obj.attr("rel")
                },
                function (r) {
                    if(r.status) {

                        // UPDATE COMPLETE
                        // update page seo url and template

                        $.ajax({
                            url: 'website/json/pagecreate.json.php',
                            data: 'pag_id=' + r.id,
                            type: 'GET',
                            success: function( data ) {

                                var result = JSON.parse(data);

                                $.msgGrowl ({
                                    type: result.type
                                    , title: result.title
                                    , text: result.description
                                });

                            },
                            error: function(x,e) {
                                throwAjaxError(x, e);
                            }
                        });

                        $(data.rslt.obj).attr("id", "node_" + r.id);
                    }
                    else {
                        $.jstree.rollback(data.rlbk);
                    }
                }
            );
        })
        .bind("remove.jstree", function (e, data) {

            var pageID = data.rslt.obj.attr("id").replace("node_","");

//		if (!confirm('Delete Page')) {
//			
//			$('#siteMap').jstree('refresh',-1);
//			return false;
//		}

            data.rslt.obj.each(function () {
                $.ajax({
                    async : false,
                    type: 'POST',
                    url: pwRoot + "website/server.php",
                    data : {
                        "operation" : "remove_node",
                        "id" : this.id.replace("node_","")
                    },
                    success : function (r) {

                        $.ajax({
                            url: 'website/json/pagedelete.json.php',
                            data: 'pag_id=' + pageID,
                            type: 'GET',
                            success: function( data ) {

                                var result = JSON.parse(data);

                                $.msgGrowl ({
                                    type: result.type
                                    , title: result.title
                                    , text: result.description
                                });

                            },
                            error: function(x,e) {
                                throwAjaxError(x, e);
                            }
                        });

                        data.inst.refresh();

                    }
                });
            });
        })
        .bind("rename.jstree", function (e, data) {
            $.post(
                pwRoot + "website/server.php",
                {
                    "operation" : "rename_node",
                    "id" : data.rslt.obj.attr("id").replace("node_",""),
                    "title" : data.rslt.new_name
                },
                function (r) {
                    if(!r.status) {
                        $.jstree.rollback(data.rlbk);
                    } else {

                        // RENAME COMPLETE - RESET SEO

                    }
                }
            );
        })
        .bind("move_node.jstree", function (e, data) {
            data.rslt.o.each(function (i) {
                $.ajax({
                    async : false,
                    type: 'POST',
                    url: pwRoot + "website/server.php",
                    data : {
                        "operation" : "move_node",

                        "id" : $(this).attr("id").replace("node_",""),
                        "ref" : data.rslt.cr === -1 ? 1 : data.rslt.np.attr("id").replace("node_",""),
                        "position" : data.rslt.cp + i,
                        "title" : data.rslt.name,
                        "copy" : data.rslt.cy ? 1 : 0
                    },
                    success : function (r) {
                        if(!r.status) {
                            $.jstree.rollback(data.rlbk);
                        }
                        else {
                            $(data.rslt.oc).attr("id", "node_" + r.id);
                            if(data.rslt.cy && $(data.rslt.oc).children("UL").length) {
                                data.inst.refresh(data.inst._get_parent(data.rslt.oc));
                            }
                        }
                        $("#analyze").click();
                    }
                });
            });
        })
        .bind("search.jstree", function (e, data) {

            $('#treesearch').parent().parent().removeClass('error');

            if (data.rslt.nodes.length > 0) {
                //$("#siteMap").jstree('open_all');
            } else {

                $('#treesearch').parent().parent().addClass('error');

                //alert("Found " + data.rslt.nodes.length + " nodes matching '" + data.rslt.str + "'.");
            }
        });


    var to = false;
    $('#treesearch').keyup(function () {

        $("#siteMap").jstree('open_all');

        if(to) { clearTimeout(to); }
        to = setTimeout(function () {

            var v = $('#treesearch').val();

            $("#siteMap").jstree("search",v);


            //$('#siteMap').jstree(true).search(v);
        }, 250);
    });

//	$("#mmenu input").click(function () {
//		switch(this.id) {
//			case "add_default":
//			case "add_folder":
//				$("#siteMap").jstree("create", null, "last", { "attr" : { "rel" : this.id.toString().replace("add_", "") } });
//				break;
//			case "search":
//				$("#siteMap").jstree("search", document.getElementById("text").value);
//				break;
//			case "text": break;
//			default:
//				$("#siteMap").jstree(this.id);
//				break;
//		}
//	});	

    $('#addSitemapPage').click(function(e){
        e.preventDefault();
        $("#siteMap").jstree("create", null, "last", { "attr" : { "rel" : this.id.toString().replace("add_", "") } });
    });

    $('#deleteSitemapPage').click(function(e){
        e.preventDefault();

        $.msgAlert ({
            type: 'warning'
            , title: 'Delete This Website Page'
            , text: 'Are you sure you wish to permanently remove this page and all subsequent pages?'
            , callback: function () {

                $("#siteMap").jstree('remove');

            }
        });

    });

    $('#renameSitemapPage').click(function(e){
        e.preventDefault();
        $("#siteMap").jstree('rename');
    });

});