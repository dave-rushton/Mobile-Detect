var resizeDelay, windowWidth;

$(function () {

    slinky = $('#mobileMenu').slinky();

    $('#mobilemenubtnwrapper').click(function () {

        $(this).find('#mobilemenubtn').toggleClass('open');

        $('body').toggleClass('menu');

        //$('#mobileMenuWrapper').slideToggle();

    });

    $(window).resize(function () {
        clearTimeout(resizeDelay);
        resizeDelay = setTimeout(winResize, 300);
    });

    winResize();

    function winResize() {

        if ($(window).width() != windowWidth) {

            $('body').removeClass('menu');
            $('#mobilemenubtn').removeClass('open');

            if ($(window).width() > 768) {

                $('#mainMenu').show();
                //$('#mobileMenuWrapper').show();

                //$('body').removeClass('menu');

                $(this).find('#mobilemenubtn').removeClass('open');

            } else {

                $('#mainMenu').hide();
                //$('#mobileMenuWrapper').hide();
                //$('body').addClass('menu');

            }

        }

        windowWidth = $(window).width();
    }

});