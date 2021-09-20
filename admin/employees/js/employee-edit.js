var employeeForm;

function getImageSizes(imgUrlElem, imgSizeElem) {
    var imgSizSelect = imgSizeElem;
    var holdSize = imgUrlElem.data('imgsiz');

    var sizes;
    var selected = "";
    selval = imgUrlElem.find(":selected").val(); //selected value
    sellen = imgUrlElem.find("[value='" + selval + "']").length;
    if (sellen > 0) {
        imgUrlElem.find("[value='" + selval + "']").each(function () {
            selected += $(this).data('imgsiz') + ",";
        });
        //convert to string to check for ,
        sizes = selected.toString();
        if (sizes.indexOf(',') > -1) {
            sizes = selected.split(',');
        } else {
            sizes = [selected];
        }
        var uniqueSize = [];
        $.each(sizes, function (i, el) {
            if ($.inArray(el, uniqueSize) === -1) uniqueSize.push(el);
        });

        resultHTML = '<option value="">Original</option>';
        var globSizes = $('.patchworks-imgsiz').data('imgsiz').split(',');

        for (i = 0; i < globSizes.length; i++) {
            if (globSizes[i].length > 0) resultHTML += '<option value="' + globSizes[i] + '">' + globSizes[i] + '</option>';
        }

        for (i = 0; i < uniqueSize.length; i++) {
            if (uniqueSize[i].length > 0 && uniqueSize[i] !== 'undefined') resultHTML += '<option value="' + uniqueSize[i] + '">' + uniqueSize[i] + '</option>';
        }
        imgSizSelect.html(resultHTML).val(holdSize);
    }
}

$(function(){

	employeeForm = $('#employeeForm');

    employeeForm.on('change', '#imgurl', function () {
        getImageSizes(employeeForm.find('#imgurl') , employeeForm.find('#imgsiz'));
    });

    getImageSizes(employeeForm.find('#imgurl') , employeeForm.find('#imgsiz'));

	$('#updateEmployeeBtn').click(function(e){
		e.preventDefault();
		employeeForm.submit();
	});
	
	employeeForm.submit(function(e){
	
		e.preventDefault();

        if ($(this).valid()) {

            $("#employeeBox").block({
                message: 'Updating',
                centerY: 0,
                centerX: 0,
                css: { top: '10px', left: '', right: '10px', border: '2px solid #a00' }
            });

            updateRecord();
        }
        else {
            $.msgGrowl ({
                type: 'error'
                , title: 'Invalid Form'
                , text: 'There is an error in the form'
            });
        }

        $("#employeeBox").unblock();
	
	});
	
	$('#deleteEmployeeBtn').click(function (e) {
		e.preventDefault();
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Employee'
			, text: 'Are you sure you wish to permanently remove this project from the database?'
			, callback: function () {
				
				$.ajax({
					url: employeeForm.attr("action"),
					data: 'action=delete&ajax=true&' + employeeForm.serialize(),
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						window.location = employeeForm.data("returnurl");
						
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

function updateRecord() {

    var fields = $(".customfield", employeeForm).serializeArray();
    var elementVariables = JSON.stringify(fields);
    var postData = encodeURIComponent(elementVariables);

    $.ajax({
        url: employeeForm.attr("action"),
        data: 'action=update&ajax=true&' + employeeForm.serialize() + '&ppltxt=' + postData,
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

                $('#id', employeeForm).val( result.id );

            } catch(Ex) {
                $.msgGrowl ({
                    type: 'error'
                    , title: 'Error'
                    , text: Ex
                });
            }

        },
        error: function (x, e) {

            throwAjaxError(x, e);

        }
    });
}