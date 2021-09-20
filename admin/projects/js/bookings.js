var bookingForm;
var bookingTable;
var searchForm, changeStatusForm;

$(function(){
	
	bookingForm = $('#bookingForm');
	searchForm = $('#searchForm');
	changeStatusForm = $('#changeStatusForm');
	
	
	$('[name="begtim"]', bookingForm).timepicker({
		defaultTime: 'current',
		minuteStep: 15,
		disableFocus: true,
		template: 'dropdown',
		showMeridian: false
	});
	$('[name="endtim"]', bookingForm).timepicker({
		defaultTime: 'current',
		minuteStep: 15,
		disableFocus: true,
		template: 'dropdown',
		showMeridian: false
	});
	
	$('[name="sta_id"]', searchForm).change(function(){
		
		//
		// get status flow
		//
		
		if ($(this).val() != '') {
			
			$.ajax({
				url: 'system/statusflow_script.php',
				data: 'action=select&ajax=true&flo_id=0&frm_id=' + $(this).val(),
				type: 'POST',
				async: false,
				success: function (data) {
					
					try {
					
						var staRecs = JSON.parse( data );
						
						var resultHTML  = '';
						
						for (s=0;s<staRecs.length;s++) {
							resultHTML += '<option value="'+staRecs[s].to_id+'">'+staRecs[s].to_nam+'</option>'	;
						}
						
						$('[name="sta_id"]', changeStatusForm).html( resultHTML );
						
					} catch(ex) {
						
					}
					
				}
			});
			
			$('#changeStatusDiv').show();
		} else {
			$('#changeStatusDiv').hide();
		}
		
	});
	$('[name="sta_id"]', searchForm).change();
	
	$('#updateStatusBtn').click(function(e){
		e.preventDefault();
		
		var booLst = '';
		
		$('.selectBookingCB:checked', $('#bookingsBody')).each(function(){
			
			booLst += (booLst == '') ? $(this).val() : ',' + $(this).val();
			
		});
		
		$.ajax({
			url: 'projects/bookings_script.php',
			data: 'action=changestatus&ajax=true&flo_id=0&boolst=' + booLst + '&sta_id=' + $('[name="sta_id"]', changeStatusForm).val(),
			type: 'POST',
			async: false,
			success: function (data) {
				
				try {
					
					getBookings();
					
				} catch(ex) {
					
				}
				
			},
			error: function (x, e) {
				throwAjaxError(x, e);
			}
		});
		
	});
	
	
	$('#selAllBoo').change(function(){
		$('input:checkbox', $('#bookingsBody')).prop('checked', $(this).prop('checked'));
	});
	
	$('[name="begdatdsp"], [name="enddatdsp"]', bookingForm).datepicker({ format: 'yyyy-mm-dd', weekStart: 1 });
	
	var weekArray = getWeekDateList();
	
	$('[name="begdat"]', searchForm).val( js2mysqlDate(weekArray[0]) );
	$('[name="enddat"]', searchForm).val( js2mysqlDate(weekArray[6]) );
	
	$('[name="begdat"], [name="enddat"]', searchForm).datepicker({ format: 'yyyy-mm-dd', weekStart: 1 })
		.on('changeDate', function(ev){
			getBookings();
		});
	
	$('#prevWeek').click(function(e){
	
		e.preventDefault();
		
		var hldDate = mysql2js( $('[name="begdat"]', searchForm).val() + ' 00:00:00' );		
			hldDate.setTime( hldDate.getTime() - (7*days) );
		$('[name="begdat"]', searchForm).val( js2mysqlDate( hldDate ) );
		
		var hldDate = mysql2js( $('[name="enddat"]', searchForm).val() + ' 00:00:00' );		
			hldDate.setTime( hldDate.getTime() - (7*days) );
		$('[name="enddat"]', searchForm).val( js2mysqlDate( hldDate ) );
		
		getBookings();
	
	});
	$('#nextWeek').click(function(e){
	
		e.preventDefault();
		
		var hldDate = mysql2js( $('[name="begdat"]', searchForm).val() + ' 00:00:00' );		
			hldDate.setTime( hldDate.getTime() + (7*days) );
		$('[name="begdat"]', searchForm).val( js2mysqlDate( hldDate ) );
		
		var hldDate = mysql2js( $('[name="enddat"]', searchForm).val() + ' 00:00:00' );		
			hldDate.setTime( hldDate.getTime() + (7*days) );
		$('[name="enddat"]', searchForm).val( js2mysqlDate( hldDate ) );
		
		getBookings();
	
	});
	
	$('[name="usedat"]', searchForm).change(function(){
		getBookings();
	});
	
	$('#bookingsBody').on('click', '.selectBookingLnk', function(e){
		e.preventDefault();
		
		var booId = $(this).data('boo_id');
		
		$.ajax({
			url: 'projects/bookings_script.php',
			data: 'action=select&ajax=true&boo_id=' + booId,
			type: 'POST',
			async: false,
			success: function (data) {

				var booking = JSON.parse(data);
				
				$('[name="boo_id"]', bookingForm).val( booking[0].boo_id );
				$('[name="tbl_id"]', bookingForm).val( booking[0].tbl_id );
				$('[name="ref_id"]', bookingForm).val( booking[0].ref_id );
				$('[name="prd_id"]', bookingForm).val( booking[0].prd_id );
				
				$('[name="boodsc"]', bookingForm).val( booking[0].boodsc );
				
				$('[name="begdatdsp"]', bookingForm).val( getMysqlDate( booking[0].begdat ) );
				$('[name="enddatdsp"]', bookingForm).val( getMysqlDate( booking[0].enddat ) );
				
				$('[name="begtim"]', bookingForm).val( getMysqlTime( booking[0].begdat ) );
				$('[name="endtim"]', bookingForm).val( getMysqlTime( booking[0].enddat ) );
				
				$('[name="sta_id"]', bookingForm).val( booking[0].sta_id );
				
				$('[name="boocol"]', bookingForm).val( booking[0].boocol );
				
				//$('[name="tbl_id"]', editionSearchForm).removeAttr("style", "").removeClass("chzn-done").data("chosen", null).next().remove();
				
				changeScreen('#bookingFormScreen');
				
			},
			error: function (x, e) {
				throwAjaxError(x, e);
			}
		});
		
	});
	
	$('#bookingsBody').on('click', '.deleteBookingBtn', function(e){
		e.preventDefault();
		
		var booId = $(this).data('boo_id');
		
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
						
						getBookings();
						
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
			}
		});
		
	});
	
	$('#updateBooking').click(function(e){
		e.preventDefault();
		bookingForm.submit();
	});
	
	bookingForm.submit(function(e){
	
		e.preventDefault();
		
		$('[name="begdat"]', bookingForm).val( $('[name="begdatdsp"]', bookingForm).val() + ' ' + $('[name="begtim"]', bookingForm).val() );
		$('[name="enddat"]', bookingForm).val( $('[name="enddatdsp"]', bookingForm).val() + ' ' + $('[name="endtim"]', bookingForm).val() );

		$.ajax({
			url: 'projects/bookings_script.php',
			data: 'action=update&ajax=true&' + bookingForm.serialize(),
			type: 'POST',
			async: false,
			success: function (data) {

				var result = JSON.parse(data);

				$.msgGrowl({
					type: result.type,
					title: result.title,
					text: result.description
				});
				
				getBookings();
				changeScreen('#listingScreen');

			},
			error: function (x, e) {
				throwAjaxError(x, e);
			}
		});
		
	});
	
	$('#newBookingBtn').click(function(e){
	
		e.preventDefault();
		
		$('[name="boo_id"]', bookingForm).val( 0 );
		$('[name="tbl_id"]', bookingForm).val( 0 );
		$('[name="ref_id"]', bookingForm).val( 0 );
		
		$('[name="boodsc"]', bookingForm).val( '' );
		$('[name="begdatdsp"]', bookingForm).val( '' );
		$('[name="begtim"]', bookingForm).val( '' );
		$('[name="enddatdsp"]', bookingForm).val( '' );
		$('[name="endtim"]', bookingForm).val( '' );
		
		$('[name="sta_id"]', bookingForm).val( 0 );
		$('[name="boocol"]', bookingForm).val( '' );
		
		changeScreen('#bookingFormScreen');
		
	});
	
	$('.screenSelect').click(function(e){
		e.preventDefault();
		changeScreen($(this).attr("href"));
	});
	
	$('[name="tbl_id"]', searchForm).change(function(){
		getBookings();
	});
	$('[name="ref_id"]', searchForm).change(function(){
		getBookings();
	});
	$('[name="sta_id"]', searchForm).change(function(){
		getBookings();
	});
	
	getBookings();
	
});

function changeScreen(screenID) {
	
	$('.adminScreen').fadeOut();
	setTimeout( function() { $(screenID).fadeIn(200, function(){ resize_chosen();}); } , 400);
	
		
}

function getBookings() {

	//alert( 'action=select&tblnam=PROJECT&tbl_id=' + $('[name="tbl_id"]', searchForm).val() + '&ref_id=' + $('[name="ref_id"]', searchForm).val() + '&sta_id=' + $('[name="sta_id"]', searchForm).val() + '&begdat=' + $('[name="begdat"]', searchForm).val() + '&enddat=' + $('[name="enddat"]', searchForm).val() );
	
	var urlData = 'action=select&tblnam=PROJECT&tbl_id=' + $('[name="tbl_id"]', searchForm).val() + '&ref_id=' + $('[name="ref_id"]', searchForm).val() + '&sta_id=' + $('[name="sta_id"]', searchForm).val();
	
	if ( $('[name="usedat"]', searchForm).prop('checked') ) {
		urlData += '&begdat=' + $('[name="begdat"]', searchForm).val() + '&enddat=' + $('[name="enddat"]', searchForm).val() + ' 23:59:59';
	}
	
	$.ajax({
		url: 'projects/bookings_table.php',
		data: urlData,
		type: 'GET',
		async: false,
		success: function (data) {
			
			//$('#chkHTML').html( data );
			
			try { bookingTable.fnDestroy(); } catch (Ex) { }
			$('#bookingsBody').html( data );
			
			var booTot = 0;
			
			$('.booDur', $('#bookingsBody')).each(function(){
				booTot += parseFloat($(this).html());
			});
			
			$('#totalDuration').html( booTot + ' hrs');
			
			bookingTable = $("table#bookingsTable").dataTable();
			
			bookingTable = $("table#bookingsTable").dataTable({
				"bDestroy": true,
				"iDisplayLength": 50,
				"aoColumns": [
                              {"bSortable": false},
                              {"iDataSort": 2},
                              {"bVisible": false},
                              {"bSortable": false},
                              {"bSortable": true},
                              {"bSortable": true},
                              {"bSortable": true},
							  {"bSortable": true},
                              {"bSortable": false}
                             ]
			});
			
		},
		error: function (x, e) {
			throwAjaxError(x, e);
		}
	});
	
}
