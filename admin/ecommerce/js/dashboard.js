
var curDat = new Date();
var invCht_Yr = curDat.getFullYear();

$(function(){
	
	$('#prevYear').click(function(e){
	
		e.preventDefault();
		
		invCht_Yr--;
		
		getAnalytics(invCht_Yr);
	
	});
	$('#nextYear').click(function(e){
	
		e.preventDefault();
		
		invCht_Yr++;
		
		getAnalytics(invCht_Yr);	
		
	});
	
	$('#incActive').click(function(e){
		e.preventDefault();
		getAnalytics(invCht_Yr);
	});
	
	getAnalytics(invCht_Yr);	
});


function getAnalytics(iInvCht_Yr) {
	
	var staID = ($('#incActive').hasClass('checkbox-active')) ? '0,10,20,30' : '10,20,30';
	
	$('.loading').block({message: 'Preparing'});
	
	$('#curYr_Num').html(iInvCht_Yr);
	
	$.ajax({
		url: "ecommerce/cashflow_dates.php",
		data: 'yr_num=' + iInvCht_Yr + '&sta_id=' + staID,
		type: "GET",
		async: true,
		success: function(data) {
			
			try {
				
				var jsonArray = JSON.parse(data);
				
				var jsonDates = jsonArray.dates;
				var jsonMoney = jsonArray.money;
				
				var arrayLength = jsonDates.length;
				var maxValue = 0;
				
				var chtdata = [];
				var chttick = [];
				var marking = [];
				
				var totalEarn = 0;
				
				for (var i = 0; i < arrayLength; i++ ) {
					
					var buildDate = jsonDates[i].split("-");
					
					flotTime = gd(buildDate[0], buildDate[1]-1, buildDate[2]);
					
					totalEarn += jsonMoney[i];
					
					chtdata.push ([flotTime,jsonMoney[i]]);
					
				}
				
				totalEarn = totalEarn / arrayLength;
				
				marking.push({ color: "#FF0000", lineWidth: 1, yaxis: { from: totalEarn, to: totalEarn} });
				
				$.plot($("#lineChart"), [{ 
					label: "Invoices", 
					data: chtdata,
					color: "#3a8ce5"
				}], {
					xaxis: {
						mode: "time",
						tickSize: [1, "month"],
    					axisLabel: "Date",
						dayNames: chttick
					},
					series: {
						lines: {
							show: true, 
							fill: true
						},
						points: {
							show: true,
						}
					},
					grid: { 
						hoverable: true, 
						clickable: true,
						markings: marking
					},
					legend: {
						show: false
					}
				});
			
				$("#lineChart").bind("plothover", function (event, pos, item) {
					if (item) {
						if (previousPoint != item.dataIndex) {
							previousPoint = item.dataIndex;
			
							$("#tooltip").remove();
							var y = item.datapoint[1].toFixed();
			
							showTooltip(item.pageX, item.pageY,
										item.series.label + " = &pound;" + y);
						}
					}
					else {
						$("#tooltip").remove();
						previousPoint = null;            
					}
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
	$('<div id="tooltip" class="flot-tooltip tooltip"><div class="tooltip-arrow"></div>' + contents + '</div>').css( {
		top: y - 43,
		left: x - 15,
	}).appendTo("body").fadeIn(200);
}