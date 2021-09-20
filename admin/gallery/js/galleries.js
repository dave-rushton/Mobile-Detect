var galleryForm;
$(function(){
	changeStatusForm = $('#changeStatusForm');
	searchForm = $('#searchForm');
	displayGallery();
});
function displayGallery() {
	var galleryTotal =0;
	var orderCount = 0;
	var staID = '';
	$('[name="tmpsta_id[]"]:checked', searchForm).each(function(){
		staID += (staID == '') ? $(this).val() : ',' + $(this).val();
	});
	$('[name="sta_id"]', searchForm).val(staID);
	var returnHTML = '';
	console.log("???");
	console.log("gallery/gallery_table.php?" + searchForm.serialize());
	$.ajax({
		url: 'gallery/gallery_table.php',
		data: searchForm.serialize(),
		type: 'GET',
		async: false,
		success: function( data ) {
			try { galTable.fnDestroy(); } catch (ex) { }
			returnHTML = data;
			$('#galleriesBody').html( data );
		
			galTable = $("table#galleriesTable").dataTable({
				"iDisplayLength": 100,
				"bDestroy": true,
				"aoColumnDefs": [
					{ "sType": "numeric", "aTargets": [ 0 ] }
				],
				"aoColumns": [
					{"bVisible": false},
					{"iDataSort": 0},
					{"bVisible": true},
					{"bSortable": true},

				]
			});
			galTable.fnSort( [ [0,'asc'] ] );
		}
	});
	return returnHTML;
}