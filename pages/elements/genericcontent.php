<?php
require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/website/classes/pageelements.cls.php");
require_once("../../admin/website/classes/page.handler.php");
require_once('../../admin/website/classes/pagecontent.cls.php'); 

$EleDao = new PelDAO();
$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$ClsNam = $EleDao->getVariable($EleObj, 'clsnam' );
$Pgc_ID = $EleDao->getVariable($EleObj, 'pgc_id' );

$contentRec = NULL;
$PgcDao = new PgcDAO();
$contentRec = $PgcDao->selectGeneric($Pgc_ID, true);

?>
<div class="pageContentText <?php echo $ClsNam; ?>">
<?php if (isset($contentRec->pgctxt)) echo htmlspecialchars_decode( stripslashes($contentRec->pgctxt) , ENT_QUOTES); ?>
</div>