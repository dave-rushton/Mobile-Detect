<?php

function seoUrl($string) {
    //Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
    $string = strtolower($string);

    $string = str_replace('+','plus',$string);

    //Strip any unwanted characters
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
}

require('../../config/config.php');
require('../patchworks.php');

require('../system/classes/categories.cls.php');
require('../system/classes/subcategories.cls.php');
require('../attributes/classes/attrgroups.cls.php');
require("../products/classes/products.cls.php");

$CatDao = new CatDAO();
$SubDao = new SubDAO();
$AtrDao = new AtrDAO();
$PrdDao = new PrdDAO();

error_reporting(E_ALL);
date_default_timezone_set('Europe/London');
ini_set('max_execution_time', 0);

require '../system/classes/simplexlsx.php';

$rowNum = 0;

//if (isset($_FILES['file'])) {

$xlsx = new SimpleXLSX( 'products.xlsx' );

$rowNum = 0;

$productRecs = array();

echo 'estimated rows: '.count( $xlsx->rows(1) ).'<br>';

$startRow = 2;
$endRow = 2500;

foreach( $xlsx->rows(1) as $r ) {

    $rowNum++;

    //if ($rowNum <= 900) continue;
    if ($rowNum < $startRow) continue;
    if ($rowNum >= $endRow) die('done');

    echo $rowNum.' : '.$r[0].'<br>';

    //
    // Find Attribute Group (attrGroup)
    //

    $Atr_ID = 0;
    $AtrSeo = seoUrl($r[1]);
    $attrGroupRec = $AtrDao->selectBySeo($AtrSeo);
    if (!isset($attrGroupRec->seourl)) {

        $AtrObj = new stdClass();
        $AtrObj->atr_id = 0;
        $AtrObj->tblnam = 'PRODUCTGROUP';
        $AtrObj->tbl_id = 1;
        $AtrObj->atrnam = $r[1];
        $AtrObj->atrdsc = '';
        $AtrObj->atrema = '';
        $AtrObj->seourl = seoUrl($r[1]);
        $AtrObj->fwdurl = '';
        $AtrObj->btntxt = 'SUBMIT';
        $AtrObj->seokey = '';
        $AtrObj->seodsc = '';
        $AtrObj->sta_id = 0;
        $AtrObj->atrtag = '';
        $Atr_ID = $AtrDao->update($AtrObj);

    } else {

        $Atr_ID = $attrGroupRec->atr_id;

    }


    $TxtArr = explode("--", $r[0]);
    $PrdNam = $TxtArr[0];
    $PrdDsc = (isset($TxtArr[1])) ? $TxtArr[1] : '';

    $PrdObj = new stdClass();

    $PrdObj->prd_id = 0;
    $PrdObj->tblnam = 'PRODUCT';
    $PrdObj->tbl_id = $Atr_ID;
    $PrdObj->prt_id = 0;
    $PrdObj->prdnam = $PrdNam;
    $PrdObj->prddsc = $PrdDsc;
    $PrdObj->prdspc = '';
    $PrdObj->unipri = $r[3];
    $PrdObj->buypri = 0;
    $PrdObj->delpri = 0;
    $PrdObj->sup_id = 0;
    $PrdObj->atr_id = $Atr_ID;
    $PrdObj->sta_id = 0;
    $PrdObj->seourl = seoUrl($r[0]);
    $PrdObj->seokey = '';
    $PrdObj->seodsc = '';
    $PrdObj->prdtag = '';
    $PrdObj->usestk = 0;
    $PrdObj->in_stk = $r[2];
    $PrdObj->on_ord = 0;
    $PrdObj->on_del = 0;
    $PrdObj->altref = '';
    $PrdObj->altnam = '';
    $PrdObj->weight = 0;
    $PrdObj->srtord = 1000;
    $PrdObj->vat_id = 0;

    $Prd_ID = $PrdDao->update($PrdObj);

}

?>