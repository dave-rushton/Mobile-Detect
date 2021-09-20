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

    $('[name="comnam"]', customerForm).on("keyup paste", function() {
        $('[name="planam"]', customerForm ).val( $('[name="comnam"]', customerForm).val() );
    });

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
	
	getActivity();
	
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
		url: 'projects/project_table.php',
		data: 'tblnam=PROJECT&cus_id='+$('[name="pla_id"]', customerForm).val()+'&tbl_id='+$('[name="pla_id"]', customerForm).val()+'&sta_id='+staID,
		type: 'GET',
		async: false,
		success: function( data ) {
			$('#projectBody').html( data );
		}
	});

}


function getActivity() {

	$.ajax({
		url: "projects/json/customer.weekhours.php",
		type: "GET",
		data: 'cuspro=CUS&pla_id=' + $('[name="pla_id"]', customerForm).val(),
		async: true,
		success: function(data) {
			
			try {
				
				var jsonArray = JSON.parse(data);
				
				var jsonDates = jsonArray.dates;
				var jsonMoney = jsonArray.money;
				var jsonHours = jsonArray.hours;
				
				var arrayLength = jsonDates.length;
				var maxValue = 0;
				
				var chtdata = [];
				var chthour = [];
				var chttick = [];
				var marking = [];
				
				var now = new Date();
					now.setMilliseconds(0);
					now.setSeconds(0);
					now.setMinutes(0);
					now.setHours(0);
				
				var totalHrs = 0;
				
				for (var i = 0; i < arrayLength; i++ ) {
					
					var buildDate = jsonDates[i].split("-");
					flotTime = gd(buildDate[0], buildDate[1]-1, buildDate[2]);
					
					totalHrs += parseFloat(jsonHours[i]);
					
					chthour.push ([flotTime,jsonHours[i]]);
					
				}
				
				totalHrs = totalHrs / arrayLength;
				
				marking.push({ color: "#FF0000", lineWidth: 1, yaxis: { from: totalHrs, to: totalHrs} });
				
				var dayOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thr", "Fri", "Sat"];
				
				$.plot($("#lineChart"), [
					{ 
					label: "hrs", 
					data: chthour,
					color: "#666",
					points: {show: true},
					bars: {show: true, fill: 1}
					}
					], 
					{
					xaxis: {
						mode: "time",
						tickSize: [1, "month"],
    					axisLabel: "Date",
						dayNames: chttick
					},
					series: {
						lines: {
							
						},
						points: {
							
						}
					},
					grid: { 
						hoverable: true, 
						clickable: true,
						markings: marking
					},
					legend: {
						
					}
				});
			
				$("#lineChart").bind("plothover", function (event, pos, item) {
					if (item) {
						if (previousPoint != item.dataIndex) {
							previousPoint = item.dataIndex;
			
							$("#tooltip").remove();
							var y = item.datapoint[1].toFixed(2);
			
							showTooltip(item.pageX, item.pageY,
										'week beginning: ' + switchDate(jsonDates[item.dataIndex]) + ' : ' + y + 'hrs');
						}
					}
					else {
						$("#tooltip").remove();
						previousPoint = null;            
					}
				});
				
				$("#lineChart").bind("plotclick", function (event, pos, item) {
					
					alert( jsonDates[item.dataIndex] );
					
//					for(var i in item){
//						alert('my '+i+' = '+ item[i]);
//					}
				});
				
				
				$('.loading').unblock();
											
			} catch(err)  {
				alert(err);
				
				$('.loading').unblock();
			}
			
		},
		error: function (x, e) {
			throwAjaxError(x, e);
		}
	});
	
}

function gd(year, month, day) {
	return new Date(year, month, day).getTime();
}

function showTooltip(x, y, contents) {
	$('#tooltip').remove();
	$('<div id="tooltip" class="flot-tooltip tooltip"><div class="tooltip-arrow"></div>' + contents + '</div>').css( {
		top: y - 43,
		left: x - 15,
	}).appendTo("body").fadeIn(200);
}