<?php

require_once("../config/config.php");
require_once("../admin/patchworks.php");
require_once("../admin/website/classes/page.handler.php");
require_once("../admin/products/classes/product_types.cls.php");
require_once("../admin/products/classes/products.cls.php");
require_once("../admin/products/classes/structure.cls.php");
require_once("../admin/products/classes/pricebands.cls.php");
require_once("../admin/gallery/classes/uploads.cls.php");
require_once("../admin/system/classes/related.cls.php");
require_once("../admin/attributes/classes/attrgroups.cls.php");
require_once("../admin/attributes/classes/attrlabels.cls.php");
require_once("../admin/attributes/classes/attrvalues.cls.php");
require_once("../admin/ecommerce/classes/ecommprop.cls.php");
require_once("../admin/system/classes/places.cls.php");

$pageHandler = new pageHandler();
$pageHandler->getPage($_GET['seourl'], $_GET, $_POST);

$PlaDao = new PlaDAO();
$loggedIn = $PlaDao->loggedIn( (isset($_POST['loginToken'])) ? $_POST['loginToken'] : NULL );

//
// SEO DETAILS
//
$pageTitle = $pageHandler->PagTtl;
$keyWords = $pageHandler->KeyWrd;
$keyDescription = $pageHandler->PagDsc;

if (isset($_GET['str_id'])) {
    $TmpStr = new StrDAO();
    $structureRec = $TmpStr->select($_GET['str_id'], NULL, NULL, true);

    $pageTitle = $structureRec->strnam;
    $keyWords = $structureRec->keywrd;
    $keyDescription = $structureRec->keydsc;
}

if (isset($_GET['prtseo'])) {
    $TmpPrt = new PrtDAO();
    $UplDao = new UplDAO();

    $productTypeRec = $TmpPrt->select(NULL, $_GET['prtseo'], NULL, NULL, NULL, NULL, NULL, NULL, true);
    $prdTypeUploads = $UplDao->select(NULL, 'PRDTYPE', $productTypeRec->prt_id, NULL, false);

    $pageTitle = $productTypeRec->prtnam;
    $keyWords = $productTypeRec->seokey;
    $keyDescription = $productTypeRec->seodsc;
}


$TmpUpl = new UplDAO();
$TmpPrd = new PrdDAO();
$TmpPrt = new PrtDAO();
$TmpPrb = new PrbDAO();
$TmpStr = new StrDAO();
$TmpEco = new EcoDAO();

$displayCurrency = '&pound;';

$Str_ID = (isset($_GET['str_id']) && is_numeric($_GET['str_id'])) ? $_GET['str_id'] : 0;

$action = (isset($_GET['action'])) ? $_GET['action'] : 'home';
$Prt_ID = (isset($_GET['prt_id'])) ? $_GET['prt_id'] : NULL;
$PrtSeo = (isset($_GET['prtseo'])) ? $_GET['prtseo'] : NULL;
$PrdSeo = (isset($_GET['prdseo'])) ? $_GET['prdseo'] : NULL;
$PrdCat = (isset($_GET['prdcat'])) ? $_GET['prdcat'] : NULL;

if ($Str_ID > 0) {
    $action = 'category';
}
if (!is_null($PrtSeo)) {
    $action = 'product';

    $productTypeRec = $TmpPrt->select($Prt_ID, $PrtSeo, NULL, NULL, NULL, NULL, NULL, NULL, true);
    if (isset($productTypeRec->prt_id) && is_numeric($productTypeRec->prt_id)) {
        $prdTypeUploads = $TmpPrt->getProductImage($productTypeRec->prt_id);
    } else {
        die('PRODUCT ERROR - Contact Administrator');
    }

}
if (!is_null($PrdSeo)) {
    $action = 'productfull';

    $TmpPrd = new PrdDAO();
    $productTypeRec = $TmpPrd->select(NULL, $PrdSeo, $Prt_ID, NULL, NULL, NULL, NULL, true, NULL, NULL);
    $Prt_ID = $productTypeRec->prt_id;

    $pageTitle = $productTypeRec->prdnam;
    $keyWords = $productTypeRec->seokey;
    $keyDescription = $productTypeRec->seodsc;

}


$headerImage = $pageHandler->PagImg;
if (empty($headerImage)) $headerImage = 'pages/img/pantone-image.jpg';
$headerText = $pageHandler->PagTtl;

$structureRec = $TmpStr->select($Str_ID, NULL, NULL, true);
if ($structureRec) {

    $headerText = $structureRec->strnam;

    $structureUploads = $TmpUpl->select(NULL, 'STRUCTURE', $structureRec->str_id, NULL, false);
    if (count($structureUploads) > 0) {
        $headerImage = 'uploads/images/products/'.$structureUploads[0]['filnam'];
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
    <meta name="format-detection" content="telephone=no">
    <link rel="icon" href="favicon.ico" type="image/x-icon"/>

    <title><?= $pageTitle; ?></title>
    <?= $pageHandler->googleAnalytics(); ?>

    <base href="<?= $patchworks->webRoot; ?>"/>
    <meta name="keywords" content="<?= $keyWords; ?>"/>
    <meta name="description" content="<?= $keyDescription; ?>"/>

    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

    <?php
        echo $pageHandler->critialCSS();
        echo $pageHandler->getTopJS($_GET['seourl']);
    ?>

    <script src="pages/js/jquery.js"></script>
</head>
<body>
<?php
    echo $pageHandler->getTopJS($_GET['seourl']);
    include('webparts/page.header.php');

    if ($action == 'home') {
?>
    <div class="container">
        <div class="pageElement" id="full-inner-0">
        <?= $pageHandler->displayElements('full-inner-0'); ?>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="pageElement" id="full-inner-1a">
                <?= $pageHandler->displayElements('full-inner-1a'); ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="pageElement" id="full-inner-2a">
                <?= $pageHandler->displayElements('full-inner-2a'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="pageElement" id="full-inner-3a">
                <?= $pageHandler->displayElements('full-inner-3a'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="pageElement" id="full-inner-1">
                <?= $pageHandler->displayElements('full-inner-1'); ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="pageElement" id="full-inner-2">
                <?= $pageHandler->displayElements('full-inner-2'); ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="pageElement" id="full-inner-3">
                <?= $pageHandler->displayElements('full-inner-3'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="pageElement" id="full-inner-4a">
                <?= $pageHandler->displayElements('full-inner-4a'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="pageElement" id="full-inner-5a">
                <?= $pageHandler->displayElements('full-inner-5a'); ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="pageElement" id="full-inner-6a">
                <?= $pageHandler->displayElements('full-inner-6a'); ?>
                </div>
            </div>
        </div>
        <div class="pageElement" id="full-inner-4">
        <?= $pageHandler->displayElements('full-inner-4'); ?>
        </div>
    </div>
<?php
}
?>
    <div class="max-container">
        <div id="productCatalogueWrapper">
        <?php
            if ($action == 'home') {
                $Str_ID = 3;
                echo '<div class="section nomargin nopadding">';
                include('products/products.category.php');
            } else if ($action == 'category') {
                $enable_bread_crumb = true;
                echo '<div class="section nomargin nopadding">';
                include('products/products.category.php');
            } else if ($action == 'product') {
                if (isset($_GET['view']) && isset($_GET['view']) == 'products') {
                    echo '<div class="section nopadding">';
                    include('products/products.productlist.php');
                } else {
                    echo '<div class="section nopadding">';
                    include('products/products.product.php');
                }
            } else if ($action == 'productfull') {
                echo '<div class="section nomargin nopadding">';
                include('products/products.productfull.php');
            }
        ?>
        </div>
    </div>
</div>
<?php
    if ($action == 'home') {
?>
<div class="pageElement" id="fullwidthcontenta">
<?php echo $pageHandler->displayElements('fullwidthcontenta'); ?>
</div>
<?php
}

include('webparts/page.footer.php');
echo $pageHandler->getBotJS($_GET['seourl']);
?>
<link rel="stylesheet" href="pages/css/style.css" />
<link rel="stylesheet" href="pages/css/magnific-popup.css" />
<script src="pages/js/jquery.magnific-popup.min.js"></script>
<script src="pages/js/scripts-products.js"></script>
</body>
</html>
