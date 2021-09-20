<?php

require_once('../../../config/config.php');
require_once('../../../admin/patchworks.php');
require_once("../../../admin/website/classes/pageelements.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

$BoxTxt = $EleDao->getVariable($EleObj, 'boxtxt', false);
$BoxNam = $EleDao->getVariable($EleObj, 'boxnam', false);
$BoxCom = $EleDao->getVariable($EleObj, 'boxcom', false);

?>
<div class="quotebox">
    <div class="quotetext">
        <em class="fa fa-quote-left">&nbsp;</em>
            <?php echo $BoxTxt; ?>
        <em class="fa fa-quote-right">&nbsp;</em>
    </div>
    <span><?php echo $BoxNam; ?>, <?php echo $BoxCom; ?></span>
    <div class="tail">&nbsp;</div>
</div>