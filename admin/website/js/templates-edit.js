var templateForm;
var templateLayout = {};

$(function(){

    //getDefaults();

    templateForm = $('#templateForm');

    templateLayout.rows = [];

    //var row = {};
    //    row.columns = [];
    //
    //column = {"colspan" : "8"}
    //row.columns.push(column);
    //column = {"colspan" : "4"}
    //row.columns.push(column);
    //templateLayout.rows.push(row);
    //
    //
    //var row = {};
    //row.columns = [];
    //column = {"colspan" : "12"}
    //row.columns.push(column);
    //templateLayout.rows.push(row);
    //
    //
    //var row = {};
    //row.columns = [];
    //column = {"colspan" : "6"}
    //row.columns.push(column);
    //column = {"colspan" : "6"}
    //row.columns.push(column);
    //templateLayout.rows.push(row);


    try {

        templateLayout.rows = JSON.parse($('#tplobj').val());

    }
    catch(err) {

        templateLayout.rows = [];

    }



    displayStructure();

    $('#addNewRow').click(function(e){

        e.preventDefault();

        var row = {};
        row.columns = [];
        column = {"colspan" : "12"}
        row.columns.push(column);
        templateLayout.rows.push(row);

        displayStructure();

    });

    $('#templateLayout').on('click', '.deleteRow', function(e){
        e.preventDefault();

        var rowNum = $(this).data('rownum');
        templateLayout.rows.splice(rowNum,1);

        displayStructure();
    });

    $('#templateLayout').on('click', '.addColumnBtn', function(e){
        e.preventDefault();

        var rowNum = $(this).data('rownum');

        column = {"colspan" : $(this).data('colcnt') }
        templateLayout.rows[rowNum].columns.push(column);

        displayStructure();
    });

    $('#templateLayout').on('click', '.deleteColumn', function(e){
        e.preventDefault();

        var rowNum = $(this).data('rownum');
        var colNum = $(this).data('colnum');
        templateLayout.rows[rowNum].columns.splice(colNum,1);

        displayStructure();
    });


    $('#templateLayout').on('click', '.editColumn', function(e){
        e.preventDefault();

        $(this).next('form').slideToggle();

    });

    $('#templateLayout').on('submit', '.columnForm', function(e){
        e.preventDefault();

        $(this).find('[name="elementname"]').val( $(this).find('[name="pageelement"]').find('option:selected').text() );

        templateLayout.rows[$(this).prev('.editColumn').data('rownum')].columns[$(this).prev('.editColumn').data('colnum')] = objectifyForm( $(this).serializeArray() );
        displayStructure();
    })

    templateForm.validate({
        rules: {
            tplnam: {
                minlength: 2,
                required: true
            },
			tplfil: {
                date: true,
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

    templateForm.eq(0).find('input').eq(0).focus();

	templateForm.submit(function(e){

		e.preventDefault();
		
		templateForm.block({ 
			message: '<h4>Updating</h4>', 
			centerY: 0,
			centerX: 0,
			css: { top: '10px', left: '', right: '10px', border: '2px solid #a00' } 
		});
		
		if ($(this).valid()) {

			$.ajax({
				url: templateForm.attr("action"),
				data: 'action=update&ajax=true&' + templateForm.serialize(),
				type: 'POST',
				async: false,
				success: function( data ) {
					
					var result = JSON.parse(data);
					
					$.msgGrowl ({
						type: result.type
						, title: result.title
						, text: result.description
					});
					
					$('#id', templateForm ).val( result.id );

				},
				error: function (x, e) {
					throwAjaxError(x, e);
				}
			});

//			$('#customerFormRow').unblock();
		}
		else {
			$.msgGrowl ({
				type: 'error'
				, title: 'Invalid Form'
				, text: 'There is an error in the form'
			});
		}
					
		templateForm.unblock();
		
	});
	
	$('#deleteTemplateBtn').click(function (e) {
		e.preventDefault();
		
		$.msgAlert ({
			type: 'warning'
			, title: 'Delete This Template'
			, text: 'Are you sure you wish to permanently remove this template from the database?'
			, callback: function () {
				
				templateForm.block({ 
					message: '<h4>Deleting</h4>', 
					centerY: 0,
					centerX: 0,
					css: { top: '10px', left: '', right: '10px', border: '2px solid #a00' } 
				});
				
				$.ajax({
					url: templateForm.attr("action"),
					data: 'action=delete&ajax=true&' + templateForm.serialize(),
					type: 'POST',
					async: false,
					success: function( data ) {
						
						var result = JSON.parse(data);
						
						$.msgGrowl ({
							type: result.type
							, title: result.title
							, text: result.description
						});
						
						window.location = templateForm.data("returnurl");
						
					},
					error: function (x, e) {
						throwAjaxError(x, e);
					}
				});
				
			}
		});
		return false;
	});

    $('#templateLayout').sortable({
        items: ".rowWrapper",
        handle: ".moveRow",
        start: function(event, ui) {
            //ReceiveElement = ui.item.parent();
            //DraggerModule = 'moveElement';
        },
        receive : function(event, ui){
            //ReceiveElement = ui.item.parent();
        },
        change: function(event, ui) {
            //ReceiveElement = ui.item.parent();
        },
        stop: function(event, ui) {

            var indexString = [];
            $('.moveRow', $('#templateLayout')).each(function(){
                indexString.push($(this).data('rownum'));
            })

            var newArray = [];
            for (i=0;i<indexString.length;i++) {
                newArray.push(templateLayout.rows[indexString[i]]);
            }

            templateLayout.rows = newArray;

            console.log(newArray);

            displayStructure();

            alert('done');

        }
    });

});


function getDefaults() {

    $.ajax({
        type: "GET",
        url: "website/pbparts/pagebuilder.xml",
        dataType: "xml",
        success: function(xml){

            $(xml).find('module').each(function(){

                var selectHTML = '';

                $(this).find('element').each(function(){

                    var elementName = $(this).children('name').text();
                    var elementType = $(this).children('file').text();

                    selectHTML += '<option value="'+elementType+'">'+elementName+'</option>'

                    $(this).children('form').find('field').each(function() {



                    });

                });

                $('#pageElement').html( selectHTML );

            });

        },
        error: function() {
            alert("An error occurred while processing XML file.");
        }
    });

}


function displayStructure() {

    var templateString = '';

    for (r=0;r<templateLayout.rows.length;r++) {

        templateString += '<div class="rowWrapper">';
        templateString += '<a class="btn btn-mini moveRow" data-rownum="'+r+'"><i class="icon icon-reorder"></i></a> ';
        templateString += '<div class="row-fluid">';

        for (c=0;c<templateLayout.rows[r].columns.length;c++) {

            templateString += '<div class="span'+templateLayout.rows[r].columns[c].colspan+'">';

            if (templateLayout.rows[r].columns[c].elementname != null) {
                templateString += '<span class="elementname">' + templateLayout.rows[r].columns[c].elementname + '</span> ';
            }

            templateString += '<a href="#" class="btn btn-mini editColumn" data-rownum="'+r+'" data-colnum="'+c+'">Edit</a> ';

            //columnFormObject = $('#columnFormWrapper').children('form');
            //var hiddenFields = '<input type="hidden" name="row" value="'+r+'" /><input type="hidden" name="column" value="'+c+'" />'
            //columnFormObject.append(hiddenFields);

            templateString += $('#columnFormWrapper').html();



            templateString += '<a href="#" class="btn btn-mini deleteColumn" data-rownum="'+r+'" data-colnum="'+c+'">Delete</a>';
            templateString += '</div>';

        }

        var btnString = '';
            btnString += '<div class="btn-group">';
            btnString += '<button class="btn dropdown-toggle btn-mini" data-toggle="dropdown">';
            btnString += '<i class="icon icon-plus"></i> ';
            btnString += '<span class="caret"></span>';
            btnString += '</button>';
            btnString += '<ul class="dropdown-menu">';
            btnString += '<li>';
            btnString += '<a href="#" class="addColumnBtn" data-rownum="'+r+'" data-colcnt="3">3</a>';
            btnString += '</li>';
            btnString += '<li>';
            btnString += '<a href="#" class="addColumnBtn" data-rownum="'+r+'" data-colcnt="4">4</a>';
            btnString += '</li>';
            btnString += '<li>';
            btnString += '<a href="#" class="addColumnBtn" data-rownum="'+r+'" data-colcnt="6">6</a>';
            btnString += '</li>';
            btnString += '<li>';
            btnString += '<a href="#" class="addColumnBtn" data-rownum="'+r+'" data-colcnt="12">12</a>';
            btnString += '</li>';
            btnString += '</ul>';
            btnString += '</div>';


        templateString += '<a href="#" class="btn btn-mini btn-danger deleteRow" data-rownum="'+r+'"> <i class="icon icon-remove"></i> </a> ';

        templateString += '<div class="addColumn" data-rownum="'+r+'"> '+btnString+' </div> ';

        templateString += '</div>';
        templateString += '</div>';

    }

    console.log( JSON.stringify(templateLayout.rows) );

    $('#tplobj').text( JSON.stringify(templateLayout.rows) );

    $('#templateLayout').html( templateString );

}

function objectifyForm(formArray) {//serialize data function

    var returnArray = {};
    for (var i = 0; i < formArray.length; i++){
        returnArray[formArray[i]['name']] = formArray[i]['value'];
    }
    return returnArray;
}