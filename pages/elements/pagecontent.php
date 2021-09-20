<?php
require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/website/classes/pageelements.cls.php");
require_once("../../admin/website/classes/page.handler.php");


$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

//$PgcTxt = $EleDao->getVariable($EleObj, 'pgctxt' );
//
//echo htmlspecialchars_decode( stripslashes($PgcTxt) , ENT_QUOTES);
//
//die();

$pageHandler = new pageHandler;
$element = $pageHandler->getElement($_GET['pel_id']);

$ClsNam = $EleDao->getVariable($EleObj, 'clsnam' );

?>
<div class="pageContentText <?php echo $ClsNam; ?>">
<?php echo htmlspecialchars_decode( stripslashes($element->pgctxt) , ENT_QUOTES); ?>
</div>