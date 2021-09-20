var customerTable;

$(function(){
	
	customerTable = buildTable( $('#customerTable') );
	
});

function buildTable(iTable) {

	var opt = {
		"bDestroy": true,
		"sPaginationType": "full_numbers",
		"oLanguage":{
			"sSearch": "<span>Search:</span> ",
			"sInfo": "Showing <span>_START_</span> to <span>_END_</span> of <span>_TOTAL_</span> entries",
			"sLengthMenu": "_MENU_ <span>entries per page</span>"
		}
	};
	if(iTable.hasClass("dataTable-noheader")){
		opt.bFilter = false;
		opt.bLengthChange = false;
	}
	if(iTable.hasClass("dataTable-nofooter")){
		opt.bInfo = false;
		opt.bPaginate = false;
	}
	if(iTable.hasClass("dataTable-nosort")){
		var column = $(this).attr('data-nosort');
		column = column.split(',');
		for (var i = 0; i < column.length; i++) {
			column[i] = parseInt(column[i]);
		};
		opt.aoColumnDefs =  [
		{ 'bSortable': false, 'aTargets': column }
		];
	}
	if(iTable.hasClass("dataTable-scroll-x")){
		opt.sScrollX = "100%";
		opt.bScrollCollapse = true;
	}
	if(iTable.hasClass("dataTable-scroll-y")){
		opt.sScrollY = "300px";
		opt.bPaginate = false;
		opt.bScrollCollapse = true;
	}
	if(iTable.hasClass("dataTable-reorder")){
		opt.sDom = "Rlfrtip";
	}
	if(iTable.hasClass("dataTable-colvis")){
		opt.sDom = 'C<"clear">lfrtip';
		opt.oColVis = {
			"buttonText": "Change columns <i class='icon-angle-down'></i>"
		};
	}
	if(iTable.hasClass('dataTable-tools')){
		if($(this).hasClass("dataTable-colvis")){
			opt.sDom= 'TC<"clear">lfrtip';
		} else {
			opt.sDom= 'T<"clear">lfrtip';
		}
		opt.oTableTools = {
			"sSwfPath": "js/plugins/datatable/swf/copy_csv_xls_pdf.swf"
		};
	}
	if(iTable.hasClass("dataTable-scroller")){
		opt.sScrollY = "300px";
		opt.bDeferRender = true;
		opt.sDom = "frtiS";
		opt.sAjaxSource = "js/plugins/datatable/demo.txt";
	}
	var oTable = iTable.dataTable(opt);
	$('.dataTables_filter input').attr("placeholder", "Search here...");
	$(".dataTables_length select").wrap("<div class='input-mini'></div>").chosen({
		disable_search_threshold: 9999999
	});
	$("#check_all").click(function(e){
		$('input', oTable.fnGetNodes()).prop('checked',this.checked);
	});
	if($(this).hasClass("dataTable-fixedcolumn")){
		new FixedColumns( oTable );
	}
	if($(this).hasClass("dataTable-columnfilter")){
		oTable.columnFilter({
			"sPlaceHolder" : "head:after"
		});
	}
	
	return oTable;
	
}