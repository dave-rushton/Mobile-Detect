var structureForm, structureModalForm;



$(function(){



    $("#structureModal").on('shown.bs.modal', function () {

        $('[name="strnam"]', structureModalForm).focus();

    });



    structureForm = $('#structureForm');

    structureModalForm = $('#structureModalForm');



    structureForm.submit(function(e){



        e.preventDefault();



        $.ajax({

            url: 'products/structure_script.php',

            data: 'action=update&' + structureForm.serialize(),

            type: 'POST',

            async: false,

            success: function (data) {



                var result = JSON.parse(data);



                buildStructure();





            },

            error: function (x, e) {

                throwAjaxError(x, e);

            }

        });



    });



    $('#buildStructure').on('click', '.selectStructureBtn', function(e){

        e.preventDefault();



        var strID = $(this).data('str_id');



        $.ajax({

            url: 'products/structure_script.php',

            data: 'action=relatedproducts&str_id='+strID+'&eleadm=true',

            type: 'POST',

            async: false,

            success: function (data) {



                var result = JSON.parse(data);



                //var resultHTML = '<ul>';

                var resultHTML = '';



                for (i=0;i<result.length;i++) {



                    resultHTML += '<li class="relatedSort" data-rel_id="'+ result[i].rel_id +'">';

                    resultHTML += result[i].prtnam;



                    resultHTML += '<a href="#" class="btn btn-mini productSort"><i class="icon icon-reorder"></i></a>';

                    resultHTML += '<a href="#" class="btn btn-mini btn-danger productRemove" data-rel_id="'+result[i].rel_id+'"><i class="icon icon-remove"></i></a>';





                    resultHTML += '</li>';



                }



                //resultHTML += '</ul>';



                $('#subStructure').html(resultHTML);



            },

            error: function (x, e) {

                throwAjaxError(x, e);

            }

        });



    });



    $('#createStructureBtn').click(function(e){

        e.preventDefault();

        //structureForm.submit();



        e.preventDefault();

        $('[name="par_id"]', structureModalForm).val( $(this).data('str_id') );

        $('[name="str_id"]', structureModalForm).val(0);

        $('[name="strnam"]', structureModalForm).val('');

        $('[name="seourl"]', structureModalForm).val('');

        $('[name="srtord"]', structureModalForm).val(99);



        $('[name="keywrd"]', structureModalForm).val('');

        $('[name="keydsc"]', structureModalForm).val('');



        $('#structureModal').modal('show');



    });



    $('#buildStructure').on('click', '.addStructureBtn', function(e){



        e.preventDefault();

        $('[name="par_id"]', structureModalForm).val( $(this).data('str_id') );

        $('[name="str_id"]', structureModalForm).val(0);

        $('[name="strnam"]', structureModalForm).val('');

        $('[name="seourl"]', structureModalForm).val('');

        $('[name="srtord"]', structureModalForm).val(99);



        $('[name="keywrd"]', structureModalForm).val('');

        $('[name="keydsc"]', structureModalForm).val('');



        $('#structureModal').modal('show');





    });

    $('#buildStructure').on('click', '.editStructureBtn', function(e){



        e.preventDefault();



        $.ajax({

            url: 'products/structure_script.php',

            data: 'action=select&str_id=' + $(this).data('str_id'),

            type: 'POST',

            async: false,

            success: function (data) {



                var result = JSON.parse(data);



                $('[name="par_id"]', structureModalForm).val(result[0].par_id);

                $('[name="str_id"]', structureModalForm).val(result[0].str_id);

                $('[name="strnam"]', structureModalForm).val(result[0].strnam);

                $('[name="seourl"]', structureModalForm).val(result[0].seourl);

                $('[name="srtord"]', structureModalForm).val(result[0].srtord);



                $('[name="keywrd"]', structureModalForm).val(result[0].keywrd);

                $('[name="keydsc"]', structureModalForm).val(result[0].keydsc);



                $('#structureModal').modal('show');

                $('[name="strnam"]', structureModalForm).focus();



            },

            error: function (x, e) {

                throwAjaxError(x, e);

            }

        });



    });

    $('#buildStructure').on('click', '.deleteStructureBtn', function(e){



        var strID = $(this).data('str_id');



        $.msgAlert ({

            type: 'warning'

            , title: 'Delete This Image'

            , text: 'Are you sure you wish to permanently remove this image from the database?'

            , callback: function () {



                $.ajax({

                    url: 'products/structure_script.php',

                    data: 'action=deletestructure&str_id=' + strID,

                    type: 'POST',

                    async: false,

                    success: function (data) {



                        buildStructure();



                    },

                    error: function (x, e) {

                        throwAjaxError(x, e);

                    }

                });



            }

        });



        e.preventDefault();



    });



    $('#updateStructureBtn').click(function(e){



        e.preventDefault();



        $.ajax({

            url: 'products/structure_script.php',

            data: 'action=update&' + structureModalForm.serialize(),

            type: 'POST',

            async: false,

            success: function (data) {



                var result = JSON.parse(data);



                buildStructure();



                $('#structureModal').modal('hide');





            },

            error: function (x, e) {

                throwAjaxError(x, e);

            }

        });



    })



    $('[name="strnam"]', structureModalForm).on("keyup paste", function() {



        $('[name="seourl"]', structureModalForm).val( seoURL( $('[name="strnam"]', structureModalForm).val() ) );



    });



    $('#subStructure').sortable({

        handle: ".productSort",

        stop: function( event, ui ) {



            $('#subBlockOut').block({ message: 'Retrieving' });



            var relLst = '';



            $('.relatedSort', $('#subStructure')).each(function(){

                relLst += (relLst == '') ? $(this).data('rel_id') : ',' + $(this).data('rel_id');

            });



            $.ajax({

                url: 'system/related_script.php',

                data: 'action=resort&ajax=true&rel_id=' + relLst,

                type: 'POST',

                async: true,

                success: function( data ) {



                    var result = JSON.parse(data);



                    $.msgGrowl ({

                        type: result.type

                        , title: result.title

                        , text: result.description

                    });



                    $('#subBlockOut').unblock();



                },

                error: function (x, e) {

                    throwAjaxError(x, e);

                }

            });



        }

    });



    $('#subStructure').on('click', '.productRemove', function(e){



        var relID = $(this).data('rel_id');



        $.msgAlert ({

            type: 'warning'

            , title: 'Delete This product From category'

            , text: 'Are you sure you wish to permanently remove this product from this category?'

            , callback: function () {



                $.ajax({

                    url: 'system/related_script.php',

                    data: 'action=delete&rel_id=' + relID,

                    type: 'POST',

                    async: false,

                    success: function (data) {



                        buildStructure();



                    },

                    error: function (x, e) {

                        throwAjaxError(x, e);

                    }

                });



            }

        });



        e.preventDefault();



    });



    buildStructure();



});



function buildStructure() {



    $('#buildStructure').block({ message: 'Retrieving' });



    $.ajax({

        url: 'products/structure_script.php',

        data: 'action=structure&str_id=0&eleadm=true',

        type: 'POST',

        async: true,

        success: function (data) {



            $('#buildStructure').html(data);



            $('#buildStructure').find('a').each(function(){



                var btnHTML = '';

                btnHTML += '<a href="#" class="btn btn-mini btn-danger deleteStructureBtn" data-str_id="'+$(this).data('str_id')+'"><i class="icon icon-trash"></i></a>';

                btnHTML += '<a href="#" class="btn btn-mini btn-secondary editStructureBtn" data-str_id="'+$(this).data('str_id')+'"><i class="icon icon-pencil"></i></a>';

                btnHTML += '<a href="#" class="btn btn-mini btn-primary addStructureBtn" data-str_id="'+$(this).data('str_id')+'"><i class="icon icon-plus-sign"></i></a>';



                $(this).parent().prepend(btnHTML);



            });



            modalStructure();



            $('#buildStructure').unblock();



        },

        error: function (x, e) {

            throwAjaxError(x, e);

        }

    });



}





function modalStructure() {



    $.ajax({

        url: 'products/structure_script.php',

        data: 'action=structure&str_id=0&ele_id=mparentID&elecls=hide&eleadm=true',

        type: 'POST',

        async: true,

        success: function (data) {



            $('#modalStructure').html(data);



            selectnav('mparentID', {

                name: 'par_id',

                label: 'Select Parent',

                nested: true,

                indent: '-'

            });



            $('#mparentID').change();



        },

        error: function (x, e) {

            throwAjaxError(x, e);

        }

    });





}