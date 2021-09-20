
$(function(){
	getAnalytics();	
});


function getAnalytics() {
	
	$('.loading').block({message: 'Preparing'});
	
	$.ajax({
		url: "googleapi/google.analytics.php",
		type: "POST",
		async: true,
		//data: "report=sitestats&fromdate=" + encodeURIComponent(switchDate($('#DatSrt').val())) + "&todate=" + encodeURIComponent(switchDate($('#DatEnd').val())),
		data: "report=sitestats",
		success: function(data) {
			
			//alert( data );
			
			try {
				
				var jsonArray = JSON.parse(data);
				
				//Core Stats 
				
				$('#StatSiteVisits').html( jsonArray['SiteVisits'] );
				$('#StatUniqueVisits').html( jsonArray['UniqueVisits'] );
				$('#StatPageViews').html( jsonArray['PageVisits'] );
				$('#StatBounceRate').html( jsonArray['BounceRate'] + '%' );
				
				// Top 5 Landing Pages Pie Chart
				
				var pieData = [];
				var TmpDat = jsonArray['LandingPages'].split(';');
				var DatLen = TmpDat.length;
				
				for( var i = 0; i < DatLen; i++) {
			
					var ArrVal = TmpDat[i].split(',');
					pieData[i] = { label: ArrVal[0].substr(0, 50), data: Math.floor(ArrVal[1]), fullLabel: ArrVal[0] }
					if(i == 4) break;
				}
								

				//Website Visitors Bar Chart
				
				var arrayLength = jsonArray['count'];
				var maxValue = 0;
				
				var chtdata = [];
				var chttick = [];
				
				for (var i = 0; i < arrayLength; i++ ) {
						
					flotTime = gd(jsonArray[i].year, jsonArray[i].month, jsonArray[i].day);
										
					chtdata.push ([flotTime,jsonArray[i].visits]);
					//chttick.push (jsonArray[i].date);
					
				}
								
				//Top Referrers List
								
				if ( jsonArray['TopReferrers'] ) {
				
					data = [];
					TmpDat = jsonArray['TopReferrers'].split(';');
					DatLen = TmpDat.length;
					var resultsHTML = '';
					
					for( var i = 0; i < DatLen; i++) {
				
						var ArrVal = TmpDat[i].split(',');
						
						if( ArrVal.length == 2 ) {
										
							resultsHTML += '<tr>';
								resultsHTML += '<td class="description"><a href="javascript:;">' + ArrVal[0] + '</a></td>';
								resultsHTML += '<td class="value"><span>' +  ArrVal[1] + '</span></td>';
							resultsHTML += '</tr>';				
							
						}
						
					}
					
					$('#TopReferrersList').html( resultsHTML );
				
				}
				
				//Most Visited Pages
				
				if ( jsonArray['MostVisitedPages'] ) {
				
					data = [];
					TmpDat = jsonArray['MostVisitedPages'].split(';');
					DatLen = TmpDat.length;
					resultsHTML = '';
					
					for( var i = 0; i < DatLen; i++) {
				
						var ArrVal = TmpDat[i].split(',');
						
						if( ArrVal.length == 2 ) {
										
							resultsHTML += '<tr>';
								resultsHTML += '<td class="description"><a href="javascript:;">' + ArrVal[0] + '</a></td>';
								resultsHTML += '<td class="value"><span>' + ArrVal[1] + '</span></td>';
							resultsHTML += '</tr>';				
						
						}
						
					}
					
					$('#MostVisitedPagesList').html( resultsHTML );
				
				}
				
				//Search Terms
				
				data = [];
				TmpDat = jsonArray['SearchTerms'].split(';');
				DatLen = TmpDat.length;
				resultsHTML = '';
				
				for( var i = 0; i < DatLen; i++) {
			
					var ArrVal = TmpDat[i].split(',');
					
					if( ArrVal.length == 2 ) {
									
						resultsHTML += '<tr>';
							resultsHTML += '<td class="description"><a href="javascript:;">' + ArrVal[0] + '</a></td>';
							resultsHTML += '<td class="value"><span>' +  ArrVal[1] + '</span></td>';
						resultsHTML += '</tr>';		
						
					}
						
				}
				
				$('#SearchTermsList').html( resultsHTML );
				
				
				var data = [[1262304000000, 1300], [1264982400000, 2200], [1267401600000, 3600], [1270080000000, 5200], [1272672000000, 4500], [1275350400000, 3900], [1277942400000, 3600], [1280620800000, 4600], [1283299200000, 5300], [1285891200000, 7100], [1288569600000, 7800], [1291241700000, 8195]];
				
//				var plot = $.plot($("#visitorLineChart"),
//					   [ { data: chtdata, label: "visits"} ], {
//						   series: {
//							   lines: { show: true },
//							   points: { show: true }
//						   },
//						   
//						   grid: { hoverable: true, clickable: true },
//						   //yaxis: { min: -1.1, max: 1.1 },
//						   //xaxis: { min: 0, max: 9 },
//					//colors: Slate.chartColors
//						 });
				
				$.plot($("#visitorLineChart"), [{ 
					label: "Visits", 
					data: chtdata,
					color: "#3a8ce5"
				}], {
					xaxis: {
//						min: (new Date(2009, 12, 1)).getTime(),
//						max: (new Date(2010, 11, 2)).getTime(),
						mode: "time",
						tickSize: [1, "day"],
    					axisLabel: "Date",
						dayNames: chttick
//						tickSize: [1, "month"],
//						monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
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
					grid: { hoverable: true, clickable: true },
					legend: {
						show: false
					}
				});
			
				$("#visitorLineChart").bind("plothover", function (event, pos, item) {
					if (item) {
						if (previousPoint != item.dataIndex) {
							previousPoint = item.dataIndex;
			
							$("#tooltip").remove();
							var y = item.datapoint[1].toFixed();
			
							showTooltip(item.pageX, item.pageY,
										item.series.label + " = " + y);
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