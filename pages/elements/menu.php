<?php

require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/website/classes/pageelements.cls.php");
require_once("../../admin/website/classes/page.handler.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$ParSeo = $EleDao->getVariable($EleObj, 'parseo', false );
$ClsNam = $EleDao->getVariable($EleObj, 'clsnam' );
$ID_Nam = $EleDao->getVariable($EleObj, 'id_nam' );

$pageHandler = new pageHandler;

if (!is_null($ParSeo)) {
	echo $pageHandler->getMenu($_GET['seourl'], 1, $ParSeo, $ID_Nam, $ClsNam);
} else {
	echo $pageHandler->getMenu($_GET['seourl'], NULL, NULL, $ID_Nam, $ClsNam);
}

?>