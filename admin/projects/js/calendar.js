
var minutes = 1000 * 60;
var hours = minutes * 60;
var days = hours * 24;
var years = days * 365;

var eventSourceArray = [];
var calendar = null;
var bookingForm;

var ttto;

var currentMousePos = { x: -1, y: -1 };
$(document).mousemove(function(event) {
	currentMousePos.x = event.pageX;
	currentMousePos.y = event.pageY;
});

$(function(){
	
	bookingForm = $('#bookingForm');
	
	bindCalendar();	
	
	$('.colorpick').colorpicker();
	
	$('[name="begdatdsp"], [name="enddatdsp"]', bookingForm).datepicker({ format: 'yyyy-mm-dd', weekStart: 1 });
	
	$('#newBookingBtn').click(function(e){
	
		e.preventDefault();
	
		$('#calendarScreen').fadeOut(400, function(){
			$('#bookingFormScreen').fadeIn(400);
		});
	});
	
	$('#cancelBooking').click(function(e){
	
		e.preventDefault();
	
		$('#bookingFormScreen').fadeOut(400, function(){
			$('#calendarScreen').fadeIn(400);
		});
	});
	
	$('#deleteBooking').click(function(e){
	
		e.preventDefault();
		
		var booId = $('[name="boo_id"]', bookingForm).val();
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Booking'
			, text: 'Are you sure you wish to permanently remove this booking from the database?'
			, callback: function () {
		
				$.ajax({
					url: 'projects/bookings_script.php',
					data: 'action=delete&ajax=true&boo_id=' + booId,
					type: 'POST',
					async: false,
					success: function (data) {
						
						$('#bookingFormScreen').fadeOut(400, function(){
							$('#calendarScreen').fadeIn(400, function(){
								calendar.fullCalendar( 'refetchEvents' );
							});
						});
						
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
			}
		});
		
	});
	
	$('[name="tbl_id"]', bookingForm).change(function(){
		
		$('[name="boodsc"]', bookingForm).val( $('[name="tbl_id"] option:selected', bookingForm).text() );
		$('[name="boocol"]', bookingForm).val( $('[name="tbl_id"] option:selected', bookingForm).data('placol') );
		
	});
	
	$('#updateBooking').click(function(e){
		e.preventDefault();
		bookingForm.submit();
	});
	
	
	bookingForm.submit(function(e){
	
		e.preventDefault();
		
		$('[name="begdat"]', bookingForm).val( $('[name="begdatdsp"]', bookingForm).val() + ' ' + $('[name="begtim"]', bookingForm).val() );
		$('[name="enddat"]', bookingForm).val( $('[name="enddatdsp"]', bookingForm).val() + ' ' + $('[name="endtim"]', bookingForm).val() );
		
		//alert( 'action=update&ajax=true&' + bookingForm.serialize() );
		
		$.ajax({
			url: 'projects/bookings_script.php',
			data: 'action=update&ajax=true&' + bookingForm.serialize(),
			type: 'POST',
			async: false,
			success: function (data) {
				
				$('#bookingFormScreen').fadeOut(400, function(){
					$('#calendarScreen').fadeIn(400, function(){
						calendar.fullCalendar( 'refetchEvents' );
					});
				});

				var result = JSON.parse(data);
				
				$.msgGrowl({
					type: result.type,
					title: result.title,
					text: result.description
				});

			},
			error: function (x, e) {
				throwAjaxError(x, e);
			}
		});
		
	});


    $('[name="bootagselect"]', $('#bookingForm')).each(function(){
        var $el = $(this);
        var search = ($el.attr("data-nosearch") === "true") ? true : false,
            opt = {};
        if(search) opt.disable_search_threshold = 9999999;
        $el.chosen(opt);

    });

	$('[name="emp_id[]"]').change(function(){

		bindCalendar();

	});
	
});

function bindCalendar() {
	
	$('.external-event').each(function() {		
		
		var eventObject = {
			title: $.trim($(this).data('planam')),
			pla_id: $.trim($(this).data('pla_id')),
			placol: $.trim($(this).data('placol'))
		};
			
		$(this).data('eventObject', eventObject);
			
		$(this).draggable({
			zIndex: 999,
			revert: true,
			revertDuration: 0,
			helper: "clone"
		});		
	});
	
	eventSourceArray.length = 0;

	$('[name="emp_id[]"]:checked').each(function(){

		eventSourceArray.push(
			{
				url: 'projects/json/events.json.php',
				type: 'GET',
				data: {
					tblnam: 'PROJECT',
//				tbl_id: $('#placeSelect').val()
					ref_id: $(this).val() //$('#placeSelect').val()
				},
				success: function (data) {

				//$.msgGrowl({
				//	type: 'success',
				//	title: 'Bookings Received',
				//	text: 'Received bookings'
				//});
				},
				error: function () {

					$.msgGrowl({
						type: 'error',
						title: 'Failure',
						text: 'Failed to receive bookings'
					});
				},
				//color: $(this).data('boocol'),
				//textColor: '#000000' //$(this).data('boocol')
			});


	})

//		eventSourceArray.push(
//		{
//			url: 'projects/json/events.json.php',
//			type: 'GET',
//			data: {
//				tblnam: 'PROJECT',
////				tbl_id: $('#placeSelect').val()
//				ref_id: 2 //$('#placeSelect').val()
//			},
//			success: function (data) {
//
////				$.msgGrowl({
////					type: 'success',
////					title: 'Bookings Received',
////					text: 'Received bookings'
////				});
//			},
//			error: function () {
//
//				$.msgGrowl({
//					type: 'error',
//					title: 'Failure',
//					text: 'Failed to receive bookings'
//				});
//			},
//			//color: $(this).data('boocol'),
//			//textColor: '#000000' //$(this).data('boocol')
//		});
	
//	});
	
//	alert(eventSourceArray.toSource());

	calendar = $('#calendar-holder');
	
	calendar.fullCalendar('destroy');
	
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
	
	d = (!getQueryVariable('d')) ? d : getQueryVariable('d');
	m = (!getQueryVariable('m')) ? m : getQueryVariable('m')-1;
	y = (!getQueryVariable('y')) ? y : getQueryVariable('y');
	
    calendar.fullCalendar({
		year: y,
		month: m,
		date: d,
        header: {
            left: 'prev, next',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,today'
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
        minTime: 7,
        maxTime: 23,
        aspectRatio: 0.5,
        defaultView: 'agendaWeek',
		columnFormat: {
			month: 'ddd',    // Mon
			week: 'ddd d/M', // Mon 9/7
			day: 'dddd d/M'  // Monday 9/7
		},
		
		
		eventMouseover: function(calEvent,jsEvent) {
			clearInterval(ttto);
			ttto = setTimeout( function() {
				
				$('#ttttl').html( calEvent.title );
				$('#popCusNam').html( calEvent.cusnam );
				$('#popProNam').html( calEvent.title );
				$('#popBooDsc').html( calEvent.text );
				$('#popBooDur').html( calEvent.boodur + 'hrs' );
				$('#popPrdNam').html( calEvent.prdnam );
				$('#popUniPri').html( '&pound;' + calEvent.unipri );
				
				$("#tooltip")
					.css("top",(currentMousePos.y + 50) + "px")
					.css("left",(currentMousePos.x - 135) + "px")
					.fadeIn("fast");
					
			}, 600);
		},
		eventMouseout: function(calEvent,jsEvent) {
			clearInterval(ttto);
			$("#tooltip").fadeOut('fast');	
		},
		
		
        eventClick: function (calEvent, jsEvent, view) {

			if ( calEvent.source.name != 'task' ) {
				
				var booId = calEvent.id;
		
				$.ajax({
					url: 'projects/bookings_script.php',
					data: 'action=select&ajax=true&boo_id=' + booId,
					type: 'POST',
					async: false,
					success: function (data) {

						var booking = JSON.parse(data);
						
						$('[name="boo_id"]', bookingForm).val( booking[0].boo_id );
						$('[name="tbl_id"]', bookingForm).val( booking[0].tbl_id );
						
						$('[name="tbl_id"]', bookingForm).change();
						
						$('[name="ref_id"]', bookingForm).val( booking[0].ref_id );
						$('[name="prd_id"]', bookingForm).val( booking[0].prd_id );
						
						$('[name="boodsc"]', bookingForm).val( booking[0].boodsc );
						
						$('[name="begdatdsp"]', bookingForm).val( getMysqlDate( booking[0].begdat ) );
						$('[name="enddatdsp"]', bookingForm).val( getMysqlDate( booking[0].enddat ) );
						
						$('[name="begtim"]', bookingForm).val( getMysqlTime( booking[0].begdat ) );
						$('[name="endtim"]', bookingForm).val( getMysqlTime( booking[0].enddat ) );
						
						$('[name="sta_id"]', bookingForm).val( booking[0].sta_id );
						
						$('[name="boocol"]', bookingForm).val( booking[0].boocol );

                        $('[name="bootag"]', bookingForm).val( booking[0].bootag );
                        $('[name="booobj"]', bookingForm).val( booking[0].booobj );

						$('#calendarScreen').fadeOut(400, function(){
							$('#bookingFormScreen').fadeIn(400);
						});

                        setTimeout( function() {
                            resize_chosen();
                        } , 400);
						
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
				
				
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
			
			$('[name="boo_id"]', bookingForm).val( 0 );
			//$('[name="tbl_id"]', bookingForm).val( booking[0].tbl_id );
			//$('[name="ref_id"]', bookingForm).val( booking[0].ref_id );
			//$('[name="prd_id"]', bookingForm).val( booking[0].prd_id );
			
			$('[name="boodsc"]', bookingForm).val( '' );
			
			$('[name="begdatdsp"]', bookingForm).val( start.getFullYear() + '-' + twoDigits(1 + start.getUTCMonth()) + '-' + start.getDate() );
			$('[name="begtim"]', bookingForm).val( twoDigits(start.getHours()) + ':' + twoDigits(start.getMinutes()) );
			
			if (end) {
			
				$('[name="enddatdsp"]', bookingForm).val( end.getFullYear() + '-' + twoDigits(1 + end.getUTCMonth()) + '-' + end.getDate() );
				$('[name="endtim"]', bookingForm).val( twoDigits(end.getHours()) + ':' + twoDigits(end.getMinutes()) );
				
			} else {
			
				$('[name="enddatdsp"]', bookingForm).val( $('[name="begdatdsp"]', bookingForm).val() );
				$('[name="endtim"]', bookingForm).val( $('[name="begtim"]', bookingForm).val() );
			
			}
			
			$('[name="tbl_id"]', bookingForm).change();
			$('[name="ref_id"]', bookingForm).change();
			$('[name="prd_id"]', bookingForm).change();
			$('[name="boocol"]', bookingForm).change();
			
			$('[name="sta_id"]', bookingForm).val( 0 );
			
			$('[name="allday"]', bookingForm).prop( 'checked', allDay );
			
			$('#calendarScreen').fadeOut(400, function(){
				$('#bookingFormScreen').fadeIn(400);
			});
			
			
			/*
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
			
			$('#calendarScreen').fadeOut(400, function(){
				$('#bookingFormScreen').fadeIn(400);
			});
			*/
			//$('#createBookingModal').modal('show');

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
			
			var begdat = date;
			
			var txtbegdat = begdat.getFullYear() + '-' + twoDigits(1 + begdat.getMonth()) + '-' + begdat.getDate();
				txtbegdat += ' ' + twoDigits(begdat.getHours()) + ':' + twoDigits(begdat.getMinutes()) + ':00';
			
			var enddat = begdat;
				enddat.setTime( enddat.getTime()+hours );
				
			var txtenddat = enddat.getFullYear() + '-' + twoDigits(1 + enddat.getMonth()) + '-' + enddat.getDate();
				txtenddat += ' ' + twoDigits(enddat.getHours()) + ':' + twoDigits(enddat.getMinutes()) + ':00';
			
			//alert( 'action=update&ajax=true&boo_id=0&tblnam=PROJECT&tbl_id='+originalEventObject.pla_id+'&boocol='+originalEventObject.placol+'&actdat=' + txtbegdat + '&begdat=' + txtbegdat + '&enddat=' + txtenddat );

			$.ajax({
                url: $('#bookingForm')
                    .attr("action"),
                data: 'action=update&ajax=true&boo_id=0&tblnam=PROJECT&tbl_id='+originalEventObject.pla_id+'&ref_id='+$('[name="emp_id[]"]:checked').first().val()+'&boocol='+originalEventObject.placol+'&actdat=' + txtbegdat + '&begdat=' + txtbegdat + '&enddat=' + txtenddat,
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
						 calendar.fullCalendar( 'refetchEvents' );
                    }

                },
                error: function (x, e) {
                    throwAjaxError(x, e);
                }
            });
			
			
			
//			$('#Boo_ID')
//                .val('0');
//            $('#BooDsc')
//                .val( originalEventObject.title );
//
//			$('#ActDat')
//                .val(date.getFullYear() + '-' + twoDigits(1 + date.getUTCMonth()) + '-' + date.getDate() );
//				
//            $('#BegDat')
//                .val(date.getFullYear() + '-' + twoDigits(1 + date.getUTCMonth()) + '-' + date.getDate() + ' ' + twoDigits(date.getHours()) + ':' + twoDigits(date.getMinutes()) + ':00');
//			$('#BegTim')	
//				.val( twoDigits(date.getHours()) + ':' + twoDigits(date.getMinutes()) );
//			
//			$('#Btk_ID')
//                .val( originalEventObject.btk_id );
//			
//			// Calculate duration
//			var addTime = parseInt(originalEventObject.btkdur) * 1000;
//			
//			date.setTime( date.getTime() + addTime );
//				
//            $('#EndDat')
//                .val(date.getFullYear() + '-' + twoDigits(1 + date.getUTCMonth()) + '-' + date.getDate() + ' ' + twoDigits(date.getHours()) + ':' + twoDigits(date.getMinutes()) + ':00');
//			$('#EndTim')	
//					.val( twoDigits(date.getHours()) + ':' + twoDigits(date.getMinutes()) );
			
////			$('#allDayCB').attr('checked', false); 
////			$('#allDayDiv').show();
//			
////			currentCalDate = calendar.fullCalendar('getDate');
//
////			loadPopup('createBookingModal');
			
//			$('#addBookingLink')
//                .click();
//			
//			$(this).remove();
			
		},
        editable: true,
        eventSources: eventSourceArray

    });
	
	//
	// Style Calendar
	//
	
	$(".fc-button-effect").remove();
	$(".fc-button-next .fc-button-content").html("<i class='icon-chevron-right'></i>");
	$(".fc-button-prev .fc-button-content").html("<i class='icon-chevron-left'></i>");
	$(".fc-button-today").addClass('fc-corner-right');
	$(".fc-button-prev").addClass('fc-corner-left');
	
}

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