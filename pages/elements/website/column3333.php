<?php

require_once('../../../config/config.php');
require_once('../../../admin/patchworks.php');
require_once("../../../admin/website/classes/pageelements.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

$Col1Txt = $EleDao->getVariable($EleObj, 'col1txt', false);
$Col2Txt = $EleDao->getVariable($EleObj, 'col2txt', false);
$Col3Txt = $EleDao->getVariable($EleObj, 'col3txt', false);
$Col4Txt = $EleDao->getVariable($EleObj, 'col4txt', false);

$ClsNam = $EleDao->getVariable($EleObj, 'clsnam', false);

if ($EleDao->getVariable($EleObj, 'nomargin', false) == 1) $ClsNam .= ' nomargin';
if ($EleDao->getVariable($EleObj, 'nopadding', false) == 1) $ClsNam .= ' nopadding';


?>

<div class="section">
    <div class="container">

<div class="div <?php echo $ClsNam; ?>">
    <div class="row">
        <div class="col-md-3">
            <?php echo $Col1Txt; ?>
        </div>
        <div class="col-md-3">
            <?php echo $Col2Txt; ?>
        </div>
        <div class="col-md-3">
            <?php echo $Col3Txt; ?>
        </div>
        <div class="col-md-3">
            <?php echo $Col4Txt; ?>
        </div>
    </div>
</div>

    </div>
</div>