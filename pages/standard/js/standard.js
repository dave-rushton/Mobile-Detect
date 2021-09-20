$(function(){

    function marquee1(a, b) {

        var width = b.width();
        var start_pos = a.width();
        var end_pos = -width;

        //adjust when window resizes6
        window.addEventListener("resize", function(){
            width = b.width();
            start_pos = a.width();
            end_pos = -width;
        })

        function scroll() {
            if (b.position().left <= -width) {
                b.css('left', start_pos);
                scroll();
            }
            else {
                time1 = (parseInt(b.position().left, 10) - end_pos) *
                    (60000 / (start_pos - end_pos)); // Increase or decrease speed by changing value 10000
                b.animate({
                    'left': -width
                }, time1, 'linear', function() {
                    scroll();
                });
            }
        }

        b.css({
            'width': width,
            'left': start_pos
        });
        scroll(a, b);

        b.mouseenter(function() {     // Remove these lines
            b.stop();                 //
            b.clearQueue();           // if you don't want
        });                           //
        b.mouseleave(function() {     // marquee to pause
            scroll(a, b);             //
        });                           // on mouse over

    }
    $('.marquee1').each(function(){
        main_width = $(this).innerWidth();
        width = $(this).find('.main1').innerWidth();
        $(this).find('.main1').css('width',((width *.6666) - main_width));
        $(this).find('.main1').css('left',"-"+main_width);
        $(this).find('.main1').css('margin-left',"-"+100+"%");
        marquee1($(this), $('.main1'));  //Enter name of container element & marquee element
    })



    async function fire(){
            setTimeout(function(){
                var video_wrapper = $('.youtube-video-place');
//  Check to see if youtube wrapper exists
                if(video_wrapper.length){
// If user clicks on the video wrapper load the video.
//         $('.play-youtube-video').on('click', function(){
                    /* Dynamically inject the iframe on demand of the user.
                     Pull the youtube url from the data attribute on the wrapper element. */
                    video_wrapper.html('<iframe allowfullscreen frameborder="0"  width="100%" height="400" class="embed-responsive-item" src="' + video_wrapper.data('yt-url') + '"></iframe>');
                    // });
                }
            },1000)

    }
    // fire()






});
