var structureToAttributeForm;

$(function(){

    structureToAttributeForm = $('#structureToAttributeForm');

    selectnav('mparentID', {
        name: 'str_id',
        label: 'Select Parent',
        nested: true,
        indent: '-'
    });


    structureToAttributeForm.submit(function(e){

        e.preventDefault();

        $.msgAlert ({
            type: 'warning'
            , title: 'Covert Structure To Product Group'
            , text: 'Are you sure you wish to convert this structure branch to an option value in Product Groups?'
            , callback: function () {

                $.ajax({
                    url: 'products/structure_script.php',
                    data: 'action=convertstructure&' + structureToAttributeForm.serialize(),
                    type: 'POST',
                    async: false,
                    success: function( data ) {

                        console.log(data);

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

    })

});