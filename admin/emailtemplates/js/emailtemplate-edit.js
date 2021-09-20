var emailTemplateForm;
var emailSectionForm;
$(function() {
    emailTemplateForm = $("#emailTemplateForm");
    emailSectionForm = $("#emailSectionForm");
    emailTemplateForm.validate({
        focusCleanup: false,
        highlight: function(label) {
            $(label).closest('.control-group').removeClass('success').addClass('error');
        },
        success: function(label) {
            label.text('OK!').addClass('valid').closest('.control-group').addClass('success');
        },
        errorPlacement: function(error, element) {
            error.appendTo(element.parents('.controls'));
        },
        submitHandler: function(emailTemplateForm) {}
    });
    emailTemplateForm.find('input').eq(1).focus();
    emailTemplateForm.submit(function(e) {
        e.preventDefault();
        emailTemplateForm.block({
            message: '<h4>Updating</h4>',
            centerY: 0,
            centerX: 0,
            css: {
                top: '10px',
                left: '',
                right: '10px',
                border: '2px solid #a00'
            }
        });
        if ($(this).valid()) {
            $.ajax({
                url: emailTemplateForm.attr("action"),
                data: 'action=update&ajax=true&' + emailTemplateForm.serialize(),
                type: 'POST',
                async: false,
                success: function(data) {
                    var result = JSON.parse(data);
                    $.msgGrowl({
                        type: result.type,
                        title: result.title,
                        text: result.description
                    });
                    $('#id', emailTemplateForm).val(result.id);
                },
                error: function(x, e) {
                    throwAjaxError(x, e);
                }
            });
        } else {
            $.msgGrowl({
                type: 'error',
                title: 'Invalid Form',
                text: 'There is an error in the form'
            });
        }
        emailTemplateForm.unblock();
    });
    $('#createSectionBtn').click(function(e) {
        e.preventDefault();
        $("#sectionTableBox").hide();
        $("#sectionBox").show(); // clear form 
        $('[name="ems_id"]', emailSectionForm).val(0);
        $('[name="emstyp"]', emailSectionForm).val('text');
        $('[name="sta_id"]', emailSectionForm).filter("[value='0']").prop("checked", true);
        $('[name="emstyp"]', emailSectionForm).focus();
    });
    $('#cancelSectionBtn').click(function(e) {
        e.preventDefault();
        $("#sectionTableBox").show();
        $("#sectionBox").hide();
    });
    emailSectionForm.submit(function(e) {
        e.preventDefault();
        if (emailSectionForm.valid()) {
            $('#sectionBox').block({
                message: 'Updating'
            });

            //alert( emailSectionForm.attr("action")+'?action=update&ajax=true&' + emailSectionForm.serialize() );

            $.ajax({
                url: emailSectionForm.attr("action"),
                data: 'action=update&ajax=true&' + emailSectionForm.serialize(),
                type: 'POST',
                async: false,
                success: function(data) {
                    var result = JSON.parse(data);
                    //console.log(result);
                    $.msgGrowl({
                        type: result.type,
                        title: result.title,
                        text: result.description
                    });
                    if (result.type == 'success') {
                        $("#sectionTableBox").show();
                        $("#sectionBox").hide();

                        //$('#id', emailSectionForm ).val( result.id );
                        // $('#atrId', emailSectionForm ).val( result.id );
                        
                    }
                    getSections();
                },
                error: function(x, e) {
                    throwAjaxError(x, e);
                }
            });
            $('#sectionBox').unblock();
        } else {
            $.msgGrowl({
                type: 'error',
                title: 'Invalid Form',
                text: 'There is an error in the form'
            });
        }
    });
    $('#updateSectionBtn').click(function(e) {
        e.preventDefault();
        emailSectionForm.submit();
        $("#sectionTableBox").show();
        $("#sectionBox").hide();
    });
    $('#sectionTableBody').on('click', '.editEmailSection', function(e) {
        e.preventDefault();
        $.ajax({
            url: emailSectionForm.attr("action"),
            data: 'action=select&ems_id=' + $(this).data("ems_id"),
            type: 'GET',
            async: true,
            success: function(data) {
                try {
                    var emsSection = JSON.parse(data);
                    console.log(emsSection);
                    $('[name="emt_id"]', emailSectionForm).val(emsSection[0].emt_id);
                    $('[name="ems_id"]', emailSectionForm).val(emsSection[0].ems_id);
                    $('[name="emstyp"]', emailSectionForm).val(emsSection[0].emstyp);
                    $('[name="sta_id"][value="' + emsSection[0].sta_id + '"]', emailSectionForm).prop("checked", true);
                    $("#sectionTableBox").hide();
                    $("#sectionBox").show();
                } catch (ex) {
                    alert(ex);
                }
            },
            error: function(x, e) {
                throwAjaxError(x, e);
            }
        });
    });
    $('#sectionTableBody').on('click', '.delEmailSection', function(e) {
        e.preventDefault();
        var ems_id = $(this).data('ems_id');
        $.msgAlert({
            type: 'warning',
            title: 'Delete This Label',
            text: 'Are you sure you wish to permanently remove this label from the database?',
            callback: function() {
                $.ajax({
                    url: emailSectionForm.attr("action"),
                    data: 'action=delete&ajax=true&ems_id=' + ems_id,
                    type: 'POST',
                    async: false,
                    success: function(data) {
                        var result = JSON.parse(data);
                        $.msgGrowl({
                            type: result.type,
                            title: result.title,
                            text: result.description
                        });
                        getSections();
                    },
                    error: function(x, e) {
                        throwAjaxError(x, e);
                    }
                });
            }
        });
    });
    $('#sectionTableBody').sortable({
        handle: '.emailSectionSort',
        stop: function(event, ui) {
            var secLst = '';
            $('.editEmailSection', $('#sectionTableBody')).each(function() {
                secLst += (secLst == '') ? $(this).data('ems_id') : ',' + $(this).data('ems_id');
            });
            $.ajax({
                url: "emailtemplates/emailsection_script.php",
                data: 'action=resort&ajax=true&ems_id=' + secLst,
                type: 'POST',
                async: false,
                success: function(data) {
                    var result = JSON.parse(data);
                    $.msgGrowl({
                        type: result.type,
                        title: result.title,
                        text: result.description
                    });
                },
                error: function(x, e) {
                    throwAjaxError(x, e);
                }
            });
        }
    });
    getSections();
});

function getSections() {
    $.ajax({
        url: 'emailtemplates/emailsection_table.php',
        data: 'emt_id=' + $('[name="emt_id"]', emailTemplateForm).val(),
        type: 'GET',
        async: true,
        success: function(data) {
            $('#sectionTableBody').html(data);
        },
        error: function(x, e) {
            throwAjaxError(x, e);
        }
    });
    $('[rel="tooltip"]', $('#sectionTableBody')).tooltip();
}