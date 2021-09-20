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
$ClsNam = $EleDao->getVariable($EleObj, 'clsnam', false);
$indent = $EleDao->getVariable($EleObj, 'indent', false);
$extrapadding = $EleDao->getVariable($EleObj, 'extrapadding', false);
$hr = $EleDao->getVariable($EleObj, 'hr', false);

if ($EleDao->getVariable($EleObj, 'nomargin', false) == 1) $ClsNam .= ' nomargin';
if ($EleDao->getVariable($EleObj, 'nopadding', false) == 1) $ClsNam .= ' nopadding';
if ($EleDao->getVariable($EleObj, 'extramargin', false) == 1) $ClsNam .= ' extramargin';
if ($EleDao->getVariable($EleObj, 'extrapadding', false) == 1) $ClsNam .= ' extrapadding';
?>


<div class="section <?php echo $hr." ". $ClsNam." ".$extrapadding." ".$indent; ?>">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <?php echo $BoxTxt; ?>
            </div>
        </div>
    </div>
</div>
