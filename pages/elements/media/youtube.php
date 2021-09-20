<?php

require_once('../../../config/config.php');
require_once('../../../admin/patchworks.php');
require_once("../../../admin/website/classes/pageelements.cls.php");


$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

$Vid_ID = $EleDao->getVariable($EleObj, 'vid_id');

?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
            </div>
            <div class="col-sm-6">

                <div class="youtubevideo">

                <iframe width="100%" height="400" src="//www.youtube.com/embed/<?php echo $Vid_ID; ?>" frameborder="0" allowfullscreen></iframe>

                </div>

            </div>
        </div>
    </div>
</div>