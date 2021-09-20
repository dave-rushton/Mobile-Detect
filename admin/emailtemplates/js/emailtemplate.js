$(function() {

    var form = $("#userTable");

    $(".delEmaTmpBtn").click(function(e) {
        e.preventDefault();
        $.msgAlert ({
            type: 'warning'
            , title: 'Delete This Employee'
            , text: 'Are you sure you wish to permanently remove this project from the database?'
            , callback: function () {

                $.ajax({
                    url: "emailtemplates/emailtemplate_script.php",
                    data: 'action=delete&ajax=true&' + $(".delEmaTmpBtn").data("emt_id"),
                    type: 'POST',
                    async: false,
                    success: function( data ) {

                        var result = JSON.parse(data);

                        $.msgGrowl ({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });

                        window.location = form.data("returnurl");
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