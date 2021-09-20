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
    $adjustheight = $EleDao->getVariable($EleObj, 'adjust_height', false);
    $class = $spacing." ".$nopadding." ".$nomargin." ".$extrapadding." ".$extramargin." ".$theme." ".$negmargin." ".$adjustheight;
//DO NOT ADD IN HERE
$main = $EleDao->getVariable($EleObj, 'main-style', false);
$style = $EleDao->getVariable($EleObj, 'style', false);


$imgurl = $EleDao->getVariable($EleObj, 'imgurl', false);
$imgsiz = $EleDao->getVariable($EleObj, 'imgsiz', false);
$image = str_replace("images/", "images/" . $imgsiz . "/", $imgurl);

$title = $EleDao->getVariable($EleObj, 'title', false);
$text = $EleDao->getVariable($EleObj, 'text', false);
$link_text = $EleDao->getVariable($EleObj, 'link_text', false);
$fwdurl = $EleDao->getVariable($EleObj, 'fwdurl', false);
$enable_link = $EleDao->getVariable($EleObj, 'enable_link', false);
$generic_content = $EleDao->getVariable($EleObj, 'generic_content', false);



//shouldn't have divs in a links.
$w = "div";

?>
<div class="section <?php echo $class;?>">
    <?php
    if(!empty($indent))
        echo '<div class="' . $indent . '">'
    ?>
    <?php
        echo '<div class="ico-section ' . $style." ".$main .'">';
            if(!empty($enable_link) || !empty($link_text)){
                $w = "span";
                echo '<a href="'. $fwdurl .'">';
            }
            if(!empty($imgurl)){
                echo '<'. $w .' class="image-wrapper">';
                    echo '<img loading="lazy" src="'. $image .'" alt="">';
                echo '</'. $w .'>';
            }
            if(!empty($title)){
                echo '<'. $w .' class="title-wrapper">';
                    echo $title;
                echo '</'. $w .'>';
            }
            if(!empty($text)){
                echo '<'. $w .' class="text-wrapper">';
                    echo $text;



                echo '</'. $w .'>';
            }
            if(!empty($generic_content)){
                echo '<'. $w .' class="text-wrapper">';
                require_once('../../admin/website/classes/pagecontent.cls.php');
                $PgcDao = new PgcDAO();
                $contentRec = $PgcDao->select($generic_content, NULL, NULL, true);
                $string =  htmlspecialchars_decode($contentRec->pgctxt);
                $string = str_replace("{year}",date('Y'),$string);
                echo $string;



                echo '</'. $w .'>';
            }

            if(!empty($link_text)){
                echo '<'. $w .' class="link-wrapper">';
                    echo '<'. $w .' class="cta">';
                        echo $link_text;
                    echo '</'. $w .'>';
                echo '</'. $w .'>';
            }
            if(!empty($enable_link || !empty($link_text))){
                echo '</a>';
            }
        echo '</div>';
    ?>
    <?php
    if(!empty($indent))
        echo '</div>'
    ?>
</div>