<?php

require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/website/classes/pageelements.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$eleVarArr = json_decode($EleObj->elevar, true);

$ImgUrl = NULL;
$ImgUrl = $EleDao->getVariable($EleObj, 'imgurl' );
$ImgSiz = $EleDao->getVariable($EleObj, 'imgsiz' );

if (!empty($ImgUrl)) {

?>
    <img src="<?php echo str_replace('images/', 'images/'.$ImgSiz.'/',$ImgUrl); ?>"/>
<?php
} else {
?>

    <div style="width: 100%; height: 300px; background: #cecece;">
    </div>

<?php
}
?>