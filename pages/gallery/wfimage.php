<?php
require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/website/classes/pageelements.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$Width  = $EleDao->getVariable($EleObj, 'width' );
$Height = $EleDao->getVariable($EleObj, 'height' );
$ImgTxt = $EleDao->getVariable($EleObj, 'imgtxt' );

?>

<img class="img-responsive" src="http://placehold.it/<?php echo $Width; ?>x<?php echo $Height; ?>&text=<?php echo $ImgTxt; ?>">