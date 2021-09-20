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

$Gal_ID = $EleDao->getVariable($EleObj, 'gal_id', false);
$text = $EleDao->getVariable($EleObj, 'text', false);
$include_title = $EleDao->getVariable($EleObj, 'include_title', false);
$include_description = $EleDao->getVariable($EleObj, 'include_description', false);
$include_link = $EleDao->getVariable($EleObj, 'include_link', false);
$link_text = $EleDao->getVariable($EleObj, 'link_text', false);
$height = $EleDao->getVariable($EleObj, 'height', false);
$imgsiz = $EleDao->getVariable($EleObj, 'imgsiz', false);

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
$specialalignment = $EleDao->getVariable($EleObj, 'specialalignment', false);
$class = $spacing." ".$nopadding." ".$nomargin." ".$extrapadding." ".$extramargin." ".$theme." ".$negmargin." ".$specialalignment;
//DO NOT ADD IN HERE

$TmpGal = new GalDAO();
$galleries = $TmpGal->select(NULL, 'WEBGALLERY', NULL, NULL, false);

if (is_numeric($Gal_ID)) {
    $galleryRec = $TmpGal->select($Gal_ID, NULL, NULL, NULL, true);
    $_GET['galseo'] = $galleryRec->seourl;
}

$UplDao = new UplDAO();

?>
<div class="section <?php echo $class;?>">

    <div  class="gallery-slider <?php echo $height;?>">

        <div class="marquee marquee1">
            <div class="main main1">
                <div class="inner">
                    <?php
                    $UplObj = $UplDao->selectByGalSeo($_GET['galseo'], false, 'WEBGALLERY');

                    for($round=0; $round<3; $round++){

                        $tableLength = count($UplObj);
                        for ($i = 0; $i < $tableLength; ++$i) {

                            $link = $UplObj[$i]['urllnk'];
                            $link = str_replace("-"," ",$link);

                            $link_text_display = str_replace("{title}", $UplObj[$i]['uplttl'],$link_text);
                            $link_text_display = str_replace("{link}",$link,$link_text_display);
                            if(!empty($height)){

                                $c = "div";
                                if(!empty($include_link) && !empty($link_text_display)){
                                    $c = "span";
                                    echo '<a href="'. $UplObj[$i]['urllnk'].'" class="cta">';
                                }
                                echo '<'.$c.' class="cover" style="background-image:url(/uploads/images/'.$imgsiz.'/' . $UplObj[$i]['filnam'].')">';
                                echo '<'.$c.' class="table">';
                                echo '<'.$c.' class="cell">';
                                echo '<'.$c.' class="container">';
                                if(!empty($indent))
                                    echo '<'.$c.' class="indent-wrapper ' . $indent . '">';

                                if(!empty($include_title)){
                                    echo '<'.$c.' class="title-wrapper h1">';
                                    echo $UplObj[$i]['uplttl'];
                                    echo "</'.$c.'>";
                                }
                                if(!empty($include_description)){
                                    echo '<'.$c.' class="text-wrapper">';
                                    echo $UplObj[$i]['upldsc'];
                                    echo "</'.$c.'>";
                                }
                                if(!empty($link_text)){

                                    if(!empty($link_text_display)){
                                        echo '<'.$c.' class="cta-wrapper">';
                                        echo '<'.$c.' class="cta">'.$link_text_display.'</'.$c.'>';
                                        echo '</'.$c.'>';
                                    }
                                }

                                if(!empty($indent))
                                    echo '</'.$c.'>';

                                echo "</'.$c.'>";
                                echo "</'.$c.'>";
                                echo "</'.$c.'>";
                                echo "</'.$c.'>";
                                if(!empty($include_link) && !empty($link_text_display)){
                                    echo '</a>';
                                }
                            }
                            if(empty($height)){

                                if(file_exists($patchworks->docRoot."uploads/images/'.$imgsiz."/" . $UplObj[$i]['filnam']")){
                                    echo '<img src="uploads/images/'.$imgsiz."/" . $UplObj[$i]['filnam'] .'" alt="'.$UplObj[$i]['uplttl'] .'">';
                                }else{
                                    echo '<img src="uploads/images/' . $UplObj[$i]['filnam'] .'" alt="'.$UplObj[$i]['uplttl'] .'">';
                                }

                            }
                        }
                    }

                    ?>
                </div>
            </div>

        </div>

    </div>
</div>