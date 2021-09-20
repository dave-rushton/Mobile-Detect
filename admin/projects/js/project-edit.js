var projectForm, taskManagerCtrl, taskForm;
var taskTables;

$(function(){
	
	projectForm = $('#projectForm');
	taskManagerCtrl = $('#taskManagerCtrl');
	taskForm = $('#taskForm');
	
	$('[name="credat"], [name="amndat"]', projectForm).datepicker({ format: 'yyyy-mm-dd', weekStart: 1 })
		.on('changeDate', function(ev){
			//getBookings();
		});
	
	$('#launchUrl').click(function(e) {
		e.preventDefault();
		if ( $('[name="plaurl"]', projectForm).val().length > 0 ) {
			window.open($('[name="plaurl"]', projectForm).val(), '_blank');
		}
	});

	$('#actControl').click(function(e){
		e.preventDefault();
		$('#actList').slideToggle(400, function(){
			$('#actControl i').toggleClass('icon-angle-down').toggleClass('icon-angle-up');
		});
	});
	
	$('[name="tbl_id"]', projectForm).change(function(){
		$('[name="placol"]', projectForm).val( $('[name="tbl_id"] option:selected', projectForm).data('placol') );

        if ( $('#id').val() == 0) {

            $('[name="planam"]').val( $(this).find('option:selected').text() );

        }

	});
	
	taskManagerCtrl.on('click', '.flowBtn', function(e){
		e.preventDefault();
		var selectedTable = $(this).parent().parent().parent().parent().next();
		var selectedTasks = $('.selBtk:checked', selectedTable);
		
		var taskList = '';
		selectedTasks.each(function(){
			taskList += (taskList == '') ? $(this).val() : ',' + $(this).val();	
		});
		
		//alert( taskList + ' -> ' + $(this).data( 'to_sta_id' ) );
		
		$.ajax({
			url: 'projects/tasks_script.php',
			data: 'action=updatestatus&ajax=true&btk_id=' + taskList + '&sta_id=' + $(this).data( 'to_sta_id' ),
			type: 'POST',
			async: false,
			success: function( data ) {
				
				//alert( data );
				
				try {
					
					var result = JSON.parse(data);
					
					$.msgGrowl ({
						type: result.type
						, title: result.title
						, text: result.description
					});
					
					getTasks();
					
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
	
	taskManagerCtrl.on('click', '.deleteTaskBtn', function(e){
		e.preventDefault();
		
		var deleteBtn = $(this);
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Task'
			, text: 'Are you sure you wish to permanently remove this task from the project?'
			, callback: function () {
				
				var selectedTable = deleteBtn.parent().parent().parent().parent().next();
				var selectedTasks = $('.selBtk:checked', selectedTable);
				
				var taskList = '';
				selectedTasks.each(function(){
					taskList += (taskList == '') ? $(this).val() : ',' + $(this).val();	
				});
				
				//alert( 'DELETE -> ' + taskList );
				
				$.ajax({
					url: 'projects/tasks_script.php',
					data: 'action=delete&ajax=true&btk_id=' + taskList,
					type: 'POST',
					async: false,
					success: function( data ) {
						//alert( data );
				
						try {
							
							var result = JSON.parse(data);
							
							$.msgGrowl ({
								type: result.type
								, title: result.title
								, text: result.description
							});
							
							getTasks();
							
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
				
			}
		});
	});
	
	taskManagerCtrl.on('change', '.sel-all', function(e){
		e.preventDefault();
		$(this).parent().parent().parent().next().find('input').prop('checked', $(this).prop('checked') );
	});
	
	
	$( "tbody[id^='tasksActiveBody_']" ).on('click', '.sel-star', function(e){
		e.preventDefault();
		$(this).toggleClass('active');
		
		var impFlg = ($(this).hasClass('active')) ? 1 : 0;
		
		$.ajax({
			url: 'projects/tasks_script.php',
			data: 'action=updateimportant&ajax=true&btk_id=' + $(this).data('btk_id') + '&impflg=' + impFlg,
			type: 'POST',
			async: false,
			success: function( data ) {
				
				//alert( data );
				
				try {
					
					var result = JSON.parse(data);
					
					$.msgGrowl ({
						type: result.type
						, title: result.title
						, text: result.description
					});
					
					//getTasks();
					
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
	
	$('#updateProjectBtn').click(function(e){
		e.preventDefault();
		projectForm.submit();
	});
	
	projectForm.submit(function(e){
	
		e.preventDefault();
		
		//alert( 'action=update&ajax=true&' + projectForm.serialize() );
		
		$.ajax({
			url: projectForm.attr("action"),
			data: 'action=update&ajax=true&' + projectForm.serialize(),
			type: 'POST',
			async: false,
			success: function( data ) {
				
				//alert( data );
				
				try {
					
					var result = JSON.parse(data);
					
					$.msgGrowl ({
						type: result.type
						, title: result.title
						, text: result.description
					});
				
					$('#id', projectForm).val( result.id );
					
					//window.location = projectForm.data("returnurl");
					
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
	
	$('#deleteProjectBtn').click(function (e) {
		e.preventDefault();
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Project'
			, text: 'Are you sure you wish to permanently remove this project from the database?'
			, callback: function () {
				
				//alert( projectForm.serialize() );
				
				$.ajax({
					url: projectForm.attr("action"),
					data: 'action=delete&ajax=true&' + projectForm.serialize(),
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						window.location = projectForm.data("returnurl");
						
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
				
			}
		});
		return false;
	});
	
	$('[name="duedat"]', taskForm).datepicker({ format: 'yyyy-mm-dd', weekStart: 1 });
	$('#clearDueDateBtn').click(function(e){
		e.preventDefault();
		$('[name="duedat"]', taskForm).val('');
	});
	
	$('#updateTaskBtn').click(function(e){
		e.preventDefault();
		taskForm.submit();
	});
	
	taskForm.validate({
        rules: {
            btkttl: {
                minlength: 2,
                required: true
            },
			btkdur: {
                min: 0.25,
                required: true
            }
        }
		,
        focusCleanup: false,

        highlight: function (label) {
            $(label).closest('.control-group').removeClass('success').addClass('error');
        },
        success: function (label) {
            label.text('OK!').addClass('valid').closest('.control-group').addClass('success');
        },
        errorPlacement: function (error, element) {
            error.appendTo(element.parents('.controls'));
        },
		submitHandler: function(form) {
			
		}
    });
	
	taskForm.submit(function(e){
	
		e.preventDefault();
		
		if (taskForm.valid() ) {
		
			$.ajax({
				url: 'projects/tasks_script.php',
				data: 'action=update&ajax=true&' + taskForm.serialize(),
				type: 'POST',
				async: false,
				success: function (data) {
	
					var result = JSON.parse(data);
	
					$.msgGrowl({
						type: result.type,
						title: result.title,
						text: result.description
					});
					
					getTasks();
					
					$('#createTaskModal').modal('hide');
	
				},
				error: function (x, e) {
					throwAjaxError(x, e);
				}
			});
		
		} else {
			
			$.msgGrowl({
				type: 'ERROR',
				title: 'Form Error',
				text: 'There is an error in the form'
			});
			
		}
		
	});
	
	getTasks();
	
	
	getActivity();
	
	
});


function getTasks() {
	
	var taskLink;
	
	$('#taskTabs').find('a').each(function(){
	
	taskLink = $(this);
	
	if (!isNaN($(this).data('sta_id'))) {
		
		$.ajax({
			url: 'projects/task_manager_table.php',
			data: 'action=select&tblnam=PROJECT&sta_id='+taskLink.data('sta_id')+'&tbl_id=' + $('[name="pla_id"]', projectForm).val(),
			type: 'GET',
			async: false,
			success: function (data) {
				
				try { $('[id="tasksActiveTable_'+taskLink.data('sta_id')+'"]').fnDestroy(); } catch (Ex) { }
				
				$('#tasksActiveBody_'+taskLink.data('sta_id')).html( data );
				
				$('#activeTasks_'+taskLink.data('sta_id')).html( $('#tasksActiveBody_'+taskLink.data('sta_id')).find('tr').length );
				
				$('[id="tasksActiveTable_'+taskLink.data('sta_id')+'"]').dataTable({
					"bDestroy": true,
					"aoColumns": [{"bSortable": false},
								  {"bSortable": false},
								  {"bSortable": true},
								  {"bSortable": true},
								  {"bSortable": true}]
				}).width('100%');
				
				$('.dataTables_length').hide();
				
			},
			error: function (x, e) {
				throwAjaxError(x, e);
			}
		});
	
	}
	
	});
	
}

function getTaskCodes() {
	
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
						
			for (c=0;c<statusCodes.length;c++) {
			
				resultHTML += '<tr>';
				resultHTML += '<td><a href="#" class="selectStatus" data-sta_id="'+statusCodes[c].sta_id+'">'+ statusCodes[c].stanam+'</a></td>';
				resultHTML += '<td><a href="#" class="btn btn-mini btn-danger deleteStatusBtn" data-sta_id="'+statusCodes[c].sta_id+'"><i class="icon-trash"></i></a></td>';
				resultHTML += '</tr>';
			
			}
			
			$('#statusCodeBody').html( resultHTML );
			
			$('#statusCodeBody').find('.selectStatus')[0].click();

		},
		error: function (x, e) {
			throwAjaxError(x, e);
		}
	});
	
}

function getActivity() {

	$.ajax({
		url: "projects/json/project.weekhours.php",
		type: "GET",
		data: 'pla_id=' + $('[name="pla_id"]', projectForm).val(),
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
					
				for (var i = 0; i < arrayLength; i++ ) {
					
					var buildDate = jsonDates[i].split("-");
					flotTime = gd(buildDate[0], buildDate[1]-1, buildDate[2]);

					chthour.push ([flotTime,jsonHours[i]]);
					
				}
				
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