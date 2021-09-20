$(function () {

    $(".icheck-me").each(function () {
        var $el = $(this);
        var skin = ($el.attr('data-skin') !== undefined) ? "_" + $el.attr('data-skin') : "",
            color = ($el.attr('data-color') !== undefined) ? "-" + $el.attr('data-color') : "";

        var opt = {
            checkboxClass: 'icheckbox' + skin + color,
            radioClass: 'iradio' + skin + color,
            increaseArea: "10%"
        }

        $el.iCheck(opt);
    });

    $('.viewMessageLink').click(function () {

        $.ajax({
            url: 'attributes/ajax/message_valueset.php',
            data: 'atr_id=' + $(this).data('atr_id') + '&tblnam=' + $(this).data('tblnam') + '&tbl_id=' + $(this).data('tbl_id'),
            type: 'GET',
            async: false,
            success: function (data) {

                $('#messageFields').html(data);

            },
            error: function (x, e) {
                throwAjaxError(e, x);
            }
        });

    });

    $(".tasklist").on("is.Changed", 'input[type=checkbox]', function () {
        $(this).parents("li").toggleClass("done");
		
		if ( $(this).parents("li").hasClass('done') ) {
			
			$.ajax({
				url: 'system/messages_script.php',
				data: 'action=update&msg_id=' + $(this).data('msg_id') + '&sta_id=1',
				type: 'GET',
				async: false,
				success: function (data) {
					
					var result = JSON.parse(data);
					
					$.msgGrowl ({
						type: result.type
						, title: result.title
						, text: result.description
					});
					
				},
				error: function (x, e) {
					throwAjaxError(e, x);
				}
			});
			
		} else {
			
			$.ajax({
				url: 'system/messages_script.php',
				data: 'action=update&msg_id=' + $(this).data('msg_id') + '&sta_id=0',
				type: 'GET',
				async: false,
				success: function (data) {
					
					var result = JSON.parse(data);
					
					$.msgGrowl ({
						type: result.type
						, title: result.title
						, text: result.description
					});
					
				},
				error: function (x, e) {
					throwAjaxError(e, x);
				}
			});
			
		}
		
    });

//    if ($("#new-task .select2-me").length > 0) {
//        function formatIcons(option) {
//            if (!option.id) return option.text;
//            return "<i class='" + option.text + "'></i> ." + option.text;
//        }
//        $("#new-task .select2-me").select2({
//            formatResult: formatIcons,
//            formatSelection: formatIcons,
//            escapeMarkup: function (m) {
//                return m;
//            }
//        });
//    }

    $(".tasklist").on('click', '.task-bookmark', function (e) {
        var $el = $(this),
            $lielement = $(this).parents('li'),
            $ulelement = $(this).parents('ul');
        e.preventDefault();
        e.stopPropagation();
        $lielement.toggleClass('bookmarked');

        if ($lielement.hasClass('bookmarked')) {
            $lielement.fadeOut(200, function () {
                $lielement.prependTo($ulelement).fadeIn();
            });
			
			$.ajax({
				url: 'system/messages_script.php',
				data: 'action=update&msg_id=' + $(this).data('msg_id') + '&sta_id=2',
				type: 'GET',
				async: false,
				success: function (data) {
					
					var result = JSON.parse(data);
					
					$.msgGrowl ({
						type: result.type
						, title: result.title
						, text: result.description
					});
					
				},
				error: function (x, e) {
					throwAjaxError(e, x);
				}
			});
			
        } else {
            if ($ulelement.find('.bookmarked').length > 0) {
                $lielement.fadeOut(200, function () {
                    $lielement.insertAfter($ulelement.find('.bookmarked').last()).fadeIn();
                });
            } else {
                $lielement.fadeOut(200, function () {
                    $lielement.prependTo($ulelement).fadeIn();
                });
            }
			
			$.ajax({
				url: 'system/messages_script.php',
				data: 'action=update&msg_id=' + $(this).data('msg_id') + '&sta_id=0',
				type: 'GET',
				async: false,
				success: function (data) {
					
					var result = JSON.parse(data);
					
					$.msgGrowl ({
						type: result.type
						, title: result.title
						, text: result.description
					});
					
				},
				error: function (x, e) {
					throwAjaxError(e, x);
				}
			});
        }
    });

    $(".tasklist").on('click', '.task-delete', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var $el = $(this);
        $el.parents("li").fadeOut();
		
		$.ajax({
			url: 'system/messages_script.php',
			data: 'action=delete&msg_id=' + $(this).data('msg_id'),
			type: 'GET',
			async: false,
			success: function (data) {
				
				var result = JSON.parse(data);
				
				$.msgGrowl ({
					type: result.type
					, title: result.title
					, text: result.description
				});
				
			},
			error: function (x, e) {
				throwAjaxError(e, x);
			}
		});
			
    });

    $(".tasklist").sortable({
        items: "li",
        opacity: 0.7,
        placeholder: 'widget-placeholder-2',
        forcePlaceholderSize: true,
        tolerance: "pointer"
    });


});