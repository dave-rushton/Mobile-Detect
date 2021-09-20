var timer;

$(function(){

    $('.scrollcontent').slimScroll({
        color: '#95e1d3',
        height: '270px',
        railOpacity: 1,
        alwaysVisible: true
    });


    $('.expandlink').click(function(e){
        e.preventDefault();
        $(this).find('i').toggleClass('fa-chevron-down').toggleClass('fa-chevron-up');
        $(this).blur().parent().find('.expandcontent').toggleClass('active');
    });



})