<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/website/classes/pageelements.cls.php");
require_once("../../admin/custom/testimonials/classes/testimonials.cls.php");
require_once("../../admin/gallery/classes/uploads.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

$Col1Txt = $EleDao->getVariable($EleObj, 'col1txt', false);
$Col2Txt = $EleDao->getVariable($EleObj, 'col2txt', false);
$Col3Txt = $EleDao->getVariable($EleObj, 'col3txt', false);

$ClsNam = $EleDao->getVariable($EleObj, 'clsnam', false);
$perpag = $EleDao->getVariable($EleObj, 'perpag', false);
$theme = $EleDao->getVariable($EleObj, 'theme', false);

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

$TstDAO = new TstDAO();
$testimonials = $TstDAO->select(NULL,NULL,$perpag,1,false);
?>
<section class="<?php echo $class;?>">
    <div class="testimonials ">
        <div class="container">
            <?php
            if(!empty($indent))
                echo '<div class="' . $indent . '">'
            ?>
            <div class="flexslider custom-arrows" data-dircon="on">
                <ul class="slides">
                    <?php
                    $colum_value = 0; //helps automatically position images
                    for($i = 0; $i < count($testimonials); $i++){
                        $testimonial = $testimonials[$i];
                        echo "<li>";
                            echo "<div class='inner-wrapper'>";
                                echo "<div class='text-wrapper'>";
                                    echo "<p>";
                                        echo nl2br($testimonial['tstdsc']);
                                    echo "</p>";
                                echo "</div>";
                                echo "<div class='title-wrapper'>";
                                    echo "<h2>";
                                        echo $testimonial['tstttl'];
                                    echo "</h2>";
                                echo "</div>";
                            echo "</div>";
                        echo "</li>";
                    }
                    ?>
                </ul>
            </div>
            <?php
            if(!empty($indent))
                echo '</div>'
            ?>
        </div>
    </div>
</section>
