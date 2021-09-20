<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/website/classes/pageelements.cls.php");
require_once("../../admin/website/classes/articles.cls.php");
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
    $class = $spacing." ".$nopadding." ".$nomargin." ".$extrapadding." ".$extramargin." ".$theme." ".$negmargin;
//DO NOT ADD IN HERE

$imgurl = $EleDao->getVariable($EleObj, 'imgurl', false);
$imgsiz = $EleDao->getVariable($EleObj, 'imgsiz', false);
$image = str_replace("images/", "images/" . $imgsiz . "/", $imgurl);

$imgicon = $EleDao->getVariable($EleObj, 'imgicon', false);
$imgsiz = $EleDao->getVariable($EleObj, 'imgsiz', false);
//$image1 = str_replace("images/", "images/" . $imgsiz . "/", $imgicon);
$image1 = $imgicon;

$text = $EleDao->getVariable($EleObj, 'text', false);
$size = $EleDao->getVariable($EleObj, 'size', false);

$textfirst = $EleDao->getVariable($EleObj, 'textfirst', false);
$fwdurl = $EleDao->getVariable($EleObj, 'fwdurl', false);
$mobimg = $EleDao->getVariable($EleObj, 'mobimg', false);


$background = $EleDao->getVariable($EleObj, 'background', false);

$TmpUpl = new UplDAO();
$custom_class = "";
if(!empty($text) && empty($image1)){
    $custom_class.=" just-text";
}
if(!empty($image1) && empty($text)){
    $custom_class.=" just-image";
}
?>
<section class="<?php echo $class; ?>">
    <?php
    $noimage = "";
    if(!empty($mobimg)){
        $noimage = "no-image";
    }
    if(!empty($indent))
        echo '<div class="' . $indent . '">';

       if(!empty($fwdurl)){

           echo '<a href="'.$fwdurl.'">';
       }
	   
    ?>
  
        <?php
		if(!empty($background)){
			 if(!empty($mobimg)){
				$noimage = "no-image-mob";
			}
			 echo '<div class="hero-graphic-section  '. $size.' '.$custom_class.' '.$noimage.'" data-diff="100">';
			 	echo '<div class="desktop">';
			 echo '<img src="'.$image.'" alt="" />';
			 echo '</div>';
			 if(!empty($mobimg)){
				echo '<div class="mob">';
				echo '<img src="'.$mobimg.'" alt="" />';
				echo '</div>';
				}
		}else{
			 echo '<div class="hero-graphic-section lazy-image '. $size.' '.$custom_class.' '.$noimage.'" data-image="'.$image.'"   data-diff="100">';
			if(!empty($mobimg)){
				echo '<img src="'.$mobimg.'" alt="" />';
				}
		}
        
         if(!empty($text) || !empty($imgurl1)){
            echo '<div class="cover">';
                echo '<div class="table">';
                    echo '<div class="cell">';
                        echo '<div class="container">';
                            echo '<div class="inner">';
                                if(!empty($textfirst)){
                                    if(!empty($text)){
                                        echo '<div class="text-wrapper">';
                                        echo $text;
                                        echo '</div>';
                                    }
                                }
                                if(!empty($image1)){
                                    echo '<div class="image-wrapper">';

                                        echo  '<img loading="lazy" src="'. $image1 .'" alt="" >';


                                    echo '</div>';
                                }
                                if(empty($textfirst)){
                                    if(!empty($text)){
                                        echo '<div class="text-wrapper">';
                                        echo $text;
                                        echo '</div>';
                                    }
                                }
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }



        if(!empty($indent))
            echo '</div>';



        ?>

    </div>
    <?php
    if(!empty($fwdurl)){
        echo '</a>';
    }
    ?>
</section>