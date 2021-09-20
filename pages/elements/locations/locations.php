<?php

    require_once("../../../config/config.php" );
    require_once("../../../admin/patchworks.php" );
    require_once("../../../admin/website/classes/pageelements.cls.php");
    require_once("../../../admin/system/classes/places.cls.php");

    $EleDao = new PelDAO();
    $Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
    $EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
    if (!$EleObj) die();

    $MouseW = $EleDao->getVariable($EleObj, 'mousew' );
    $Height = $EleDao->getVariable($EleObj, 'height' );

    if ($MouseW == 'on') $MouseW = 'true';

    if (empty($Height)) $Height = 450;
    if (empty($MouseW) || is_null($MouseW)) $MouseW = 'false';


    $location = new PlaDAO();

    $places = $location->select(NULL,'LOCATION');

    $lat ="";
    foreach ($places as $place){
		if(!empty($place['goolat'])){
			 $lat.=$place['goolat'].",";
        $lon.=$place['goolng'].",";
		}
       
    }
    $lat = substr_replace($lat, "", -1);
    $lon = substr_replace($lon, "", -1);
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
        padding: .5em 1em;
        /*
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        */
        /*text-shadow: 0 -1px #000000;*/
        /*-webkit-box-shadow: 0 0 8px #000;
        box-shadow: 0 0 8px #000;*/
        width: 280px;
    }

</style>

<div id="mapoutter">
    <div id="mapDiv" data-mazzom="18" data-setzom="16" data-lat="<?php echo $lat;?>" data-lon="<?php echo $lon;?>" style="width: 100%; height: 260px">

    </div>
</div>

