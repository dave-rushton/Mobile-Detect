$(function(){

	var attrGroupForm = $('#attrGroupForm');
	var attrLabelForm = $('#attrLabelForm');

	//
	// basic screen functionality
	//
	
	$('#createAttrLabelBtn').click(function(e){
		e.preventDefault();
		
		// clear form
		$('[name="atllbl"]',attrLabelForm ).val( '' );
		$('[name="duplicate_reference"]',attrLabelForm ).val( '' );
		$('[name="atl_id"]',attrLabelForm ).val( 0 );
		$('#AtrLst_UL',attrLabelForm).html('');
		$('[name="atltyp"]', attrLabelForm).val('text');
		$('[name="atltyp"]', attrLabelForm).change();
		$('[name="atlreq"]', attrLabelForm).prop('checked', false);
		$('[name="atlspc"]', attrLabelForm).prop('checked', false);
		$('#attrLabelTableBox').hide();
		$('#attrLabelBox').show();

        $('[name="atllbl"]',attrLabelForm ).focus();



	});
	
	$('#cancelLabelBtn').click(function(e){
		e.preventDefault();
		$('#attrLabelTableBox').show();
		$('#attrLabelBox').hide();
		
	});
	
	
	//
	// form functionality
	//
	
//	attrGroupForm.validate({
//        rules: {
//            atrnam: {
//                minlength: 2,
//                required: true
//            },
//            sta_id: {
//                required: true
//            }
//        }
//    });

    attrGroupForm.submit(function(e){
	
		e.preventDefault();
		
		if (attrGroupForm.valid()) {
			
			//$('#attrGroupBox').block({ message: 'Updating' });
			
			//alert( attrGroupForm.attr("action")+'?ajax=true&' + attrGroupForm.serialize() );
			
			$.ajax({
				url: attrGroupForm.attr("action"),
				data: 'action=update&ajax=true&' + attrGroupForm.serialize(),
				type: 'POST',
				async: false,
				success: function( data ) {

					console.log(data);

					var result = JSON.parse(data);
					
					$.msgGrowl ({
						type: result.type
						, title: result.title
						, text: result.description
					});
					
					if (result.type == 'success') {
						$('#createAttrLabelBtn').show();
						$('#deleteAttrGroupBtn').removeClass('hide');
						$('#id', attrGroupForm ).val( result.id );
						$('#atrId', attrLabelForm ).val( result.id );
					}
					
				},
				error: function (x, e) {
					throwAjaxError(x, e);
				}
			});

			$('#attrGroupBox').unblock();
		}
		else {
			$.msgGrowl ({
				type: 'error'
				, title: 'Invalid Form'
				, text: 'There is an error in the form'
			});
		}
		
	});
	
	$('#updateAttrGroupBtn,#updateAttrGroupBtn1').click(function(e){
		e.preventDefault();
		attrGroupForm.submit();

		//Regenerate colnum select list without refresh

		$('#attrLabelTableBox').show();
		$('#attrLabelBox').hide();

		var optionsHTML = "";
		for (var i = 1; i <= $('[name="numcol"]', attrGroupForm).val(); i++) {
			optionsHTML+="<option value='" + i + "'>" + i + "</option>";
		}
		$('[name="colnum"]', attrLabelForm).html(optionsHTML);

	});
	
//	attrLabelForm.validate({
//        rules: {
//            atllbl: {
//                minlength: 2,
//                required: true
//            }
//        }
//    });
	
	attrLabelForm.submit(function(e){
		
		e.preventDefault();
		
		var AtlLst = '';
		$('.AtlLstVal').each(function(){
			AtlLst += ( AtlLst == '' ) ? $(this).val() : ',' + $(this).val();
		});
		
		$('[name="atllst"]',attrLabelForm ).val( AtlLst );
		
		if (attrLabelForm.valid()) {
			
			$.ajax({
				url: attrLabelForm.attr("action"),
				data: 'action=update&ajax=true&' + attrLabelForm.serialize(),
				type: 'POST',
				async: false,
				success: function( data ) {
					
					var result = JSON.parse(data);
					
					$.msgGrowl ({
						type: result.type
						, title: result.title
						, text: result.description
					});
					
					if (result.type == 'success') {					
						$('#deleteAttrLabelBtn').removeClass('hide');
						$('#id', attrLabelForm ).val( result.id );
					}
					
					getAttrLabels();
					
					$('#attrLabelTableBox').show();
					$('#attrLabelBox').hide();
					
				},
				error: function (x, e) {
					throwAjaxError(x, e);
				}
			});
			
		
		}
			
	});
	
	$('#updateAttrLabelBtn').click(function(e){
		e.preventDefault();
		attrLabelForm.submit();
	});
	
	//
	// attribute type functionality
	//
	
	$('[name="atltyp"]', attrLabelForm).change(function(){
		
		if ( $(this).val() == 'select' || $(this).val() == 'radio' ) {
			$('#AtlLstDiv').removeClass('hide');
		} else {
			$('#AtlLstDiv').addClass('hide');
		}
		
	});
	
	$('#addAltLst').click(function(e){
		
		e.preventDefault();
		
		if ( $('#AddAltLst').val() != '' ) {
			
			var resultHTML  = '<li style="list-style: none; padding: 0; margin: 0 0 3px 0;">';
				resultHTML += '<div class="input-append">';
				resultHTML += '<input type="text" class="input-medium AtlLstVal" value="'+$('#AddAltLst').val()+'" />';
				resultHTML += '<div class="btn-group">';
				resultHTML += '<button class="btn removeAltLst" type="button" rel="tooltip" title="Remove From List"><i class="icon icon-remove"></i></button>';
				resultHTML += '<a href="#" class="btn" type="button" rel="tooltip" title="Drag To Reorder List"><i class="icon icon-reorder moveLI"></i></a>';
				resultHTML += '</div>';
				resultHTML += '</div>';
				resultHTML += '</li>';
			
			$('#AtrLst_UL').append( resultHTML );
			
			$('#AddAltLst').val('');
			
		}
		
	});
	
	$('#AtrLst_UL').on('click', '.removeAltLst', function (e) {
		e.preventDefault();
		$(this).closest('li').remove();
	});
	
	$('#attrLabelBody').on('click', '.editAttrLabel', function (e) {
		e.preventDefault();
		
		$.ajax({
			url: 'attributes/attrlabel_script.php',
			data: 'action=select&atl_id=' + $(this).data("atl_id"),
			type: 'GET',
			async: true,
			success: function( data ) {
				
				try {
					
					var attrLabel = JSON.parse(data);
					
					$('[name="atllbl"]',attrLabelForm ).val( attrLabel[0].atllbl );
					$('[name="atl_id"]',attrLabelForm ).val( attrLabel[0].atl_id );
					$('[name="atltyp"]',attrLabelForm ).val( attrLabel[0].atltyp );
					$('[name="srctyp"]',attrLabelForm ).val( attrLabel[0].srctyp );
					$('[name="srtord"]',attrLabelForm ).val( attrLabel[0].srtord );
					$('[name="colnum"]',attrLabelForm ).val( attrLabel[0].colnum );
					$('[name="duplicate_reference"]',attrLabelForm ).val( attrLabel[0].duplicate_reference );

					$('[name="atlreq"]',attrLabelForm ).prop('checked', (attrLabel[0].atlreq == 1) ? true : false );
					$('[name="atlspc"]',attrLabelForm ).prop('checked', (attrLabel[0].atlspc == 1) ? true : false );
					$('[name="srcabl"]',attrLabelForm ).prop('checked', (attrLabel[0].srcabl == 1) ? true : false );
					
					$('[name="atltyp"]', attrLabelForm).change();
					
					var atlArr = attrLabel[0].atllst.split(",");
					var resultHTML = '';
					
					if (attrLabel[0].atllst.length > 0) {
					
						for (i=0;i<atlArr.length;i++) {
							
							resultHTML += '<li style="list-style: none; padding: 0; margin: 0 0 3px 0;">';
							resultHTML += '<div class="input-append">';
							resultHTML += '<input type="text" class="input-medium AtlLstVal" value="'+atlArr[i]+'" />';
							resultHTML += '<div class="btn-group">';
							resultHTML += '<button class="btn" type="button" rel="tooltip" title="Remove From List"><i class="icon icon-remove removeAltLst"></i></button>';
							resultHTML += '<a href="#" class="btn" type="button" rel="tooltip" title="Drag To Reorder List"><i class="icon icon-reorder moveLI"></i></a>';
							resultHTML += '</div>';
							resultHTML += '</div>';
							resultHTML += '</li>';
							
						}
					
					}
					
					$('#AtrLst_UL').html( resultHTML );
					
					$('#attrLabelTableBox').hide();
					$('#attrLabelBox').show();
					
					
				} catch (ex) {
					
					alert(ex);
					
				}
				
				
			},
			error: function (x, e) {
				throwAjaxError(x, e);
			}
		});
		
	});
	
	$('#attrLabelBody').on('click', '.deleteAttrLabelBtn', function (e) {
		e.preventDefault();
		
		var atlId = $(this).data('atl_id');
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Label'
			, text: 'Are you sure you wish to permanently remove this label from the database?'
			, callback: function () {
				
				$.ajax({
					url: 'attributes/attrlabel_script.php',
					data: 'action=delete&ajax=true&atl_id=' + atlId,
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						getAttrLabels();
		
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
				
			}
		});
		
	});
	
	$('#AtrLst_UL').sortable({ handle: '.moveLI' });
	
	$('#attrLabelBody').sortable({ 
		handle: '.attrLabelSort' ,
		stop: function( event, ui ) {
			
			var atlLst = '';
			
			$('.editAttrLabel', $('#attrLabelBody')).each(function(){
			
				atlLst += (atlLst == '') ? $(this).data('atl_id') : ',' + $(this).data('atl_id');
				
			});
			
			$.ajax({
				url: 'attributes/attrlabel_script.php',
				data: 'action=resort&ajax=true&atl_id=' + atlLst,
				type: 'POST',
				async: false,
				success: function( data ) {
					
					var result = JSON.parse(data);
					
					$.msgGrowl ({
						type: result.type
						, title: result.title
						, text: result.description
					});
	
				},
				error: function (x, e) {
					throwAjaxError(x, e);
				}
			});
			
		}
	});
	
	getAttrLabels();
});

function getAttrLabels() {

	$.ajax({
		url: 'attributes/attrlabels_table.php',
		data: 'atr_id=' + $('[name="atr_id"]',attrGroupForm).val(),
		type: 'GET',
		async: true,
		success: function( data ) {
			$('#attrLabelBody').html( data );
			
		},
		error: function (x, e) {
			throwAjaxError(x, e);
		}
	});
	
	$('[rel="tooltip"]', $('#attrLabelBody')).tooltip();
	
}