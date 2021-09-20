<?php

require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/ecommerce/classes/ecommprop.cls.php");
require_once("../../admin/website/classes/keyword.cls.php");
require_once("../../admin/products/classes/products.cls.php");
require_once("../../admin/products/classes/product_types.cls.php");
require_once("../../admin/gallery/classes/uploads.cls.php");

function cmp($a, $b) { return strcmp($a->occurances, $b->occurances); }

$TmpPrt = new PrtDAO();
$UplDao = new UplDAO();
$TmpEco = new EcoDAO();
$eCommProp = $TmpEco->select(true);

$searchTerm = (isset($_REQUEST['keyword'])) ? $_REQUEST['keyword'] : '';

$sdArray = array();
$i = 0;

if ($searchTerm != '') {

    //
    // Website Products
    //

    $PrdDao = new PrdDAO();
    $products = $PrdDao->select(NULL, NULL, NULL, NULL, NULL, NULL, NULL, FALSE, 0, 9999999);

    for ($a=0; $a<count($products);$a++) {

        $sdArray[$i] = new searchDetail;
        $sdArray[$i]->TblNam = 'products';
        $sdArray[$i]->Tbl_ID = $products[$a]['prd_id'];

        $sdArray[$i]->getWordCount($searchTerm, $products[$a]['prdnam']);
        $sdArray[$i]->getWordCount($searchTerm, $products[$a]['prddsc']);
        $sdArray[$i]->getWordCount($searchTerm, $products[$a]['altref']);
        $sdArray[$i]->getWordCount($searchTerm, $products[$a]['altnam']);
        $sdArray[$i]->searchContent = $products[$a]['prddsc'];
        $sdArray[$i]->contentPageName = $products[$a]['prdnam'];
        $sdArray[$i]->contentID = $products[$a]['prd_id'];

        $productImage = $TmpPrt->getProductImage($products[$a]['prd_id']);
        $sdArray[$i]->contentImage = (isset($productImage[0]['filnam'])) ? $productImage[0]['filnam'] : '';

        if (empty($sdArray[$i]->contentImage)) {

            $productTypeImage = $TmpPrt->getProductTypeImage($products[$a]['prt_id']);
            $sdArray[$i]->contentImage = (isset($productTypeImage[0]['filnam'])) ? $productTypeImage[0]['filnam'] : '';

        }

        $sdArray[$i]->seoUrl = $products[$a]['seourl'];
        $sdArray[$i]->inSEO($searchTerm, $products[$a]['seourl']);
        $sdArray[$i]->inTitle($searchTerm, $products[$a]['prdnam']);

        ++$i;

    }

}

$j = 0;

$productArray = array();

if ( $searchTerm != '') {
    usort($sdArray, "cmp");

    $sdArray = array_reverse($sdArray);
    foreach ($sdArray as $searchResults) {

        if ($searchResults->occurances < 1) continue;

        $j++;

        array_push($productArray, $searchResults);

    }
}


echo json_encode($productArray);
die();

?>