
var markersArray = [];

var map, geocoder, currentPosition, infobox;

var labelText;

var infobox;

$(window).load(function(){

    initialize();
    initiate_geolocation();

    $('#locationsForm').submit(function(e){
        e.preventDefault();

        try {infobox.close();} catch(ex) {}

        codeAddress( $('#locPstCod').val() );

    });

    getEvents();

});

function initialize() {

    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(0, 0);
    var myOptions = {
        scrollwheel: false,
    zoom: 14,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
}
map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
}

function initiate_geolocation() {
    //navigator.geolocation.getCurrentPosition(handle_geolocation_query);
}

function handle_geolocation_query(position){

    currentPosition = position;

    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(currentPosition.coords.latitude, currentPosition.coords.longitude);

    $('#GooLat').val(currentPosition.coords.latitude);
    $('#GooLng').val(currentPosition.coords.longitude);

    getEvents();

}


function codeAddress(iAddress) {

    clearEvents();

    geocoder.geocode({
        'address': iAddress
    }, function (results, status) {

        if (status == google.maps.GeocoderStatus.OK) {

            clearEvents();
            getEvents();

            marker = new google.maps.Marker({
                position: results[0].geometry.location,
                draggable: true
            });

            bounds.extend(new google.maps.LatLng(marker.position.lat(), marker.position.lng()));

            $('#GooLat').val(marker.position.lat());
            $('#GooLng').val(marker.position.lng());

            getEvents();

        } else {

        }
    });

}

function clearEvents() {

    for (var i = 0; i < markersArray.length; i++ ) {
        markersArray[i].setMap(null);
    }
    markersArray.length = 0;

}

function getEvents() {

    clearEvents();

    var bounds = new google.maps.LatLngBounds();

    var multiLocation = true;

    $.ajax({
        url: 'admin/system/json/mapsearch.json.php',
        data: 'tblnam=LOCATION&goolat='+$('#GooLat').val()+'&goolng='+$('#GooLng').val()+'&pladis='+$('#plaDis').val(),
        type: 'GET',
        async: false,
        success: function( data ) {

            eventRecs = JSON.parse( data );

            if (eventRecs.length <= 1) multiLocation = false;

            for (e=0;e<eventRecs.length;e++) {

                bounds.extend(new google.maps.LatLng(eventRecs[e].goolat, eventRecs[e].goolng));

                var labelText = '<p>';
                labelText += '' + eventRecs[e].planam + '<br><br><br>';
                labelText += '' + eventRecs[e].adr1 + '<br>';
                labelText += '' + eventRecs[e].adr2 + '<br>';
                labelText += '' + eventRecs[e].adr3 + '<br>';
                labelText += '' + eventRecs[e].adr4 + '<br>';
                labelText += '' + eventRecs[e].pstcod + '<br></p>';

                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(eventRecs[e].goolat, eventRecs[e].goolng),
                    //icon: new google.maps.MarkerImage('pages/img/group-2.png',null, null, new google.maps.Point(10,50)),
                    map: map,
                    draggable: false,
                    arrayIndex: e,
                    address : [ eventRecs[e].adr1, eventRecs[e].adr2, eventRecs[e].adr3, eventRecs[e].adr4, eventRecs[e].pstcod ],
                    infoBoxOptions: {
                        content: labelText, //document.getElementById("infobox"),
                        disableAutoPan: false,
                        maxWidth: 150,
                        pixelOffset: new google.maps.Size(-140, 0),
                        zIndex: null,
                        boxStyle: {
                            //background: "url('http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/examples/tipbox.gif') no-repeat #ffffff",
                            //opacity: 0.75,
                            //width: "280px"
                        },
                        closeBoxMargin: "0",
                        closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif",
                        infoBoxClearance: new google.maps.Size(1, 1)
                    }
                });

                google.maps.event.addListener(marker, 'click', function() {

                    try {infobox.close();} catch(ex) {}
                    infobox = new InfoBox(this.infoBoxOptions)
                    infobox.open(map, this);
                });

                markersArray.push(marker);

            }

        },
        error: function (x, e) {
            alert('error');
        }
    });



    if (!multiLocation) {
        map.setZoom(15);
        map.setCenter(markersArray[0].position);
    } else {
        map.fitBounds(bounds);
        map.setCenter(bounds.getCenter(), map.panToBounds(bounds));
    }

}
