<?php

require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/system/classes/related.cls.php");
require_once("../../admin/system/classes/places.cls.php");
require_once("../../admin/products/classes/products.cls.php");

// CHECK LOGIN !!!

$PlaDao = new PlaDAO();
$loggedIn = $PlaDao->loggedIn($_SESSION['loginToken']);

if (!$loggedIn) {
    die();
}

$PrdDao = new PrdDAO();
$Prd_ID = (isset($_REQUEST['prd_id']) && is_numeric($_REQUEST['prd_id'])) ? $_REQUEST['prd_id'] : NULL;
$PrdObj = $PrdDao->select($Prd_ID, NULL, NULL, NULL, NULL, NULL, NULL, true, NULL, NULL);

$RelDao = new RelDAO();
$relatedRecs = $RelDao->select(NULL,'CUS',$loggedIn->pla_id,'PRODUCT',$Prd_ID,false);

if (isset($relatedRecs) && count($relatedRecs) > 0) {

    $RelDao->delete( $relatedRecs[0]['rel_id'] );

} else {

    $RelObj = new stdClass();
    $RelObj->rel_id = 0;
    $RelObj->tblnam = 'CUS';
    $RelObj->tbl_id = $loggedIn->pla_id;
    $RelObj->refnam = 'PRODUCT';
    $RelObj->ref_id = $PrdObj->prd_id;
    $RelObj->reltyp = 'LIKE';
    $RelObj->srtord = 0;

    $Rel_ID = $RelDao->update($RelObj);

}

?>