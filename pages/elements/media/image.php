<?php

require_once("../../../config/config.php");
require_once("../../../admin/patchworks.php");
require_once("../../../admin/website/classes/pageelements.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$eleVarArr = json_decode($EleObj->elevar, true);

$ImgUrl = NULL;
$ImgUrl = $EleDao->getVariable($EleObj, 'imgurl' );
$ImgSiz = $EleDao->getVariable($EleObj, 'imgsiz' );
$Height = $EleDao->getVariable($EleObj, 'height' );
$nopadding = $EleDao->getVariable($EleObj, 'nopadding' );


if (!empty($ImgUrl)) {

?>

    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="imageelement" style="background-image: url('<?php echo str_replace('images/', 'images/'.$ImgSiz.'/',$ImgUrl); ?>'); height: <?php echo $Height.'px'; ?>">

                    </div>
                </div>
            </div>
        </div>
    </div>

<!--    <img src="--><?php //echo str_replace('images/', 'images/'.$ImgSiz.'/',$ImgUrl); ?><!--" />-->
<?php
} else {
?>

    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">

                    <div style="width: 100%; height: 350px; background: #cecece;">
                    </div>

                </div>
            </div>
        </div>
    </div>



<?php
}
?>