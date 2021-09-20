$(function(){


    $('.qtyselect').change(function(){

        if ($(this).val() < 10) {

            $(this).closest('form').find('[name="qty"]').val( $(this).val() );
            $(this).closest('form').submit();

        } else {

            $(this).closest('form').find('.qtyinputwrapper').removeClass('hide');
            $(this).closest('form').find('.qtyselectwrapper').addClass('hide');
            $(this).closest('form').find('[name="qty"]').val( $(this).val()).select();

        }

    });


});