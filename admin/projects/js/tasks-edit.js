$(function () {

    $('#taskForm').validate({
        rules: {
            btkttl: {
                minlength: 2,
                required: true
            }
        }
		,
        focusCleanup: false,

        highlight: function (label) {
            $(label).closest('.control-group').removeClass('success').addClass('error');
        },
        success: function (label) {
            label.text('OK!').addClass('valid').closest('.control-group').addClass('success');
        },
        errorPlacement: function (error, element) {
            error.appendTo(element.parents('.controls'));
        },
		submitHandler: function(form) {
			
		}
    });

    $('.form').eq(0).find('input').eq(0).focus();

	$('#taskForm').submit(function(){
	
		if ($(this).valid()) {
			
			$('#taskFormRow').block({ message: 'Updating' });
			
//			alert( $('#taskForm').attr("action")+'?ajax=true&' + $('#taskForm').serialize() );
			
			$.ajax({
				url: $('#taskForm').attr("action"),
				data: 'action=update&ajax=true&' + $('#taskForm').serialize(),
				type: 'POST',
				async: false,
				success: function( data ) {
					
//					alert(data);
					
					var result = JSON.parse(data);
					
					$.msgGrowl ({
						type: result.type
						, title: result.title
						, text: result.description
					});
					
					$('#id', $('#taskForm') ).val( result.id );

				},
				error: function (x, e) {
					throwAjaxError(x, e);
				}
			});

			$('#taskFormRow').unblock();
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
	
	$('#deleteTaskBtn').click(function (e) {
		e.preventDefault();
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Task'
			, text: 'Are you sure you wish to permanently remove this task from the database?'
			, callback: function () {
				
				$.ajax({
					url: $('#taskForm').attr("action"),
					data: 'action=delete&ajax=true&' + $('#taskForm').serialize(),
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						window.location = $('#taskForm').data("returnurl");
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