<?php
require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/website/classes/pageelements.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

//$Width  = $EleDao->getVariable($EleObj, 'width' );
//$Height = $EleDao->getVariable($EleObj, 'height' );
//$ImgTxt = $EleDao->getVariable($EleObj, 'imgtxt' );

?>


<a class="imagezoom fancybox" 
	title="Click on the right side of the image to move forward." 
	href="http://www.this.patchworkscms.com/uploads/b1.jpg" 
	data-fancybox-group="gallery"> 
	<span class="blackout"><em class="icon-search">&nbsp;</em></span>
	<img src="http://www.this.patchworkscms.com/uploads/b1.jpg" alt="" style="-webkit-transform: scale(1, 1);">
</a>