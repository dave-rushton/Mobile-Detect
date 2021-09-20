<?php

require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/system/classes/related.cls.php");
require_once("../../admin/system/classes/places.cls.php");
require_once("../../admin/gallery/classes/uploads.cls.php");

// CHECK LOGIN !!!

$PlaDao = new PlaDAO();
$loggedIn = $PlaDao->loggedIn($_SESSION['loginToken']);

if (!$loggedIn) {
    header('location: useraccount/login');
    exit();
}

$UplDao = new UplDAO();
$Upl_ID = (isset($_REQUEST['upl_id']) && is_numeric($_REQUEST['upl_id'])) ? $_REQUEST['upl_id'] : NULL;
$UplObj = $UplDao->select($Upl_ID, NULL, NULL, NULL, true);

$RelDao = new RelDAO();
$relatedRecs = $RelDao->select(NULL,'CUS',$loggedIn->pla_id,'UPLOAD',$Upl_ID,false);

if (isset($relatedRecs) && count($relatedRecs) <= 0) {

    $RelObj = new stdClass();
    $RelObj->rel_id = 0;
    $RelObj->tblnam = 'CUS';
    $RelObj->tbl_id = $loggedIn->pla_id;
    $RelObj->refnam = 'UPLOAD';
    $RelObj->ref_id = $UplObj->upl_id;
    $RelObj->reltyp = 'CUSDL';
    $RelObj->srtord = 0;

    $Rel_ID = $RelDao->update($RelObj);

}

$file_url = $patchworks->webRoot . 'uploads/files/' . $UplObj->filnam;
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
readfile($file_url);

?>