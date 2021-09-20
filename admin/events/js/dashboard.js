var bookingDashForm;

$(function(){
	
	
	
//	$('.inactiveLink').click(function(e){
//		
//		e.preventDefault();
//		
//		alert($(this).data('pla_id'));
//		
//		$(this).closest('.dropdown').fadeOut('fast');
//		
//	});
	
	bookingDashForm = $('#bookingDashForm');
	
	var weekArray = getWeekDateList();
	
	$('[name="begdat"]', bookingDashForm).val( js2mysqlDate(weekArray[0]) );
	$('[name="enddat"]', bookingDashForm).val( js2mysqlDate(weekArray[6]) );
	
	//alert( js2mysqlDate(weekArray[0]) + ' ' + js2mysqlDate(weekArray[6]) );
	
	$('#prevWeek').click(function(e){
	
		e.preventDefault();
		
		var hldDate = mysql2js( $('[name="begdat"]', bookingDashForm).val() + ' 00:00:00' );		
			hldDate.setTime( hldDate.getTime() - (7*days) );
		$('[name="begdat"]', bookingDashForm).val( js2mysqlDate( hldDate ) );
		
		var hldDate = mysql2js( $('[name="enddat"]', bookingDashForm).val() + ' 00:00:00' );		
			hldDate.setTime( hldDate.getTime() - (7*days) );
		$('[name="enddat"]', bookingDashForm).val( js2mysqlDate( hldDate ) );
		
		getAvailability();
	
	});
	$('#nextWeek').click(function(e){
	
		e.preventDefault();
		
		var hldDate = mysql2js( $('[name="begdat"]', bookingDashForm).val() + ' 00:00:00' );		
			hldDate.setTime( hldDate.getTime() + (7*days) );
		$('[name="begdat"]', bookingDashForm).val( js2mysqlDate( hldDate ) );
		
		var hldDate = mysql2js( $('[name="enddat"]', bookingDashForm).val() + ' 00:00:00' );		
			hldDate.setTime( hldDate.getTime() + (7*days) );
		$('[name="enddat"]', bookingDashForm).val( js2mysqlDate( hldDate ) );
		
		getAvailability();
		
	});
	
	getAvailability();
	getAnalytics();
	//getDependancy();
	
	$(".projectChart").each(function(){
		var color = "#881302",
		$el = $(this);
		var trackColor = $el.attr("data-trackcolor");
		if($el.attr('data-color'))
		{
			color = $el.attr('data-color');
		}
		else
		{
			if(parseInt($el.attr("data-percent")) <= 25)
			{
				color = "#046114";
			}
			else if(parseInt($el.attr("data-percent")) > 25 && parseInt($el.attr("data-percent")) < 75)
			{
				color = "#dfc864";
			}
		}
		$el.easyPieChart({
			animate: 1000,
			barColor: color,
			lineWidth: 5,
			size: 80,
			lineCap: 'square',
			trackColor: trackColor
		});
	});
	
});

function getAvailability() {

	//alert( "bookings/availability_dates.php?begdat=" + $('[name="begdat"]', bookingDashForm).val() + "&enddat=" + $('[name="enddat"]', bookingDashForm).val() );
	
	getDependancy($('[name="begdat"]', bookingDashForm).val(), $('[name="enddat"]', bookingDashForm).val())
	
	$.ajax({
		url: "bookings/availability_dates.php",
		type: "GET",
		data: "begdat=" + $('[name="begdat"]', bookingDashForm).val() + "&enddat=" + $('[name="enddat"]', bookingDashForm).val(),
		async: true,
		success: function(data) {
			
			var jsonArray = JSON.parse(data);
			var jsonDates = jsonArray.dates;
			var jsonMoney = jsonArray.money;
			var arrayLength = jsonDates.length;
			
			var totalHours = 0;
			
			for (var i = 0; i < arrayLength; i++ ) {
				
				var buildDate = jsonDates[i].split("-");
				flotTime = gd(buildDate[0], buildDate[1]-1, buildDate[2]);
				var checkDateDay = new Date();
					checkDateDay.setTime(flotTime);
				
				var avlPer = 100 - ((jsonMoney[i] / 10) * 100).toFixed(2);
				
				totalHours += jsonMoney[i];
				
				//green , blue , orange , red
				
				var barCol = 'bar-green';
				if (avlPer < 80) barCol = 'bar-blue';
				if (avlPer < 60) barCol = 'bar-orange';
				if (avlPer < 40) barCol = 'bar-red';
				
				$('#day' + checkDateDay.getDay()).removeClass('bar-red bar-orange bar-blue bar-green').addClass(barCol).css({width: avlPer + '%'});
			}
			
			// display week days
			var jsonDisplay = jsonArray.wkday ;
			$('#monAvl').html( jsonDisplay[0] );
			$('#tueAvl').html( jsonDisplay[1] );
			$('#wedAvl').html( jsonDisplay[2] );
			$('#thuAvl').html( jsonDisplay[3] );
			$('#friAvl').html( jsonDisplay[4] );
			$('#satAvl').html( jsonDisplay[5] );
			$('#sunAvl').html( jsonDisplay[6] );
			
			//$('#twBarCht').data('percent', ((totalHours/50) * 100)).html( ((totalHours/50) * 100) + '%');
			//$('#twTotHrs').html( totalHours + 'hrs' );
			
			//$('#avlData').find('.spark').find('.chart').data('easyPieChart').update( ((totalHours/50) * 100) );
			
			$('#avlPercent').html( parseInt(100 - ((totalHours/50) * 100)) + '%' );
			
			//$('.chart').data('percent').update( ((totalHours/50) * 100) );
			
		},
		error: function (x, e) {
			throwAjaxError(x, e);
		}
	});
	
}

function getAnalytics() {
	
	$.ajax({
		url: "bookings/workflow_dates.php",
		type: "GET",
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
				
				// today
				marking.push({ color: "#000000", lineWidth: 1, xaxis: { from: now, to: now} });
				// earning warning
				marking.push({ color: "#FF0000", lineWidth: 1, yaxis: { from: 120, to: 120} });
					
				for (var i = 0; i < arrayLength; i++ ) {
					
					var buildDate = jsonDates[i].split("-");
					flotTime = gd(buildDate[0], buildDate[1]-1, buildDate[2]);
					
							
					if ( isWeekend(buildDate[0], buildDate[1]-1, buildDate[2]) ) {
						// add weekends
						marking.push({ color: "#8cbf26", lineWidth: 1, xaxis: { from: flotTime, to: flotTime + (24*hours)} });
					}
					
					chtdata.push ([flotTime,jsonMoney[i]]);
					chthour.push ([flotTime,jsonHours[i]]);
					
					
				}
				
				var dayOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thr", "Fri", "Sat"];
				
				$.plot($("#lineChart"), [
					{ 
					label: "&pound;", 
					data: chtdata,
					color: "#f8a31f",
					points: {show: true},
					lines: {show: true}
					},
					{ 
					label: "hrs", 
					data: chthour,
					color: "#666",
					yaxis: 2,
					points: {show: true},
					lines: {show: true}
					}
					], 
					{
					xaxis: {
						mode: "time",
						tickFormatter: function (val, axis) {           
							return dayOfWeek[new Date(val).getDay()] + ' ' + new Date(val).getDate();
						},
						tickSize: [1, "day"],
    					axisLabel: "Date",
						dayNames: chttick
					},
					yaxes: [
						{
							tickFormatter: function (val, axis) {
								return "&pound;" + val;
							},
							max: 250,
							axisLabel: "Income",
							axisLabelUseCanvas: true,
							axisLabelFontSizePixels: 12,
							axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
							axisLabelPadding: 5
						},
						{
							position: 0,
							tickFormatter: function (val, axis) {
								return val + "hrs";
							},
							max: 24,
							axisLabel: "Hours",
							axisLabelUseCanvas: true,
							axisLabelFontSizePixels: 12,
							axisLabelFontFamily: "Verdana, Arial, Helvetica, Tahoma, sans-serif",
							axisLabelPadding: 5
						}
					],
					series: {
						lines: {
							//show: true, 
							//fill: true,
							//fillColor: { colors: ["#fff", "#ddd"] }
						},
						points: {
							//show: true,
						}
					},
					grid: { 
						hoverable: true, 
						clickable: true,
						//backgroundColor: { colors: ["#D1D1D1", "#7A7A7A"] },
						markings: marking
					},
					legend: {
						//show: false
					}
				});
			
				$("#lineChart").bind("plothover", function (event, pos, item) {
					if (item) {
						if (previousPoint != item.dataIndex) {
							previousPoint = item.dataIndex;
			
							$("#tooltip").remove();
							var y = item.datapoint[1].toFixed(2);
			
							showTooltip(item.pageX, item.pageY,
										item.series.label + " " + y);
						}
					}
					else {
						$("#tooltip").remove();
						previousPoint = null;            
					}
				});
				
				$("#lineChart").bind("plotclick", function (event, pos, item) {
					
					var caldata = jsonDates[item.dataIndex].split("-");
					
					window.location = 'bookings/calendar.php?y='+caldata[0]+'&m='+caldata[1]+'&d='+caldata[2];
					
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


function getDependancy(iBegDat, iEndDat) {

	var hldData = '';
	
	if (iBegDat) hldData += 'begdat=' + iBegDat;
	
	if (iBegDat) hldData += (hldData == '') ? 'enddat=' + iEndDat : '&enddat=' + iEndDat;

	$.ajax({
		url: "bookings/json/customer.weekhours.php",
		type: "GET",
		
		data: hldData + '&cuspro=PRO',
		
		//data: 'pla_id=' + $('[name="pla_id"]', customerForm).val(),
		async: true,
		success: function(data) {
			
			//alert( data );
			
			try {
				
				var jsonArray = JSON.parse(data);
				
				var jsonNames = jsonArray.names;
				var jsonDates = jsonArray.dates;
				var jsonMoney = jsonArray.money;
				var jsonHours = jsonArray.hours;
				var jsonColor = jsonArray.color;
				
				var arrayLength = jsonDates.length;
				var maxValue = 0;
				
				var chtdata = [];
				var chthour = [];
				var chttick = [];
				var chtcols = [];
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
					
					chthour.push ([i, jsonHours[i]]);
					chttick.push ([i, jsonNames[i]]);
					chtcols.push ([i, jsonColor[i]]);
					
				}

                var stack = 0,
                    bars = true,
                    lines = false,
                    steps = false;
                
				
				$.plot($("#dependancyChart"), [chthour], {
					series: {
						lines: {
							show: lines,
							fill: true,
							steps: steps
						},
						bars: {
							show: true,
							barWidth: 0.8,
							borderWidth: 0,
							align: "center",
							//Color: "#368EE0"
						}
						
					},
					xaxis: {
						ticks: chttick,
						axisLabel: "Project"
					},
					yaxis: {
						axisLabel: 'Hours',
					},
					//colors: jsonColor
				});
                
				
				
				
//				totalHrs = totalHrs / arrayLength;
//				
//				marking.push({ color: "#FF0000", lineWidth: 1, yaxis: { from: totalHrs, to: totalHrs} });
//				
//				var dayOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thr", "Fri", "Sat"];
				
//				$.plot($("#dependancyChart"), [
//					{ 
//					label: "hrs", 
//					data: chthour,
//					color: "#666",
//					points: {show: true},
//					bars: {show: true, fill: 1}
//					}
//					], 
//					{
//					xaxis: {
//						mode: "time",
//						//tickSize: [1, "month"],
//    					axisLabel: "Date",
//						dayNames: chttick
//					},
//					series: {
//						lines: {
//							
//						},
//						points: {
//							
//						}
//					},
//					grid: { 
//						hoverable: true, 
//						clickable: true,
//						markings: marking
//					},
//					legend: {
//						
//					}
//				});
			
//				$("#dependancyChart").bind("plothover", function (event, pos, item) {
//					if (item) {
//						if (previousPoint != item.dataIndex) {
//							previousPoint = item.dataIndex;
//			
//							$("#tooltip").remove();
//							var y = item.datapoint[1].toFixed(2);
//			
//							showTooltip(item.pageX, item.pageY,
//										'project: ' + jsonNames[item.dataIndex] + ' : ' + y + 'hrs');
//						}
//					}
//					else {
//						$("#tooltip").remove();
//						previousPoint = null;            
//					}
//				});
				
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
function isWeekend(year, month, day) {
	
	return (new Date(year, month, day).getDay() == 6 /*|| new Date(year, month, day).getDay() == 0*/);
	
	//new Date(year, month, day).getTime();
}

function showTooltip(x, y, contents) {
	$('<div id="tooltip" class="flot-tooltip tooltip"><div class="tooltip-arrow"></div>' + contents + '</div>').css( {
		top: y - 43,
		left: x - 15,
	}).appendTo("body").fadeIn(200);
}