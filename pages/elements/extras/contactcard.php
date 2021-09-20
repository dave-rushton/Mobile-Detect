<?php

require_once('../../../config/config.php');
require_once('../../../admin/patchworks.php');
require_once("../../../admin/website/classes/pageelements.cls.php" );

require_once('classes/vcard.cls.php');

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

?>

<a rel="external" href="pages/elements/download.php?pel_id=<?php echo $Pel_ID; ?>"class="contactlink btn btn-primary"><i class="fa fa-download"></i> Contact Card</a>