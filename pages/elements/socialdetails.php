<?php
require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/website/classes/pageelements.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$Twitter = $EleDao->getVariable($EleObj, 'twitter' );
$Facebook = $EleDao->getVariable($EleObj, 'facebook' );
$Google = $EleDao->getVariable($EleObj, 'google' );
$Instagram = $EleDao->getVariable($EleObj, 'instagram' );

if (strlen($Twitter) > 4 && substr($Twitter,0,4) != 'http') $Twitter = 'http://'.$Twitter;
if (strlen($Facebook) > 4 && substr($Facebook,0,4) != 'http') $Facebook = 'http://'.$Facebook;
if (strlen($Google) > 4 && substr($Google,0,4) != 'http') $Google = 'http://'.$Google;
if (strlen($Instagram) > 4 && substr($Instagram,0,4) != 'http') $Instagram = 'http://'.$Instagram;

?>

<div class="social">
    <ul>

        <?php if (!empty($Twitter)) { echo '<li><a href="'.$Twitter.'" target="_blank"> <i class="fa fa-twitter"></i> Twitter</a></li>'; } ?>
        <?php if (!empty($Facebook)) { echo '<li><a href="'.$Facebook.'" target="_blank"> <i class="fa fa-facebook"></i> Facebook</a></li>'; } ?>
        <?php if (!empty($Google)) { echo '<li><a href="'.$Google.'" target="_blank"> <i class="fa fa-google-plus"></i> Google+</a></li>'; } ?>
        <?php if (!empty($Instagram)) { echo '<li><a href="'.$Instagram.'" target="_blank"> <i class="fa fa-instagram"></i> Instagram</a></li>'; } ?>

    </ul>
</div>