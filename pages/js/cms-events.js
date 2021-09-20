$(function(){

    $('#calendarWrapper').on('click', '#calendarMonthPrev', function(e){

        e.preventDefault();

        $.ajax({
            url: 'pages/events/calendar.month.php',
            data: 'action=getmonth&m='+$(this).data('month')+'&y='+$(this).data('year'),
            type: 'GET',
            async: false,
            success: function( data ) {

                $('#calendarWrapper').html( data );

            }

        });


    });
    $('#calendarWrapper').on('click', '#calendarMonthNext', function(e){

        e.preventDefault();

        $.ajax({
            url: 'pages/events/calendar.month.php',
            data: 'action=getmonth&m='+$(this).data('month')+'&y='+$(this).data('year'),
            type: 'GET',
            async: false,
            success: function( data ) {

                $('#calendarWrapper').html( data );

            }

        });

    });

    $.ajax({
        url: 'pages/events/calendar.month.php',
        data: 'action=getmonth',
        type: 'GET',
        async: false,
        success: function( data ) {

            $('#calendarWrapper').html( data );

        }

    });

})