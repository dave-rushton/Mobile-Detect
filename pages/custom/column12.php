<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/website/classes/pageelements.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

$BoxTxt = $EleDao->getVariable($EleObj, 'boxtxt', false);
$ClsNam = $EleDao->getVariable($EleObj, 'clsnam', false);


?>


<div class="section <?php echo $ClsNam; ?>">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <?php echo $BoxTxt; ?>
            </div>
        </div>
    </div>
</div>
