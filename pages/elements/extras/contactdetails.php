<?php
require_once("../../../config/config.php");
require_once("../../../admin/patchworks.php");
require_once("../../../admin/website/classes/pageelements.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$ConTel = $EleDao->getVariable($EleObj, 'contel' );
$ConFax = $EleDao->getVariable($EleObj, 'confax' );
$ConMob = $EleDao->getVariable($EleObj, 'conmob' );
$ConEma = $EleDao->getVariable($EleObj, 'conema' );

?>

<div class="contact">
    <ul>
        <?php if (!empty($ConTel)) { echo '<li><i class="fa fa-phone"></i> '.$ConTel.'</li>'; } ?>
        <?php if (!empty($ConFax)) { echo '<li><i class="fa fa-fax"></i> '.$ConFax.'</li>'; } ?>
        <?php if (!empty($ConMob)) { echo '<li><i class="fa fa-mobile"></i> '.$ConMob.'</li>'; } ?>
        <?php if (!empty($ConEma)) { echo '<li><i class="fa fa-envelope"></i> '.$ConEma.'</li>'; } ?>
    </ul>
</div>