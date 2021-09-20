var htAccessForm;

$(function(){

    htAccessForm = $('#htAccessForm');

    htAccessForm.submit(function(e){

        e.preventDefault();

        if (htAccessForm.valid()) {

            $.ajax({
                url: 'website/htaccess_script.php',
                data: 'action=update&ajax=true&hta_id=0&' + htAccessForm.serialize(),
                type: 'POST',
                async: false,
                success: function( data ) {

                    var result = JSON.parse(data);

                    $.msgGrowl ({
                        type: result.type
                        , title: result.title
                        , text: result.description
                    });

                    getHtAccess();

                    $('[name="frmurl"]', htAccessForm).val('').focus();
                    $('[name="to_url"]', htAccessForm).val('');
                    $('[name="htaobj"]', htAccessForm).val('');

                },
                error: function (x, e) {
                    throwAjaxError(x, e);
                }
            });

        } else {
            $('.help-inline').hide();
        }

    });

    $('#htAccessBody').on('click', '.deleteAccessLink', function (e) {

        e.preventDefault();

        thisRow = $(this).parent().parent();

        var htaId = $(this).data('hta_id');

        $.msgAlert ({
            type: 'warning'
            , title: 'Delete This Access Record'
            , text: 'Are you sure you wish to permanently remove this access record from the database?'
            , callback: function () {

                $.ajax({
                    url: 'website/htaccess_script.php',
                    data: 'action=delete&ajax=true&hta_id=' + htaId,
                    type: 'POST',
                    async: false,
                    success: function( data ) {

                        var result = JSON.parse(data);

                        $.msgGrowl ({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });

                        thisRow.fadeOut();

                    },
                    error: function (x, e) {
                        throwAjaxError(x, e);
                    }
                });

            }
        });

    });

    $('#htAccessBody').sortable({
        handle: ".resortAccessLink",
        stop: function(){

            $('#htAccessBody').block({ message: 'UPDATING : PLEASE WAIT' });

            var resortFiles = $('#htAccessBody').find('.resortAccessLink');

            var SrtOrd = '';

            resortFiles.each(function(){
                SrtOrd += (SrtOrd == '') ? $(this).data('hta_id') : ','+$(this).data('hta_id');
            });

            $.ajax({
                url: 'website/htaccess_script.php',
                data: 'action=resort&srtord=' + SrtOrd,
                type: 'GET',
                async: false,
                success: function( data ) {

                    $.msgGrowl ({
                        type: 'success'
                        , title: 'Access Re-Ordered'
                        , text: 'Access Re-Ordered'
                    });

                    $('#htAccessBody').unblock();

                },
                error: function (x, e) {
                    throwAjaxError(x, e);
                }
            });

        }
    });

    $('#rebuildFile').click(function(e){

        e.preventDefault();

        $.msgAlert ({
            type: 'warning'
            , title: 'Rebuild htAccess file'
            , text: 'Are you sure you wish to rebuild the htAccess file?'
            , callback: function () {

                $.ajax({
                    url: 'website/htaccess_script.php',
                    data: 'action=rebuild&ajax=true&hta_id=0',
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

    });


    $('#importForm').submit(function(e){

        $('#importForm').block({ message: 'IMPORTING : PLEASE WAIT' });

        e.preventDefault();

        var data;

        data = new FormData();
        data.append('file', $('#file')[0].files[0]);

        $.ajax({
            url: 'website/import.htaccess.php',
            data: data,
            processData: false,
            type: 'POST',
            contentType: false,
            aSync: false,
            success: function (data) {

                console.log(data);

                getHtAccess();

                $('#importForm').unblock();

            },
            error: function (x, e) {

                throwAjaxError(x, e);

                $('#importFailed').slideDown();

            }
        });

    });

    $('#updateAllhtAccess').click(function(e){

        $('#htAccessBody').block({ message: 'UPDATING : PLEASE WAIT' });

        e.preventDefault();

        var sendArray = [];

        var htRows = $('#htAccessBody').find('tr');

        htRows.each(function(e){

            htRec = {
                hta_id : $(this).find('.deleteAccessLink').data('hta_id'),
                frmurl : $(this).find('[name="frmurl[]"]').val(),
                to_url : $(this).find('[name="to_url[]"]').val()
            }

            sendArray.push(htRec);

        });

        $.ajax({
            url: 'website/htaccess_script.php',
            data: 'action=updateall&hta_id=0&jsondata=' + JSON.stringify(sendArray),
            type: 'POST',
            aSync: false,
            success: function (data) {

                console.log( data );

                $('#htAccessBody').unblock();

            },
            error: function (x, e) {

                throwAjaxError(x, e);

                $('#importFailed').slideDown();

            }
        });

    });

    $('[name="frmurl"]', htAccessForm).val('').focus();

    getHtAccess();

});

function getHtAccess() {

    $.ajax({
        url: 'website/htaccess_script.php',
        data: 'action=select',
        type: 'POST',
        async: false,
        success: function( data ) {

            var result = JSON.parse(data);

            var resultHTML = '';

            for (i=0;i<result.length;i++) {

                resultHTML += '<tr>';
                resultHTML += '<td>'+result[i].hta_id+'</td>';
                resultHTML += '<td><input name="frmurl[]" value="'+result[i].frmurl+'" style="width: 100%"></td>';
                resultHTML += '<td><input name="to_url[]" value="'+result[i].to_url+'" style="width: 100%"></td>';
                resultHTML += '<td style="text-align: right">';
                resultHTML += '<a href="#" class="btn btn-mini btn-danger deleteAccessLink" data-hta_id="'+result[i].hta_id+'"><i class="icon icon-remove"></i></a> ';
                resultHTML += '<a href="#" class="btn btn-mini btn-warning resortAccessLink" data-hta_id="'+result[i].hta_id+'"><i class="icon icon-reorder"></i></a>';
                resultHTML += '</td>';
                resultHTML += '</tr>';

            }

            $('#htAccessBody').html( resultHTML );


            $('.scrollarea').slimScroll({
                height: '400px'
            });

        },
        error: function (x, e) {
            throwAjaxError(x, e);
        }
    });

}