<?php



require_once('../../config/config.php');

require_once('../../admin/patchworks.php');

require_once("../../admin/website/classes/pageelements.cls.php" );



$EleDao = new PelDAO();



$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;

$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);

if (!$EleObj) die();



$ImgTxt = $EleDao->getVariable($EleObj, 'imgtxt', false );



?>



<div class="section headingfade">

    <div class="container">

        <div class="row">

            <div class="col-sm-12">

                <h3>

                    <span class="slabtext"><?php echo nl2br($ImgTxt); ?></span>

                </h3>

            </div>

        </div>

    </div>

</div>