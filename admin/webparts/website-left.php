
<div id="left">

	<div class="subnav">
		<div class="subnav-title">
			<a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span>Website</span></a>
		</div>

		<ul class="subnav-menu">
            <li>
                <a href="<?php echo $patchworks->webRoot; ?>" target="_blank">View Website</a>
            </li>
            <li>
				<a href="website/sitemap.php">Sitemap</a>
			</li>
			<li>
				<a href="website/articles.php">Articles</a>
			</li>
			<li>
				<a href="gallery/galleries.php">Galleries</a>
			</li>
			<li>
				<a href="website/forms.php">Forms</a>
			</li>
		</ul>
	</div>
	
	<div class="subnav" style="display: none;">
		<div class="subnav-title">
			<a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span>Schedule</span></a>
		</div>
		<div class="subnav-content">
			<div class="pagestats bar">
				<span>Availability:</span>
				<div class="progress small">
					<div class="bar" style="width:40%">
					</div>
				</div>
			</div>
			<div class="pagestats bar">
				<span>Work Load:</span>
				<div class="progress small">
					<div class="bar bar-lightred" style="width:80%">
					</div>
				</div>
			</div>
			<div class="pagestats bar">
				<span>Resources used:</span>
				<div class="progress small">
					<div class="bar bar-green" style="width:20%">
					</div>
				</div>
			</div>
			<ul class="pagestats style-3">
					<li>
						<div class="spark">
							<div class="chart" data-percent="73" data-color="#f96d6d" data-trackcolor="#fae2e2">
								73%
							</div>
						</div>
						<div class="bottom">
							<span class="name">7 Day<br />Availability</span>
						</div>
					</li>
				</ul>
		</div>
	</div>
	
</div>
<script src="js/jquery_cookie.js"></script>
<script src="js/plugins/sparklines/jquery.sparklines.min.js"></script>
<script>
	
	$(function(){
		
		if (!$.cookie("userCookie")) {
			
//			$.ajax({
//				url: "googleapi/google.analytics.php",
//				type: "POST",
//				async: true,
//				//data: "report=sitestats&fromdate=" + encodeURIComponent(switchDate($('#DatSrt').val())) + "&todate=" + encodeURIComponent(switchDate($('#DatEnd').val())),
//				data: "report=dashboard",
//				success: function(data) {
//
//					//alert( data );
//
//					try {
//
//						var jsonArray = JSON.parse(data);
//
//						//Core Stats
//
//						$('#webLeftSiteVisits').html( jsonArray['SiteVisits'] );
//						$.cookie("visitsCookie", jsonArray['SiteVisits'], { expires : 1 });
//
//						var arrayLength = jsonArray['count'];
//						var maxValue = 0;
//
//						var userData = [];
//						var userCookie = '';
//
//						for (var i = 0; i < arrayLength; i++ ) {
//
//							userData[i] = jsonArray[i].visits;
//							userCookie += (userCookie == '') ? jsonArray[i].visits : ',' + jsonArray[i].visits;
//						}
//
//						$.cookie("userCookie", userCookie, { expires : 1 });
//
//						var $el = $("#online-users");
//
//						$el.sparkline(userData, {
//							width: ($("#left").width() > 200) ? 100 : $("#left").width() - 100,
//							height: '25px',
//							enableTagOptions: true
//						});
//
//					} catch(err)  {
//
//						$('.loading').unblock();
//					}
//
//				},
//				error: function (x, e) {
//					throwAjaxError(x, e);
//				}
//			});
	
		} else {
			
			$('#webLeftSiteVisits').html( $.cookie("visitsCookie") );
			
			var userData = $.cookie("userCookie");
				userData = userData.split(",");
			
			for (i=0;i<userData.length;i++) {
				userData[i] = parseInt(userData[i]);
			}
			
			var $el = $("#online-users");
				
			$el.sparkline(userData, {
				width: ($("#left").width() > 200) ? 100 : $("#left").width() - 100,
				height: '25px',
				enableTagOptions: true
			});
			
		}
		
		$(".chart").each(function(){
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
	
	</script>