<?php

require_once('../../config/config.php');
require_once('../patchworks.php');

require_once("../products/classes/structure.cls.php");
require_once("../products/classes/products.cls.php");
require_once("../products/classes/product_types.cls.php");
require_once("../system/classes/related.cls.php");
require_once("../gallery/classes/uploads.cls.php");
require_once("../ecommerce/classes/order.cls.php");
require_once("../ecommerce/classes/orderline.cls.php");
require_once("../website/classes/articles.cls.php");

$StrDao = new StrDAO();
$RelDao = new RelDAO();
$PrtDao = new PrtDAO();
$PrdDao = new PrdDAO();
$UplDao = new UplDAO();
$ArtDao = new ArtDAO();

define('HOST', "localhost");
define('USER', "root");
define('PASS', "");
define('DATABASE1', "kamarin");
define('DATABASE2', "isf");

error_reporting(E_ALL);
date_default_timezone_set('Europe/London');
ini_set('max_execution_time', 0);

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


$DATABASE1  = mysqli_connect(HOST, USER, PASS, DATABASE1);
$DATABASE2  = mysqli_connect(HOST, USER, PASS, DATABASE2);
if(!$DATABASE1){
    die("DATABASE1 CONNECTION ERROR: ".mysqli_connect_error());
}
if(!$DATABASE2){
    die("DATABASE2 CONNECTION ERROR: ".mysqli_connect_error());
}




mysqli_query($DATABASE2, 'TRUNCATE `structure`');
mysqli_query($DATABASE2, 'TRUNCATE `producttypes`');
mysqli_query($DATABASE2, 'TRUNCATE `products`');
mysqli_query($DATABASE2, 'TRUNCATE `related`');

//mysqli_query($DATABASE2, 'TRUNCATE `uploads`');
//mysqli_query($DATABASE2, 'TRUNCATE `orders`');
//mysqli_query($DATABASE2, 'TRUNCATE `orderline`');


$startItem = 1;
$endItem = 9999;
$itemCount = 0;

$TOPLEVEL_QUERY = mysqli_query($DATABASE1, 'SELECT * FROM `stock_categories`');
while ($structureTop = mysqli_fetch_assoc($TOPLEVEL_QUERY)) {

    $itemCount++;

    $structureRec = new stdClass();
    $structureRec->str_id = 0;
    $structureRec->tblnam = 'STRUCTURE';
    $structureRec->tbl_id  = $structureTop['category_id'];
    $structureRec->par_id = 0;
    $structureRec->strnam = $structureTop['description'];
    $structureRec->seourl = seoUrl($structureTop['description']);
    $structureRec->strobj = $structureTop['code'];
    $structureRec->strimg = '';
    $structureRec->srtord = $itemCount * 10;
    $structureRec->sta_id = 0;
    $structureRec->keywrd = $structureTop['description'];
    $structureRec->keydsc = $structureTop['description'];

    $PARENT_QUERY = mysqli_query($DATABASE1, 'SELECT * FROM `stock_categories` WHERE `code` = "'.$structureTop['parent_category_code'].'"');
    while ($parentStructure = mysqli_fetch_assoc($PARENT_QUERY)) {

        $PARENT_PW_QUERY = mysqli_query($DATABASE2, 'SELECT * FROM `structure` WHERE `tbl_id` = "'.$parentStructure['category_id'].'"');
        while ($parentPwStructure = mysqli_fetch_assoc($PARENT_PW_QUERY)) {

            $structureRec->par_id = $parentPwStructure['str_id'];

        }

    }

//    $objectData = array();
//    $objectObject = new stdClass();
//    $objectObject->name = 'abv';
//    $objectObject->value = $product['abv'];
//    array_push($objectData, $objectObject);
//    $objectObject = new stdClass();
//    $objectObject->name = 'volume';
//    $objectObject->value = $product['volume'];
//    array_push($objectData, $objectObject);
//    $structureRec->strobj = json_encode($objectData);

    $Par_ID = $StrDao->update($structureRec);

}


$PRODUCT_QUERY = mysqli_query($DATABASE1, 'SELECT * FROM `stock_records`');
while ($products = mysqli_fetch_assoc($PRODUCT_QUERY)) {

    // CREATE PRT
    // CREATE PRD

    $PrtObj = new stdClass();
    $PrtObj->prt_id = 0;
    $PrtObj->tblnam = 'PRODUCT';
    $PrtObj->tbl_id = $products['stock_id'];
    $PrtObj->prtnam = $products['stock_code'];
    $PrtObj->prtdsc = $products['description'];
    $PrtObj->prtspc = $products['extended_description'];
    $PrtObj->unipri = $products['sell_price'];
    $PrtObj->buypri = $products['cost_price'];
    $PrtObj->delpri = 0;
    $PrtObj->atr_id = 0;
    $PrtObj->sta_id = 0;
    $PrtObj->seourl = seoUrl($products['stock_code']);
    $PrtObj->seokey = $products['stock_code'].' '.$products['description'];
    $PrtObj->seodsc = $products['stock_code'].' '.$products['description'];
    $PrtObj->usestk = 0;
    $PrtObj->hompag = 0;
    $PrtObj->prttag = '';
    $PrtObj->prtobj = '';
    $PrtObj->prtimg = $products['image_names'];
    $PrtObj->vat_id = $products['vat_code'];
    $Prt_ID = $PrtDao->update($PrtObj);

    $PrdObj = new stdClass();
    $PrdObj->prd_id = 0;
    $PrdObj->tblnam = '';
    $PrdObj->tbl_id = 0;
    $PrdObj->prt_id = $Prt_ID;
    $PrdObj->prdnam = $PrtObj->prtnam;
    $PrdObj->prddsc = $PrtObj->prtdsc;
    $PrdObj->prdspc = $PrtObj->prtspc;
    $PrdObj->unipri = $PrtObj->unipri;
    $PrdObj->buypri = $PrtObj->buypri;
    $PrdObj->delpri = $PrtObj->delpri;
    $PrdObj->sup_id = 0;
    $PrdObj->atr_id = 0;
    $PrdObj->sta_id = $PrtObj->sta_id;
    $PrdObj->seourl = $PrtObj->seourl;
    $PrdObj->seokey = $PrtObj->seokey;
    $PrdObj->seodsc = $PrtObj->seodsc;
    $PrdObj->prdtag = '';
    $PrdObj->usestk = 0;
    $PrdObj->in_stk = $products['free_stock_quantity'];
    $PrdObj->on_ord = 0;
    $PrdObj->on_del = 0;
    $PrdObj->altref = $products['stock_code'];
    $PrdObj->altnam = '';
    $PrdObj->weight = $products['weight'];
    $PrdObj->srtord = 1000;
    $PrdObj->vat_id = $products['vat_code']; //// NEED TO LINK UP PROPERLY
    $PrdObj->prdobj = '';
    $PrdObj->prdimg = $products['image_names'];
    $Prd_ID = $PrdDao->update($PrdObj);


    $catArray = explode(",",$products['category_codes']);

    for ($i = 0; $i < count($catArray); $i++) {

        $PARENT_QUERY = mysqli_query($DATABASE2, 'SELECT * FROM `structure` WHERE `strobj` = "' . $catArray[$i] . '"');
        while ($parentStructure = mysqli_fetch_assoc($PARENT_QUERY)) {

            $RelObj = new stdClass();
            $RelObj->rel_id = 0;
            $RelObj->tblnam = 'PRODUCT';
            $RelObj->tbl_id = $Prt_ID;
            $RelObj->refnam = 'STRUCTURE';
            $RelObj->ref_id = $parentStructure['str_id'];
            $RelObj->reltyp = 'LINK';
            $RelObj->srtord = 9999;
            $RelDao->update($RelObj);

        }

    }


    // PRICE BANDS

//    $PrbObj = new stdClass();
//    $PrbObj->prb_id = 0;
//    $PrbObj->cus_id = 0;
//    $PrbObj->prt_id = 0;
//    $PrbObj->prd_id = 0;
//    $PrbObj->begdat = '';
//    $PrbObj->enddat = '';
//    $PrbObj->prityp = 'A';
//    $PrbObj->numuni = 1;
//    $PrbObj->unipri = 0;
//    $PrbObj->sta_id = 0;
//    $Prb_ID = $PrbDao->update($PrbObj);


}


mysqli_close($DATABASE1);
mysqli_close($DATABASE2);

?>