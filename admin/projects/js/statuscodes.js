var statusCodeForm;

var selectedStatus;

$(function(){
	
	statusCodeForm = $('#statusCodeForm');
	
	$('#createStatusBtn').click(function(e){
		e.preventDefault();
		statusCodeForm.submit();
	});
	
	statusCodeForm.submit(function(e){
	
		e.preventDefault();
		
		$.ajax({
			url: statusCodeForm.attr('action'),
			data: 'action=update&ajax=true&' + statusCodeForm.serialize(),
			type: 'POST',
			async: false,
			success: function (data) {
				
				var result = JSON.parse(data);

				$.msgGrowl({
					type: result.type,
					title: result.title,
					text: result.description
				});
				
				if (result.type == 'success') {
					
					$('[name="stanam"]', statusCodeForm).val('');
					
					getCodes();
				
//					resultHTML = '<tr>';
//					resultHTML += '<td><a href="#">'+ $('[name="stanam"]', statusCodeForm).val() +'</a></td>';
//					resultHTML += '<td><a href="#" class="btn btn-mini btn-danger deleteStatusBtn" data-sta_id="'+result.id+'"><i class="icon-trash"></i></a></td>';
//					resultHTML += '</tr>';
//	
//					resultHTML2 = '<tr>';
//					resultHTML2 += '<td>'+ $('[name="stanam"]', statusCodeForm).val() +'</td>';
//					resultHTML2 += '<td><a href="#" class="btn btn-mini"><i class="icon-check-empty"></i></a></td>';
//					resultHTML2 += '</tr>';
//					
//					$('#statusCodeBody').append( resultHTML );
//					$('#statusFlowBody').append( resultHTML2 );
					
				}

			},
			error: function (x, e) {
				throwAjaxError(x, e);
			}
		});
		
	});
	
	$('#statusCodeTable').on( 'click', '.selectStatus', function(e){
		e.preventDefault();
		$('tr', '#statusCodeBody').removeClass('info');
		$(this).parent().parent().addClass('info');
		
		selectedStatus = $(this).data('sta_id');
		
		//
		// get status flow and amend tick box classes
		//
		
		//alert( selectedStatus );
		
		$.ajax({
			url: 'system/statusflow_script.php',
			data: 'action=select&ajax=true&flo_id=0&frm_id=' + selectedStatus,
			type: 'POST',
			async: false,
			success: function (data) {
				
				var cblink;
				
				$('.statusCheck').each(function(){
					$(this).removeClass('btn-success').find('i').addClass('icon-check-empty').removeClass('icon-check');
				});
				
				var result = JSON.parse(data);
				
				for (f=0;f<result.length;f++) {
					
					var cb = $(".statusCheck[data-sta_id='" + result[f].to_id + "']");
					
					cb.addClass('btn-success').find('i').removeClass('icon-check-empty').addClass('icon-check');
						
				}

			},
			error: function (x, e) {
				throwAjaxError(x, e);
			}
		});
		
	});
	
	$('#statusCodeTable').on( 'click', '.deleteStatusBtn', function(e){
		e.preventDefault();
		
		var alink = $(this);
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Status'
			, text: 'Are you sure you wish to permanently remove this status and related status flow records from the database?'
			, callback: function () {
		
				$('.current').removeClass('current');
				
				var remRow = alink.parent().parent();
					remRow.addClass('current');
					
				var statRows = $('#statusCodeBody').find('tr');
				var arow = statRows.index( $('.current') );
				
				$.ajax({
					url: statusCodeForm.attr('action'),
					data: 'action=delete&ajax=true&sta_id=' + alink.data('sta_id'),
					type: 'POST',
					async: false,
					success: function (data) {
						
						var result = JSON.parse(data);
		
						$.msgGrowl({
							type: result.type,
							title: result.title,
							text: result.description
						});
						
						getCodes();
						
					}
				});
			}
		});
		
	});
	
	
	$('#statusFlowBody').on('click', '.btn', function(e){
		e.preventDefault();
		$(this).toggleClass('btn-success').find('i').toggleClass('icon-check-empty').toggleClass('icon-check');
		
		if ( $(this).hasClass('btn-success') ) { 
			
			$.ajax({
				url: 'system/statusflow_script.php',
				data: 'action=update&ajax=true&flo_id=0&frm_id=' + selectedStatus + '&to_id=' + $(this).data('sta_id'),
				type: 'POST',
				async: false,
				success: function (data) {
					
					var result = JSON.parse(data);
	
					$.msgGrowl({
						type: result.type,
						title: result.title,
						text: result.description
					});
					
					if (result.type == 'success') {
						
					}
	
				},
				error: function (x, e) {
					throwAjaxError(x, e);
				}
			});
			
			
		} else {
			
			$.ajax({
				url: 'system/statusflow_script.php',
				data: 'action=remove&ajax=true&flo_id=0&frm_id=' + selectedStatus + '&to_id=' + $(this).data('sta_id'),
				type: 'POST',
				async: false,
				success: function (data) {
					
					var result = JSON.parse(data);
	
					$.msgGrowl({
						type: result.type,
						title: result.title,
						text: result.description
					});
	
				},
				error: function (x, e) {
					throwAjaxError(x, e);
				}
			});
			
		}
		
	});
	
	
	
	getCodes();
	
	
});

function getCodes() {
	
	$.ajax({
		url: statusCodeForm.attr('action'),
		data: 'action=select&ajax=true&' + statusCodeForm.serialize(),
		type: 'POST',
		async: false,
		success: function (data) {
			
			resultHTML = '';
			resultHTML2 = '';
			
			var statusCodes = JSON.parse(data);
			
			resultHTML += '<tr>';
			resultHTML += '<td><a href="#" class="selectStatus" data-sta_id="0">New Records</a></td>';
			resultHTML += '<td></td>';
			resultHTML += '</tr>';
			
//			resultHTML2 += '<tr data-sta_id="0">';
//			resultHTML2 += '<td>New Record</td>';
//			resultHTML2 += '<td><a href="#" class="btn btn-mini statusCheck" data-sta_id="0"><i class="icon-check-empty"></i></a></td>';
//			resultHTML2 += '</tr>';
			
			for (c=0;c<statusCodes.length;c++) {
			
				resultHTML += '<tr>';
				resultHTML += '<td><a href="#" class="selectStatus" data-sta_id="'+statusCodes[c].sta_id+'">'+ statusCodes[c].stanam+'</a></td>';
				resultHTML += '<td><a href="#" class="btn btn-mini btn-danger deleteStatusBtn" data-sta_id="'+statusCodes[c].sta_id+'"><i class="icon-trash"></i></a></td>';
				resultHTML += '</tr>';

				resultHTML2 += '<tr data-sta_id="'+statusCodes[c].sta_id+'">';
				resultHTML2 += '<td>'+ statusCodes[c].stanam+'</td>';
				resultHTML2 += '<td><a href="#" class="btn btn-mini statusCheck" data-sta_id="'+statusCodes[c].sta_id+'"><i class="icon-check-empty"></i></a></td>';
				resultHTML2 += '</tr>';
			
			}
			
			$('#statusCodeBody').html( resultHTML );
			$('#statusFlowBody').html( resultHTML2 );
			
			$('#statusCodeBody').find('.selectStatus')[0].click();

		},
		error: function (x, e) {
			throwAjaxError(x, e);
		}
	});
	
}