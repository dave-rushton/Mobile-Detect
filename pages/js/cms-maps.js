
var mapArray = [];

$(window).load(function() {

    $('.mapCanvas').each(function(){

        mapItem = {
            goolat: $(this).data('goolat'),
            goolng: $(this).data('goolng'),
            map_id: $(this).attr('id')
        }

        mapArray.push(mapItem);

    });

    for (mp = 0; mp < mapArray.length; mp++) {

        mapOptions = {
            zoom: 14,
            center: new google.maps.LatLng(mapArray[mp].goolat, mapArray[mp].goolng),
            panControl:false,
            zoomControl:true,
            mapTypeControl:false,
            scaleControl:true,
            streetViewControl:false,
            overviewMapControl:false,
            rotateControl:true,
            scrollwheel: false
        }

        map = new google.maps.Map(document.getElementById(mapArray[mp].map_id), mapOptions);
        var marker = new google.maps.Marker({
            position: mapOptions.center,
            map: map
        });

    }

});