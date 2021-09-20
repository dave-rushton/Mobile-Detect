var timesheetForm;

$(function(){
	
	timesheetForm = $('#timesheetForm');
	
	var weekArray = getWeekDateList();
	
	$('[name="begdat"]', timesheetForm).val( js2mysqlDate(weekArray[0]) );
	$('[name="enddat"]', timesheetForm).val( js2mysqlDate(weekArray[6]) );
	
	$('[name="begdat"], [name="enddat"]', timesheetForm).datepicker({ format: 'yyyy-mm-dd', weekStart: 1 });
	
	$('#prevWeek').click(function(e){
	
		e.preventDefault();
		
		var hldDate = mysql2js( $('[name="begdat"]', timesheetForm).val() + ' 00:00:00' );		
			hldDate.setTime( hldDate.getTime() - (7*days) );
		$('[name="begdat"]', timesheetForm).val( js2mysqlDate( hldDate ) );
		
		var hldDate = mysql2js( $('[name="enddat"]', timesheetForm).val() + ' 00:00:00' );		
			hldDate.setTime( hldDate.getTime() - (7*days) );
		$('[name="enddat"]', timesheetForm).val( js2mysqlDate( hldDate ) );
		
		getBookings();
	
	});
	$('#nextWeek').click(function(e){
	
		e.preventDefault();
		
		var hldDate = mysql2js( $('[name="begdat"]', timesheetForm).val() + ' 00:00:00' );		
			hldDate.setTime( hldDate.getTime() + (7*days) );
		$('[name="begdat"]', timesheetForm).val( js2mysqlDate( hldDate ) );
		
		var hldDate = mysql2js( $('[name="enddat"]', timesheetForm).val() + ' 00:00:00' );		
			hldDate.setTime( hldDate.getTime() + (7*days) );
		$('[name="enddat"]', timesheetForm).val( js2mysqlDate( hldDate ) );
		
		getBookings();
	
	});
	
	timesheetForm.submit(function(e){
		e.preventDefault();	
		getBookings();
	});
	
	getBookings();
	
});

function getBookings() {
	
	//alert( 'action=update&ajax=true&' + $('#timesheetForm').serialize() );
	
	$.ajax({
		url: 'projects/reports/timesheet.php',
		data: 'action=update&ajax=true&' + timesheetForm.serialize(),
		type: 'GET',
		async: false,
		success: function( data ) {
			
			//alert( data );
			
			$('#reportBox').html( data );

		},
		error: function (x, e) {
			
			throwAjaxError(x, e);
			
		}
	});
		
}