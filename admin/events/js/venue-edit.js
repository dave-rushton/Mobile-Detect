var venueForm;

var geocoder;
var map;
var marker = [];

$(function(){
	
	$('#adrControl').click(function(e){
		e.preventDefault();
		$('#adrInputs').slideToggle(400, function(){
			$('#adrControl i').toggleClass('icon-angle-down').toggleClass('icon-angle-up');
		});
	});

	venueForm = $('#venueForm');

    $('[name="comnam"]', venueForm).bind('change paste keyup', function(){
        $('[name="planam"]', venueForm).val( $('[name="comnam"]', venueForm).val() );
    })

	initialize();
	
	if (marker) {
		for (var i = 0; i < marker.length; i++ ) {
			marker[i].setMap(null);
		}
	}
	
	if ( $('#GooLat', venueForm).val() != '' && $('#GooLng', venueForm).val() != '' ) {
		placeMarker( $('#GooLat', venueForm).val() , $('#GooLng', venueForm).val() );
	} else if ($('#GooGeo', venueForm).val() != '') {
		codeAddress();
	}	
	
	$('#geoLocate').click(function(e){
		e.preventDefault();
		codeAddress( $('[name="pstcod"]', venueForm).val() );	
	});
	
	
	$('#updateVenueBtn').click(function(e){
		e.preventDefault();
		venueForm.submit();
	});
	
	venueForm.submit(function(e){
	
		e.preventDefault();
		
		$.ajax({
			url: venueForm.attr("action"),
			data: 'action=update&ajax=true&' + venueForm.serialize(),
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
				
					$('#id', venueForm).val( result.id );
					
					//window.location = venueForm.data("returnurl");
					
				} catch(Ex) {
					
					$.msgGrowl ({
						type: 'error'
						, title: 'Error'
						, text: Ex
					});
					
					//$.growlUI('Error', 'Contact your administrator'); 
				}

			},
			error: function (x, e) {
				alert(x + ' ' + e);
				throwAjaxError(x, e);
				
			}
		});
		
	
	});
	
	$('#deleteVenueBtn').click(function (e) {
		e.preventDefault();
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Venue'
			, text: 'Are you sure you wish to permanently remove this project from the database?'
			, callback: function () {
				
				$.ajax({
					url: venueForm.attr("action"),
					data: 'action=delete&ajax=true&' + venueForm.serialize(),
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						window.location = venueForm.data("returnurl");
						
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
				
			}
		});
		return false;
	});
	
	$('#bookingsBody').on('click', '.selectBookingLnk', function(e){
		e.preventDefault();
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
					url: 'bookings/bookings_script.php',
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
	
	getBookings();
	
});


function getBookings() {

	var urlData = 'action=select&tblnam=PROJECT&tbl_id=' + $('[name="pla_id"]', venueForm).val();
	
	$.ajax({
		url: 'bookings/bookings_table.php',
		data: urlData,
		type: 'GET',
		async: false,
		success: function (data) {
			
			try { bookingTable.fnDestroy(); } catch (Ex) { }
			$('#bookingsBody').html( data );
			
			var booTot = 0;
			var totBoo = 0;
			
			$('.booDur', $('#bookingsBody')).each(function(){
				booTot += parseFloat($(this).html());
				totBoo++;
			});
			
			$('#totalDuration').html( booTot );
			$('#totalBookings').html( totBoo );
			
			bookingTable = $("table#bookingsTable").dataTable({
				"bDestroy": true,
				"aoColumns": [
                              {"bSortable": false},
                              {"iDataSort": 2},
                              {"bVisible": false},
                              {"bSortable": false},
                              {"bSortable": true},
                              {"bVisible": false},
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


function initialize() {

    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng($('#GooLat').val(), $('#GooLng').val());
    var myOptions = {
        zoom: 12,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
}

function codeAddress(iAddress) {

    //var address = document.getElementById("GooGeo").value;

    geocoder.geocode({
        'address': iAddress
    }, function (results, status) {

        if (status == google.maps.GeocoderStatus.OK) {
			
			map.setCenter(results[0].geometry.location);
            
			var markerLength = marker.length;
			
			marker[markerLength] = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location,
                draggable: true
            });

            google.maps.event.addListener(marker[markerLength], "click", function(){marker[markerLength].openInfoWindowHtml('here I am');});
            
			google.maps.event.addListener(marker[markerLength], "dragend", function () {
                map.panTo(new google.maps.LatLng(marker[markerLength].position.lat(), marker[markerLength].position.lng()));
				$('#GooLat').val(marker[markerLength].position.lat());
				$('#GooLng').val(marker[markerLength].position.lng());
                
            });
			
			$('#GooLat').val(marker[markerLength].position.lat());
			$('#GooLng').val(marker[markerLength].position.lng());

        } else {
			
			$.msgGrowl ({
				type: 'warning'
				, title: 'Geocode was not successful'
				, text: status
			});
			
        }
    });

}

function placeMarker (iGooLat, iGooLng) {

	var markerLength = marker.length;
	var GooLatLng = new google.maps.LatLng(iGooLat,iGooLng);
	
	marker[markerLength] = new google.maps.Marker({
		map: map,
		position: GooLatLng,
		draggable: true
	});
	
	google.maps.event.addListener(marker[markerLength], "click", function(){marker[markerLength].openInfoWindowHtml('here I am');});
	
	google.maps.event.addListener(marker[markerLength], "dragend", function () {
		map.panTo(new google.maps.LatLng(marker[markerLength].position.lat(), marker[markerLength].position.lng()));
		$('#GooLat').val(marker[markerLength].position.lat());
		$('#GooLng').val(marker[markerLength].position.lng());
		
	});
	
	$('#GooLat').val(marker[markerLength].position.lat());
	$('#GooLng').val(marker[markerLength].position.lng());
	
	map.panTo(new google.maps.LatLng(marker[markerLength].position.lat(), marker[markerLength].position.lng()));
	
}