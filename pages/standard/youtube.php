<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/website/classes/pageelements.cls.php");



$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

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


$indent = $EleDao->getVariable($EleObj, 'indent', false);



$link_location = $EleDao->getVariable($EleObj, 'link_location');
if(empty($link_location)){
    $link_location = 'https://www.youtube.com/watch?v=I02nI6chKG8';
}
else if(strlen($link_location) < 5){
    $link_location = 'https://www.youtube.com/watch?v=I02nI6chKG8';
}

//FOOL PROOF CALCULATIONS
$link_location = str_replace("https://", "", $link_location);
$link_location = str_replace("http://", "", $link_location);
$link_location = str_replace("www.", "", $link_location);
$link_location = str_replace("youtube.com/", "", $link_location);
$link_location = str_replace("youtube.co.uk/", "", $link_location);
$link_location = str_replace("watch?v=", "", $link_location);
$link_location = str_replace("v=", "", $link_location);
$link_builder = "https://www.youtube.com/embed/".$link_location;
?>

<div class="section <?php echo $class;?>">
    <?php
        if(!empty($indent)) {
            echo '<div class="youtube-section auto">';
            echo '<div class="' . $indent . '">';
        }else{
            echo '<div class="youtube-section">';
        }

        ?>

    <?php
        echo  '<iframe loading="lazy" width="100%" height="400" src="' . $link_builder . '" frameborder="0" allowfullscreen></iframe>';

        if(!empty($indent)) {
            echo '</div>';
            echo '</div>';
        }else{
            echo '</div>';
        }

    ?>
</div>