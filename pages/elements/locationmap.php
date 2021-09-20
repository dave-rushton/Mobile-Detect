<?php

require_once( "../../config/config.php" );
require_once( "../../admin/patchworks.php" );
require_once("../../admin/website/classes/pageelements.cls.php");


$EleDao = new PelDAO();
$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

?>

<?php
if (!isset($_GET['locseo'])) {
    ?>

    <style>

        .infobox-wrapper {
            display: none;
        }

        .infoBox {
            /*border: 2px solid black;*/
            margin-top: 8px;
            background: #cecece;
            color: #000;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            padding: 5px 10px;
            width: 280px;
        }

        .infoBox .mainImg {
            max-width: 260px;
            margin-top: 5px;
            margin-bottom: 10px;
        }

    </style>

    <script type="text/javascript"
            src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDjZSf7lI4D80NIwFMozDDABq-tSkGgKIs&sensor=false"></script>
    <script type="text/javascript"
            src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox.js"></script>
    <script>

        var markersArray = [];

        var map, geocoder, currentPosition, infobox, bounds;

        var labelText;

        var infobox;

        $(window).load(function () {

            initialize();
            initiate_geolocation();

            $('#locationsForm').submit(function (e) {
                e.preventDefault();

                try {
                    infobox.close();
                } catch (ex) {
                }

                codeAddress($('#locPstCod').val());

            });

            getEvents();

        });

        function initialize() {

            geocoder = new google.maps.Geocoder();
            var latlng = new google.maps.LatLng(0, 0);
            var myOptions = {
                zoom: 14,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
        }

        function initiate_geolocation() {
            //navigator.geolocation.getCurrentPosition(handle_geolocation_query);
        }

        function handle_geolocation_query(position) {

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

                    alert('err');

                }
            });

        }

        function clearEvents() {

            for (var i = 0; i < markersArray.length; i++) {
                markersArray[i].setMap(null);
            }
            markersArray.length = 0;

        }


        function getEvents() {

            clearEvents();

            bounds = new google.maps.LatLngBounds();

            var multiLocation = true;

            $.ajax({
                url: 'admin/system/json/mapsearch.json.php',
                data: 'tblnam=LOCATION&goolat=' + $('#GooLat').val() + '&goolng=' + $('#GooLng').val() + '&pladis=' + $('#plaDis').val(),
                type: 'GET',
                async: false,
                success: function (data) {

                    eventRecs = JSON.parse(data);

                    if (eventRecs.length <= 1) multiLocation = false;

                    for (e = 0; e < eventRecs.length; e++) {

                        bounds.extend(new google.maps.LatLng(eventRecs[e].goolat, eventRecs[e].goolng));

                        var labelText = '<img src="uploads/images/locations/'+eventRecs[e].plaimg+'" alt="" class="mainImg" />';

                        labelText += '<p>';
                        labelText += '<strong>' + eventRecs[e].planam + '</strong><br><br><br>';
                        labelText += eventRecs[e].adr1 + '<br>';
                        labelText += eventRecs[e].adr2 + '<br>';
                        labelText += eventRecs[e].adr3 + '<br>';
                        labelText += eventRecs[e].adr4 + '<br>';
                        labelText += eventRecs[e].pstcod + '<br></p>';

                        labelText += '<p><a href="' + $('#SeoUrl').val() + '/location/' + eventRecs[e].seourl + '" class="readmore">Read More</a></p>';

                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(eventRecs[e].goolat, eventRecs[e].goolng),
                            //icon: new google.maps.MarkerImage('pages/img/group-2.png',null, null, new google.maps.Point(10,50)),
                            map: map,
                            draggable: false,
                            arrayIndex: e,
                            address: [eventRecs[e].adr1, eventRecs[e].adr2, eventRecs[e].adr3, eventRecs[e].adr4, eventRecs[e].pstcod],
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

                        google.maps.event.addListener(marker, 'click', function () {

                            try {
                                infobox.close();
                            } catch (ex) {
                            }
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


            if (!multiLocation && markersArray.length > 0) {
                map.setZoom(15);
                map.setCenter(markersArray[0].position);
            } else {
                map.fitBounds(bounds);
                map.setCenter(bounds.getCenter(), map.panToBounds(bounds));
            }

        }

    </script>

    <form class="form-vertical" id="locationsForm">

        <input type="hidden" id="GooLat">
        <input type="hidden" id="GooLng">
        <input type="hidden" id="SeoUrl" value="<?php echo $_GET['seourl']; ?>">

        <div class="pw-form">
            <div class="pw-form-header">
                <h3></h3>
            </div>
            <div class="pw-form-content">
                <div class="control-group form-group">
                    <div class="controls">
                        <label>Search</label>
                        <input type="text" name="searchaddress" id="locPstCod" class="form-control">
                    </div>
                </div>
                <div class="control-group form-group">
                    <div class="controls">
                        <label>Distance</label>

                        <select name="searchdistance" id="plaDis" class="form-control">
                            <option value="9999" selected>Any Distance</option>
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>

                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">SEARCH</button>
            </div>
        </div>
    </form>

    <div class="locationsMap img-thumbnail" id="map_canvas" style="margin-top: 20px; width: 100%; height: 550px;">
    </div>

<?php
} else {

    require_once("../../admin/system/classes/places.cls.php");
    $TmpPla = new PlaDAO();
    $locationRec = $TmpPla->selectBySeo($_GET['locseo']);
    if (isset($locationRec->planam)) {

        ?>

        <h1><?php echo $locationRec->planam; ?></h1>

        <p>
            <?php echo ($locationRec->adr1 != '') ? $locationRec->adr1 . '<br>' : ''; ?>
            <?php echo ($locationRec->adr2 != '') ? $locationRec->adr2 . '<br>' : ''; ?>
            <?php echo ($locationRec->adr3 != '') ? $locationRec->adr3 . '<br>' : ''; ?>
            <?php echo ($locationRec->adr4 != '') ? $locationRec->adr4 . '<br>' : ''; ?>
            <?php echo ($locationRec->pstcod != '') ? $locationRec->pstcod : ''; ?>
        </p>

        <p>
            <?php echo ($locationRec->platel != '') ? '<i class="fa fa-phone" style="width: 20px;"></i> ' . $locationRec->platel . '<br>' : ''; ?>
            <?php echo ($locationRec->plamob != '') ? '<i class="fa fa-fax" style="width: 20px;"></i> ' . $locationRec->plamob . '<br>' : ''; ?>
            <?php echo ($locationRec->plaema != '') ? '<i class="fa fa-envelope" style="width: 20px;"></i> <a href="mailto:' . $locationRec->plaema . '">' . $locationRec->plaema . '</a><br>' : ''; ?>
            <?php echo ($locationRec->plaurl != '') ? '<i class="fa fa-globe" style="width: 20px;"></i> <a href="' . $locationRec->plaurl . '">' . str_replace('https://','',str_replace('http://','',$locationRec->plaurl)) . '</a>' : ''; ?>
        </p>

        <div class="alert alert-warning"><?php echo $patchworks->getJSONVariable($locationRec->platxt, 'cusfld1', false); ?></div>
        <div class="alert alert-info"><?php echo $patchworks->getJSONVariable($locationRec->platxt, 'cusfld2', false); ?></div>

    <?php
    }
}
?>