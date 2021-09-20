
$(function(){

    $('.flexslider').each(function(){

        var DirCon = ($(this).data('dircon') == 'on') ? true : false;
        var SldSel = ($(this).data('sldsel') == 'on') ? true : false;
        var SldTyp = ($(this).attr('data-sldtyp')) ? $(this).data('sldtyp') : 'slide';

        var SldSpd = ($(this).attr('data-sldspd')) ? $(this).data('sldspd') : 4000;
        var AniSpd = ($(this).attr('data-anispd')) ? $(this).data('anispd') : 600;

        var NumItm = ($(this).attr('data-numitm')) ? $(this).data('numitm') : 4;
        var ItmWid = ($(this).attr('data-itmwid')) ? $(this).data('itmwid') : 220;

        if ($(this).data('type') == 'multislide') {

            $(this).flexslider({
                animation: SldTyp,
                animationLoop: true,
                controlNav: SldSel,
                directionNav: DirCon,
                itemWidth: ItmWid,
                itemMargin: 0,
                minItems: 1,
                maxItems: NumItm,
                prevText: "",
                nextText: "",
                slideshowSpeed: SldSpd,
                animationSpeed: AniSpd,
                start: function(slider) {
                    //$(".homeslide > .slidecontent > .title").fitText(1.2, { minFontSize: '16px'});
                },
                after: function(slider) {
                    //$(".homeslide > .slidecontent > .title").fitText(1.2, { minFontSize: '16px'});
                }
            });

        } else {

            $(this).flexslider({
                animation: SldTyp,
                animationLoop: true,
                controlNav: SldSel,
                directionNav: DirCon,
                prevText: "",
                nextText: "",
                slideshowSpeed: SldSpd,
                animationSpeed: AniSpd,
                start: function(slider) {
                    //$(".homeslide > .slidecontent > .title").fitText(1.2, { minFontSize: '16px'});
                },
                after: function(slider) {
                    //$(".homeslide > .slidecontent > .title").fitText(1.2, { minFontSize: '16px'});
                }
            });

        }

    });



});