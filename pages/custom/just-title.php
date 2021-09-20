<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/website/classes/pageelements.cls.php");
require_once("../../admin/gallery/classes/gallery.cls.php");
require_once("../../admin/gallery/classes/uploads.cls.php");
$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

$Col1Txt = $EleDao->getVariable($EleObj, 'col1txt', false);
$Gal_ID = $EleDao->getVariable($EleObj, 'gal_id', false);
$text = $EleDao->getVariable($EleObj, 'text', false);
$nopadding = $EleDao->getVariable($EleObj, 'nopadding', false);
$nomargin = $EleDao->getVariable($EleObj, 'nomargin', false);
$extrapadding = $EleDao->getVariable($EleObj, 'extrapadding', false);
$extramargin = $EleDao->getVariable($EleObj, 'extramargin', false);
$sizing = $EleDao->getVariable($EleObj, 'sizing', false);
$heading = $EleDao->getVariable($EleObj, 'heading', false);
$title = $EleDao->getVariable($EleObj, 'title', false);

$image = str_replace("images/", "images/" . $imgsiz . "/", $image);

$class = $nopadding . " " . $nomargin . " " . $extrapadding . " " . $extramargin;
echo "<section class='".$class."'>";
    echo "<div class='just-title'>";
        echo "<" . $heading . " class='" . $sizing . "'>";
echo "<span class='styled'>";
        echo $title;
        echo "</" . $heading . ">";
        echo "</span>";
        echo "<hr/>";
    echo "</div>";
echo "</section>";
?>





