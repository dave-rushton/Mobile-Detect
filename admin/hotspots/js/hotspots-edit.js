var hotSpotForm;

$(function(){

    hotSpotForm = $('#hotSpotForm');

    $('#updateHotSpotBtn').click(function(e){
        e.preventDefault();
        hotSpotForm.submit();
    });

    hotSpotForm.submit(function(e){

        e.preventDefault();

        var fields = $(".customfield", hotSpotForm).serializeArray();
        var elementVariables = JSON.stringify(fields);
        var postData = encodeURIComponent(elementVariables);

        if( $('#logofile')[0].files[0] ) {

            alert('logo');

            var data;

            data = new FormData();
            data.append('logofile', $('#logofile')[0].files[0]);
            data.append('newname', $('[name="hotnam"]', hotSpotForm).val());

            $.ajax({
                url: 'hotspots/uploadhotspot.php',
                data: data,
                processData: false,
                type: 'POST',
                contentType: false,
                aSync: false,
                success: function (data) {

                    alert(data);

                    $('[name="hotimg"]', hotSpotForm).val(data);

                    $.ajax({
                        url: hotSpotForm.attr("action"),
                        data: 'action=update&ajax=true&' + hotSpotForm.serialize() + '&hottxt=' + postData,
                        type: 'POST',
                        async: false,
                        success: function( data ) {

                            alert(data);

                            try {

                                var result = JSON.parse(data);

                                $.msgGrowl ({
                                    type: result.type
                                    , title: result.title
                                    , text: result.description
                                });

                                $('#id', hotSpotForm).val( result.id );

                            } catch(Ex) {
                                $.msgGrowl ({
                                    type: 'error'
                                    , title: 'Error'
                                    , text: Ex
                                });
                            }

                        },
                        error: function (x, e) {

                            throwAjaxError(x, e);

                        }
                    });

                },
                error: function (x, e) {

                    throwAjaxError(x, e);

                }
            });

        } else {

            alert('no logo');

            $.ajax({
                url: hotSpotForm.attr("action"),
                data: 'action=update&ajax=true&' + hotSpotForm.serialize() + '&hottxt=' + postData,
                type: 'POST',
                async: false,
                success: function( data ) {

                    try {

                        var result = JSON.parse(data);

                        $.msgGrowl ({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });

                        $('#id', hotSpotForm).val( result.id );

                    } catch(Ex) {
                        $.msgGrowl ({
                            type: 'error'
                            , title: 'Error'
                            , text: Ex
                        });

                    }

                },
                error: function (x, e) {

                    throwAjaxError(x, e);

                }
            });

        }

    });

    $('#deleteHotSpotBtn').click(function (e) {
        e.preventDefault();

        $.msgAlert ({
            type: 'warning'
            , title: 'Delete This Hot Spot'
            , text: 'Are you sure you wish to permanently remove this Hot Spot from the database?'
            , callback: function () {

                $.ajax({
                    url: hotSpotForm.attr("action"),
                    data: 'action=delete&ajax=true&' + hotSpotForm.serialize(),
                    type: 'POST',
                    async: false,
                    success: function( data ) {

                        var result = JSON.parse(data);

                        $.msgGrowl ({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });

                        window.location = hotSpotForm.data("returnurl");

                    },
                    error: function (x, e) {
                        throwAjaxError(x, e);
                    }
                });

            }
        });
        return false;
    });


    getHotSpots();


    $( "#addHotSpot" ).draggable({
        revert: true,
        helper: 'clone',
        revert: "invalid"
    });

    $( ".hotSpotDetail" ).draggable({
        containment: '#hotSpotWrapper',
        stop: function() {

            parWid = $(this).parent().width();
            parHei = $(this).parent().height();

            leftPosition  = $(this).css('left').replace('px','');
            topPosition   = $(this).css('top').replace('px','');

            topPercent = (topPosition / parHei) * 100;
            leftPercent = (leftPosition / parWid) * 100;

            $.ajax({
                url: hotSpotForm.attr("action"),
                data: 'action=updatehotspot&ajax=true&hsp_id=' + $(this).data('hsp_id') + '&hottop='+topPercent+'&hotlft=' + leftPercent,
                type: 'POST',
                async: false,
                success: function( data ) {

                    try {

                        var result = JSON.parse(data);

                        $.msgGrowl ({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });

                    } catch(Ex) {
                        $.msgGrowl ({
                            type: 'error'
                            , title: 'Error'
                            , text: Ex
                        });
                    }

                },
                error: function (x, e) {

                    throwAjaxError(x, e);

                }
            });

        }
    });


    var parPos = $( "#hotSpotWrapper").position();
    var parLeft = parPos.left;
    var parTop = parPos.top;


    $( "#hotSpotWrapper" ).droppable({

        accept: "#addHotSpot",

        drop: function( event, ui ) {

            leftPosition  = ui.offset.left - $(this).offset().left;
            topPosition   = ui.offset.top - $(this).offset().top;

            parWid = $( "#hotSpotWrapper" ).width();
            parHei = $( "#hotSpotWrapper" ).height();

            topPercent = (topPosition / parHei) * 100;
            leftPercent = (leftPosition / parWid) * 100;

             $.ajax({
                url: hotSpotForm.attr("action"),
                data: 'action=createhotspot&ajax=true&hot_id=' + $('[name="hot_id"]', hotSpotForm).val() + '&hottop='+topPercent+'&hotlft=' + leftPercent,
                type: 'POST',
                async: false,
                success: function( data ) {
                    try {

                        var result = JSON.parse(data);

                        $.msgGrowl ({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });

                        var spotHTML = '<a href="#" data-hsp_id="' + result.id + '" class="hotSpotDetail" style="top: '+topPercent+'%; left: '+leftPercent+'%;"></a>';

                        $('#hotSpotWrapper').append( spotHTML );

                        $( ".hotSpotDetail" , $('#hotSpotWrapper')).last().draggable({
                            containment: '#hotSpotWrapper'
                        });


                    } catch(Ex) {
                        $.msgGrowl ({
                            type: 'error'
                            , title: 'Error'
                            , text: Ex
                        });
                    }
                },
                error: function (x, e) {
                    throwAjaxError(x, e);
                }
            });

        }
    });

    $( "#hotSpotWrapper").on('click', 'a.hotSpotDetail', function(e){

        e.preventDefault();

        $('[name="hsp_id"]', $('#hotSpotDetailForm')).val( $(this).data('hsp_id') );

        $.ajax({
            url: hotSpotForm.attr("action"),
            data: 'action=selecthotspot&ajax=true&hsp_id=' + $(this).data('hsp_id'),
            type: 'POST',
            async: false,
            success: function( data ) {

                try {

                    var result = JSON.parse(data);

                    $('[name="hspttl"]', $('#hotSpotDetailForm')).val( result.hspttl );
                    $('[name="hsptxt"]', $('#hotSpotDetailForm')).val( result.hsptxt );

                    $('#hotSpotModal').modal('show');


                } catch(Ex) {
                    $.msgGrowl ({
                        type: 'error'
                        , title: 'Error'
                        , text: Ex
                    });
                }
            },
            error: function (x, e) {

                throwAjaxError(x, e);

            }
        });

    });

    $('#hotSpotDetailForm').submit(function(e){

        e.preventDefault();

        $.ajax({
            url: hotSpotForm.attr("action"),
            data: 'action=updatehotspotdetails&ajax=true&' + $('#hotSpotDetailForm').serialize(),
            type: 'POST',
            async: false,
            success: function( data ) {

                try {

                    var result = JSON.parse(data);

                    $.msgGrowl ({
                        type: result.type
                        , title: result.title
                        , text: result.description
                    });

                    $('#hotSpotModal').modal('hide');

                } catch(Ex) {
                    $.msgGrowl ({
                        type: 'error'
                        , title: 'Error'
                        , text: Ex
                    });
                }

            },
            error: function (x, e) {

                throwAjaxError(x, e);

            }
        });


    });


    $('#deleteHotSpotDetailBtn').click(function(e){

        e.preventDefault();

        if (confirm('Delete Hot Spot')) {

            $.ajax({
                url: hotSpotForm.attr("action"),
                data: 'action=deletespotdetails&ajax=true&' + $('#hotSpotDetailForm').serialize(),
                type: 'POST',
                async: false,
                success: function( data ) {

                    try {

                        getHotSpots();

                        var result = JSON.parse(data);

                        $.msgGrowl ({
                            type: result.type
                            , title: result.title
                            , text: result.description
                        });

                        $('#hotSpotModal').modal('hide');

                    } catch(Ex) {
                        $.msgGrowl ({
                            type: 'error'
                            , title: 'Error'
                            , text: Ex
                        });
                    }

                },
                error: function (x, e) {

                    throwAjaxError(x, e);

                }
            });

        }

    });


});

function getHotSpots() {

    $('.hotSpotDetail').remove();

    $.ajax({
        url: hotSpotForm.attr("action"),
        data: 'action=selecthotspots&ajax=true&hot_id=' + $('[name="hot_id"]', hotSpotForm).val(),
        type: 'POST',
        async: false,
        success: function( data ) {

            try {

                var result = JSON.parse(data);

                for (i=0;i<result.length;i++) {

                    var spotHTML = '<a href="#"  data-hsp_id="' + result[i].hsp_id + '" class="hotSpotDetail" style="top: '+result[i].hottop+'%; left: '+result[i].hotlft+'%;"></a>';

                    $('#hotSpotWrapper').append( spotHTML );

                    $( ".hotSpotDetail" , $('#hotSpotWrapper')).last().draggable({
                        containment: '#hotSpotWrapper'
                    });

                }


            } catch(Ex) {
                $.msgGrowl ({
                    type: 'error'
                    , title: 'Error'
                    , text: Ex
                });
            }
        },
        error: function (x, e) {

            throwAjaxError(x, e);

        }
    });

}