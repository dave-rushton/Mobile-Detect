var pwRoot;

$(function(){

    $('.submit-form').click(function(e){
        e.preventDefault();
        $(this).closest('form').submit();
    });

    pwRoot = $('#pwRoot').val();

    $('#ecommPropForm').submit(function(e){

        e.preventDefault();

        if ($(this).valid()) {

            $('#ecommPropForm').block({ message: 'Updating' });

            $.ajax({
                url: $('#ecommPropForm').attr("action"),
                data: 'action=update&ajax=true&' + $('#ecommPropForm').serialize(),
                type: 'POST',
                async: false,
                success: function( data ) {

//                    alert(data);

                    logConsole(data);

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

            $('#ecommPropForm').unblock();
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

});