<?php

require_once('../../../config/config.php');
require_once('../../../admin/patchworks.php');
require_once("../../../admin/website/classes/pageelements.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

$LeftTxt = $EleDao->getVariable($EleObj, 'lefttxt', false);
$RightTxt = $EleDao->getVariable($EleObj, 'righttxt', false);
$ClsNam = $EleDao->getVariable($EleObj, 'clsnam', false);

if ($EleDao->getVariable($EleObj, 'nomargin', false) == 1) $ClsNam .= ' nomargin';
if ($EleDao->getVariable($EleObj, 'nopadding', false) == 1) $ClsNam .= ' nopadding';

$RgtCls = $EleDao->getVariable($EleObj, 'rgtcls', false);
$LftCls = $EleDao->getVariable($EleObj, 'lftcls', false);

$ColOne = 6;
$ColTwo = 6;
$Layout = $EleDao->getVariable($EleObj, 'layout', false);
if (!empty($Layout)) {

    $colWidth = explode("-",$Layout);

    $ColOne = $colWidth[0];
    $ColTwo = $colWidth[1];
}

?>



<div class="section <?php echo $ClsNam; ?>">
    <div class="container">
        <div class="row">
            <div class="col-md-<?php echo $ColOne; ?>">
                <div class="<?php echo $LftCls; ?>">
                    <?php echo $LeftTxt; ?>
                </div>
            </div>
            <div class="col-md-<?php echo $ColTwo; ?>">
                <div class="<?php echo $RgtCls; ?>">
                    <?php echo $RightTxt; ?>
                </div>
            </div>
        </div>
    </div>
</div>