
<div id="left">
	<form action="system/searchresults.php" method="GET" class='search-form'>
			<div class="search-pane">
				<input type="text" name="keyword" placeholder="Search here...">
				<button type="submit"><i class="icon-search"></i></button>
			</div>
		</form>
	
	<div class="subnav">
		<div class="subnav-title">
			<a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span>Stock</span></a>
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
		</div>
	</div>
	
</div>
<script src="js/plugins/sparklines/jquery.sparklines.min.js"></script>
<script>
	
	$(function(){
	
		var $el = $("#online-users"),
		userData = [255,455,385,759,500,284,581,684,255,455,385,759,500,293,585,342,684];

		$el.sparkline(userData, {
			width: ($("#left").width() > 200) ? 100 : $("#left").width() - 100,
			height: '25px',
			enableTagOptions: true
		});
		
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