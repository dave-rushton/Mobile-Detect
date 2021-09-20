<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/website/classes/pageelements.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

$text = $EleDao->getVariable($EleObj, 'text', false);

//DO NOT ADD IN HERE
    $spacing = $EleDao->getVariable($EleObj, 'spacing', false);
    $nopadding = $EleDao->getVariable($EleObj, 'nopadding', false);
    $nomargin = $EleDao->getVariable($EleObj, 'nomargin', false);
    $extrapadding = $EleDao->getVariable($EleObj, 'extrapadding', false);
    $extramargin = $EleDao->getVariable($EleObj, 'extramargin', false);
    $theme = $EleDao->getVariable($EleObj, 'theme', false);
    $animation = $EleDao->getVariable($EleObj, 'animation', false);
    $indent = $EleDao->getVariable($EleObj, 'indent', false);
    $negmargin = $EleDao->getVariable($EleObj, 'negmargin', false);
    $class = $spacing." ".$nopadding." ".$nomargin." ".$extrapadding." ".$extramargin." ".$theme." ".$negmargin;
//DO NOT ADD IN HERE

?>
<div class="section <?php echo $class;?>">
    <div class="container">
        <?php
        if(!empty($indent))
            echo '<div class="' . $indent . '">'
        ?>
        <div class="row">
            <div class="col-sm-12">
                <?php echo $text; ?>
            </div>
        </div>
        <?php
        if(!empty($indent))
            echo '</div>'
        ?>
    </div>
</div>
