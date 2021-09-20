
(function( $ ){
	
		$.fn.retina = function(retina_part) {
		// Set default retina file part to '-2x'
		// Eg. some_image.jpg will become some_image-2x.jpg
		var settings = {'retina_part': '-2x'};
		if(retina_part) jQuery.extend(settings, { 'retina_part': retina_part });
		if(window.devicePixelRatio >= 2) {
			this.each(function(index, element) {
				if(!$(element).attr('src')) return;

				var checkForRetina = new RegExp("(.+)("+settings['retina_part']+"\\.\\w{3,4})");
				if(checkForRetina.test($(element).attr('src'))) return;

				var new_image_src = $(element).attr('src').replace(/(.+)(\.\w{3,4})$/, "$1"+ settings['retina_part'] +"$2");
				$.ajax({url: new_image_src, type: "HEAD", success: function() {
					$(element).attr('src', new_image_src);
				}});
			});
		}
		return this;
	}
})( jQuery );

$(document).ready(function() {
	var mobile = false,
	tooltipOnlyForDesktop = true,
	notifyActivatedSelector = 'button-active';

	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
		mobile = true;
	}
	
	if(tooltipOnlyForDesktop)
	{
		if(!mobile)
		{
			$('[rel=tooltip]').tooltip();
		}
	}
	
	$(".retina-ready").retina("@2x");
	
});

$(window).resize(function() {
	// chosen resize bug
	resize_chosen();
	
	if ( $(window).width() < 1025 ) {
		
		$('form').not('.orientation-fixed').each(function(){
			$(this).removeClass('form-horizontal').addClass('form-vertical');
		});
		
	} else {
		
		$('form').not('.orientation-fixed').each(function(){
			$(this).removeClass('form-vertical').addClass('form-horizontal');
		});
		
	}
	
});

$(function(){
	
	if($('.form-validate').length > 0)
	{
		$('.form-validate').each(function(){
			var id = $(this).attr('id');
			$("#"+id).validate({
				errorElement:'span',
				onfocusout: false,
				onkeyup: false,
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
		});
	}

    //$(".content-slideUp").click(function (e) {
    //    e.preventDefault();
    //    var $el = $(this),
    //    content = $el.parents('.box').find(".box-content");
    //    content.slideToggle('fast', function(){
    //       $el.find("i").toggleClass('icon-angle-up').toggleClass("icon-angle-down");
    //       if(!$el.find("i").hasClass("icon-angle-up")){
    //        if(content.hasClass('scrollable')) slimScrollUpdate(content);
    //    } else {
    //        if(content.hasClass('scrollable')) destroySlimscroll(content);
    //    }
    //});
    //
    //});
	
});

function throwAjaxError(x, e) {

    if (x.status == 0) {
        $.msgGrowl({
            type: 'error',
            title: 'Offline',
            text: 'Check your network'
        });
    } else if (x.status == 404) {
        $.msgGrowl({
            type: 'error',
            title: 'Unknown URL',
            text: 'Contact your administrator'
        });
    } else if (x.status == 500) {
        $.msgGrowl({
            type: 'error',
            title: 'Fail',
            text: 'Server error'
        });
    } else if (e == 'timeout') {
        $.msgGrowl({
            type: 'error',
            title: 'Timeout',
            text: 'Check your network'
        });
    }

}

function logConsole(msg) {
	setTimeout(function() {
		throw new Error(msg);
	}, 0);
}

function seoURL(name) {
    name = name.toLowerCase(); // lowercase
    name = name.replace(/^\s+|\s+$/g, ''); // remove leading and trailing whitespaces
    name = name.replace(/&/g, 'and'); // convert (continuous) & to and
	name = name.replace(/\s+/g, '-'); // convert (continuous) whitespaces to one -
	name = name.replace("--", '-'); // convert (continuous) whitespaces to one -
	name = name.replace("--", '-'); // convert (continuous) whitespaces to one -
    name = name.replace(/[^0-9a-zA-Z-]/g, ''); // remove everything that is not [a-z09] or -
    return name;
}

function resize_chosen(){
	$('.chzn-container').each(function() {
		var $el = $(this);
		$el.css('width', $el.parent().width()+'px');
		$el.find(".chzn-drop").css('width', ($el.parent().width()-2)+'px');
		$el.find(".chzn-search input").css('width', ($el.parent().width()-37)+'px');
	});
}

function destroyChosen(iElement) {
	iElement.removeAttr("style", "").removeClass("chzn-done").data("chosen", null).next().remove();
}

function getQueryVariable(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? null : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function getJSONvariable (iNeedle, iHaystack) {

    try {

        var objectVariables = JSON.parse(iHaystack);

        for (i = 0; i < objectVariables.length; i++) {

            if (objectVariables[i].name == iNeedle) return objectVariables[i].value;

        }

    } catch (err) {

    }

    return '';

}