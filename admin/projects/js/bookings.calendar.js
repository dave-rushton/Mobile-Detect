var eventSourceArray = [];
var calendar = null;

$(document)
    .ready(function () {

	$('#placeSelect').change(function(){
		$('#Tbl_ID').val( $(this).val() );
		bindCalendar();	
	});
	
	
	$('.calFilter').change(function(){		
		bindCalendar();
	});

    $('#bookingForm')
        .validate({
        rules: {
            boodsc: {
                minlength: 2,
                required: true
            }
        },
        focusCleanup: false,

        highlight: function (label) {
            $(label)
                .closest('.control-group')
                .removeClass('success')
                .addClass('error');
        },
        success: function (label) {
            label.text('OK!')
                .addClass('valid')
                .closest('.control-group')
                .addClass('success');
        },
        errorPlacement: function (error, element) {
            error.appendTo(element.parents('.controls'));
        },
        submitHandler: function (form) {

        }
    });

    $('#bookingForm')
        .submit(function () {

        if ($(this)
            .valid()) {

            $('#bookingForm')
                .block({
                message: 'Updating'
            });

//			alert( 'action=update&ajax=true&' + $('#bookingForm').serialize() );

            $.ajax({
                url: $('#bookingForm').attr("action"),
                data: 'action=update&ajax=true&' + $('#bookingForm').serialize(),
                type: 'POST',
                async: false,
                success: function (data) {

//					alert(data);

                    var result = JSON.parse(data);

                    $.msgGrowl({
                        type: result.type,
                        title: result.title,
                        text: result.description
                    });

                    if (result.type = 'success') {
						
						calendar.fullCalendar( 'refetchEvents' );
						
//                        calendar.fullCalendar('renderEvent', {
//							id: result.id,
//                            title: $('#BooDsc')
//                                .val(),
//                            start: $('#BegDat')
//                                .val(),
//                            end: $('#EndDat')
//                                .val(),
//                            allDay: ($('#allDayCB:checked', $('#bookingForm')).length > 0) ? true : false,
//                            eventBackgroundColor: '#ccc'
//                        },
//                        true // make the event "stick"
//                        );

                        $('#cancelBookingLink')
                            .click();

                    }

                },
                error: function (x, e) {
                    throwAjaxError(x, e);
                }
            });

            $('#bookingForm')
                .unblock();
        } else {
            $.msgGrowl({
                type: 'error',
                title: 'Invalid Form',
                text: 'There is an error in the form'
            });
        }
        return false;
    });

	$('#placeSelect').change();

    $('#datepicker-inline')
        .datepicker();
    $('#datepicker-inline2')
        .datepicker();
		
		
	$('#taskList li.external-event').each(function() {		
		
		var eventObject = {
			title: $.trim($(this).text()),
			btk_id: $.trim($(this).data('btk_id')),
			btkdur: $.trim($(this).data('btkdur'))
		};
			
		$(this).data('eventObject', eventObject);
			
		$(this).draggable({
			zIndex: 999,
			revert: true,
			revertDuration: 0
		});		
	});	
	
	//
	// set up booking modal
	//
	
	$('#BegTim').timebox();
	$('#EndTim').timebox();
	
	$('#allDayCB').change(function(){
		if ( $(this).is(':checked') ) $('#allDayDiv').hide(); else $('#allDayDiv').show();
	});

});

function bindCalendar() {

//	alert( $('#webRootUrl').val() + '/projects/json/events.json.php' );

//	alert( $('#placeSelect').val() );

	eventSourceArray.length = 0;

//	$('.calFilter:checked').each(function(){
//		
//		var currentVal = $(this).val();
	
		eventSourceArray.push(	
		{
			url: $('#webRootUrl').val() + '/projects/json/events.json.php',
			type: 'GET',
			data: {
				tblnam: 'VEN',
				tbl_id: $('#placeSelect').val()
			},
			success: function (data) {
				
				$.msgGrowl({
					type: 'success',
					title: 'Bookings Received',
					text: 'Received bookings'
				});
			},
			error: function () {
				
				$.msgGrowl({
					type: 'error',
					title: 'Failure',
					text: 'Failed to receive bookings'
				});
			},
			color: $(this).data('bgcolor'),
			textColor: $(this).data('color')
		});
	
//	});
	
//	alert(eventSourceArray.toSource());

	calendar = $('#calendar-holder');
	
	calendar.fullCalendar('destroy');
	
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    calendar.fullCalendar({
        header: {
            left: 'prev, next',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
		firstDay: 1,
        selectable: true,
        selectHelper: true,
		droppable: true,
		defaultEventMinutes: 60,
		weekMode: 'variable',
        //			ignoreTimezone: true,
        //allDaySlot: false,
        slotMinutes: 15,
        firstHour: 8,
        //			minTime: 6,
        //			maxTime: 20,
        aspectRatio: 1,
        defaultView: 'agendaWeek',
		columnFormat: {
			month: 'ddd',    // Mon
			week: 'ddd d/M', // Mon 9/7
			day: 'dddd d/M'  // Monday 9/7
		},
        eventClick: function (calEvent, jsEvent, view) {

			if ( calEvent.source.name != 'task' ) {

				$('#Boo_ID')
					.val(calEvent.id);
				$('#RemTim')
					.val(calEvent.remtim);
				$('#TblNam')
					.val(calEvent.tblnam);
				$('#Tbl_ID')
					.val(calEvent.tbl_id);
				$('#BooDsc')
					.val(calEvent.title);
					
				$('#Btk_ID')
                .val( 0 );
					
				$('#ActDat')
					.val( calEvent.start.getFullYear() + '-' + twoDigits(1 + calEvent.start.getUTCMonth()) + '-' + calEvent.start.getDate() );
				$('#BegDat')
					.val(calEvent.start.getFullYear() + '-' + twoDigits(1 + calEvent.start.getUTCMonth()) + '-' + calEvent.start.getDate() + ' ' + twoDigits(calEvent.start.getHours()) + ':' + twoDigits(calEvent.start.getMinutes()) + ':00');
				$('#BegTim')	
					.val( twoDigits(calEvent.start.getHours()) + ':' + twoDigits(calEvent.start.getMinutes()) );
				if (calEvent.end) {
					$('#EndDat')
						.val(calEvent.end.getFullYear() + '-' + twoDigits(1 + calEvent.end.getUTCMonth()) + '-' + calEvent.end.getDate() + ' ' + twoDigits(calEvent.end.getHours()) + ':' + twoDigits(calEvent.end.getMinutes()) + ':00');
					$('#EndTim')	
						.val( twoDigits(calEvent.end.getHours()) + ':' + twoDigits(calEvent.end.getMinutes()) );
					
				} else {
					$('#EndDat').val( $('#BegDat').val() );
					$('#EndTim').val( $('#BegTim').val() );
				}
				
				$('#Sta_ID')
					.val(calEvent.sta_id);
//				$('#BooDsc')
//					.val(calEvent.boodsc);
				
				if ( calEvent.allDay ) { 
					$('#allDayCB').attr('checked', true); 
					$('#allDayDiv').hide(); 
				} else {
					$('#allDayCB').attr('checked', false); 
					$('#allDayDiv').show();
				}
	
				$('#addBookingLink')
					.click();
				
			}

        },
        eventResize: function (event, dayDelta, minuteDelta, revertFunc) {

            var begdat = event.start.getFullYear() + '-' + twoDigits(1 + event.start.getUTCMonth()) + '-' + event.start.getDate() + ' ' + twoDigits(event.start.getHours()) + ':' + event.start.getMinutes() + ':00';
            var enddat = event.end.getFullYear() + '-' + twoDigits(1 + event.end.getUTCMonth()) + '-' + event.end.getDate() + ' ' + twoDigits(event.end.getHours()) + ':' + event.end.getMinutes() + ':00';

            $.ajax({
                url: $('#bookingForm')
                    .attr("action"),
                data: 'action=update&ajax=true&boo_id=' + event.id + '&begdat=' + begdat + '&enddat=' + enddat,
                type: 'POST',
                async: false,
                success: function (data) {

                    var result = JSON.parse(data);

                    $.msgGrowl({
                        type: result.type,
                        title: result.title,
                        text: result.description
                    });

                    if (result.type = 'success') {

//                        calendar.fullCalendar('renderEvent', {
//                            title: $('#BooDsc')
//                                .val(),
//                            start: $('#BegDat')
//                                .val(),
//                            end: $('#EndDat')
//                                .val(),
//                            allDay: false,
//                            eventBackgroundColor: '#ccc'
//                        },
//                        true // make the event "stick"
//                        );
                    }

                },
                error: function (x, e) {
                    throwAjaxError(x, e);
                }
            });

        },
        eventDrop: function (event, dayDelta, minuteDelta, allDay, revertFunc) {
			
//			alert(
//				event.title + " was moved " +
//				dayDelta + " days and " +
//				minuteDelta + " minutes."
//			);
//			
//			alert(event.start + ' ' + event.end);
			
			var allday = (allDay) ? 1 : 0;
			
			var begdat = event.start.getFullYear() + '-' + twoDigits(1 + event.start.getMonth()) + '-' + event.start.getDate();
			var enddat = event.start.getFullYear() + '-' + twoDigits(1 + event.start.getMonth()) + '-' + event.start.getDate();
			
			if (allday == 1) {
				begdat += ' 00:00:00';
				enddat += ' 00:00:00';
			} else {
				begdat += ' ' + twoDigits(event.start.getHours()) + ':' + twoDigits(event.start.getMinutes()) + ':00';
				
				if (event.end) {
					enddat += ' ' + twoDigits(event.end.getHours()) + ':' + twoDigits(event.end.getMinutes()) + ':00';
				} else {
					
					var hlddat = new Date();
						hlddat.setTime( event.start.getTime()+hours );
						
					event.enddat = hlddat;
					
					enddat = hlddat.getFullYear() + '-' + twoDigits(1 + hlddat.getMonth()) + '-' + hlddat.getDate() + ' ' + twoDigits(hlddat.getHours()) + ':' + twoDigits(hlddat.getMinutes()) + ':00';
				}
				
			}
			
			//alert( 'action=update&ajax=true&boo_id=' + event.id + '&begdat=' + begdat + '&enddat=' + enddat );
            $.ajax({
                url: $('#bookingForm')
                    .attr("action"),
                data: 'action=update&ajax=true&boo_id=' + event.id + '&begdat=' + begdat + '&enddat=' + enddat,
                type: 'POST',
                async: false,
                success: function (data) {

                    var result = JSON.parse(data);

                    $.msgGrowl({
                        type: result.type,
                        title: result.title,
                        text: result.description
                    });

                    if (result.type = 'success') {

                    }

                },
                error: function (x, e) {
                    throwAjaxError(x, e);
                }
            });

        },
        dayClick: function (date, allDay, jsEvent, view) {

//            if (allDay) {
//                alert('Clicked on the entire day: ' + date);
//            } else {
//                alert('Clicked on the slot: ' + date);
//            }

            //alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);

            //alert('Current view: ' + view.name);

            // change the day's background color just for fun
            //$(this).css('background-color', 'red');

        },
        select: function (start, end, allDay) {
			
			alert('#');
			
			$('#Boo_ID')
                .val('0');
			$('#RemTim')
                .val('0');
            $('#BegDat')
                .val(start.getFullYear() + '-' + twoDigits(1 + start.getUTCMonth()) + '-' + start.getDate() + ' ' + twoDigits(start.getHours()) + ':' + twoDigits(start.getMinutes()) + ':00');
			$('#ActDat')
				.val( start.getFullYear() + '-' + twoDigits(1 + start.getUTCMonth()) + '-' + start.getDate() );
			$('#BegTim')	
				.val( twoDigits(start.getHours()) + ':' + twoDigits(start.getMinutes()) );
			
			$('#Btk_ID')
                .val( 0 );
			
			if (end) {
				$('#EndDat')
					.val(end.getFullYear() + '-' + twoDigits(1 + end.getUTCMonth()) + '-' + end.getDate() + ' ' + twoDigits(end.getHours()) + ':' + twoDigits(end.getMinutes()) + ':00');
				$('#EndTim')	
					.val( twoDigits(end.getHours()) + ':' + twoDigits(end.getMinutes()) );
				
			} else {
				$('#EndDat').val( $('#BegDat').val() );
				$('#EndTim').val( $('#BegTim').val() );
			}
			
			$('#Sta_ID')
                .val(0);
			$('#BooDsc')
                .val('');
			
			if ( allDay ) { 
				$('#allDayCB').attr('checked', true); 
				$('#allDayDiv').hide(); 
			} else {
				$('#allDayCB').attr('checked', false); 
				$('#allDayDiv').show();
			}
			
			$('#createBookingModal').modal('show');

//            $('#addBookingLink')
//                .click();

            calendar.fullCalendar('unselect');
        },
		drop: function(date, allDay) { // this function is called when something is dropped
			
			// retrieve the dropped element's stored Event Object
			var originalEventObject = $(this).data('eventObject');
				
			// we need to copy it, so that multiple events don't have a reference to the same object
			var copiedEventObject = $.extend({}, originalEventObject);
				
			// assign it the date that was reported
			copiedEventObject.start = date;
			copiedEventObject.allDay = allDay;
			
			$('#Boo_ID')
                .val('0');
            $('#BooDsc')
                .val( originalEventObject.title );
				

			$('#ActDat')
                .val(date.getFullYear() + '-' + twoDigits(1 + date.getUTCMonth()) + '-' + date.getDate() );
				
            $('#BegDat')
                .val(date.getFullYear() + '-' + twoDigits(1 + date.getUTCMonth()) + '-' + date.getDate() + ' ' + twoDigits(date.getHours()) + ':' + twoDigits(date.getMinutes()) + ':00');
			$('#BegTim')	
				.val( twoDigits(date.getHours()) + ':' + twoDigits(date.getMinutes()) );
			
			$('#Btk_ID')
                .val( originalEventObject.btk_id );
			
			// Calculate duration
			var addTime = parseInt(originalEventObject.btkdur) * 1000;
			
			date.setTime( date.getTime() + addTime );
				
            $('#EndDat')
                .val(date.getFullYear() + '-' + twoDigits(1 + date.getUTCMonth()) + '-' + date.getDate() + ' ' + twoDigits(date.getHours()) + ':' + twoDigits(date.getMinutes()) + ':00');
			$('#EndTim')	
					.val( twoDigits(date.getHours()) + ':' + twoDigits(date.getMinutes()) );
			
//			$('#allDayCB').attr('checked', false); 
//			$('#allDayDiv').show();
			
//			currentCalDate = calendar.fullCalendar('getDate');

//			loadPopup('createBookingModal');
			
			$('#addBookingLink')
                .click();
			
			$(this).remove();
			
		},
        editable: true,
        eventSources: eventSourceArray

    });
	
}

var minutes = 1000 * 60;
var hours = minutes * 60;
var days = hours * 24;
var years = days * 365;

function twoDigits(d) {
    if (0 <= d && d < 10) return "0" + d.toString();
    if (-10 < d && d < 0) return "-0" + (-1 * d)
        .toString();
    return d.toString();
}


function js2mysql(iActDat) {

    if (iActDat == null) {
        iActDat = new Date();
    }

    return iActDat.getUTCFullYear() + "-" + twoDigits(1 + iActDat.getUTCMonth()) + "-" + twoDigits(iActDat.getUTCDate()) + " " + twoDigits(iActDat.getUTCHours()) + ":" + twoDigits(iActDat.getUTCMinutes()) + ":" + twoDigits(iActDat.getUTCSeconds());

}