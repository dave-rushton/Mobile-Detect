jQuery(function ($) {
    // $('.qtyselect').change(function() {
    //     if ($(this).val() < 10) {
    //         $(this).closest('form').find('[name="qty"]').val( $(this).val() );
    //     } else {
    //         $(this).closest('form').find('.qtyinputwrapper').removeClass('hide');
    //         $(this).closest('form').find('.qtyselectwrapper').addClass('hide');
    //         $(this).closest('form').find('[name="qty"]').val( $(this).val()).select();
    //     }
    // });

    let imageLinkNotPopup = $('.image-link:not(#productPopupLink)');
    imageLinkNotPopup.click(function (e) {
        e.preventDefault();

        $(this).parent().parent().find('.active').removeClass('active');
        $(this).parent().addClass('active');

        $('#heroImage').attr("src", $(this).attr('href')).data('arr_id', $(this).data('arr_id'));
        $('#productPopupLink').attr("href", $(this).find('img').attr('href'));

        $('[name="prd_id"]', $('#productForm')).val( $(this).data('prd_id') );
        $('#productName').html( $(this).data('prdnam') );
        $('#productPrice').html( $(this).data('unipri') );
    });

    let itemArray = [];

    imageLinkNotPopup.each(function () {
        itemArray.push({src: $(this).attr('href')});
    });

    $('#productPopupLink').magnificPopup({
        items: itemArray,
        type: 'image',
        gallery: {
            enabled: true
        },
        callbacks: {
            open: function () {
                let startAt = $('#heroImage').data('arr_id');
                $.magnificPopup.instance.goTo(startAt);
            }
        }
    });

    $('.tabswrapper').on('click','.tabselect li a', function(e) {
        e.preventDefault();

        $(this).parent().parent().find('li').removeClass('active');
        $(this).parent().addClass('active');

        $(this).parent().parent().next().find('.tab').removeClass('active');

        $( $(this).attr("href")).addClass('active');
    });

    $('.likeproduct').click(function(e){
        e.preventDefault();

        let thisButton = $(this);

        $.ajax({
            url: thisButton.attr('href'),
            type: 'POST',
            async: false,
            success: function( data ) {
                thisButton.toggleClass('active');
            },
            error: function (x, e) {
            }
        });
    });

    $('.dynaform').submit(function(e){
        e.preventDefault();

        let thisForm = $(this);

        $.ajax({
            url: thisForm.attr("action"),
            data: 'ajax=true&' + thisForm.serialize(),
            type: 'POST',
            async: false,
            success: function( data ) {
                if ( !$('#shoppingCartLink > span').html() ) {
                    $('#shoppingCartLink').html('<span>'+$('[name="qty"]', thisForm).val()+'</span>');
                } else {
                    let qty = $('#shoppingCartLink > span').html();
                    $('#shoppingCartLink').html('<span>'+ (parseFloat($('[name="qty"]', thisForm).val()) + parseFloat(qty)) +'</span>');
                }
            },
            error: function (x, e) {
            }
        });
    });
});
