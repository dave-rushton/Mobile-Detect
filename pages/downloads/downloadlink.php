<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/website/classes/pageelements.cls.php");


$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

$BoxTtl = $EleDao->getVariable($EleObj, 'boxttl');
$BoxTxt = $EleDao->getVariable($EleObj, 'boxtxt', false);
$FwdUrl = $EleDao->getVariable($EleObj, 'upl_id');
$ClsNam = $EleDao->getVariable($EleObj, 'clsnam');

?>

<a href="<?php echo $FwdUrl; ?>" target="_blank" class="downloadlink <?php echo $ClsNam; ?>">
    <span class="box">
        <span class="textwrap">
            <span class="textmain"><?php echo nl2br($BoxTxt); ?></span>
        </span>
    </span>
</a>