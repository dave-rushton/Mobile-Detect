<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/website/classes/pageelements.cls.php" );

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$BoxTtl = $EleDao->getVariable($EleObj, 'boxttl', false );
$BoxTxt = $EleDao->getVariable($EleObj, 'boxtxt', false );

?>

<div class="section nopadding">
    <div class="container">
        <div class="col-sm-12">

            <div class="expandwrapper">
                <a href="#" class="expandlink"><?php echo $BoxTtl; ?> <i class="fa fa-chevron-down"></i></a>
                <div class="expandcontent">
                    <?php echo $BoxTxt; ?>
                </div>
            </div>

        </div>
    </div>
</div>

