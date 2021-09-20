var customerForm;

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
	
	customerForm = $('#customerForm');

	initialize();
	
	if (marker) {
		for (var i = 0; i < marker.length; i++ ) {
			marker[i].setMap(null);
		}
	}
	
	if ( $('#GooLat', customerForm).val() != '' && $('#GooLng', customerForm).val() != '' ) {
		placeMarker( $('#GooLat', customerForm).val() , $('#GooLng', customerForm).val() );
	} else if ($('#GooGeo', customerForm).val() != '') {
		codeAddress();
	}	
	
	$('#allProjects').click(function(e){
		e.preventDefault();
		getProjects();
	});
	
	$('#geoLocate').click(function(e){
		e.preventDefault();
		codeAddress( $('[name="pstcod"]', customerForm).val() );	
	});
	
	$('#updateCustomerBtn').click(function(e){
		e.preventDefault();
		customerForm.submit();
	});
	
	customerForm.submit(function(e){
	
		e.preventDefault();
		
		//alert( customerForm.serialize() );
		
		$.ajax({
			url: customerForm.attr("action"),
			data: 'action=update&ajax=true&' + customerForm.serialize(),
			type: 'POST',
			async: false,
			success: function( data ) {
				
				try {
					
					//alert( data );
					
					var result = JSON.parse(data);
					
					$.msgGrowl ({
						type: result.type
						, title: result.title
						, text: result.description
					});
				
					$('#id', customerForm).val( result.id );
					
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
				
				throwAjaxError(x, e);
				
			}
		});
		
	
	});
	
	$('#deleteCustomerBtn').click(function (e) {
		e.preventDefault();
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Customer'
			, text: 'Are you sure you wish to permanently remove this customer from the database?'
			, callback: function () {
				
				$.ajax({
					url: customerForm.attr("action"),
					data: 'action=delete&ajax=true&' + customerForm.serialize(),
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						window.location = customerForm.data("returnurl");
						
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
				
			}
		});
		return false;
	});
	
	getProjects();
	
});


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

            //google.maps.event.addListener(map, "moveend", function () {
//                map.clearOverlays();
//                var center = map.getCenter();
//                var marker = new GMarker(center, {
//                    draggable: true
//                });
//                map.addOverlay(marker);
//                document.getElementById("GooLat").innerHTML = center.lat().toFixed(5);
//                document.getElementById("GooLng").innerHTML = center.lng().toFixed(5);
//
//                google.maps.event.addListener(marker, "dragend", function () {
//                    var pt = marker.getPoint();
//                    map.panTo(pt);
//                    document.getElementById("GooLat").innerHTML = pt.lat().toFixed(5);
//                    document.getElementById("GooLng").innerHTML = pt.lng().toFixed(5);
//                });
//
//            });

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

//	alert( iGooLat + ' ' + iGooLng );

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

function getProjects() {
	
	var staID = ($('#allProjects').hasClass('checkbox-active')) ? null : 0;
	
	//alert( 'tblnam=PROJECT&tbl_id='+$('[name="pla_id"]', customerForm).val()+'&sta_id=0' );

	$.ajax({
		url: 'system/place_table.php',
		data: 'tblnam=PROJECT&cus_id='+$('[name="pla_id"]', customerForm).val()+'&tbl_id='+$('[name="pla_id"]', customerForm).val()+'&sta_id='+staID,
		type: 'GET',
		async: false,
		success: function( data ) {
			$('#projectBody').html( data );
		}
	});

}