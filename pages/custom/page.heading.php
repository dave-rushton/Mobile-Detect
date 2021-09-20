<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/website/classes/pageelements.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$ImgUrl = NULL;
$ImgUrl = $EleDao->getVariable($EleObj, 'imgurl');
$ImgSiz = $EleDao->getVariable($EleObj, 'imgsiz');
$ImgTxt = $EleDao->getVariable($EleObj, 'imgtxt', false);
$FwdUrl = $EleDao->getVariable($EleObj, 'fwdurl');

?>
<div class="section nomargin nopadding">
    <div class="homeslide"
         style="background-image: url('<?php echo str_replace('images/', 'images/' . $ImgSiz . '/', $ImgUrl); ?>'); ?>">
        <div class="content">
            <h1>
                <span class="slabtext"><?php echo nl2br($ImgTxt); ?></span>
            </h1>
            <?php if (!empty($FwdUrl)) { ?>
                <p>
                    <a href="<?php echo $FwdUrl; ?>" class="button">See More</a>
                </p>
            <?php } ?>
        </div>
    </div>
</div>